<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Address */
/* @var $form yii\widgets\ActiveForm */
/* @var $province array */
/* @var $city array */
/* @var $district array */
?>

<div class="swim-address-form">

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

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group field-match-imgurl">
        <label class="col-sm-2 control-label" for="address-imgurl">场馆图片</label>
        <div class="col-sm-4" style="width:550px;">
            <?=
            FileInput::widget([
                'name' => 'Address[imgurl]',
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

    <div class="form-group field-match-province-city-district">
        <label class="col-sm-2 control-label" for="address-imgurl">省市区</label>
        <div class="col-sm-2">
            <?= Html::dropDownList('Address[province]', $model->province, $province, ['class' => 'form-control', 'id' => 'address-province', 'prompt' => '请选择省']) ?>
        </div>
        <div class="col-sm-2">
            <?= Html::dropDownList('Address[city]', $model->city, $city, ['class' => 'form-control', 'id' => 'address-city', 'prompt' => '请选择市']) ?>
        </div>
        <div class="col-sm-2">
            <?= Html::dropDownList('Address[district]', $model->district, $district, ['class' => 'form-control', 'id' => 'address-district', 'prompt' => '请选择区']) ?>
        </div>
    </div>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true])
        ->label('详细地址' . Html::a(' (去获取经纬度)', 'https://lbs.amap.com/console/show/picker', ['target' => '_blank']))
    ?>

    <?= $form->field($model, 'longitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'latitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lane')->textInput() ?>
    <div class="col-md-2">
    </div>
    <div class="col-md-4">
        <?= Html::checkbox('ckOption', false, ['label' => '查看', 'value' => 'view', 'class' => 'ckMark']) ?>
        <?= Html::checkbox('ckOption', false, ['label' => '继续创建', 'value' => 'create', 'class' => 'ckMark']) ?>
        <?= Html::checkbox('ckOption', false, ['label' => '继续编辑', 'value' => 'update', 'class' => 'ckMark']) ?>
    <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs('
        //省改变
        $("#address-province").change(function() {
            var provinceName = $(this).val();
            $("#address-city").html("<option value=\"0\">请选择市</option>");
            $("#address-district").html("<option value=\"0\">请选择区</option>");
            if (provinceName != "") {
                getArea(provinceName, "address-city");
            }
        });

        //市改变
        $("#address-city").change(function() {
            var cityName = $(this).val();
            $("#address-district").html("<option value=\"0\">请选择区</option>");
            if (cityName != "") {
                getArea(cityName, "address-district");
            }
        });

        //获取列表
        function getArea(name, selectID)
        {
            var href = "' . \yii\helpers\Url::to(['/mcloud-region/get-region-by-name'], true). '";

            $.ajax({
                "type"  : "GET",
                "url"   : href,
                "data"  : {name : name},
                success : function(d) {
                    $("#" + selectID).append(d);
                }
            });
        }
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
