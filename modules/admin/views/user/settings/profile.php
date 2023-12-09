<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use Da\User\Helper\TimezoneHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View           $this
 * @var yii\widgets\ActiveForm $form
 * @var \Da\User\Model\Profile $model
 * @var TimezoneHelper         $timezoneHelper
 */

$this->title = Yii::t('usuario', 'Profile settings');
$this->params['breadcrumbs'][] = $this->title;
$timezoneHelper = $model->make(TimezoneHelper::class);

\app\assets\AdminPluginAsset::register($this);
?>

<div class="clearfix"></div>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu') ?>
    </div>
    <div class="col-md-9">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="card-body">
                <?php $form = ActiveForm::begin(
                    [
                        'id' => $model->formName(),
                        'options' => ['class' => 'form-horizontal'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-12\">{error}\n{hint}</div>",
                            'labelOptions' => ['class' => 'col-lg-3 control-label'],
                        ],
                        'enableAjaxValidation' => true,
                        'enableClientValidation' => false,
                        'validateOnBlur' => false,
                    ]
                ); ?>

                <?= $form->field($model, 'name') ?>

                <?= $form->field($model, 'public_email') ?>

                <?= $form->field($model, 'website') ?>

                <?= $form->field($model, 'location') ?>

                <?= $form->field($model, 'avatarFile')->fileInput() ?>

                <?= $form
                    ->field($model, 'timezone')
                    ->dropDownList(ArrayHelper::map($timezoneHelper->getAll(), 'timezone', 'name'));
                ?>
                <?= $form
                    ->field($model, 'gravatar_email')
                    ->hint(
                        Html::a(
                            Yii::t('usuario', 'Change your avatar at Gravatar.com'),
                            'http://gravatar.com',
                            ['target' => '_blank']
                        )
                    ) ?>

                <?= $form->field($model, 'bio')->textarea() ?>

                <div class="form-group">
                    <div class="col-lg-12">
                        <?= Html::submitButton(Yii::t('usuario', 'Save'), ['class' => 'btn btn-block btn-success']) ?>
                        <br>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
