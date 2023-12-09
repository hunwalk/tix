<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use Da\User\Widget\ConnectWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View            $this
 * @var \Da\User\Form\LoginForm $model
 * @var \Da\User\Module         $module
 */

$this->title = Yii::t('usuario', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/shared/_alert', ['module' => Yii::$app->getModule('user')]) ?>

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
                        'validateOnBlur' => false,
                        'validateOnType' => false,
                        'validateOnChange' => false,
                    ]
                ) ?>

                <?= $form->field(
                    $model,
                    'login',
                    ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']]
                ) ?>

                <?= $form
                    ->field(
                        $model,
                        'password',
                        ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']]
                    )
                    ->passwordInput()
                    ->label(
                        Yii::t('usuario', 'Password')
                        . ($module->allowPasswordRecovery ?
                            ' (' . Html::a(
                                Yii::t('usuario', 'Forgot password?'),
                                ['/user/recovery/request'],
                                ['tabindex' => '5']
                            )
                            . ')' : '')
                    ) ?>

                <?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '4']) ?>

                <?= Html::submitButton(
                    Yii::t('usuario', 'Sign in'),
                    ['class' => 'btn btn-primary btn-block', 'tabindex' => '3']
                ) ?>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex p-2 flex-column">
                            <?php if ($module->enableEmailConfirmation): ?>
                                <?= Html::a(
                                    Yii::t('usuario', 'Didn\'t receive confirmation message?'),
                                    ['/user/registration/resend'],
                                    ['class' => 'btn btn-default', 'style' => ['margin-bottom' => '10px']]
                                ) ?>
                            <?php endif ?>
                            <?php if ($module->enableRegistration): ?>

                                <?= Html::a(Yii::t('usuario', 'Don\'t have an account? Sign up!'), ['/user/registration/register'],['class' => 'btn btn-default']) ?>

                            <?php endif ?>
                            <?= ConnectWidget::widget(
                                [
                                    'baseAuthUrl' => ['/user/security/auth'],
                                ]
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>
