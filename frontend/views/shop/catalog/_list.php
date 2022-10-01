<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

?>

<div id="display-control" class="row">
    <div class="col-lg-3">
        <div class="mb-3">
            <a href="https://demo.opencart.com/index.php?route=product/compare&amp;language=en-gb" id="compare-total" class="btn btn-primary d-block"><i class="fas fa-exchange-alt"></i> <span class="d-none d-xl-inline">Product Compare (0)</span></a>
        </div>
    </div>
    <div class="col-lg-1 d-none d-lg-block">
        <div class="btn-group">
            <button type="button" id="button-list" class="btn btn-light" data-bs-toggle="tooltip" title="List"><i class="fas fa-th-list"></i></button>
            <button type="button" id="button-grid" class="btn btn-light" data-bs-toggle="tooltip" title="Grid"><i class="fas fa-th"></i></button>
        </div>
    </div>
    <div class="col-lg-4 offset-lg-1 col-6">
        <div class="input-group mb-3">
            <div class="input-group">
                <label for="input-sort" class="input-group-text">Sort By</label>
                <select id="input-sort" class="form-select" onchange="location = this.value;">
                    <?
                    $values = [
                        '' => 'Default',
                        'name' => 'Name (A - Z)',
                        '-name' => 'Name (Z - A)',
                        'price' => 'Price (Low &gt; High)',
                        '-price' => 'Price (High &gt; Low)',
                        '-rating' => 'Rating (Highest)',
                        'rating' => 'Rating (Lowest)',
                    ];

                    $current = Yii::$app->request->get('sort');
                    ?>
                    <? foreach ($values as $value => $label): ?>
                        <option value="<?= Html::encode(Url::current(['sort' => $value ?: null])) ?>"
                            <? if ($current == $value): ?>
                                selected="selected"
                            <? endif; ?>
                        ><?= $label ?></option>
                    <? endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="input-group mb-3">
            <div class="input-group">
                <label for="input-limit" class="input-group-text">Show</label>
                <select id="input-limit" class="form-select" onchange="location = this.value;">
                    <?
                    $values = [15, 25, 50, 75, 100];
                    $current = $dataProvider->getPagination()->getPageSize();
                    ?>
                    <? foreach ($values as $value): ?>
                        <option value="<?= Html::encode(Url::current(['per-page' => $value])) ?>"
                            <? if ($current == $value): ?>
                                selected="selected"
                            <? endif; ?>
                        ><?= $value ?></option>
                    <? endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</div>
<div id="product-list" class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-4">
    <? foreach ($dataProvider->getModels() as $product): ?>
        <?= $this->render('_product', [
            'product' => $product
        ])?>
    <? endforeach; ?>
</div>
<div class="row">
    <div class="col-sm-6 text-start">
        <?= LinkPager::widget([
            'pagination' => $dataProvider->getPagination(),
        ]) ?>
    </div>
    <div class="col-sm-6 text-end">Showing <?= $dataProvider->getCount() ?> of <?= $dataProvider->getTotalCount() ?></div>
</div>
