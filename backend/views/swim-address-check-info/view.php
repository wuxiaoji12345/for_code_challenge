<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\AddressCheck */
/* @var $detail yii\data\ArrayDataProvider */
/* @var $html string */
/* @var $province string */

$this->title = '场馆检查详情';
$this->params['breadcrumbs'][] = ['label' => '场馆检查信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="swim-address-check-view">
    <h2 style="text-align:center;"><?= $province; ?>游泳场所开放管理检查表</h2>
    <?php echo $html; ?>
    <?php /*echo GridView::widget([
        'dataProvider' => $detail,
        'showHeader'=> false, //去掉 header
        'columns' => [
            [
                'value' => function ($model) {
                    return $model['name'];
                }
            ],
            [
                'format' => 'raw',
                'value' => function ($model) {
                    return $model['info'];
                }
            ],
        ],
        'summary' => '',
    ]);*/ ?>
</div>
