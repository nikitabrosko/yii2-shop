<?php

namespace common\bootstrap;

use shop\services\auth\PasswordResetService;
use shop\services\auth\SignupService;
use shop\services\contact\ContactService;
use yii\base\BootstrapInterface;
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
    }
}