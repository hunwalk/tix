<?php

namespace app\modules\admin;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    public $layoutPath = '@admin/views/layout';

    public $layout = 'main';

    public $defaultRoute = 'dashboard';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();



        // custom initialization code goes here
    }
}
