<?php

namespace shop\readModels\shop;

use shop\entities\shop\Brand;
use shop\entities\shop\Category;
use shop\entities\shop\product\Product;
use shop\entities\shop\product\Value;
use shop\entities\shop\Tag;
use shop\forms\shop\search\SearchForm;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class ProductReadRepository
{
    public function getAll(): DataProviderInterface
    {
        $query = Product::find()
            ->alias('p')
            ->active('p')
            ->with('mainPhoto');

        return $this->getProvider($query);
    }

    public function getAllIterator(): iterable
    {
        return Product::find()
            ->alias('p')
            ->active('p')
            ->with('mainPhoto', 'brand')
            ->each();
    }

    public function getAllByRange(int $offset, int $limit): array
    {
        return Product::find()
            ->alias('p')
            ->active('p')
            ->orderBy(['id' => SORT_ASC])
            ->limit($limit)
            ->offset($offset)
            ->all();
    }

    public function count(): int
    {
        return Product::find()
            ->active()
            ->count();
    }

    public function getAllByCategory(Category $category): DataProviderInterface
    {
        $query = Product::find()
            ->alias('p')
            ->active('p')
            ->with('mainPhoto', 'category');

        $ids = ArrayHelper::merge([$category->id], $category->getDescendants()
            ->select('id')
            ->column());

        $query->joinWith(['categoryAssignments ca'], false);
        $query->andWhere(['or', ['p.category_id' => $ids], ['ca.category_id' => $ids]]);
        $query->groupBy('p.id');

        return $this->getProvider($query);
    }

    public function getAllByBrand(Brand $brand): DataProviderInterface
    {
        $query = Product::find()
            ->alias('p')
            ->active('p')
            ->with('mainPhoto');

        $query->andWhere(['p.brand_id' => $brand->id]);

        return $this->getProvider($query);
    }

    public function getAllByTag(Tag $tag): DataProviderInterface
    {
        $query = Product::find()
            ->alias('p')
            ->active('p')
            ->with('mainPhoto');

        $query->joinWith(['tagAssignments ta'], false);
        $query->andWhere(['ta.tag_id' => $tag->id]);
        $query->groupBy('p.id');

        return $this->getProvider($query);
    }

    public function getFeatured($limit) : array
    {
        return Product::find()
            ->with('mainPhoto')
            ->orderBy(['id' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

    public function find($id)
    {
        return Product::find()
            ->active()
            ->andWhere(['id' => $id])
            ->one();
    }

    private function getProvider(ActiveQuery $query): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['p.id' => SORT_ASC],
                        'desc' => ['p.id' => SORT_DESC],
                    ],
                    'name' => [
                        'asc' => ['p.name' => SORT_ASC],
                        'desc' => ['p.name' => SORT_DESC],
                    ],
                    'price' => [
                        'asc' => ['p.price_new' => SORT_ASC],
                        'desc' => ['p.price_new' => SORT_DESC],
                    ],
                    'rating' => [
                        'asc' => ['p.rating' => SORT_ASC],
                        'desc' => ['p.rating' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => [
                'pageSizeLimit' => [15, 100],
            ]
        ]);
    }

    public function search(SearchForm $form): DataProviderInterface
    {
        $query = Product::find()
            ->alias('p')
            ->active('p')
            ->with('mainPhoto', 'category');

        if ($form->brand) {
            $query->andWhere(['p.brand_id' => $form->brand]);
        }

        if ($form->category) {
            if ($category = Category::findOne($form->category)) {
                $ids = ArrayHelper::merge([$form->category], $category->getChildren()->select('id')->column());
                $query->joinWith(['categoryAssignments ca'], false);
                $query->andWhere(['or', ['p.category_id' => $ids], ['ca.category_id' => $ids]]);
            } else {
                $query->andWhere(['p.id' => 0]);
            }
        }

        if ($form->values) {
            $productIds = null;

            foreach ($form->values as $value) {
                if ($value->isFilled()) {
                    $q = Value::find()->andWhere(['characteristic_id' => $value->getId()]);

                    $q->andFilterWhere(['>=', 'CAST(value AS SIGNED)', $value->from]);
                    $q->andFilterWhere(['<=', 'CAST(value AS SIGNED)', $value->to]);
                    $q->andFilterWhere(['value' => $value->equal]);

                    $foundIds = $q->select('product_id')->column();
                    $productIds = $productIds === null ? $foundIds : array_intersect($productIds, $foundIds);
                }
            }

            if ($productIds !== null) {
                $query->andWhere(['p.id' => $productIds]);
            }
        }

        if (!empty($form->text)) {
            $query->andWhere(['or', ['like', 'code', $form->text], ['like', 'name', $form->text]]);
        }

        $query->groupBy('p.id');

        return $this->getProvider($query);
    }

    public function getWishList($userId) : ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Product::find()
                ->alias('p')
                ->active('p')
                ->joinWith('wishlistItems w', false, 'INNER JOIN')
                ->andWhere(['w.user_id' => $userId]),
            'sort' => false,
        ]);
    }
}