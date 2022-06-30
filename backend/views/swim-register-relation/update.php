<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MatchSessionItem */

$this->title = '修改订单状态: ' . $model->order_no;
$this->params['breadcrumbs'][] = ['label' => '线上报名信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->order_no];
$this->params['breadcrumbs'][] = '修改';
?>
<section class="scrollable padder">
    <div class="row bg-light m-b">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold"><?= Html::encode($this->title) ?></header>
                <div class="panel-body">
                    <?= $this->render('_form', [
                    'model' => $model,
                    ]) ?>
                </div>
            </section>
        </div>
    </div>
</section>
