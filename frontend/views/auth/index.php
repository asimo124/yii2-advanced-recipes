<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = \Yii::$app->name . ' | OAuth2 Login';

?>
<div class="card login-box">
    <div class="card-head">
        <?= Html::img('/eSuite.Logo.Horizontal.png', ['alt' => 'eSUITE']); ?>
    </div>
    <div class="card-body">
        <h4>Log in</h4>
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($loginFormModel, 'username', ['inputOptions' => ['placeholder'=>'Email Address','autofocus' => 'autofocus', 'class' => 'form-control transparent']])->textInput()->label(false) ?>
        <?= $form->field($loginFormModel, 'password', ['inputOptions' => ['placeholder'=>'Password','class' => 'form-control transparent']])->passwordInput()->label(false) ?>
        <div class="form-group">

        </div>

        <div class="row">
            <div class="col-xs-12">
                <?= Html::submitButton('Log In', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <div style="clear: both; height: 12px;"></div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
