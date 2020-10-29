<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

if (file_exists(__DIR__ . '/db-local.php')) {
    $dblocal = require __DIR__ . '/db-local.php';
    $db = yii\helpers\ArrayHelper::merge(
        $db,
        $dblocal
    );
}

if (file_exists(__DIR__ . '/params-local.php')) {
    $paramslocal = require __DIR__ . '/params-local.php';
    $params = yii\helpers\ArrayHelper::merge(
        $params,
        $paramslocal
    );
}

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

if (file_exists(__DIR__ . '/console-local.php')) {
    $configlocal = require __DIR__ . '/console-local.php';
    $config = yii\helpers\ArrayHelper::merge(
        $config,
        $configlocal
    );
}

return $config;
