<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Address;

/* @var $this yii\web\View */
/* @var $model backend\models\Pool */

$this->title = '详情';
$this->params['breadcrumbs'][] = ['label' => '泳池管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pool-view">
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
    <section class="scrollable padder">
        <div class="row bg-light m-b">
            <div class="col-md-12">
                <section class="panel panel-default">
                    <header class="panel-heading font-bold">详细</header>
                    <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                'label' => '场馆',
                                'value' => function ($model) {
                                    return (new Address())->getNameByID($model->sid);
                                }
                            ],
                            [
                                'label' => '泳池名称',
                                'attribute' => 'name',
                            ],
                            [
                                'label' => '更新时间',
                                'attribute' => 'update_time',
                            ],
                        ],
                    ]) ?>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>
