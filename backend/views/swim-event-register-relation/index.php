<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\EventRelation;
use common\models\Event;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Search\EventRelationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $params array */

$this->title = '培训报名信息';
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
                                [
                                    'label' => '序号',
                                    'value' => function ($model, $key, $index) use ($begin) {
                                        return $begin + $index;
                                    }
                                ],
                                [
                                    'label' => '订单号',
                                    'contentOptions' => ['style' => 'width:100px;'],
                                    'value' => function($model) {
                                        return $model->order_no;
                                    }
                                ],
                                [
                                    'label' => '培训名称',
                                    'contentOptions' => ['style' => 'width:200px;'],
                                    'value' => function($model) {
                                        return (new Event())->getTitleByID($model['matchid']);
                                    }
                                ],
                                [
                                    'label' => '组别',
                                    'attribute' => 'typename',
                                    'value' => function($model) {
                                        return $model->typename;
                                    }
                                ],
                                [
                                    'label' => '姓名',
                                    'value' => function($model) {
                                        $modelInfos = $model->info;
                                        return isset($modelInfos[0]->name) ? $modelInfos[0]->name : '';
                                    }
                                ],
                                [
                                    'label' => '性别',
                                    'value' => function($model) {
                                        $modelInfos = $model->info;
                                        return isset($modelInfos[0]->sex) ? $modelInfos[0]->sex : '';
                                    }
                                ],
                                [
                                    'label' => '手机',
                                    'value' => function($model) {
                                        $modelInfos = $model->info;
                                        return isset($modelInfos[0]->mobile) ? $modelInfos[0]->mobile : '';
                                    }
                                ],
                                [
                                    'label' => '身份证',
                                    'value' => function($model) {
                                        $modelInfos = $model->info;
                                        if (isset($modelInfos[0]->idnumber)) {
                                            return substr($modelInfos[0]->idnumber, 0, 6) . '****'
                                                . substr($modelInfos[0]->idnumber, -4);
                                        }

                                        return '';
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
                                        return isset(EventRelation::$stateList[$model->state])
                                            ? EventRelation::$stateList[$model->state] : '-';
                                    }
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