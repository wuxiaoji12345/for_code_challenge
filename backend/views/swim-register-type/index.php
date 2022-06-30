<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Match;
use backend\models\RegisterType;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Search\RegisterTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $params array */

$this->title = '线上报名设置管理';
$this->params['breadcrumbs'][] = $this->title;
$pre = $dataProvider->pagination->getPageCount();
$count = $dataProvider->getCount();
$totalCount = $dataProvider->getTotalCount();
$begin = $dataProvider->pagination->getPage() * $dataProvider->pagination->pageSize + 1;
$end = $begin + $count - 1;
?>
<p>
    <?= Html::a('创建线上报名设置', ['create'], ['class' => 'btn btn-success']) ?>
</p>
<?= $this->render('_search', ['model' => $searchModel, 'params' => $params]); ?>
<section class="scrollable padder">
    <div class="row bg-light m-b">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold">线上报名设置列表
                    <div class="pull-right">
                        <div class="summary">
                            第<b><?= $begin . '-' . $end ?></b>条, 共<b><?= $dataProvider->totalCount ?></b>条数据.
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="swim-register-type-index">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'options' => ['style' => 'table-layout:fixed;'],
                            'columns' => [
                                'id',
                                [
                                    'label' => '赛事名称',
                                    'contentOptions' => ['style' => 'width:250px;'],
                                    'value' => function($model) {
                                        return (new Match())->getTitleByID($model->matchid);
                                    }
                                ],
                                [
                                    'label' => '标题',
                                    'value' => function($model) {
                                        return $model->title;
                                    }
                                ],
                                [
                                    'label' => '人数限制',
                                    'value' => function($model) {
                                        return $model->mincount . '-' . $model->maxcount . '人';
                                    }
                                ],
                                [
                                    'label' => '女性人数限制',
                                    'value' => function($model) {
                                        return $model->fmincount . '-' . $model->fmaxcount . '人';
                                    }
                                ],
                                [
                                    'label' => '当前剩余/最大组别数',
                                    'value' => function($model) {
                                        return $model->num . '/' . $model->amount;
                                    }
                                ],
                                [
                                    'label' => '是否需要审核',
                                    'value' => function($model) {
                                        return ($model->needcheck == 2) ?  '是' : '否';
                                    }
                                ],
                                [
                                    'label' => '单人报名报名上限',
                                    'value' => function($model) {
                                        return ($model->registerlimit == RegisterType::REGISTER_NO_LIMIT)
                                            ?  '无上限' : $model->registerlimit;
                                    }
                                ],
                                [
                                    'label' => '权重',
                                    'value' => function($model) {
                                        return $model->weight;
                                    }
                                ],
                                [
                                    'label' => '更新时间',
                                    'value' => function($model) {
                                        return $model->update_time;
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => '操作',
                                    'template' => '{view} {update}',
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