<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Address;

/* @var $this yii\web\View */
/* @var $model backend\models\AddressLifeguard */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '场馆救生员表管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="address-lifeguard-view">
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
                                    return (new Address())->getNameByID($model->swim_address_id);
                                }
                            ],
                            'name',
                            [
                                'label' => '性别',
                                'value' => function ($model) {
                                    if ($model->gender == 1) {
                                        return '男';
                                    } elseif ($model->gender == 2) {
                                        return '女';
                                    } else {
                                        return '';
                                    }
                                }
                            ],
                            'mobile',
                            'id_card',
                            [
                                'label' => '证件类型',
                                'value' => function ($model) {
                                    if ($model->cert_type == 1) {
                                        return '救生员证';
                                    } elseif ($model->cert_type == 2) {
                                        return '国职证书';
                                    } else {
                                        return '';
                                    }
                                }
                            ],
                            'cert_level',
                        ],
                    ]) ?>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>
