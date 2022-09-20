<?php

namespace frontend\services\auth;

use frontend\forms\ResendVerificationEmailForm;
use Yii;
use common\entities\User;
use frontend\forms\SignupForm;
use yii\base\InvalidArgumentException;
use yii\mail\MailerInterface;

class SignupService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup(SignupForm $form) : bool
    {
        $user = User::signup($form->username, $form->email, $form->password);

        if (!$user->save()) {
            throw new \DomainException('Sorry, we are unable to sign you up.');
        }

        if (!$this
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($user->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send()) {
            throw new \DomainException('Sorry, we are unable to send an email.');
        }

        return true;
    }

    public function resendVerificationEmail(ResendVerificationEmailForm $form) : bool {
        $user = User::findOne([
            'email' => $form->email,
            'status' => User::STATUS_INACTIVE
        ]);

        if ($user === null) {
            return false;
        }

        if (!$this
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($user->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send()) {
            throw new \DomainException('Sorry, we are unable to send an email.');
        }

        return true;
    }

    public function verifyEmail(string $token) : ?User {
        if (empty($token)) {
            throw new InvalidArgumentException('Verify email token cannot be blank.');
        }

        $user = User::findByVerificationToken($token);

        if (!$user) {
            throw new InvalidArgumentException('Wrong verify email token.');
        }

        $user->status = User::STATUS_ACTIVE;
        return $user->save(false) ? $user : null;
    }
}