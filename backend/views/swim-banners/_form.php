<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Banners;
use kartik\datetime\DateTimePicker;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Banners */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="swim-banners-form">

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

    <?= $form->field($model, 'position')->dropDownList(Banners::$positionList, ['prompt' => '请选择banner位置'])->label('banner位置') ?>


    <div class="form-group field-banners-imgurl">
        <label class="col-sm-2 control-label" for="banners-imgurl">banner图片</label>
        <div class="col-sm-4" style="width:550px;">
            <?=
            FileInput::widget([
                'name' => 'Banners[imgurl]',
                'options' => [
                    'accept' => 'image/*',
                    'multiple' => false
                ],
                'pluginOptions' => [
                    'initialPreview'=>[
                        $model->imgurl
                    ],
                    'initialPreviewAsData'=>true,
                    'showUpload' => false,
                    'showRemove' => true,
                ]
            ]);
            ?>
        </div>
    </div>

    <?= $form->field($model, 'jumptype')
        ->dropDownList(Banners::$jumpTypeList, ['prompt' => '请选择跳转类型'])
        ->label('跳转类型') ?>

    <?= $form->field($model, 'jumpurl')->textInput(['maxlength' => true, 'id' => 'jumpurl'])->label('跳转url') ?>

    <?= $form->field($model, 'jumpvalue')
        ->textInput(['maxlength' => true, 'id' => 'jumpvalue', 'placeholder' => 'json格式，例如：{["gid":"55"]}'])
        ->label('跳转参数') ?>

    <?= $form->field($model, 'starttime')->widget(DateTimePicker::className(), [
        'options' => ['placeholder' => 'banner开始时间', 'class' => 'col-sm-4'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd hh:ii:ss'
        ]
    ])->label('banner开始时间') ?>

    <?= $form->field($model, 'endtime')->widget(DateTimePicker::className(), [
        'options' => ['placeholder' => 'banner结束时间', 'class' => 'col-sm-4'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd hh:ii:ss'
        ]
    ])->label('banner结束时间') ?>

    <?= $form->field($model, 'weight')->textInput()->label('权重') ?>
    <div class="col-md-2">
    </div>
    <div class="col-md-4">
        <?= Html::checkbox('ckOption', false, ['label' => '查看', 'value' => 'view', 'class' => 'ckMark']) ?>
        <?= Html::checkbox('ckOption', false, ['label' => '继续创建', 'value' => 'create', 'class' => 'ckMark']) ?>
        <?= Html::checkbox('ckOption', false, ['label' => '继续编辑', 'value' => 'update', 'class' => 'ckMark']) ?>
    <?= Html::submitButton($model->isNewRecord ? '创建' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs('
        $("#banners-jumptype").change(function() {
            var type = $(this).val();
            if (type == 1) {
                $(".field-jumpurl").hide();
                $(".field-jumpvalue").show();
                $("#jumpurl").val("");
            } else {
                $(".field-jumpurl").show();
                $(".field-jumpvalue").hide();
                $("#jumpvalue").val("");
            }
        });
    ');
?>
<script>
    <?php $this->beginBlock('additionJs') ?>
    $(document).ready(function () {
        $(".ckMark").click(function() {
            if ($(this).is(':checked')) {
                $(".ckMark").prop("checked", false);
                $(this).prop("checked", true);
            }
        });
    });
    <?php $this->endBlock() ?>
    <?php $this->registerJs($this->blocks['additionJs']) ?>
</script>
