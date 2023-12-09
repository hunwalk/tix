<?php

namespace app\commands;

use yii\console\controllers\HelpController as BaseHelpController;
use yii\helpers\Console;

class HelpController extends BaseHelpController
{
    protected function getDefaultHelpHeader()
    {
        $this->stdout("\nTIX v0.1 running on Yii " . \Yii::getVersion() . ".\n",Console::FG_GREEN);
    }
}