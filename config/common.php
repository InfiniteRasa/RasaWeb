<?php

require(__DIR__ . '/loader.php');

$env_specific = load_config('common.php');
$env_specific_local = load_config('common.local.php');

$common = [
    'id' => 'rasanet-web',
    'name' => 'Rasa.NET Portal',
    'basePath' => dirname(__DIR__),
    'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
    'bootstrap' => [ 'log' ],
    'sourceLanguage' => '00',
    'language' => 'en',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => [ 'error', 'warning' ],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'admin' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/modules/admin/messages',
                    'fileMap' => [
                        'admin' => 'admin.php'
                    ]
                ],
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'app' => 'app.php',
                        'const' => 'const.php'
                    ],
                ]
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'charset' => 'utf8'
        ]
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'defaultRoute' => 'user'
        ]
    ],
    'params' => [
        'adminEmail' => 'admin@example.com',
        'registrationEnabled' => true
    ]
];

return yii\helpers\ArrayHelper::merge($common, $env_specific, $env_specific_local);
