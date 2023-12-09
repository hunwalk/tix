<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\records\SystemVariable */

$this->title = $model->key;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'System Variables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$files = glob(Yii::getAlias('@runtime/forge/system/'.$model->key.'_*'));
?>
<div class="card card-default">

    <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        <div class="actions d-flex">
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary mr-3']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h3>Details</h3>
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'key',
                        'value:ntext',
                        'value_type',
                    ],
                ]) ?>
            </div>
            <div class="col-md-6">
                <h3>Uses</h3>
                <div class="d-flex flex-column">
                    <?php foreach ($files as $file): ?>
                        <?php $data = json_decode(file_get_contents($file),true) ?>
                        <div class="card card-success">
                            <div class="card-body">
                                <table class="table table-striped table-bordered detail-view">
                                    <tbody>
                                        <tr>
                                            <th>File</th>
                                            <td><code><?= $data['file'].':'.$data['line'] ?></code></td>
                                        </tr>
                                        <tr>
                                            <th>Value</th>
                                            <td><?= $data['value'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Used</th>
                                            <td><code><?= date('Y-m-d H:i:s', $data['used']) ?></code></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>

    </div>


</div>
