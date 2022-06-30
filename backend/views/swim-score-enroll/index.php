<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Match;
use backend\models\MatchSession;
use backend\models\MatchSessionItem;
use backend\models\ScoreEnroll;
use backend\models\ScoreStates;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Search\ScoreEnrollSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $params array */

$this->title = '参赛分组信息';
$this->params['breadcrumbs'][] = $this->title;
$pre = $dataProvider->pagination->getPageCount();
$count = $dataProvider->getCount();
$totalCount = $dataProvider->getTotalCount();
$begin = $dataProvider->pagination->getPage() * $dataProvider->pagination->pageSize + 1;
$end = $begin + $count - 1;
?>
<p>
    <?php //echo Html::a('创建', ['create'], ['class' => 'btn btn-success']) ?>
</p>
<?= $this->render('_search', ['model' => $searchModel, 'params' => $params]); ?>
<section class="scrollable padder">
    <div class="row bg-light m-b">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold">列表
                    <div class="pull-right">
                        <div class="summary">
                            第<b><?= $begin . '-' . $end ?></b>条, 共<b><?= $dataProvider->totalCount ?></b>条数据.
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="swim-score-enroll-index">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                'id',
                                [
                                    'label' => '赛事名称',
                                    'value' => function($model) {
                                        return (new Match())->getTitleByID($model->matchid);
                                    }
                                ],
                                [
                                    'label' => '场次名称',
                                    'value' => function($model) {
                                        return (new MatchSession())->getNameByID($model->ssid);
                                    }
                                ],
                                [
                                    'label' => '项目名称',
                                    'value' => function($model) {
                                        return (new MatchSessionItem())->getNameByID($model->itemid);
                                    }
                                ],
                                [
                                    'label' => '比赛组别/泳道',
                                    'value' => function($model) {
                                        $groupLaneData = (new ScoreStates())->getGroupNameLane(
                                                $model->itemid, $model->id);
                                        return '第' . $groupLaneData['group'] . '组 / 第' . $groupLaneData['lane'] . '泳道';
                                    }
                                ],
                                [
                                    'label' => '姓名',
                                    'value' => function($model) {
                                        return $model->name;
                                    }
                                ],
                                [
                                    'label' => '手机号',
                                    'value' => function($model) {
                                        return $model->phone;
                                    }
                                ],
                                [
                                    'label' => '报名方式 ',
                                    'value' => function($model) {
                                        return isset(ScoreEnroll::$typeList[$model->type])
                                            ? ScoreEnroll::$typeList[$model->type] : '-';
                                    }
                                ],
                                [
                                    'label' => '更新时间',
                                    'value' => function($model) {
                                        return $model->update_time;
                                    }
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