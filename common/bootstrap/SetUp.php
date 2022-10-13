<?php

namespace common\bootstrap;

use shop\cart\Cart;
use shop\cart\cost\calculator\DynamicCost;
use shop\cart\cost\calculator\SimpleCost;
use shop\cart\storage\CookieStorage;
use shop\cart\storage\SessionStorage;
use shop\services\auth\PasswordResetService;
use shop\services\auth\SignupService;
use shop\services\contact\ContactService;
use yii\base\BootstrapInterface;
use yii\caching\Cache;
use yii\di\Instance;
use yii\mail\MailerInterface;

class SetUp implements BootstrapInterface {

    public function bootstrap($app) {
        $container = \Yii::$container;

        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });

        $container->setSingleton(SignupService::class, [], [
            Instance::of(MailerInterface::class)
        ]);

        $container->setSingleton(PasswordResetService::class, [], [
            Instance::of(MailerInterface::class)
        ]);

        $container->setSingleton(ContactService::class, [], [
            Instance::of(MailerInterface::class)
        ]);

        $container->setSingleton(Cache::class, function () use ($app) {
            return $app->cache;
        });

        $container->setSingleton(Cart::class, function () {
            return new Cart(
                new CookieStorage('cart', strtotime('+1 year', time())),
                new DynamicCost(new SimpleCost())
            );
        });
    }
}