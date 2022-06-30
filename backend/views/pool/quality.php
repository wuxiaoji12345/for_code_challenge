<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\PoolQuality;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Search\PoolQualitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $info string */

$this->title = '水质详情';
$this->params['breadcrumbs'][] = $this->title;

$pre = $dataProvider->pagination->getPageCount();
$count = $dataProvider->getCount();
$totalCount = $dataProvider->getTotalCount();
$begin = $dataProvider->pagination->getPage() * $dataProvider->pagination->pageSize + 1;
$end = $begin + $count - 1;
?>
<?php //echo $this->render('_search_quality', ['model' => $searchModel]); ?>
<section class="scrollable padder">
    <div class="row bg-light m-b">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold">
                    水质详情列表（<b><?= $info ?></b>）
                    <div class="pull-right">
                        <div class="summary">
                            第<b><?= $begin . '-' . $end ?></b>条, 共<b><?= $dataProvider->totalCount ?></b>条数据.
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="pool-quality-index">
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
                                    'label' => '检查日期',
                                    'attribute' => 'cdate'
                                ],
                                [
                                    'label' => '检查员',
                                    'attribute' => 'checkname'
                                ],
                                [
                                    'label' => '检查项目',
                                    'value' => function ($model) {
                                        if (isset(PoolQuality::$typeList[$model->type])) {
                                            return PoolQuality::$typeList[$model->type];
                                        }
                                        return '';
                                    }
                                ],
                                [
                                    'label' => '检查数值',
                                    'attribute' => 'value'
                                ],
                                [
                                    'label' => '检查时间',
                                    'value' => function ($model) {
                                        return date('Y-m-d H:i:s', $model->create_time);
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