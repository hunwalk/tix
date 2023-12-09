<?php

namespace app\assets;

use Yii;
use yii\web\AssetBundle;

class AdminPluginAsset extends AssetBundle
{
    public $sourcePath = '@bower/adminlte/plugins';
    public $baseUrl = '@web/adminlte-plugins';

    public $css = [
        'dropzone/min/dropzone.min.css',
        'fullcalendar/main.min.css',
    ];

    public $js = [
        'jquery-ui/jquery-ui.min.js',
        'dropzone/min/dropzone.min.js',
        'fullcalendar/main.min.js',
    ];

    public $depends = [
        'app\assets\AdminAsset',
    ];

    public static function getBaseUrl(){
        return Yii::$app->assetManager->getBundle(AdminPluginAsset::class)->baseUrl;
    }
}