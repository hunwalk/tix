<?php

use Da\User\Controller\AdminController;
use Da\User\Controller\SettingsController;
use yii\web\View;

return [
    'modules' => [
        'user' => [
            'prefix' => 'admin/user',
            'routes' => [
                '<id:\d+>' => 'profile/show',
                '<action:(login|logout)>' => 'security/<action>',
                '<action:(register|resend)>' => 'registration/<action>',
                'confirm/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'registration/confirm',
                'forgot' => 'recovery/request',
                'recover/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'recovery/reset',
                '<controller>' => '<controller>',
                '<controller>/<action>' => '<controller>/<action>',
            ],
            'controllerMap' => [
                'admin' => [
                    'class' => AdminController::class,
                    'layout' => '@app/modules/admin/views/layouts/main'
                ],
                'security' => [
                    'class' => \Da\User\Controller\SecurityController::class,
                    'layout' => '@app/modules/admin/views/layouts/user-login'
                ],
                'registration' => [
                    'class' => \Da\User\Controller\RegistrationController::class,
                    'layout' => '@app/modules/admin/views/layouts/user-login'
                ],
                'settings' => [
                    'class' => \app\modules\admin\controllers\user\SettingsController::class,
                    'layout' => '@app/modules/admin/views/layouts/main'
                ],
                'role' => [
                    'class' => \Da\User\Controller\RoleController::class,
                    'layout' => '@app/modules/admin/views/layouts/main'
                ],
                'recovery' => [
                    'class' => \Da\User\Controller\RecoveryController::class,
                    'layout' => '@app/modules/admin/views/layouts/user-login'
                ],
                'permission' => [
                    'class' => \Da\User\Controller\PermissionController::class,
                    'layout' => '@app/modules/admin/views/layouts/main'
                ],
                'rule' => [
                    'class' => \Da\User\Controller\RuleController::class,
                    'layout' => '@app/modules/admin/views/layouts/main'
                ],
                'profile' => [
                    'class' => \Da\User\Controller\ProfileController::class,
                    'layout' => '@app/modules/admin/views/layouts/main'
                ]
            ],
            'class' => Da\User\Module::class,
            'classMap' => [
                'Profile' => \app\modules\admin\models\Profile::class,
            ],
            'enableGdprCompliance' => true,

            // ...other configs from here: [Configuration Options](installation/configuration-options.md), e.g.
            'administrators' => ['admin'], // this is required for accessing administrative actions
            'generatePasswords' => true,
            'viewPath' => '@app/modules/admin/views/user',
//             'switchIdentitySessionKey' => 'myown_usuario_admin_user_key',
        ]
    ],
    'components' => [
        'view' => [
            'class' => View::class,
            'theme' => [
                'pathMap' => [
                    '@Da/User/resources/views' => '@app/modules/admin/views/user'
                ]
            ]
        ]
    ]
];