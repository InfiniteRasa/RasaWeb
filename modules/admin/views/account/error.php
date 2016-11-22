<?php

use yii\base\UserException;
use yii\bootstrap\Html;
use yii\web\HttpException;

/* @var $this yii\web\View */
/* @var $exception \yii\web\HttpException|\Exception */

if ($exception instanceof HttpException) {
    $code = $exception->statusCode;
} else {
    $code = $exception->getCode();
}

$name = 'Error';

if ($code) {
    $name .= " (#$code)";
}

if ($exception instanceof UserException) {
    $message = $exception->getMessage();
} else {
    $message = 'An internal server error occurred.';
}

$this->title = Html::encode($name);

?>

<h1><?= Html::encode($name) ?></h1>
<h2><?= nl2br(Html::encode($message)) ?></h2>
<div class="version">
    <?= date('Y-m-d H:i:s', time()) ?>
</div>
