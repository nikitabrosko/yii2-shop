<?php

namespace frontend\controllers;

use shop\entities\shop\product\Product;
use shop\services\yandex\YandexMarket;
use yii\caching\TagDependency;
use yii\helpers\Url;
use yii\web\Controller;

class MarketController extends Controller
{
    private $generator;

    public function __construct($id, $module, YandexMarket $generator, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->generator = $generator;
    }

    public function actionIndex()
    {
        $xml = \Yii::$app->cache->getOrSet('yandex-market', function () {
            return $this->generator->generate(function (Product $product) {
                return Url::to(['/shop/catalog/product', 'id' => $product->id], true);
            });
        }, null, new TagDependency(['tags' => ['categories', 'products']]));

        return \Yii::$app->response->sendContentAsFile($xml, 'yandex-market.xml', [
            'mimeType' => 'application/xml',
            'inline' => true,
        ]);
    }
}