<?php

namespace app\assets;

use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@bower/adminlte/plugins/fontawesome-free';
    public $baseUrl = '@web/fontawesome';

    public $css = [
        'css/all.min.css',
    ];
}