<?php

/* @var \app\models\db\Account $model */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$form = ActiveForm::begin([
    'id' => 'register-form',
    'options' => [ 'class' => 'form-horizontal' ],
    'action' => [ 'site/register' ],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
        'labelOptions' => [ 'class' => 'col-lg-3 control-label' ],
    ],
]);

$this->title = Yii::t('app', 'title.register');

?>

<?= $form->field($model, 'username')->textInput() ?>

<?= $form->field($model, 'email')->textInput() ?>

<?= $form->field($model, 'password')->passwordInput() ?>

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-9">
        <?= Html::submitButton(Yii::t('app', 'button.register'), [ 'class' => 'btn btn-primary', 'name' => 'register-button' ]) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
