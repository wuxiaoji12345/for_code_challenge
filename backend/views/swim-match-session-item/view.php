<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Match;
use backend\models\MatchSessionItem;

/* @var $this yii\web\View */
/* @var $model backend\models\MatchSessionItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '赛事场次项目管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="swim-match-session-item-view">
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
            'ssid',
            'name',
            [
                'label' => '赛事名称',
                'value' => function($model) {
                    return isset(MatchSessionItem::$typeList[$model->type])
                        ? MatchSessionItem::$typeList[$model->type] : '-';
                }
            ],
            [
                'label' => '赛事名称',
                'value' => function($model) {
                    return isset(MatchSessionItem::$genderList[$model->gender])
                        ? MatchSessionItem::$genderList[$model->gender] : '-';
                }
            ],
            'distance',
            'agemin',
            'agemax',
            'weight',
        ],
    ]) ?>

</div>
