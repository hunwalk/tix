<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\records\SystemVariable */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card card-primary system-variable-form">
    <div class="card-header">
        <h3 class="card-title"><?= $this->title ?></h3>
    </div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="card-body">
              <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>

      <?= $form->field($model, 'value')->textarea(['rows' => 6]) ?>

      <?= $form->field($model, 'value_type')->dropDownList($model->valueTypes()) ?>

    </div>

    <div class="card-footer">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
