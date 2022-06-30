<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UserChannelExtra */

$this->title = '修改用户渠道额外信息: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '用户渠道额外信息管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
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
