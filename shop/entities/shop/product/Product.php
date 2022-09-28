<?php

namespace shop\entities\shop\product;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use shop\entities\behaviors\MetaBehavior;
use shop\entities\Meta;
use shop\entities\shop\Brand;
use shop\entities\shop\Category;
use shop\exceptions\AlreadyExistsException;
use shop\exceptions\NotFoundException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * @property integer $id
 * @property integer $created_at
 * @property string $code
 * @property string $name
 * @property integer $category_id
 * @property integer $brand_id
 * @property integer $price_old
 * @property integer $price_new
 * @property integer $rating
 *
 * @property Meta $meta
 * @property Brand $brand
 * @property Category $category
 *
 * @property CategoryAssignment[] $categoryAssignments
 * @property Value[] $values
 * @property Photo[] $photos
 * @property TagAssignment[] $tagAssignments
 * @property RelatedAssignment[] $relatedAssignments
 * @property Modification[] $modifications
 * @property Review[] $reviews
 */
class Product extends ActiveRecord
{
    public $meta;

    public static function create($brandId, $categoryId, $code, $name, Meta $meta) : self
    {
        $product = new static();
        $product->brand_id = $brandId;
        $product->category_id = $categoryId;
        $product->code = $code;
        $product->name = $name;
        $product->meta = $meta;
        $product->created_at = time();

        return $product;
    }

    public function edit($brandId = null, $code = null, $name = null, Meta $meta = null)
    {
        if ($brandId) $this->brand_id = $brandId;
        if ($code) $this->code = $code;
        if ($name) $this->name = $name;
        if ($meta) $this->meta = $meta;
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

    public function getModification($id) : Modification
    {
        foreach ($this->modifications as $modification) {
            if ($modification->isIdEqualTo($id)) {
                return $modification;
            }
        }

        throw new NotFoundException('Modification not found.');
    }

    public function addModification($code, $name, $price)
    {
        $modifications = $this->modifications;

        foreach ($modifications as $modification) {
            if ($modification->isCodeEqualTo($code)) {
                throw new AlreadyExistsException('Modification is already exists.');
            }
        }

        $modifications[] = Modification::create($code, $name, $price);
        $this->modifications = $modifications;
    }

    public function editModification($id, $code, $name, $price)
    {
        $modifications = $this->modifications;

        if (!$this->doWithForeach($modifications, $id,
            function(Modification $modification, array $modifications) use ($code, $name, $price)
            {
                $modification->edit($code, $name, $price);
                $this->modifications = $modifications;
            })) {
            throw new NotFoundException('Modification not found.');
        }
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
            throw new \DomainException('Product saving error.');
        }

        return true;
    }

    public function delete() : bool
    {
        if (!parent::delete()) {
            throw new \DomainException('Product removing error.');
        }

        return true;
    }
}