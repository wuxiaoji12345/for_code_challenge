<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Match;
use backend\models\RegisterType;

/* @var $this yii\web\View */
/* @var $model backend\models\RegisterType */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '线上报名设置管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="swim-register-type-view">
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
                'label' => '赛事名称',
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
                'label' => '预付款',
                'value' => function($model) {
                    return $model->fees;
                }
            ],
            [
                'label' => '最大组别数/当前剩余',
                'value' => function($model) {
                    return $model->amount . '/' . $model->num;
                }
            ],
            [
                'label' => '是否需要审核',
                'value' => function($model) {
                    return ($model->needcheck == 0) ?  '是' : '否';
                }
            ],
            [
                'label' => '报名上限',
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
        ],
    ]) ?>

</div>
