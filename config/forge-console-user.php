<?php

return [
    'controllerMap' => [
        'migrate-user' => [
            'class' => \yii\console\controllers\MigrateController::class,
            'migrationPath' => [
                '@yii/rbac/migrations', // Just in case you forgot to run it on console (see next note)
            ],
            'migrationNamespaces' => [
                'Da\User\Migration',
            ],
        ],
    ],
    'modules' => [
        'user' => [
            'class' => Da\User\Module::class,
            'administrators' => ['admin'],
            'generatePasswords' => true,
        ]
    ],
];