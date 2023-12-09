<?php

if (!defined('YII_ENV') || YII_ENV === 'dev'){
    // We are not in dev mode

    if (!function_exists('dump')){
        function dump(){
            Yii::warning('dump() method is used, but environment is not dev');
            return null;
        }
    }

}