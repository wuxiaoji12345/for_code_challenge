<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Search\AddressSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '场馆管理';
$this->params['breadcrumbs'][] = $this->title;

$pre = $dataProvider->pagination->getPageCount();
$count = $dataProvider->getCount();
$totalCount = $dataProvider->getTotalCount();
$begin = $dataProvider->pagination->getPage() * $dataProvider->pagination->pageSize + 1;
$end = $begin + $count - 1;
?>
<p>
    <?= Html::a('创建', ['create'], ['class' => 'btn btn-success']) ?>
</p>
<?php  echo $this->render('_search', ['model' => $searchModel]); ?>
<section class="scrollable padder">
    <div class="row bg-light m-b">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold">
                    场馆列表
                    <div class="pull-right">
                        <div class="summary">
                            第<b><?= $begin . '-' . $end ?></b>条, 共<b><?= $dataProvider->totalCount ?></b>条数据.
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="swim-address-index">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'layout' => "{items}{pager}",  //{items}{summary}{pager}
                        'pager' => [
                            'options'=>['class'=>'pagination'],
                            'prevPageLabel' => '上一页',
                            'firstPageLabel'=> '首页',
                            'nextPageLabel' => '下一页',
                            'lastPageLabel' => '末页',
                            'maxButtonCount'=>'10',
                        ],
                        'columns' => [
                            'id',
                            'name',
                            [
                                'label' => '场馆图片',
                                'format' => 'raw',
                                'value'=> function($model) {
                                    return Html::img($model->imgurl, ['width' => '100px']);
                                },
                            ],
                            'province',
                            'city',
                            'district',
                            'address',
                            'lane',
                            [
                                'label' => '是否展示',
                                'format' => 'raw',
                                'value' => function($model) {
                                    return \kartik\switchinput\SwitchInput::widget([
                                        'id' => 'publish_' . $model->id,
                                        'name' => 'publish_' . $model->id,
                                        'value' => $model->publish == 1 ? true : false,
                                        'pluginOptions'=>[
                                            'size' => 'mini',
                                            'handleWidth' => 10,
                                            'onText'=>'是',
                                            'offText'=>'否',
                                            'onColor' => 'success',
                                            'offColor' => 'danger',
                                        ],
                                        'pluginEvents' => [
                                            "switchChange.bootstrapSwitch" => "function(event, state) {
                                                    var match_id = this.name.replace('publish_', '');
                                                    var ret = $.ajax({
                                                        type:'POST',
                                                        url: '/swim-address/update-publish',
                                                        data: {
                                                            'id': match_id,
                                                            'publish': state ? 1 : 0
                                                        },
                                                        datatype: 'json',
                                                        success:function(data){
                                                            if (data.code != 0) {
                                                                alert('修改状态失败');
                                                            }
                                                        },
                                                        error: function(){
                                                            alert('修改状态失败');
                                                        }   
                                                    });
                                                }"
                                        ],
                                    ]);
                                }
                            ],
                            [
                                'attribute' => 'update_time',
                                'label' => '更新时间',
                                'enableSorting'=>false,
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
                    ]); ?>
                     </div>
                </div>
            </section>
        </div>
    </div>
</section>