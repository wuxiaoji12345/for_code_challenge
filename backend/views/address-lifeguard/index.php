<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Address;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Search\AddressLifeguardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '场馆救生员管理';
$this->params['breadcrumbs'][] = $this->title;

$pre = $dataProvider->pagination->getPageCount();
$count = $dataProvider->getCount();
$totalCount = $dataProvider->getTotalCount();
$begin = $dataProvider->pagination->getPage() * $dataProvider->pagination->pageSize + 1;
$end = $begin + $count - 1;
?>
<p>
    <?= Html::a('新增救生员', ['create'], ['class' => 'btn btn-success']) ?>
</p>
<?= $this->render('_search', ['model' => $searchModel]); ?>
<section class="scrollable padder">
    <div class="row bg-light m-b">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold">
                    场馆救生员列表
                    <div class="pull-right">
                        <div class="summary">
                            第<b><?= $begin . '-' . $end ?></b>条, 共<b><?= $dataProvider->totalCount ?></b>条数据.
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="address-lifeguard-index">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                [
                                    'label' => '序号',
                                    'value' => function ($model, $key, $index) use ($begin) {
                                        return $begin + $index;
                                    }
                                ],
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
                                [
                                    'label' => '证件类型',
                                    'attribute' => 'cert_level',
                                ],
                                [
                                    'label' => '新增时间',
                                    'value' => function ($model) {
                                        return date('Y-m-d H:i:s', $model->create_time);
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => '操作',
                                    'template' => '{view} {update}',
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => '删除',
                                    'template' => '{delete}',
                                    'buttons' => [
                                        'delete' => function($url, $model){
                                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
                                                'data' => [
                                                    'confirm' => '确认删除?',
                                                    'method' => 'post',
                                                ],
                                            ]);
                                        }
                                    ]
                                ],
                            ],
                            'layout' => '{items}{pager}',
                            'summary' => '', //Total xxxx items.
                            'pager' => [
                                'options'=>['class'=>'pagination'],
                                'prevPageLabel' => '上一页',
                                'firstPageLabel'=> '首页',
                                'nextPageLabel' => '下一页',
                                'lastPageLabel' => '末页',
                                'maxButtonCount'=>'10',
                            ]
                        ]); ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>