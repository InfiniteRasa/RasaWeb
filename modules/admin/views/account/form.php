<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\Account */
/* @var $disabled boolean */

$this->title = Yii::t('app', $disabled ? 'title.account.view' : 'title.account.edit');

$options = [];

if (isset($disabled) && $disabled) {
    $options['disabled'] = true;
}
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput($options) ?>

    <?= $form->field($model, 'email')->textInput($options) ?>

    <?= $form->field($model, 'password')->textInput($options) ?>

    <?= $form->field($model, 'level')->textInput($options) ?>

    <?= $form->field($model, 'locked')->checkbox($options) ?>

    <?php if (!$disabled): ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'form.button.save'), [ 'class' => 'btn btn-primary' ]) ?>
        </div>
    <?php endif; ?>

    <?php ActiveForm::end(); ?>

</div>
