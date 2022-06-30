<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\AddressCheckComment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="address-check-comment-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form-horizontal',
            'enctype' => 'multipart/form-data', //图片上传设置 重要
        ],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-4\">{input}</div><div class=\"help-block\">{error}</div>",
            'labelOptions' => [
                'class' => 'col-sm-2 control-label'
            ]
        ]
    ]); ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 5])->label('文字') ?>

    <div class="form-group field-address-check-comment-imgurl">
        <label class="col-sm-2 control-label" for="reward-imgurl">图片</label>
        <div class="col-sm-4" style="width:550px;">
            <?=
            FileInput::widget([
                'name' => 'AddressCheckComment[imgurl]',
                'options' => [
                    'accept' => 'image/*',
                    'multiple' => false
                ],
                'pluginOptions' => [
                    /*'initialPreview'=>[
                        $model->imgurl
                    ],*/
                    'initialPreviewAsData'=>true,
                    'showUpload' => false,
                    'showRemove' => true,
                ]
            ]);
            ?>
        </div>
    </div>


    <div class="col-md-2">
    </div>
    <div class="col-md-4">
        <?= Html::submitButton($model->isNewRecord ? '回复' : '回复', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>