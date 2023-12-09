<?php

namespace app\controllers;

use app\modules\admin\models\DatabaseConfigForm;
use yii\web\Controller;

class InstallController extends Controller
{
    public $defaultAction = 'database-config';

    public $layout = 'install';

    public function actionDatabaseConfig(){
        $databaseConfigForm = new DatabaseConfigForm();

        if ($databaseConfigForm->load(\Yii::$app->request->post())){
            $databaseConfigForm->write();
        }

        return $this->render('config',[
            'databaseConfigForm' => $databaseConfigForm
        ]);
    }
}