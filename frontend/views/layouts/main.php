<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html dir="ltr" lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link href="<?= Html::encode(Url::canonical()) ?>" rel="canonical">
    <link href="<?= Yii::getAlias('@web/img/catalog/cart.png') ?>" rel="icon">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<nav id="top">
    <div id="alert" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>
    <div class="container">
        <div class="nav float-start">
            <ul class="list-inline">
                <li class="list-inline-item"> <form action="https://demo.opencart.com/index.php?route=common/currency|save&amp;language=en-gb" method="post" enctype="multipart/form-data" id="form-currency">
                        <div class="dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="dropdown-toggle"><strong>$</strong> <span class="d-none d-md-inline">Currency</span> <i class="fas fa-caret-down"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="EUR" class="dropdown-item">€ Euro</a></li>
                                <li><a href="GBP" class="dropdown-item">£ Pound Sterling</a></li>
                                <li><a href="USD" class="dropdown-item">$ US Dollar</a></li>
                            </ul>
                        </div>
                        <input type="hidden" name="code" value="" /> <input type="hidden" name="redirect" value="https://demo.opencart.com/index.php?route=common/home" />
                    </form>
                </li>
                <li class="list-inline-item"></li>
            </ul>
        </div>
        <div class="nav float-end">
            <ul class="list-inline">
                <li class="list-inline-item"><a href="https://demo.opencart.com/index.php?route=information/contact&amp;language=en-gb"><i class="fas fa-phone"></i></a> <span class="d-none d-md-inline">123456789</span></li>
                <li class="list-inline-item">
                    <div class="dropdown">
                        <a href="" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-user"></i> <span class="d-none d-md-inline">My Account</span> <i class="fas fa-caret-down"></i></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <?php if (Yii::$app->user->isGuest): ?>
                                <li class="nav-item"><a href="<?= Html::encode(Url::to(['/auth/login'])) ?>" class="nav-link">Login</a></li>
                                <li class="nav-item"><a href="<?= Html::encode(Url::to(['/auth/signup'])) ?>" class="nav-link">Signup</a></li>
                            <?php else: ?>
                                <li class="nav-item"><a href="<?= Html::encode(Url::to(['/cabinet/default/cabinet'])) ?>" class="nav-link">Cabinet</a></li>
                                <li class="nav-item"><a href="<?= Html::encode(Url::to(['/auth/logout'])) ?>" data-method="post" class="nav-link">Logout</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
                <li class="list-inline-item"><a href="<?= Html::encode(Url::to(['/cabinet/wishlist/wishlist'])) ?>" id="wishlist-total" title="Wish List (0)"><i class="fas fa-heart"></i> <span class="d-none d-md-inline">Wish List (0)</span></a></li>
                <li class="list-inline-item"><a href="" title="Shopping Cart"><i class="fas fa-shopping-cart"></i> <span class="d-none d-md-inline">Shopping Cart</span></a></li>
                <li class="list-inline-item"><a href="" title="Checkout"><i class="fas fa-share"></i> <span class="d-none d-md-inline">Checkout</span></a></li>
            </ul>
        </div>
    </div>
</nav>
<header>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-lg-4">
                <div id="logo">
                    <a href="<?= Html::encode(Url::to(['/site/index'])) ?>"><img src="https://demo.opencart.com/image/catalog/opencart-logo.png" title="Your Store" alt="Your Store" class="img-fluid" /></a>
                </div>
            </div>
            <div class="col-md-5">
                <?= Html::beginForm(['/shop/catalog/search'], 'get') ?>
                <div id="search" class="input-group mb-3">
                    <input type="text" name="text" value="" placeholder="Search" class="form-control form-control-lg"/>
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-light btn-lg"><i class="fa fa-search"></i></button>
                    </span>
                </div>
                <?= Html::endForm() ?>
            </div>
            <div id="header-cart" class="col-md-4 col-lg-3"><div class="dropdown d-grid">
                    <button type="button" data-bs-toggle="dropdown" class="btn btn-inverse btn-block dropdown-toggle"><i class="fas fa-shopping-cart"></i> 0 item(s) - $0.00</button>
                    <ul class="dropdown-menu dropdown-menu-right" style="width: 500px;">
                        <li>
                            <p class="text-center">Your shopping cart is empty!</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
