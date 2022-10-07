<?php

namespace common\bootstrap;

use frontend\urls\CategoryUrlRule;
use shop\readModels\shop\CategoryReadRepository;
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
    }
}