<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                   $this
 * @var \Da\User\Form\RegistrationForm $model
 * @var \Da\User\Model\User            $user
 * @var \Da\User\Module                $module
 */

$this->title = Yii::t('usuario', 'Sign up');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="card-body">
                <?php $form = ActiveForm::begin(
                    [
                        'id' => $model->formName(),
                        'enableAjaxValidation' => true,
                        'enableClientValidation' => false,
                    ]
                ); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'username') ?>

                <?php if ($module->generatePasswords === false): ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                <?php endif ?>

                <?php if ($module->enableGdprCompliance): ?>
                    <?= $form->field($model, 'gdpr_consent')->checkbox(['value' => 1]) ?>
                <?php endif ?>

                <?= Html::submitButton(Yii::t('usuario', 'Sign up'), ['class' => 'btn btn-success btn-block']) ?>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="card-footer d-flex p-2 flex-column">
                <?= Html::a(Yii::t('usuario', 'Already registered? Sign in!'), ['/user/security/login'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
        <br>

    </div>
</div>
