<?php
/** @var array $item */

if (is_array($item)){
    $url = \yii\helpers\ArrayHelper::getValue($item,'url');
    $icon = \yii\helpers\ArrayHelper::getValue($item,'icon');
    $name = \yii\helpers\ArrayHelper::getValue($item,'name');
    $active = \yii\helpers\ArrayHelper::getValue($item,'active');
    $visible = \yii\helpers\ArrayHelper::getValue($item,'visible');
    $dataMethod = \yii\helpers\ArrayHelper::getValue($item,'data-method');
    $items = \yii\helpers\ArrayHelper::getValue($item,'items');
}

?>
    <?php if (is_string($item)): ?>
        <li class="nav-header">EXAMPLES</li>
    <?php endif; ?>

    <?php if (is_array($item)): ?>
        <li class="nav-item <?= $active && $items ? 'menu-open' : null ?>">
            <a href="<?= \yii\helpers\Url::to($url) ?>" class="nav-link <?= $active ? 'active' : null ?>" <?= $dataMethod ? 'data-method="'.$dataMethod.'"' : null ?>>
                <i class="nav-icon fas fa-<?= $icon ?>"></i>
                <p>
                    <?= $name ?>
                    <?php if ($items): ?>
                        <i class="right fas fa-angle-left"></i>
                    <?php endif; ?>
                </p>
            </a>

            <?php if ($items): ?>
                <ul class="nav nav-treeview">
                    <?php foreach ($items as $subItem): ?>
                    <?php
                        $subItemUrl = \yii\helpers\ArrayHelper::getValue($subItem,'url');
                        $subItemIcon = \yii\helpers\ArrayHelper::getValue($subItem,'icon');
                        $subItemName = \yii\helpers\ArrayHelper::getValue($subItem,'name');
                        $subItemActive = \yii\helpers\ArrayHelper::getValue($subItem,'active');
                    ?>
                    <li class="nav-item">
                        <a href="<?= \yii\helpers\Url::to($subItemUrl) ?>" class="nav-link <?= $subItemActive ? 'active' : null ?>">
                            <i class="fas fa-<?= $subItemIcon?> nav-icon"></i>
                            <p><?= $subItemName ?></p>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
    <?php endif; ?>
