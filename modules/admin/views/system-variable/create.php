<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\records\SystemVariable */

$this->title = Yii::t('app', 'Create System Variable');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'System Variables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row system-variable-create">

    <div class="col-md-12">

        <?= $this->render('_form', [
        'model' => $model,
        ]) ?>

    </div>

</div>
