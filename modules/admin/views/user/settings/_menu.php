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
use yii\widgets\Menu;

/** @var \Da\User\Model\User $user */
$user = Yii::$app->user->identity;
$module = Yii::$app->getModule('user');
$networksVisible = count(Yii::$app->authClientCollection->clients) > 0;

?>
<div class="card card-default">
    <div class="card-header d-flex p-3 align-items-center ">

        <div class="p-1">
            <?= Html::img(
                $user->profile->getAvatarUrl(50),
                [
                    'class' => 'img-rounded',
                    'alt' => $user->profile->name ?: $user->username,
                ]
            ) ?>
        </div>

        <h3 class="cart-title m-0 pl-3">
            <?= $user->profile->name ?: $user->username ?>
        </h3>


    </div>
    <div class="card-body">
        <?= \yii\bootstrap4\Nav::widget(
            [
                'options' => [
                    'class' => 'nav nav-pills ml-auto p-2 d-flex flex-column',
                ],
                'items' => [
                    ['label' => Yii::t('usuario', 'Profile'), 'url' => ['/user/settings/profile']],
                    ['label' => Yii::t('usuario', 'Account'), 'url' => ['/user/settings/account']],
                    ['label' => Yii::t('usuario', 'Privacy'),
                        'url' => ['/user/settings/privacy'],
                        'visible' => $module->enableGdprCompliance,
                    ],
                    [
                        'label' => Yii::t('usuario', 'Networks'),
                        'url' => ['/user/settings/networks'],
                        'visible' => $networksVisible,
                    ],
                ],
            ]
        ) ?>
    </div>
</div>
