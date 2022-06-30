<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Match */

$this->title = '发布赛事';
$this->params['breadcrumbs'][] = ['label' => 'Matches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="match-create">
    <?= $this->render('_form', [
        'model' => $model,
        'action'=>['/event/create']
    ]) ?>

</div>
