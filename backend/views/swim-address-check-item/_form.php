<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model backend\models\AddressCheckItem */
/* @var $form yii\widgets\ActiveForm */
/* @var $parent array */
?>

<div class="swim-address-check-item-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form-horizontal'
        ],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-4\">{input}</div><div class=\"help-block\">{error}</div>",
            'labelOptions' => [
                'class' => 'col-sm-2 control-label'
            ]
        ]
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pid')->dropDownList($parent, ['prompt' => '请选择']) ?>

    <?= $form->field($model, 'weight')->textInput() ?>

    <?= $form->field($model, 'info')
        ->textarea(['maxlength' => true,
            'rows' => 5,
            'placeholder'=> '如为父项目，不需要填。 json格式：{"selectOption":{"s1":{"符合":1,"不符合":0},"s2":{"符合":1}},
            "inputOption":["备注"]} selectOption为选择项 inputOption为输入项'
        ]) ?>

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