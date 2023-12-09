<?php

namespace app\modules\admin\controllers;

use app\modules\admin\widgets\Alert;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Default controller for the `admin` module
 */
class DashboardController extends Controller
{
    public function behaviors()
    {
        return [
            'accessControl' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if (!\Yii::$app->forge->isDotEnvLoaded){
            \Yii::$app->session->setFlash('danger','.env file is missing or not loaded properly');
        }
        return $this->render('index');
    }
}
