<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$common_cfg = require(__DIR__ . '/common.php');

$env_specific = load_config('commands.php');
$env_specific_local = load_config('commands.local.php');

$config = [
    'id' => $common_cfg['id'] . '-commands',
    'controllerNamespace' => 'app\commands',
    'components' => [],
    'bootstrap' => []
];

return yii\helpers\ArrayHelper::merge($common_cfg, $config, $env_specific, $env_specific_local);
