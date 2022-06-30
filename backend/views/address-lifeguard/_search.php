<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Search\AddressLifeguardSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel panel-default">
    <header class="panel-heading">
        搜索
    </header>
    <div class="panel-body">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            "options" => ['class' => 'form-inline'],
            'fieldConfig' => [
                'template' => "{input}",
                'labelOptions' => [
                    'class' => 'control-label'
                ]
            ]
        ]); ?>

    <?= $form->field($model, 'name')->textInput(['placeholder'=> '姓名']) ?>

    <?= $form->field($model, 'mobile')->textInput(['placeholder'=> '手机号']) ?>

    <?= $form->field($model, 'id_card')->textInput(['placeholder'=> '身份证']) ?>

    <?php // echo $form->field($model, 'cert_type') ?>

    <?php // echo $form->field($model, 'cert_level') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'update_time') ?>

        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
