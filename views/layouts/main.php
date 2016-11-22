<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    $items = [
        [ 'label' => Yii::t('app', 'menu.home'), 'url' => [ '/site/index' ] ]
    ];

    if (Yii::$app->user->isGuest) {
        $items[] = [ 'label' => Yii::t('app', 'menu.login'), 'url' => [ '/site/login' ] ];
        $items[] = [ 'label' => Yii::t('app', 'menu.register'), 'url' => [ '/site/register' ] ];
    } else {
        if (Yii::$app->user->identity->level >= 10) {
            $items[] = [ 'label' => Yii::t('app', 'menu.admin'), 'url' => [ '/admin' ] ];
        }

        $items[] = [
            'label' => Yii::t('app', 'menu.logout', [ 'name' => Yii::$app->user->identity->username ]),
            'url' => [ '/site/logout' ],
            'linkOptions' => [ 'data-method' => 'post' ]
        ];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right' ],
        'items' => $items
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
