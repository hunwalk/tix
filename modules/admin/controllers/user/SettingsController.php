<?php

namespace app\modules\admin\controllers\user;

use \Da\User\Controller\SettingsController as BaseSettingsController;
use Da\User\Event\ProfileEvent;
use Da\User\Event\UserEvent;
use Yii;
use yii\web\UploadedFile;

class SettingsController extends BaseSettingsController
{
    public function init()
    {
        parent::init();
        $this->on(UserEvent::EVENT_AFTER_PROFILE_UPDATE,function (ProfileEvent $event){
            if (Yii::$app->request->isPost) {
                $event->profile->avatarFile = UploadedFile::getInstance($event->profile, 'avatarFile');
                $uploaded = $event->profile->uploadAvatar();
                if ($uploaded){
                    $event->profile->save();
                }
            }
        });
    }
}