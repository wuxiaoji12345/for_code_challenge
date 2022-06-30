<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Banners;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Search\BannersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '广告条管理';
$this->params['breadcrumbs'][] = $this->title;
$pre = $dataProvider->pagination->getPageCount();
$count = $dataProvider->getCount();
$totalCount = $dataProvider->getTotalCount();
$begin = $dataProvider->pagination->getPage() * $dataProvider->pagination->pageSize + 1;
$end = $begin + $count - 1;
?>
<p>
    <?= Html::a('创建广告条', ['create'], ['class' => 'btn btn-success']) ?>
</p>
<?php //echo $this->render('_search', ['model' => $searchModel]); ?>
<section class="scrollable padder">
    <div class="row bg-light m-b">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold">广告条列表
                    <div class="pull-right">
                        <div class="summary">
                            第<b><?= $begin . '-' . $end ?></b>条, 共<b><?= $dataProvider->totalCount ?></b>条数据.
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="swim-banners-index">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'options' => ['style' => 'table-layout:fixed;'],
                            'columns' => [
                                'id',
                                [
                                    'label' => 'banner位置',
                                    'value' => function($model) {
                                        return isset(Banners::$positionList[$model->position])
                                            ? Banners::$positionList[$model->position] : '-';
                                    }
                                ],
                                [
                                    'label' => 'banner图片',
                                    'format' => 'raw',
                                    'value'=> function($model) {
                                        return Html::img($model->imgurl, ['width' => '100px']);
                                    },
                                ],
                                [
                                    'label' => '跳转类型',
                                    'value' => function($model) {
                                        return isset(Banners::$jumpTypeList[$model->jumptype])
                                            ? Banners::$jumpTypeList[$model->jumptype] : '-';
                                    }
                                ],
                                [
                                    'label' => '跳转类型对应url或配置参数',
                                    'value' => function($model) {
                                        if ($model->jumptype == Banners::JUMP_INNER) {
                                            return $model->jumpvalue;
                                        } elseif ($model->jumptype == Banners::JUMP_OUTER_URL) {
                                            return $model->jumpurl;
                                        } else {
                                            return '';
                                        }
                                    }
                                ],
                                [
                                    'label' => 'banner持续时间',
                                    'value' => function($model) {
                                        return $model->starttime . ' ~ ' .  $model->endtime;
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