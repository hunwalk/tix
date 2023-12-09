<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\records\SystemVariable */

$this->title = Yii::t('app', 'Update System Variable: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'System Variables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="row system-variable-update">

    <div class="col-md-12">

        <?= $this->render('_form', [
        'model' => $model,
        ]) ?>

    </div>

</div>
