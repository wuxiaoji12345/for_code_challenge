<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AddressCheckItem */
/* @var $parent array */

$this->title = '创建';
$this->params['breadcrumbs'][] = ['label' => '场馆检查项目管理', 'url' => ['index']];
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
                        'parent' => $parent,
                    ]) ?>
                </div>
            </section>
        </div>
    </div>
</section>
