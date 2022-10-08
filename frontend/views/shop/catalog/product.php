<?php

/* @var $this yii\web\View */
/* @var $product shop\entities\shop\product\Product */
/* @var $addToCartForm shop\forms\shop\AddToCartForm */
/* @var $reviewForm shop\forms\Shop\ReviewForm */

use frontend\assets\MagnificPopupAsset;
use shop\helpers\PriceHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = $product->name;

$this->registerMetaTag(['name' => 'description', 'content' => $product->meta->description]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $product->meta->keywords]);

$this->params['breadcrumbs'][] = ['label' => 'Catalog', 'url' => ['index']];

foreach ($product->category->parents as $parent) {
    if (!$parent->isRoot()) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category', 'id' => $parent->id]];
    }
}

$this->params['breadcrumbs'][] = ['label' => $product->category->name, 'url' => ['category', 'id' => $product->category->id]];
$this->params['breadcrumbs'][] = $product->name;

MagnificPopupAsset::register($this);
?>

<div class="row">
    <div id="content" class="col">
        <div class="row">
            <div class="col-sm mb-3">
                <div class="image magnific-popup">
                    <? foreach ($product->photos as $i => $photo): ?>
                        <? if ($i == 1): ?>
                        <div>
                        <? endif; ?>

                        <? if ($i == 0): ?>
                            <a href="<?= $photo->getThumbFileUrl('file', 'catalog_origin') ?>" title="<?= $product->name ?>">
                                <img src="<?= $photo->getThumbFileUrl('file', 'catalog_product_main') ?>"
                                     title="<?= $product->name ?>"
                                     alt="<?= $product->name ?>"
                                     class="img-thumbnail mb-3" />
                            </a>
                        <? else: ?>
                            <a href="<?= $photo->getThumbFileUrl('file', 'catalog_origin') ?>" title="<?= $product->name ?>">
                                <img src="<?= $photo->getThumbFileUrl('file', 'catalog_product_additional') ?>"
                                     title="<?= $product->name ?>"
                                     alt="<?= $product->name ?>"
                                     class="img-thumbnail" />
                            </a>&nbsp;
                        <? endif; ?>

                        <? if ($i == count($product->photos) - 1): ?>
                            </div>
                        <? endif; ?>
                    <? endforeach; ?>
                </div>
            </div>
            <div class="col-sm">
                <h1><?= Html::encode($product->name) ?></h1>
                <ul class="list-unstyled">
                    <li>Brand: <a href="<?= Html::encode(Url::to(['brand', 'id' => $product->brand->id])) ?>"><?= Html::encode($product->brand->name) ?></a></li>
                    <li>
                        Tags:
                        <? foreach ($product->tags as $tag): ?>
                            <a href="<?= Html::encode(Url::to(['tag', 'id' => $tag->id])) ?>"><?= Html::encode($tag->name) ?></a>
                        <? endforeach; ?>
                    </li>
                    <li>Product Code: <?= Html::encode($product->code) ?></li>
                </ul>
                <ul class="list-unstyled">
                    <? if ($product->price_old): ?>
                        <li><span class="price-old">BYN <?= PriceHelper::format($product->price_old) ?></span></li>
                    <? endif;?>
                    <li><h2><span class="price-new">BYN <?= PriceHelper::format($product->price_new) ?></span></h2></li>
                </ul>
                <form method="post" data-oc-toggle="ajax">
                    <div class="btn-group">
                        <button type="submit" formaction="https://demo.opencart.com/index.php?route=account/wishlist|add&amp;language=en-gb" data-bs-toggle="tooltip" class="btn btn-light" title="Add to Wish List" onclick="wishlist.add('42');"><i class="fas fa-heart"></i></button>
                        <button type="submit" formaction="https://demo.opencart.com/index.php?route=product/compare|add&amp;language=en-gb" data-bs-toggle="tooltip" class="btn btn-light" title="Compare this Product" onclick="compare.add('42');"><i class="fas fa-exchange-alt"></i></button>
                    </div>
                    <input type="hidden" name="product_id" value="42" />
                </form>
                <br />
                <div id="product">
                    <form id="form-product">
                        <hr>
                        <h3>Available Options</h3>

                        <? $form = ActiveForm::begin() ?>

                        <div class="mb-3">
                            <?= $form->field($addToCartForm, 'modification')->dropDownList($addToCartForm->modificationsList(),
                                ['prompt' => 'Choose modification', 'class' => 'form-select']) ?>
                        </div>
                        <div class="mb-3">
                            <?= $form->field($addToCartForm, 'quantity')->textInput() ?>
                        </div>

                        <div class="mb-3">
                            <?= Html::submitButton('Add to Cart', ['class' => 'btn btn-primary btn-lg btn-block']) ?>
                        </div>

                        <? ActiveForm::end() ?>

                        <div class="rating">
                            <p><span class="fas fa-stack"><i class="far fa-star fa-stack-1x"></i></span><span class="fas fa-stack"><i class="far fa-star fa-stack-1x"></i></span><span class="fas fa-stack"><i class="far fa-star fa-stack-1x"></i></span><span class="fas fa-stack"><i class="far fa-star fa-stack-1x"></i></span><span class="fas fa-stack"><i class="far fa-star fa-stack-1x"></i></span> <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">0 reviews</a> / <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">Write a review</a></p>
                        </div>
                    </form>
                </div>
            </div>
            <ul class="nav nav-tabs">
                <li class="nav-item"><a href="#tab-description" id="description-tab" class="nav-link active" data-bs-toggle="tab">Description</a></li>
                <li class="nav-item"><a href="#tab-specification" id="specification-tab" class="nav-link" data-bs-toggle="tab">Specification</a></li>
                <li class="nav-item"><a href="#tab-review" id="review-tab" class="nav-link" data-bs-toggle="tab">Reviews (0)</a></li>
            </ul>
            <div class="tab-content">
                <div id="tab-description" class="tab-pane fade show active mb-4" role="tabpanel" aria-labelledby="description-tab">
                    <?= Yii::$app->formatter->asNtext($product->description) ?>
                </div>
                <div id="tab-specification" class="tab-pane fade" role="tabpanel" aria-labelledby="specification-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                            <?php foreach ($product->values as $value): ?>
                                <?php if (!empty($value->value)): ?>
                                    <tr>
                                        <th><?= Html::encode($value->characteristic->name) ?></th>
                                        <td><?= Html::encode($value->value) ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="tab-review" class="tab-pane fade">
                    <form id="form-review">
                        <div id="review"></div>
                        <h2>Write a review</h2>

                        <?php if (Yii::$app->user->isGuest): ?>
                            <div class="panel-panel-info">
                                <div class="panel-body">
                                    Please <?= Html::a('Log In', ['/auth/auth/login']) ?> for writing a review.
                                </div>
                            </div>
                        <?php else: ?>
                            <?php $form = ActiveForm::begin(['id' => 'form-review']) ?>

                            <div class="mb-3 required">
                                <?= $form->field($reviewForm, 'text')->textarea(['rows' => 5]) ?>
                            </div>

                            <div class="row mb-3 required">
                                <?= $form->field($reviewForm, 'vote')->dropDownList($reviewForm->votesList(), ['prompt' => 'Select vote', 'class' => 'form-select']) ?>
                            </div>

                            <div class="d-inline-block pt-2 pd-2 w-100">
                                <div class="float-end">
                                    <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
                                </div>
                            </div>

                            <?php ActiveForm::end() ?>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!--<script type="text/javascript">
    $('#input-subscription').on('change', function (e) {
        var element = this;

        $('.subscription').addClass('d-none');

        $('#subscription-description-' + $(element).val()).removeClass('d-none');
    });

    $('#form-product').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: 'index.php?route=checkout/cart|add&language=en-gb',
            type: 'post',
            data: $('#form-product').serialize(),
            dataType: 'json',
            contentType: 'application/x-www-form-urlencoded',
            cache: false,
            processData: false,
            beforeSend: function () {
                $('#button-cart').prop('disabled', true).addClass('loading');
            },
            complete: function () {
                $('#button-cart').prop('disabled', false).removeClass('loading');
            },
            success: function (json) {
                $('#form-product').find('.is-invalid').removeClass('is-invalid');
                $('#form-product').find('.invalid-feedback').removeClass('d-block');

                if (json['error']) {
                    for (key in json['error']) {
                        $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                        $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                    }
                }

                if (json['success']) {
                    $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fas fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    $('#header-cart').load('index.php?route=common/cart|info');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#review').on('click', '.pagination a', function (e) {
        e.preventDefault();

        $('#review').fadeOut('slow');

        $('#review').load(this.href);

        $('#review').fadeIn('slow');
    });

    $('#review').load('index.php?route=product/review|review&product_id=42');

    $('#button-review').on('click', function () {
        $.ajax({
            url: 'index.php?route=product/review|write&product_id=42',
            type: 'post',
            dataType: 'json',
            data: $('#form-review').serialize(),
            beforeSend: function () {
                $('#button-review').prop('disabled', true).addClass('loading');
            },
            complete: function () {
                $('#button-review').prop('disabled', false).removeClass('loading');
            },
            success: function (json) {
                $('.alert-dismissible').remove();

                if (json['error']) {
                    for (key in json['error']) {
                        $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                        $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                    }
                }

                if (json['success']) {
                    $('#form-review').after('<div class="alert alert-success alert-dismissible"><i class="fas fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    $('#input-name').val('');
                    $('#input-text').val('');
                    $('input[name=\'rating\']:checked').prop('checked', false);
                }
            }
        });
    });

    $(document).ready(function () {
        $('.date').daterangepicker({
            singleDatePicker: true,
            autoApply: true,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        $('.time').daterangepicker({
            singleDatePicker: true,
            datePicker: false,
            autoApply: true,
            timePicker: true,
            timePicker24Hour: true,
            locale: {
                format: 'HH:mm'
            }
        }).on('show.daterangepicker', function (ev, picker) {
            picker.container.find('.calendar-table').hide();
        });

        $('.datetime').daterangepicker({
            singleDatePicker: true,
            autoApply: true,
            timePicker: true,
            timePicker24Hour: true,
            locale: {
                format: 'YYYY-MM-DD HH:mm'
            }
        });
    });
    //</script>-->

<? $popup_js = <<<POPUP_JS
$('.magnific-popup').magnificPopup({
    type: 'image',
    delegate: 'a',
    gallery: {
        enabled: true
    }
});
POPUP_JS;
$this->registerJs($popup_js);
?>