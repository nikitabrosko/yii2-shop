<?php

namespace frontend\widgets;

use shop\readModels\shop\ProductReadRepository;
use yii\base\Widget;

class FeaturedProductsWidget extends Widget
{
    public $limit;

    private $productReadRepository;

    public function __construct(ProductReadRepository $repository, $config = [])
    {
        parent::__construct($config);

        $this->productReadRepository = $repository;
    }

    public function run()
    {
        return $this->render('featured', [
            'products' => $this->productReadRepository->getFeatured($this->limit)
        ]);
    }
}