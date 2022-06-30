<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\AddressCheckItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '场馆检查项目管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="swim-address-check-item-view">
    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除嘛?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
                'label' => '父项目',
                'value' => function($model) {
                    return (new \backend\models\AddressCheckItem())->getNameByID($model->pid);
                }
            ],
            'weight',
            [
                'label' => '检查内容',
                'format' => 'html',
                'value' => function($model) {
                    return $model->getCheckInfoForIndexPage();
                }
            ],
        ],
    ]) ?>

</div>
