<?php

/* @var \app\models\forms\LoginForm $model */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => [ 'class' => 'form-horizontal' ],
    'action' => [ 'site/login' ],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
        'labelOptions' => [ 'class' => 'col-lg-3 control-label' ],
    ],
]);
?>

<?= $form->field($model, 'email')->textInput() ?>

<?= $form->field($model, 'password')->passwordInput() ?>

<?= $form->field($model, 'rememberMe')->checkbox([
    'template' => "<div class=\"col-lg-offset-3 col-lg-4\">{input} {label}</div>\n<div class=\"col-lg-5\">{error}</div>",
]) ?>

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-9">
        <?= Html::submitButton(Yii::t('app', 'button.login'), [ 'class' => 'btn btn-primary', 'name' => 'login-button' ]) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
