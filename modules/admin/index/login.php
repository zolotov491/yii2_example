<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\LoginForm */
use kartik\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = Yii::t('app', 'Enter');
?>

<div class="col-md-4 col-md-offset-4 text-center">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'Please fill out the following fields to login') ?></p>

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>

    <?= Html::icon('user') . $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
    <?= Html::icon('lock') . $form->field($model, 'password')->passwordInput() ?>
    <?= Html::icon('heart') . $form->field($model, 'rememberMe')->checkbox() ?>

    <div style="color:#999">
        <?= Yii::t('app', 'If you forgot your password you can'); ?>
        <?= Html::a(Yii::t('app', 'reset it'), ['/site/request-password-reset']) ?>.
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'SignIn'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>