<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $category shop\entities\Shop\Category */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Catalog';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="panel panel-default">
    <div class="panel-body">
        <? foreach ($category->children as $child): ?>
            <a href="<?= Html::encode(Url::to(['category', 'id' => $child->id])) ?>"><?= Html::encode($child->name) ?></a> &nbsp;
        <? endforeach; ?>
    </div>
</div>

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
                <label for="input-sort" class="input-group-text">Sort By</label> <select id="input-sort" class="form-select" onchange="location = this.value;">
                    <option value="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20&amp;sort=p.sort_order&amp;order=ASC" selected>Default</option>
                    <option value="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20&amp;sort=pd.name&amp;order=ASC">Name (A - Z)</option>
                    <option value="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20&amp;sort=pd.name&amp;order=DESC">Name (Z - A)</option>
                    <option value="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20&amp;sort=p.price&amp;order=ASC">Price (Low &gt; High)</option>
                    <option value="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20&amp;sort=p.price&amp;order=DESC">Price (High &gt; Low)</option>
                    <option value="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20&amp;sort=rating&amp;order=DESC">Rating (Highest)</option>
                    <option value="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20&amp;sort=rating&amp;order=ASC">Rating (Lowest)</option>
                    <option value="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20&amp;sort=p.model&amp;order=ASC">Model (A - Z)</option>
                    <option value="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20&amp;sort=p.model&amp;order=DESC">Model (Z - A)</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="input-group mb-3">
            <div class="input-group">
                <label for="input-limit" class="input-group-text">Show</label> <select id="input-limit" class="form-select" onchange="location = this.value;">
                    <option value="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20&amp;limit=10" selected>10</option>
                    <option value="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20&amp;limit=25">25</option>
                    <option value="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20&amp;limit=50">50</option>
                    <option value="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20&amp;limit=75">75</option>
                    <option value="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20&amp;limit=100">100</option>
                </select>
            </div>
        </div>
    </div>
</div>
<div id="product-list" class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-4">
    <? foreach ($dataProvider->getModels() as $product): ?>
        <?= $this->render('_product',
            [
                    'product' => $product
            ])?>
    <? endforeach; ?>
</div>
<div class="row">
    <div class="col-sm-6 text-start"><ul class="pagination">
            <li class="page-item active"><span class="page-link">1</span></li>
            <li class="page-item"><a href="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20&amp;page=2" class="page-link">2</a></li>
            <li class="page-item"><a href="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20&amp;page=2" class="page-link">&gt;</a></li>
            <li class="page-item"><a href="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20&amp;page=2" class="page-link">&gt;|</a></li>
        </ul></div>
    <div class="col-sm-6 text-end">Showing 1 to 10 of 12 (2 Pages)</div>
</div>