<?php

namespace app;

use app\records\SystemVariable;
use yii\base\Application;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        \Yii::$app->language = SystemVariable::getValue('system.language','en');
        \Yii::$app->timeZone = SystemVariable::getValue('system.timeZone','Europe/Bratislava');
        \Yii::$app->formatter->defaultTimeZone = SystemVariable::getValue('system.formatter.defaultTimeZone','UTC');
    }
}