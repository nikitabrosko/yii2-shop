<?php

/* @var $this yii/web/View */
/* @var $product shop\entities\shop\product\Product */

use shop\helpers\PriceHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

$url = Url::to(['product', 'id' => $product->id]);

?>

<div class="col">
    <form method="post" data-oc-toggle="ajax" data-oc-load="https://demo.opencart.com/index.php?route=common/cart|info&amp;language=en-gb" data-oc-target="#header-cart">
        <div class="product-thumb">
            <? if ($product->mainPhoto): ?>
                <div class="image">
                    <a href="<?= Html::encode($url) ?>">
                        <img src="<?= Html::encode($product->mainPhoto->getThumbFileUrl('file', 'catalog_list')) ?>"
                             alt=""
                             class="img-fluid" />
                    </a>
                </div>
            <? endif; ?>
            <div class="content">
                <div class="description">
                    <h4><a href="<?= Html::encode($url) ?>"><?= Html::encode($product->name) ?></a></h4>
                    <p><?= Html::encode(StringHelper::truncateWords(strip_tags($product->description), 20)) ?></p>
                    <div class="price">
                        <span class="price-new">BYN<?= Html::encode(PriceHelper::format($product->price_new)) ?></span>
                        <? if ($product->price_old): ?>
                            <span class="price-old">BYN<?= Html::encode(PriceHelper::format($product->price_old)) ?></span>
                        <? endif; ?>
                    </div>
                </div>
                <div class="button-group">
                    <button type="submit" formaction="https://demo.opencart.com/index.php?route=checkout/cart|add&amp;language=en-gb" data-bs-toggle="tooltip" title="Add to Cart"><i class="fas fa-shopping-cart"></i></button>
                    <button type="submit" formaction="https://demo.opencart.com/index.php?route=account/wishlist|add&amp;language=en-gb" data-bs-toggle="tooltip" title="Add to Wish List"><i class="fas fa-heart"></i></button>
                    <button type="submit" formaction="https://demo.opencart.com/index.php?route=product/compare|add&amp;language=en-gb" data-bs-toggle="tooltip" title="Compare this Product"><i class="fas fa-exchange-alt"></i></button>
                </div>
            </div>
            <input type="hidden" name="product_id" value="42" />
            <input type="hidden" name="quantity" value="2" />
        </div>
    </form>
</div>
