<?php
/** @var string $key */
/** @var string $title */
/** @var string $icon */
/** @var string $message */
?>
<div class="alert alert-<?= $key ?> alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <?php if ($title): ?>
    <h5><?= $icon ? \yii\helpers\Html::tag('i','',['class' => ['icon','fa',$icon]]) : null ?><?= $title ?></h5>
    <?php endif; ?>
    <?= $message ?>
</div>