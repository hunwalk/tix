<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

\app\assets\TicoAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-full bg-gray-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>

    </style>
</head>
<body>

<?php $this->beginBody() ?>

<div class="container">
    <div class="header">
        <img src="/img/tico.png" alt="">
    </div>
    <?= $content ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
