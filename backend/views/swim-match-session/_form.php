<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use backend\models\Address;
use backend\models\Match;

/* @var $this yii\web\View */
/* @var $model backend\models\MatchSession */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="swim-match-session-form">

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

    <?= $form->field($model, 'matchid')
        ->dropDownList((new Match())->dropdownList(), ['prompt' => '请选择赛事'])
        ->label('赛事名称') ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('场次名') ?>

    <?= $form->field($model, 'swim_address_id')
        ->dropDownList((new Address())->dropdownList(), ['prompt' => '请选择比赛场馆'])
        ->label('比赛场馆') ?>

    <?= $form->field($model, 'register_count')->textInput(['maxlength' => true])->label('用户最多可报名场次数 (最多2场)') ?>

    <?= $form->field($model, 'start_time')
        ->widget(DateTimePicker::className(), [
                'options' => ['placeholder' => '比赛开始时间', 'class' => 'col-sm-4'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii:ss'
                ]
            ])->label('比赛开始时间') ?>
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
