<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\modules\admin\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use app\assets\AdminAsset;

AdminAsset::register($this);
$adminAssetUrl = AdminAsset::getBaseUrl();
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="login-page">
    <?php $this->beginBody() ?>
    <div class="login-box">
        <div class="login-logo">
            <img style="width: 100px" src="/img/forge-logo.png" alt=""><br>
            <a href="/">Yii2 Forge</a>
        </div>

        <?= $content ?>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>
