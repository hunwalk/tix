<?php

namespace app\assets;

use Yii;
use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $sourcePath = '@bower/adminlte/dist';
    public $baseUrl = '@web/adminlte';

    public $css = [
        'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback',
        'css/adminlte.min.css',
    ];

    public $js = [
        'js/adminlte.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'app\assets\FontAwesomeAsset',
    ];

    public static function getBaseUrl(){
        return Yii::$app->assetManager->getBundle(AdminAsset::class)->baseUrl;
    }
}