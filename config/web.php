<?php

// preloading forge stuff
$forge = require __DIR__ . '/forge-web.php';

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['app\Bootstrap','log'],
    'timeZone' => 'Europe/Bratislava',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@admin' => '@app/modules/admin',
    ],
    'modules' => [
        'admin' => \app\modules\admin\Module::class
    ],
    'components' => [
        'assetManager' => [
            'bundles' => [
                'dosamigos\selectize\SelectizeAsset' => [
                    'sourcePath' => null,
                    'baseUrl' => '@web',
                    'basePath' => '@webroot',
                    'css' => [
                        'css/selectize.bootstrap4.css'
                    ],
                    'js' => [
                        'js/selectize.js'
                    ],
                    'depends' => [
                        'yii\bootstrap4\BootstrapAsset',
                        'yii\web\JqueryAsset',
                    ]
                ],
            ],
        ],
        'forge' => \app\services\ForgeService::class,
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Ery99--11dozmRzWKGskwpEfw__yJObv',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ''=>'site/index',
                'privacy-policy'=>'site/privacy',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [ //here
            'crud' => [ // generator name
                'class' => 'yii\gii\generators\crud\Generator', // generator class
                'templates' => [ //setting for out templates
                    'adminlte' => '@app/db/generators/crud/adminlte', // template name => path to template
                ]
            ]
        ],
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

$config = \yii\helpers\ArrayHelper::merge($forge,$config);

return $config;
