<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AddressCheckComment */

$this->title = '新增回复';
$this->params['breadcrumbs'][] = ['label' => '场馆检查信息', 'url' => ['swim-address-check-info/index']];
$this->params['breadcrumbs'][] = ['label' => '场馆检查评论', 'url' => ['index', 'id' => $model->swim_address_check_id]];
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
