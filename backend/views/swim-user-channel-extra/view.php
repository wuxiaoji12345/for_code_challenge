<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\Utils;
use backend\models\UserChannelExtra;

/* @var $this yii\web\View */
/* @var $model backend\models\UserChannelExtra */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '用户渠道额外信息管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="swim-user-channel-extra-view">
    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除嘛?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => '用户渠道id',
                'value' => function($model) {
                    return $model->user_channel_id;
                }
            ],
            [
                'label' => '用户渠道id加密内容',
                'value' => function($model) {
                    return Utils::ecbEncrypt(Yii::$app->params['channelIDKey'], $model->user_channel_id);
                }
            ],
            [
                'label' => '真实姓名',
                'value' => function($model) {
                    return $model->realname;
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
                'label' => '更新时间',
                'value' => function($model) {
                    return $model->update_time;
                }
            ],
        ],
    ]) ?>

</div>
