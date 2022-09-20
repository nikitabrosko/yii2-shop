<?php

namespace frontend\services\contact;

use frontend\forms\ContactForm;
use Yii;
use yii\mail\MailerInterface;

class ContactService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(ContactForm $form) : bool
    {
        if (!$this->mailer->compose()
            ->setTo($form->email)
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setReplyTo([$form->email => $form->name])
            ->setSubject($form->subject)
            ->setTextBody($form->body)
            ->send()) {
            throw new \DomainException('Sorry, we are unable to send an email.');
        }

        return true;
    }
}