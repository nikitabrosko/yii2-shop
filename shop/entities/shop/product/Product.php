<?php

namespace shop\entities\shop\product;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use shop\entities\behaviors\MetaBehavior;
use shop\entities\Meta;
use shop\entities\shop\Brand;
use shop\entities\shop\Category;
use shop\entities\shop\product\queries\ProductQuery;
use shop\entities\shop\Tag;
use shop\entities\user\WishlistItem;
use shop\exceptions\AlreadyExistsException;
use shop\exceptions\DeleteErrorException;
use shop\exceptions\NotFoundException;
use shop\exceptions\SavingErrorException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * @property integer $id
 * @property integer $created_at
 * @property string $code
 * @property string $name
 * @property string $description
 * @property integer $category_id
 * @property integer $brand_id
 * @property integer $price_old
 * @property integer $price_new
 * @property integer $rating
 * @property integer $status
 * @property integer $weight
 * @property integer quantity
 *
 * @property Meta $meta
 * @property Brand $brand
 * @property Category $category
 *
 * @property CategoryAssignment[] $categoryAssignments
 * @property Category[] $categories
 * @property Value[] $values
 * @property Photo[] $photos
 * @property Photo $mainPhoto
 * @property TagAssignment[] $tagAssignments
 * @property Tag[] $tags
 * @property RelatedAssignment[] $relatedAssignments
 * @property Modification[] $modifications
 * @property Review[] $reviews
 */
