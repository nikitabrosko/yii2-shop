<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var \shop\forms\auth\LoginForm $model */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-6">
        <div class="card mb-3">
            <div class="card-body">
                <h2>New Customer</h2>
                <p><strong>Register Account</strong></p>
                <p>By creating an account you will be able to shop faster, be up to date on an order's status,
                    and keep track of the orders you have previously made.</p>
                <a href="<?= Html::encode(Url::to(['/auth/signup/signup'])) ?>" class="btn btn-primary">Continue</a>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h2>Social Login</h2>
                <?= yii\authclient\widgets\AuthChoice::widget([
                    'baseAuthUrl' => ['auth/network/auth']
                ]); ?>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card mb-3">
            <div class="card-body">
                <h2>Returning Customer</h2>
                <p><strong>I am a returning customer</strong></p>

                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <div class="mb-3">
                    <?= $form->field($model, 'username')->textInput() ?>
                </div>

                <div class="mb-3">
                    <?= $form->field($model, 'password')->passwordInput() ?>
                </div>

                <div class="mb-3">
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                </div>

                <div style="color:#999;margin:1em 0">
                    If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                    <br>
                    Need new verification email? <?= Html::a('Resend', ['site/resend-verification-email']) ?>
                </div>

                <div>
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
