<?php

namespace app\modules\admin\models;

use Da\User\Model\Profile as BaseProfile;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use yii\web\UploadedFile;

/**
 * @property string $avatar
 */
class Profile extends BaseProfile
{
    /**
     * @var UploadedFile|null
     */
    public $avatarFile;

    public function rules()
    {
        $rules = parent::rules();
        $rules['avatarFile'] = ['avatarFile', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'];
        return $rules;
    }

    public function getAvatarUrl($size = 200)
    {
        if ($this->avatar){
            $imagePath = \Yii::getAlias('@webroot'.$this->avatar);
            $manager = new ImageManager(['driver' => 'gd']);
            $img = $manager->make($imagePath)->fit($size,$size);
            return $img->encode('data-url');
        }
        return parent::getAvatarUrl($size);
    }

    public function uploadAvatar()
    {
        if ($this->avatarFile && $this->validate()) {

            $relativeImageDirectory = '/media/user/avatar';
            $absoluteImageDirectory = \Yii::getAlias('@webroot'.$relativeImageDirectory);
            $filename = substr(\Yii::$app->security->generateRandomString(10),0,10) .'_'. date('YmdHis') . '.' . $this->avatarFile->extension;
            $this->avatarFile->saveAs($absoluteImageDirectory.DIRECTORY_SEPARATOR.$filename);
            $this->avatar = $relativeImageDirectory.DIRECTORY_SEPARATOR.$filename;
            $this->avatarFile = null;

            //resize
            $manager = new ImageManager(['driver' => 'gd']);
            $image = $manager->make($absoluteImageDirectory.DIRECTORY_SEPARATOR.$filename)->fit(300,300)->save($absoluteImageDirectory.DIRECTORY_SEPARATOR.$filename);
            return true;
        } else {
            return false;
        }
    }
}