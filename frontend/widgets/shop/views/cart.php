<?php

/* @var $cart shop\cart\Cart */

use shop\helpers\PriceHelper;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div id="header-cart" class="col-md-4 col-lg-3">
    <div class="dropdown d-grid">
        <button type="button" data-bs-toggle="dropdown" class="btn btn-inverse btn-block dropdown-toggle"><i class="fas fa-shopping-cart"></i> <?= $cart->getAmount() ?> item(s) - <?= PriceHelper::format($cart->getCost()->getTotal()) ?></button>
        <ul class="dropdown-menu dropdown-menu-right" style="width: 500px;">
            <li>
                <table class="table table-striped">
                    <?php foreach ($cart->getItems() as $item): ?>
                        <?php
                        $product = $item->getProduct();
                        $modification = $item->getModification();
                        $url = Html::encode(Url::to(['/shop/catalog/product', 'id' => $product->id]));
                        ?>
                        <tr>
                            <td class="text-center">
                                <?php if ($product->mainPhoto): ?>
                                    <img src="<?= $product->mainPhoto->getThumbFileUrl('file', 'cart_list') ?>" alt="" class="img-thumbnail" />
                                <?php endif; ?>
                            </td>
                            <td class="text-left">
                                <a href="<?= $url ?>"><?= Html::encode($product->name) ?></a>
                                <?php if ($modification): ?>
                                    <br/><small><?= Html::encode($modification->name) ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-right">x <?= $item->getQuantity() ?></td>
                            <td class="text-right"><?= PriceHelper::format($item->getCost()) ?></td>
                            <td class="text-center">
                                <a href="<?= Html::encode(Url::to(['/shop/cart/remove', 'id' => $item->getId()])) ?>" title="Remove" class="btn btn-danger btn-xs" data-method="post"><i class="fa fa-times"></i></a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </li>
            <li>
                <div>
                    <?php $cost = $cart->getCost(); ?>
                    <table class="table table-bordered">
                        <tr>
                            <td class="text-right"><strong>Sub-Total:</strong></td>
                            <td class="text-right"><?= PriceHelper::format($cost->getOrigin()) ?></td>
                        </tr>
                        <?php foreach ($cost->getDiscounts() as $discount): ?>
                            <tr>
                                <td class="text-right"><strong><?= Html::encode($discount->getName()) ?>:</strong></td>
                                <td class="text-right"><?= PriceHelper::format($discount->getValue()) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td class="text-right"><strong>Total:</strong></td>
                            <td class="text-right"><?= PriceHelper::format($cost->getTotal()) ?></td>
                        </tr>
                    </table>
                    <p class="text-right">
                        <a href="<?= Html::encode(Url::to(['/shop/cart/cart'])) ?>"><strong>
                                <i class="fa fa-shopping-cart"></i> View Cart</strong>
                        </a>
                        &nbsp;&nbsp;&nbsp;
                        <a href="/index.php?route=checkout/checkout"><strong>
                                <i class="fa fa-share"></i> Checkout</strong>
                        </a>
                    </p>
                </div>
            </li>
        </ul>
    </div>
</div>
