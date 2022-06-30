<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Match */

$this->title = '编辑: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '赛事列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="match-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
