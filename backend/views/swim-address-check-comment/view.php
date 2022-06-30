<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\AddressCheckComment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '场馆检查评论管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="address-check-comment-view">
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
                            'swim_address_check_id',
                            'swim_address_id',
                            [
                                'label' => '图片',
                                'format' => 'raw',
                                'value'=> function($model) {
                                    return Html::img($model->imgurl, ['width' => '100px']);
                                },
                            ],
                                                    'comment',
                            'bkurid',
                            'is_stadium',
                            'status',
                            'create_time:datetime',
                            'update_time',
                        ],
                    ]) ?>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>
