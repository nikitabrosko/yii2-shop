<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\widgets\shop\FeaturedProductsWidget;

?>
<?php $this->beginContent('@frontend/views/layouts/main.php') ?>

    <div id="common-home">
        <div class="row">
            <div id="content" class="col"><div id="carousel-banner-0" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carousel-banner-0" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#carousel-banner-0" data-bs-slide-to="1"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="row justify-content-center">
                                <div class="col-12 text-center">
                                    <img src="https://demo.opencart.com/image/cache/catalog/demo/banners/iPhone6-1140x380.jpg" alt="iPhone 6" class="img-fluid" />
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row justify-content-center">
                                <div class="col-12 text-center">
                                    <img src="https://demo.opencart.com/image/cache/catalog/demo/banners/MacBookAir-1140x380.jpg" alt="MacBookAir" class="img-fluid" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="carousel-control-prev" data-bs-target="#carousel-banner-0" data-bs-slide="prev"><span class="fa fa-chevron-left"></span></button>
                    <button type="button" class="carousel-control-next" data-bs-target="#carousel-banner-0" data-bs-slide="next"><span class="fa fa-chevron-right"></span></button>
                </div>
                <h3>Featured</h3>

                <?= FeaturedProductsWidget::widget([
                    'limit' => 4,
                ]) ?>

                <div id="carousel-banner-1" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carousel-banner-1" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#carousel-banner-1" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#carousel-banner-1" data-bs-slide-to="2"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="row justify-content-center">
                                <div class="col-2 text-center">
                                    <img src="https://demo.opencart.com/image/cache/catalog/demo/manufacturer/harley-130x100.png" alt="Harley Davidson" class="img-fluid" />
                                </div>
                                <div class="col-2 text-center">
                                    <img src="https://demo.opencart.com/image/cache/catalog/demo/manufacturer/dell-130x100.png" alt="Dell" class="img-fluid" />
                                </div>
                                <div class="col-2 text-center">
                                    <img src="https://demo.opencart.com/image/cache/catalog/demo/manufacturer/disney-130x100.png" alt="Disney" class="img-fluid" />
                                </div>
                                <div class="col-2 text-center">
                                    <img src="https://demo.opencart.com/image/cache/catalog/demo/manufacturer/cocacola-130x100.png" alt="Coca Cola" class="img-fluid" />
                                </div>
                                <div class="col-2 text-center">
                                    <img src="https://demo.opencart.com/image/cache/catalog/demo/manufacturer/burgerking-130x100.png" alt="Burger King" class="img-fluid" />
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row justify-content-center">
                                <div class="col-2 text-center">
                                    <img src="https://demo.opencart.com/image/cache/catalog/demo/manufacturer/canon-130x100.png" alt="Canon" class="img-fluid" />
                                </div>
                                <div class="col-2 text-center">
                                    <img src="https://demo.opencart.com/image/cache/catalog/demo/manufacturer/nfl-130x100.png" alt="NFL" class="img-fluid" />
                                </div>
                                <div class="col-2 text-center">
                                    <img src="https://demo.opencart.com/image/cache/catalog/demo/manufacturer/redbull-130x100.png" alt="RedBull" class="img-fluid" />
                                </div>
                                <div class="col-2 text-center">
                                    <img src="https://demo.opencart.com/image/cache/catalog/demo/manufacturer/sony-130x100.png" alt="Sony" class="img-fluid" />
                                </div>
                                <div class="col-2 text-center">
                                    <img src="https://demo.opencart.com/image/cache/catalog/demo/manufacturer/starbucks-130x100.png" alt="Starbucks" class="img-fluid" />
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row justify-content-center">
                                <div class="col-2 text-center">
                                    <img src="https://demo.opencart.com/image/cache/catalog/demo/manufacturer/nintendo-130x100.png" alt="Nintendo" class="img-fluid" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="carousel-control-prev" data-bs-target="#carousel-banner-1" data-bs-slide="prev"><span class="fa fa-chevron-left"></span></button>
                    <button type="button" class="carousel-control-next" data-bs-target="#carousel-banner-1" data-bs-slide="next"><span class="fa fa-chevron-right"></span></button>
                </div>
                <?= $content ?>
            </div>
        </div>
    </div>
<?php $this->registerJs("
$(document).ready(function () {
    new bootstrap.Carousel(document.querySelector('#carousel-banner-0'), {
        ride: 'carousel',
        interval: 5000,
        wrap: true
    });
});") ?>

<?php $this->registerJs("
$(document).ready(function () {
    new bootstrap.Carousel(document.querySelector('#carousel-banner-1'), {
        ride: 'carousel',
        interval: 5000,
        wrap: true
    });
});") ?>

<?php $this->endContent() ?>