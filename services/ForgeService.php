<?php

namespace app\services;

use yii\base\Component;

/**
 * @property-read bool $isDotEnvLoaded
 */
class ForgeService extends Component
{
    public $menu;

    protected $_isDotEnvLoaded = false;

    public function init()
    {
        if ($_ENV){
            $this->_isDotEnvLoaded = true;
        }
    }

    /**
     * @return bool
     */
    public function getIsDotEnvLoaded(): bool
    {
        return $this->_isDotEnvLoaded;
    }
}