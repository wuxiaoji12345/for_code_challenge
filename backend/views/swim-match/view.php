<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use crazydb\ueditor\UEditor;
use backend\models\Match;

/* @var $this yii\web\View */
/* @var $model backend\models\Match */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '赛事管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="swim-match-view">
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
            'title',
            [
                'label' => '赛事图片',
                'format' => 'html',
                'value'=> function($data) {
                    return Html::img($data->imgurl, ['width' => '100px']);
                },
            ],
            [
                'label' => '报名时间',
                'value' => function($model) {
                    return date('Y-m-d H:i:s', $model->reg_start_time) . ' - ' .
                        date('Y-m-d H:i:s', $model->reg_end_time);
                }
            ],
            [
                'label' => '赛事时间',
                'value' => function($model) {
                    return date('Y-m-d H:i:s', $model->start_time) . ' - ' .
                        date('Y-m-d H:i:s', $model->end_time);
                }
            ],
            'weight',
            [
                'label' => '发布状态',
                'value' => function($model) {
                    return isset(Match::$publish[$model->publish])
                        ? Match::$publish[$model->publish] : '-';
                }
            ],
            [
                'label' => '简介',
                'format' => 'raw',
                'value' => function($model) {
                    return UEditor::widget([
                        'model' => $model,
                        'attribute' => 'intro',
                        'config' => [
                            'autoHeightEnabled' => false,
                        ]
                    ]);
                }
            ],
            [
                'label' => '声明',
                'format' => 'raw',
                'value' => function($model) {
                    return UEditor::widget([
                        'model' => $model,
                        'attribute' => 'disclaimer',
                        'config' => [
                            'autoHeightEnabled' => false,
                        ]
                    ]);
                }
            ],
        ],
    ]) ?>

</div>
