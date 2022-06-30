<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Address */
/* @var $province array */
/* @var $city array */
/* @var $district array */

$this->title = '编辑: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '场馆管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑';
?>

<section class="scrollable padder">
    <div class="row bg-light m-b">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold"><?= Html::encode($this->title) ?></header>
                <div class="panel-body">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'province' => $province,
                        'city' => $city,
                        'district' => $district,
                    ]) ?>
                </div>
            </section>
        </div>
    </div>
</section>