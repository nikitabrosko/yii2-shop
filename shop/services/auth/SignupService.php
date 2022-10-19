<?php

namespace shop\services\auth;

use shop\access\Rbac;
use shop\forms\auth\ResendVerificationEmailForm;
use shop\services\RoleManager;
use shop\services\TransactionManager;
use Yii;
use shop\entities\user\User;
use shop\forms\auth\SignupForm;
use yii\base\InvalidArgumentException;
use yii\mail\MailerInterface;

class SignupService
{
    private $mailer;
    private $roleManager;
    private $transactionManager;

    public function __construct(
        MailerInterface $mailer,
        RoleManager $roleManager,
        TransactionManager $transactionManager)
    {
        $this->mailer = $mailer;
        $this->roleManager = $roleManager;
        $this->transactionManager = $transactionManager;
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup(SignupForm $form) : bool
    {
        $user = User::signup($form->username, $form->email, $form->password);

        $this->transactionManager->wrap(function () use ($user) {
            if (!$user->save()) {
                throw new \DomainException('Sorry, we are unable to sign you up.');
            }

            $this->roleManager->assign($user->id, Rbac::ROLE_USER);
        });

        if (!$this
            ->mailer
            ->compose(
                ['html' => 'auth/signup/confirm-html', 'text' => 'auth/signup/confirm-text'],
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
                ['html' => 'auth/signup/confirm-html', 'text' => 'auth/signup/confirm-text'],
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