<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Match;
use backend\models\RegisterRelation;
use backend\models\RegisterDetail;
use backend\models\RegisterInfo;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Search\RegisterRelationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $params array */

$this->title = '线上报名信息';
$this->params['breadcrumbs'][] = $this->title;

$pre = $dataProvider->pagination->getPageCount();
$count = $dataProvider->getCount();
$totalCount = $dataProvider->getTotalCount();
$begin = $dataProvider->pagination->getPage() * $dataProvider->pagination->pageSize + 1;
$end = $begin + $count - 1;
?>
<p>
    <?php //echo Html::a('创建活动报名组记录', ['create'], ['class' => 'btn btn-success']) ?>
</p>
<?= $this->render('_search', ['model' => $searchModel, 'params' => $params]); ?>
<section class="scrollable padder">
    <div class="row bg-light m-b">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold">
                    用户报名信息列表
                    <div class="pull-right">
                        <div class="summary">
                            第<b><?= $begin . '-' . $end ?></b>条, 共<b><?= $dataProvider->totalCount ?></b>条数据.
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="swim-register-relation-index">
                        <?= GridView::widget([
                            'layout' => '{items}{pager}',
                            'dataProvider' => $dataProvider,
                            'options' => ['style' => 'table-layout:fixed;'],
                            'columns' => [
                                'id',
                                [
                                    'label' => '订单号',
                                    'contentOptions' => ['style' => 'width:100px;'],
                                    'value' => function($model) {
                                        return $model->order_no;
                                    }
                                ],
                                [
                                    'label' => '赛事名称',
                                    'contentOptions' => ['style' => 'width:200px;'],
                                    'value' => function($model) {
                                        return (new Match())->getTitleByID($model->matchid);
                                    }
                                ],
                                [
                                    'label' => '场次id/名称',
                                    'contentOptions' => ['style' => 'width:200px;'],
                                    'value' => function($model) {
                                        $data = (new RegisterDetail())->getSSidName($model->id);
                                        return !empty($data) ? $data['ssid'] . '/' . $data['name'] : '';
                                    }
                                ],
                                [
                                    'label' => '姓名',
                                    'value' => function($model) {
                                        return (new RegisterInfo())->getFiledByRridMatchid($model->id, $model->matchid, 'name');
                                    }
                                ],
                                [
                                    'label' => '手机',
                                    'value' => function($model) {
                                        return (new RegisterInfo())->getFiledByRridMatchid($model->id, $model->matchid, 'mobile');
                                    }
                                ],
                                [
                                    'label' => '费用',
                                    'value' => function($model) {
                                        return $model->fees;
                                    }
                                ],
                                [
                                    'label' => '支付状态',
                                    'value' => function($model) {
                                        return isset(RegisterRelation::$stateList[$model->state])
                                            ? RegisterRelation::$stateList[$model->state] : '-';
                                    }
                                ],
                                [
                                    'label' => '报名项目',
                                    'format' => 'raw',
                                    'value' => function($model) {
                                        $itemNames =  (new RegisterDetail())->getItemNamesByOrderID($model->id);
                                        return implode('<br/>', $itemNames);
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => '操作',
                                    'template' => '{update}',
                                    'buttons' => [
                                        'update' => function($url, $model){
                                            return Html::a('修改订单状态',
                                                ['update', 'id' => $model->id]);
                                        },
                                    ]
                                ],
                            ],
                            //'summary' => '', //Total xxxx items.
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