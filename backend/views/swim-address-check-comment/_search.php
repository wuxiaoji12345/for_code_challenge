<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Search\AddressCheckCommentSearch */
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

        <?= $form->field($model, 'swim_address_check_id')->textInput(['placeholder'=> '名称']) ?>

    <?= $form->field($model, 'swim_address_id')->textInput(['placeholder'=> '名称']) ?>

    <?= $form->field($model, 'imgurl')->textInput(['placeholder'=> '名称']) ?>

    <?= $form->field($model, 'comment')->textInput(['placeholder'=> '名称']) ?>

    <?= $form->field($model, 'bkurid')->textInput(['placeholder'=> '名称']) ?>

    <?php // echo $form->field($model, 'is_stadium') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'update_time') ?>

        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