<main>
    <div class="container">
        <nav id="menu" class="navbar navbar-expand-lg navbar-light bg-primary">
            <div id="category" class="d-block d-sm-block d-lg-none"></div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#narbar-menu"><i class="fas fa-bars"></i></button>
            <div class="collapse navbar-collapse" id="narbar-menu">
                <ul class="nav navbar-nav">
                    <li class="nav-item"><a href="<?= Html::encode(Url::to(['/site/index'])) ?>" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="<?= Html::encode(Url::to(['/catalog'])) ?>" class="nav-link">Catalog</a></li>
                    <li class="nav-item"><a href="<?= Html::encode(Url::to(['/site/about'])) ?>" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="<?= Html::encode(Url::to(['/contact'])) ?>" class="nav-link">Contact</a></li>
                    <!--<li class="nav-item dropdown"><a href="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Desktops</a>
                        <div class="dropdown-menu">
                            <div class="dropdown-inner">
                                <ul class="list-unstyled">
                                    <li><a href="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20_26" class="nav-link">PC (0)</a></li>
                                    <li><a href="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20_27" class="nav-link">Mac (1)</a></li>
                                </ul>
                            </div>
                            <a href="https://demo.opencart.com/index.php?route=product/category&amp;language=en-gb&amp;path=20" class="see-all">Show All Desktops</a>
                        </div>
                    </li>-->
                </ul>
            </div>
        </nav>
    </div>
    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>
<footer>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <h5>Information</h5>
                <ul class="list-unstyled">
                    <li><a href="https://demo.opencart.com/index.php?route=information/information&amp;language=en-gb&amp;information_id=2">Terms &amp; Conditions</a></li>
                    <li><a href="https://demo.opencart.com/index.php?route=information/information&amp;language=en-gb&amp;information_id=4">Delivery Information</a></li>
                    <li><a href="https://demo.opencart.com/index.php?route=information/information&amp;language=en-gb&amp;information_id=1">About Us</a></li>
                    <li><a href="https://demo.opencart.com/index.php?route=information/information&amp;language=en-gb&amp;information_id=3">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="col-sm-3">
                <h5>Customer Service</h5>
                <ul class="list-unstyled">
                    <li><a href="https://demo.opencart.com/index.php?route=information/contact&amp;language=en-gb">Contact Us</a></li>
                    <li><a href="https://demo.opencart.com/index.php?route=account/returns|add&amp;language=en-gb">Returns</a></li>
                    <li><a href="https://demo.opencart.com/index.php?route=information/sitemap&amp;language=en-gb">Site Map</a></li>
                </ul>
            </div>
            <div class="col-sm-3">
                <h5>Extras</h5>
                <ul class="list-unstyled">
                    <li><a href="https://demo.opencart.com/index.php?route=product/manufacturer&amp;language=en-gb">Brands</a></li>
                    <li><a href="https://demo.opencart.com/index.php?route=checkout/voucher&amp;language=en-gb">Gift Certificates</a></li>
                    <li><a href="https://demo.opencart.com/index.php?route=account/affiliate&amp;language=en-gb">Affiliate</a></li>
                    <li><a href="https://demo.opencart.com/index.php?route=product/special&amp;language=en-gb">Specials</a></li>
                </ul>
            </div>
            <div class="col-sm-3">
                <h5>My Account</h5>
                <ul class="list-unstyled">
                    <li><a href="https://demo.opencart.com/index.php?route=account/account&amp;language=en-gb">My Account</a></li>
                    <li><a href="https://demo.opencart.com/index.php?route=account/order&amp;language=en-gb">Order History</a></li>
                    <li><a href="https://demo.opencart.com/index.php?route=account/wishlist&amp;language=en-gb">Wish List</a></li>
                    <li><a href="https://demo.opencart.com/index.php?route=account/newsletter&amp;language=en-gb">Newsletter</a></li>
                </ul>
            </div>
        </div>
        <hr>
        <p>Powered By <a href="https://www.opencart.com">OpenCart</a><br /> Your Store &copy; 2022</p>

    </div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