class Product extends ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

    public $meta;

    public static function create($brandId, $categoryId, $code, $name, $description, $weight, $quantity, Meta $meta) : self
    {
        $product = new static();
        $product->brand_id = $brandId;
        $product->category_id = $categoryId;
        $product->code = $code;
        $product->name = $name;
        $product->meta = $meta;
        $product->status = self::STATUS_DRAFT;
        $product->created_at = time();
        $product->description = $description;
        $product->weight = $weight;
        $product->quantity = $quantity;

        return $product;
    }

    public function edit($brandId, $code, $name, $description, $weight, Meta $meta)
    {
        $this->brand_id = $brandId;
        $this->code = $code;
        $this->name = $name;
        $this->description = $description;
        $this->weight = $weight;
        $this->meta = $meta;
    }

    public function setQuantity($quantity)
    {
        if ($this->modifications) {
            throw new \DomainException('Change modifications quantity.');
        }

        $this->quantity = $quantity;
    }

    public function updatePrice($new, $old)
    {
        $this->price_new = $new;
        $this->price_old = $old;
    }

    public function changeMainCategory($categoryId)
    {
        $this->category_id = $categoryId;
    }

    public function activate()
    {
        if ($this->isActive()) {
            throw new \DomainException('Product is already active');
        }

        $this->status = self::STATUS_ACTIVE;
    }

    public function draft()
    {
        if ($this->isDraft()) {
            throw new \DomainException('Product is already in draft');
        }

        $this->status = self::STATUS_DRAFT;
    }

    public function isActive() : bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isDraft() : bool
    {
        return $this->status == self::STATUS_DRAFT;
    }

    public function isAvailable() : bool
    {
        return $this->quantity > 0;
    }

    public function canChangeQuantity() : bool
    {
        return !$this->modifications;
    }

    public function canBeCheckout($modificationId, $quantity) : bool
    {
        if ($modificationId) {
            return $quantity <= $this->getModification($modificationId)->quantity;
        }

        return $quantity <= $this->quantity;
    }

    public function checkout($modificationId, $quantity)
    {
        if ($modificationId) {
            $modifications = $this->modifications;

            foreach ($modifications as $modification) {
                if ($modification->isIdEqualTo($modificationId)) {
                    $modification->checkout($quantity);

                    $this->updateModifications($modifications);

                    return;
                }
            }
        }

        if ($quantity > $this->quantity) {
            throw new \DomainException('Only' . $this->quantity . ' items are available.');
        }

        $this->quantity -= $quantity;
    }

    public function updateValue($id, $value)
    {
        $values = $this->values;

        foreach ($values as $val) {
            if ($val->isForCharacteristic($id)) {
                return;
            }
        }

        $values[] = Value::create($id, $value);

        $this->values = $values;
    }

    public function getValue($id) : Value
    {
        $values = $this->values;

        foreach ($values as $val) {
            if ($val->isForCharacteristic($id)) {
                return $val;
            }
        }

        return Value::blank($id);
    }

    public function getModification($id) : ?Modification
    {
        foreach ($this->modifications as $modification) {
            if ($modification->isIdEqualTo($id)) {
                return $modification;
            }
        }

        throw new NotFoundException('Modification is not found.');
    }

    public function getModificationPrice($id): int
    {
        foreach ($this->modifications as $modification) {
            if ($modification->isIdEqualTo($id)) {
                return $modification->price ?: $this->price_new;
            }
        }
        throw new \DomainException('Modification is not found.');
    }

    public function addModification($code, $name, $price, $quantity)
    {
        $modifications = $this->modifications;

        foreach ($modifications as $modification) {
            if ($modification->isCodeEqualTo($code)) {
                throw new AlreadyExistsException('Modification is already exists.');
            }
        }

        $modifications[] = Modification::create($code, $name, $price, $quantity);
        $this->updateModifications($modifications);
    }

    public function editModification($id, $code, $name, $price, $quantity)
    {
        $modifications = $this->modifications;

        if (!$this->doWithForeach($modifications, $id,
            function(Modification $modification, array $modifications) use ($code, $name, $price, $quantity) {
                $modification->edit($code, $name, $price, $quantity);
                $this->updateModifications($modifications);
            })) {
            throw new NotFoundException('Modification not found.');
        }
    }

    public function updateModifications(array $modifications)
    {
        $this->modifications = $modifications;
        $this->quantity = array_sum(array_map(function (Modification $modification) {
            return $modification->quantity;
        }, $this->modifications));
    }

    public function removeModification($id): void
    {
        $modifications = $this->modifications;

        foreach ($modifications as $i => $modification) {
            if ($modification->isIdEqualTo($id)) {
                unset($modifications[$i]);
                $this->modifications = $modifications;

                return;
            }
        }

        throw new NotFoundException('Modification not found.');
    }

    public function assignCategory($id)
    {
        $assignments = $this->categoryAssignments;

        foreach ($assignments as $assignment) {
            if ($assignment->isForCategory($id)) {
                return;
            }
        }

        $assignments[] = CategoryAssignment::create($id);
        $this->categoryAssignments = $assignments;
    }

    public function revokeCategory($id)
    {
        $assignments = $this->categoryAssignments;

        foreach ($assignments as $i => $assignment) {
            if ($assignment->isForCategory($id)) {
                unset($assignments[$i]);
                $this->categoryAssignments = $assignments;

                return;
            }
        }

        throw new NotFoundException('Assignment not found.');
    }

    public function revokeCategories()
    {
        $this->categoryAssignments = [];
    }

    public function assignTag($id)
    {
        $assignments = $this->tagAssignments;

        foreach ($assignments as $assignment) {
            if ($assignment->isForTag($id)) {
                return;
            }
        }

        $assignments[] = TagAssignment::create($id);
        $this->tagAssignments = $assignments;
    }

    public function revokeTag($id)
    {
        $assignments = $this->tagAssignments;

        foreach ($assignments as $i => $assignment) {
            if ($assignment->isForTag($id)) {
                unset($assignments[$i]);
                $this->tagAssignments = $assignments;

                return;
            }
        }

        throw new NotFoundException('Assignment not found.');
    }

    public function revokeTags()
    {
        $this->tagAssignments = [];
    }

    public function addPhoto(UploadedFile $file)
    {
        $photos = $this->photos;
        $photos[] = Photo::create($file);

        $this->updatePhotos($photos);
    }

    public function changeMainPhoto($id)
    {
        $photos = $this->photos;

        foreach ($photos as $photo) {
            if ($photo->isIdEqualTo($id)) {
                $this->mainPhoto = $photo;

                return;
            }
        }

        throw new NotFoundException('Photo not found.');
    }

    public function removePhoto($id)
    {
        $photos = $this->photos;

        foreach ($photos as $i => $photo) {
            if ($photo->isIdEqualTo($id)) {
                unset($photos[$i]);
                $this->updatePhotos($photos);

                return;
            }
        }

        throw new NotFoundException('Photo not found.');
    }

    public function removePhotos()
    {
        $this->photos = [];
    }

    public function movePhotoUp($id)
    {
        $photos = $this->photos;

        foreach ($photos as $i => $photo) {
            if ($photo->isIdEqualTo($id)) {
                if ($prev = $photos[$i - 1] ?? null) {
                    $photos[$i - 1] = $photo;
                    $photos[$i] = $prev;
                    $this->updatePhotos($photos);
                }

                return;
            }
        }

        throw new NotFoundException('Photo not found.');
    }

    public function movePhotoDown($id)
    {
        $photos = $this->photos;

        foreach ($photos as $i => $photo) {
            if ($photo->isIdEqualTo($id)) {
                if ($next = $photos[$i + 1] ?? null) {
                    $photos[$i] = $next;
                    $photos[$i + 1] = $photo;
                    $this->updatePhotos($photos);
                }

                return;
            }
        }

        throw new NotFoundException('Photo not found.');
    }

    private function updatePhotos(array $photos)
    {
        foreach ($photos as $i => $photo) {
            $photo->setSort($i);
        }

        $this->photos = $photos;
        $this->populateRelation('mainPhoto', reset($photos));
    }

    public function assignRelatedProduct($id)
    {
        $assignments = $this->relatedAssignments;

        foreach ($assignments as $assignment) {
            if ($assignment->isForProduct($id)) {
                return;
            }
        }

        $assignments[] = RelatedAssignment::create($id);
        $this->relatedAssignments = $assignments;
    }

    public function revokeRelatedProduct($id)
    {
        $assignments = $this->relatedAssignments;

        foreach ($assignments as $i => $assignment) {
            if ($assignment->isForProduct($id)) {
                unset($assignments[$i]);
                $this->relatedAssignments = $assignments;

                return;
            }
        }

        throw new NotFoundException('Assignment not found.');
    }

    public function addReview($userId, $vote, $text)
    {
        $reviews = $this->reviews;

        $reviews[] = Review::create($userId, $vote, $text);

        $this->updateReviews($reviews);
    }
    
    public function editReview($id, $vote, $text)
    {
        $reviews = $this->reviews;

        if (!$this->doWithForeach($reviews, $id,
            function(Review $review, array $reviews) use ($vote, $text)
            {
                $review->edit($vote, $text);
                $this->updateReviews($reviews);
            })) {
            throw new NotFoundException('Review not found.');
        }
    }

    public function activateReview($id)
    {
        $reviews = $this->reviews;

        if (!$this->doWithForeach($reviews, $id,
            function(Review $review, array $reviews)
            {
                $review->activate();
                $this->updateReviews($reviews);
            })) {
            throw new NotFoundException('Review not found.');
        }
    }

    public function draftReview($id)
    {
        $reviews = $this->reviews;

        if (!$this->doWithForeach($reviews, $id,
            function(Review $review, array $reviews)
            {
                $review->draft();
                $this->updateReviews($reviews);
            })) {
            throw new NotFoundException('Review not found.');
        }
    }

    private function doWithForeach($objects, $id, callable $callback) : bool
    {
        foreach ($objects as $object) {
            if ($object->isIdEqualTo($id)) {
                $callback($object, $objects);

                return true;
            }
        }

        return false;
    }

    public function removeReview($id)
    {
        $reviews = $this->reviews;

        foreach ($reviews as $i => $review) {
            if ($review->isIdEqualTo($id)) {
                unset($reviews[$i]);
                $this->updateReviews($reviews);

                return;
            }
        }

        throw new NotFoundException('Review not found.');
    }

    public function updateReviews(array $reviews)
    {
        $amount = 0;
        $total = 0;

        foreach ($reviews as $review) {
            if ($review->isActive()) {
                $amount++;
                $total += $review->getRating();
            }
        }

        $this->reviews = $reviews;
        $this->rating = $amount ? $total / $amount : null;
    }

    public function getBrand() : ActiveQuery
    {
        return $this->hasOne(Brand::class, ['id' => 'brand_id']);
    }

    public function getCategory() : ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getCategoryAssignments() : ActiveQuery
    {
        return $this->hasMany(CategoryAssignment::class, ['product_id' => 'id']);
    }

    public function getCategories() : ActiveQuery
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->via('categoryAssignments');
    }

    public function getValues() : ActiveQuery
    {
        return $this->hasMany(Value::class, ['product_id' => 'id']);
    }

    public function getPhotos() : ActiveQuery
    {
        return $this->hasMany(Photo::class, ['product_id' => 'id'])->orderBy('sort');
    }

    public function getTagAssignments() : ActiveQuery
    {
        return $this->hasMany(TagAssignment::class, ['product_id' => 'id']);
    }

    public function getTags() : ActiveQuery
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->via('tagAssignments');
    }

    public function getRelatedAssignments() : ActiveQuery
    {
        return $this->hasMany(RelatedAssignment::class, ['product_id' => 'id']);
    }

    public function getModifications() : ActiveQuery
    {
        return $this->hasMany(Modification::class, ['product_id' => 'id']);
    }

    public function getReviews() : ActiveQuery
    {
        return $this->hasMany(Review::class, ['product_id' => 'id']);
    }

    public function getMainPhoto(): ActiveQuery
    {
        return $this->hasOne(Photo::class, ['id' => 'main_photo_id']);
    }

    public function getWishlistItems(): ActiveQuery
    {
        return $this->hasMany(WishlistItem::class, ['product_id' => 'id']);
    }

    public static function tableName() : string
    {
        return '{{%shop_products}}';
    }

    public function behaviors() : array
    {
        return [
            MetaBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['categoryAssignments', 'tagAssignments',
                    'relatedAssignments', 'values', 'photos', 'modifications', 'reviews'],
            ],
        ];
    }

    public function transactions() : array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function save($runValidation = true, $attributeNames = null) : bool
    {
        if (!parent::save($runValidation, $attributeNames)) {
            throw new SavingErrorException('Product saving error.');
        }

        return true;
    }

    public function delete() : bool
    {
        if (!parent::delete()) {
            throw new DeleteErrorException('Product deleting error.');
        }

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        $related = $this->getRelatedRecords();

        parent::afterSave($insert, $changedAttributes);

        if (array_key_exists('mainPhoto', $related)) {
            $this->updateAttributes(['main_photo_id' => $related['mainPhoto'] ? $related['mainPhoto']->id : null]);
        }
    }

    public static function find() : ProductQuery
    {
        return new ProductQuery(static::class);
    }
}