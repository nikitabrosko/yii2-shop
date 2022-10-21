<?php

namespace common\bootstrap;

use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use shop\cart\Cart;
use shop\cart\cost\calculator\DynamicCost;
use shop\cart\cost\calculator\SimpleCost;
use shop\cart\storage\CookieStorage;
use shop\cart\storage\HybridStorage;
use shop\cart\storage\SessionStorage;
use shop\useCases\auth\PasswordResetService;
use shop\useCases\auth\SignupService;
use shop\useCases\contact\ContactService;
use shop\services\yandex\ShopInfo;
use shop\services\yandex\YandexMarket;
use yii\base\BootstrapInterface;
use yii\caching\Cache;
use yii\di\Instance;
use yii\mail\MailerInterface;
use yii\rbac\ManagerInterface;

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

        $container->setSingleton(Cart::class, function () use ($app) {
            return new Cart(
                new HybridStorage($app->get('user'), 'cart', 3600 * 24, $app->db),
                new DynamicCost(new SimpleCost())
            );
        });

        $container->set(CKEditor::class, [
            'editorOptions' => ElFinder::ckeditorOptions('elfinder'),
        ]);

        $container->setSingleton(ManagerInterface::class, function () use ($app) {
            return $app->authManager;
        });

        $container->setSingleton(YandexMarket::class, [], [
            new ShopInfo($app->name, $app->name, $app->params['frontendHostInfo']),
        ]);
    }
}