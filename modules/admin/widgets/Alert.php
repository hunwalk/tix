<?php

namespace app\modules\admin\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;

class Alert extends Widget
{
    private $_messages;

    const SUPPORTED_KEYS = [
        'info',
        'warning',
        'danger',
        'success',
        'json'
    ];

    public function init()
    {
        parent::init();
        $this->_messages = \Yii::$app->session->getAllFlashes(true);
    }

    public function run()
    {
        foreach ($this->_messages as $messageKey => $messageText){
            if (in_array($messageKey,self::SUPPORTED_KEYS)){

                if ($messageKey === 'json'){
                    $data = json_decode($messageText,true);
                }else{
                    $data = [
                        'key' => $messageKey,
                        'message' => $messageText,
                        'title' => null,
                        'icon' => null,
                    ];
                }

                return \Yii::$app->view->render('@app/modules/admin/widgets/views/alert/alert',$data);
            }

        }
        return null;
    }

    public static function setJsonAlert($args = []){
        \Yii::$app->session->setFlash('json',json_encode($args));
    }
}