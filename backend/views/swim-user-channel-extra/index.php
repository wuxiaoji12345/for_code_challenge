<?php

use backend\models\Address;
use common\helpers\Utils;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\UserChannelExtra;
use backend\models\UserChannel;
use backend\models\UserInfo;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Search\UserChannelExtraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $params array */

$this->title = '场馆检查员设置管理';
$this->params['breadcrumbs'][] = $this->title;
$pre = $dataProvider->pagination->getPageCount();
$count = $dataProvider->getCount();
$totalCount = $dataProvider->getTotalCount();
$begin = $dataProvider->pagination->getPage() * $dataProvider->pagination->pageSize + 1;
$end = $begin + $count - 1;
?>
<p>
    <?= Html::a('创建场馆检查员', ['create'], ['class' => 'btn btn-success']) ?>
</p>
<?= $this->render('_search', ['model' => $searchModel, 'params' => $params]); ?>
<section class="scrollable padder">
    <div class="row bg-light m-b">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold">用户渠道额外信息列表
                    <div class="pull-right">
                        <div class="summary">
                            第<b><?= $begin . '-' . $end ?></b>条, 共<b><?= $dataProvider->totalCount ?></b>条数据.
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="swim-user-channel-extra-index">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                'id',
                                [
                                    'label' => '用户渠道id',
                                    'value' => function($model) {
                                        return $model->user_channel_id;
                                    }
                                ],
                                [
                                    'label' => '用户昵称',
                                    'value' => function($model) {
                                        $modelChannel = UserChannel::findOne($model->user_channel_id);
                                        if (isset($modelChannel)) {
                                            $modelUserInfo = UserInfo::findOne($modelChannel->urid);
                                            if (isset($modelUserInfo)) {
                                                return $modelUserInfo->nickname;
                                            }
                                        }
                                        return '';
                                    }
                                ],
                                [
                                    'label' => '真实姓名',
                                    'value' => function($model) {
                                        return $model->realname;
                                    }
                                ],
                                [
                                    'label' => '用户渠道id加密内容',
                                    'value' => function($model) {
                                        return Utils::ecbEncrypt(Yii::$app->params['channelIDKey'], $model->user_channel_id);
//                                        return $model->user_channel_id;
                                    }
                                ],
                                [
                                    'label' => '是否为场馆检查员',
                                    'value' => function($model) {
                                        return $model->is_checker == UserChannelExtra::CHECKER_YES ? '是' : '否';
                                    }
                                ],
                                [
                                    'label' => '是否为超级检查员',
                                    'value' => function($model) {
                                        return $model->is_super_checker == UserChannelExtra::SUPER_CHECKER_YES ? '是' : '否';
                                    }
                                ],
                                [
                                    'label' => '所属场馆',
                                    'value' => function ($model) {
                                        if ($model->is_owner == 0) {
                                            return '';
                                        }
                                        return (new Address())->getNameByID($model->is_owner);
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