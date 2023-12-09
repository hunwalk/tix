<?php

use app\services\ForgeService;
use yii\console\Application;
use yii\web\User;

/**
 * This class only exists here for IDE (PHPStorm/Netbeans/...) autocompletion.
 * This file is never included anywhere.
 * Adjust this file to match classes configured in your application config, to enable IDE autocompletion for custom components.
 * Example: A property phpdoc can be added in `__Application` class as `@property Rollbar|__Rollbar $rollbar` and adding a class in this file
 * ```php
 */
class Yii {
    /**
     * @var \yii\web\Application|\yii\console\Application|__Application
     */
    public static $app;
}

/**
 * @property yii\rbac\DbManager $authManager 
 * @property User|__WebUser $user
 * @property ForgeService $forge Forge service
 */
class __Application {
}

/**
 * @property app\models\User $identity
 */
class __WebUser {
}
