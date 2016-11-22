<?php

use yii\bootstrap\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'title.account.list');
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'id',
                'label' => Yii::t('admin', 'account.grid.id'),
            ],
            [
                'attribute' => 'username',
                'label' => Yii::t('admin', 'account.grid.username')
            ],
            [
                'attribute' => 'email',
                'label' => Yii::t('admin', 'account.grid.email'),
                'format' => 'email'
            ],
            [
                'attribute' => 'locked',
                'label' => Yii::t('admin', 'account.grid.locked'),
                'format' => 'boolean'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {disable}',
                'buttons' => [
                    'disable' => function ($url, $model) {
                        /* @var \app\models\db\Account $model */
                        if (!$model->locked) {
                            return Html::a('<span class="glyphicon glyphicon-eye-close"></span>', $url, [
                                'aria-label' => Yii::t('admin', 'account.grid.lock'),
                                'title' => Yii::t('admin', 'account.grid.lock'),
                                'data-pjax' => '0'
                            ]);
                        }

                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'aria-label' => Yii::t('admin', 'account.grid.unlock'),
                            'title' => Yii::t('admin', 'account.grid.unlock'),
                            'data-pjax' => '0'
                        ]);
                    }
                ],
                'urlCreator' => function ($action, $model) {
                    switch ($action) {
                        case 'update':
                            return [ 'account/form', 'id' => $model->id ];

                        case 'disable':
                            return [ 'account/lock', 'id' => $model->id ];

                        case 'view':
                            return [ 'account/form', 'id' => $model->id, 'view' => 'true' ];
                    }

                    return '#';
                }
            ]
        ]
    ]); ?>

</div>
