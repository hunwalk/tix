<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card card-primary <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">
    <div class="card-header">
        <h3 class="card-title"><?= "<?= "?>$this->title<?=" ?>" ?></h3>
    </div>
    <?= "<?php " ?>$form = ActiveForm::begin(); ?>
    <div class="card-body">
        <?php foreach ($generator->getColumnNames() as $attribute) {
            if (in_array($attribute, $safeAttributes)) {
                echo "      <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
            }
        } ?>
    </div>

    <div class="card-footer">
        <?= "<?= " ?>Html::submitButton(<?= $generator->generateString('Save') ?>, ['class' => 'btn btn-success']) ?>
    </div>
    <?= "<?php " ?>ActiveForm::end(); ?>
</div>
