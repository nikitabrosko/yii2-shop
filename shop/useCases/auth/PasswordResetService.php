<?php

namespace shop\useCases\auth;

use shop\entities\user\User;
use shop\forms\auth\PasswordResetRequestForm;
use shop\forms\auth\ResetPasswordForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\mail\MailerInterface;

class PasswordResetService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function requestPasswordReset(PasswordResetRequestForm $form) : bool {
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $form->email,
        ]);

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        if (!$this
            ->mailer
            ->compose(
                ['html' => 'auth/reset/confirm-html', 'text' => 'auth/reset/confirm-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($user->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send()) {
            throw new \DomainException('Sorry, we are unable to send an email.');
        }

        return true;
    }

    public function resetPassword(string $token, ResetPasswordForm $form) : bool
    {
        if (empty($token)) {
            throw new InvalidArgumentException('Password reset token cannot be blank.');
        }

        $user = User::findByPasswordResetToken($token);

        if (!$user) {
            throw new InvalidArgumentException('Wrong password reset token.');
        }

        $user->setPassword($form->password);
        $user->removePasswordResetToken();
        $user->generateAuthKey();

        return $user->save(false);
    }
}