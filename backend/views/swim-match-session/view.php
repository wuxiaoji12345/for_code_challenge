<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Address;
use backend\models\Match;

/* @var $this yii\web\View */
/* @var $model backend\models\MatchSession */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '赛事场次管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="swim-match-session-view">
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
            'name',
            [
                'label' => '比赛场馆',
                'value' => function($model) {
                    return (new Address())->getNameByID($model->swim_address_id);
                }
            ],
            'register_count',
            'start_time',
        ],
    ]) ?>

</div>
