<?php

// comment out the following two lines when deployed to production
//defined('YII_DEBUG') or define('YII_DEBUG', true);
//defined('YII_ENV') or define('YII_ENV', 'dev');

// composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// custom yii classmap
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

//load helper stuff
require __DIR__ . "/../lib/helper_functions.php";

// load web preloader
require __DIR__ . "/../lib/web_preload.php";

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
