<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Pool */

$this->title = '创建泳池';
$this->params['breadcrumbs'][] = ['label' => '泳池管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
