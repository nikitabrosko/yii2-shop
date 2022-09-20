<?php

namespace common\bootstrap;

use frontend\services\auth\PasswordResetService;
use frontend\services\auth\SignupService;
use frontend\services\contact\ContactService;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface {

    public function bootstrap($app) {
        $container = \Yii::$container;

        $container->setSingleton(SignupService::class, [], [
            $app->mailer
        ]);

        $container->setSingleton(PasswordResetService::class, [], [
            $app->mailer
        ]);

        $container->setSingleton(ContactService::class, [], [
            $app->mailer
        ]);
    }
}