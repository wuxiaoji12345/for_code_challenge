<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Search\AddressCheckCommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var array $params */

$this->title = '场馆检查评论';
$this->params['breadcrumbs'][] = ['label' => '场馆检查信息', 'url' => ['swim-address-check-info/index']];
$this->params['breadcrumbs'][] = $this->title;

$pre = $dataProvider->pagination->getPageCount();
$count = $dataProvider->getCount();
$totalCount = $dataProvider->getTotalCount();
$begin = $dataProvider->pagination->getPage() * $dataProvider->pagination->pageSize + 1;
$end = $begin + $count - 1;
?>
<p>
    <?= Html::a('回复', ['create', 'id' => $params['id']], ['class' => 'btn btn-success']) ?>
</p>
<?php //$this->render('_search', ['model' => $searchModel]); ?>
<section class="scrollable padder">
    <div class="row bg-light m-b">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold">
                    场馆检查评论列表
                    <div class="pull-right">
                        <div class="summary">
                            第<b><?= $begin . '-' . $end ?></b>条, 共<b><?= $dataProvider->totalCount ?></b>条数据.
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="address-check-comment-index">
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
                                    'label' => '图片',
                                    'format' => 'raw',
                                    'value'=> function($model) {
                                        return Html::img($model->imgurl, ['width' => '100px']);
                                    },
                                ],
                                'comment',
                                [
                                    'label' => '是否检查员回复',
                                    'value'=> function($model) {
                                        return ($model->is_stadium == 1 ? '否' : '是');
                                    },
                                ],
                                [
                                    'label' => '回复时间',
                                    'attribute' => 'create_time',
                                    'format' => ['date', 'php:Y-m-d H:i:s'],
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