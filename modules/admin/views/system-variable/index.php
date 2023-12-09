<?php

use app\records\SystemVariable;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\records\search\SystemVariableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'System Variables');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card card-default">

    <div class="card-header d-flex flex-row p-3 justify-content-between align-items-center">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        <?= Html::a(Yii::t('app', 'Create System Variable'), ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <div class="card-body">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'key',
                'value:ntext',
                'value_type',
                [
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, SystemVariable $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    }
                ],
            ],
        ]); ?>
    </div>




</div>
