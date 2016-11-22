<?php

$common_cfg = require( __DIR__ . '/common.php');

$env_specific = load_config('web.php');
$env_specific_local = load_config('web.local.php');

$cookieSuffix = '_' . md5($common_cfg['id'] . '$' . (isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : ''));

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'whAmz6_-lekoAiLIhgtiLWj35aflj_',
            'csrfParam' => '_csrf' . $cookieSuffix
        ],
        'session' => [
            'name' => '_session' . $cookieSuffix
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache'
        ],
        'user' => [
            'identityClass' => 'app\models\db\Account',
            'enableAutoLogin' => false,
            'identityCookie' => [ 'name' => '_identity' . $cookieSuffix, 'httpOnly' => true ],
            'autoRenewCookie' => true
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                ''                            => 'site/index',
                'admin/accounts'              => 'admin/account/list',
                'admin/account/<id:\d+>'      => 'admin/account/form',
                'admin/account/<id:\d+>/lock' => 'admin/account/lock',

                'validate/<token:\w+>'        => 'site/validate'
            ]
        ]
    ]
];

return yii\helpers\ArrayHelper::merge($common_cfg, $config, $env_specific, $env_specific_local);

