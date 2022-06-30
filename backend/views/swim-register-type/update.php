<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RegisterType */

$this->title = '修改线上报名设置: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '线上报名设置管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
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
