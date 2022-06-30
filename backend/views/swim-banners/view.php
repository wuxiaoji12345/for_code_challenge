<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Banners;

/* @var $this yii\web\View */
/* @var $model common\models\Banners */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '广告条管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="swim-banners-view">
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
                    return Html::img($model->imgurl, ['width' => '200px']);
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
                'label' => 'banner开始时间',
                'value' => function($model) {
                    return $model->starttime;
                }
            ],
            [
                'label' => 'banner结束时间',
                'value' => function($model) {
                    return $model->endtime;
                }
            ],
            [
                'label' => '权重',
                'value' => function($model) {
                    return $model->weight;
                }
            ],
        ],
    ]) ?>

</div>
