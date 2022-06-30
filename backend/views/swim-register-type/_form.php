<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Match;
use backend\models\RegisterType;

/* @var $this yii\web\View */
/* @var $model backend\models\RegisterType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="swim-register-type-form">

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

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('自定义组别名') ?>

    <div class="form-group field-swimregistertype-min_max_count">
        <label class="col-sm-2 control-label" for="swimregistertype-min_max_count">每组人数限制</label>
        <div class="col-sm-4">
            <?=
            Html::input('text', 'SwimRegisterType[mincount]',
                $model->mincount,
                ['class' => 'form-control', 'placeholder' => '最小人数'])   ;
            ?>
        </div>
        <div class="col-sm-4">
            <?=
            Html::input('text', 'SwimRegisterType[maxcount]',
                $model->maxcount,
                ['class' => 'form-control', 'placeholder' => '最大人数'])   ;
            ?>
        </div>
    </div>

    <div class="form-group field-swimregistertype-min_max_count">
        <label class="col-sm-2 control-label" for="swimregistertype-min_max_count">每组女性人数限制</label>
        <div class="col-sm-4">
            <?=
            Html::input('text', 'SwimRegisterType[fmincount]',
                $model->fmincount,
                ['class' => 'form-control', 'placeholder' => '最小人数'])   ;
            ?>
        </div>
        <div class="col-sm-4">
            <?=
            Html::input('text', 'SwimRegisterType[fmaxcount]',
                $model->fmaxcount,
                ['class' => 'form-control', 'placeholder' => '最大人数'])   ;
            ?>
        </div>
    </div>

    <?= $form->field($model, 'fees')->textInput(['maxlength' => true])->label('预付款(单位元)') ?>

    <?= $form->field($model, 'amount')->textInput()->label('最大组数量') ?>

    <?= $form->field($model, 'num')->textInput()->label('当前剩余报名数量') ?>

    <?= $form->field($model, 'notice')->textInput(['maxlength' => true])->label('报名要求') ?>

    <?= $form->field($model, 'type')
        ->dropDownList(RegisterType::$typeList, ['prompt' => '请选择类型'])
        ->label('类型') ?>

    <?= $form->field($model, 'groupform')->textarea(['rows' => 6, 'style' => 'width:650px;'])
        ->label('团队信息模板<br><br>'
            . Html::a('去生成模版', 'http://mcloud.moveclub.cn/formdesign', ['class' => 'btn btn-primary', 'target' => '_blank'])
        ) ?>

    <?= $form->field($model, 'registerform')->textarea(['rows' => 6, 'style' => 'width:650px;'])
        ->label('选手填写信息模板<br><br>'
            . Html::a('去生成模版', 'http://mcloud.moveclub.cn/formdesign', ['class' => 'btn btn-primary', 'target' => '_blank'])
        )
    ?>

    <?= $form->field($model, 'needcheck')
        ->dropDownList(RegisterType::$checkList, ['prompt' => '请选择审核类型'])
        ->label('审核类型') ?>

    <?= $form->field($model, 'registerlimit')->textInput()->textInput(['placeholder' => '报名限制，0-无限制'])
        ->label('单人报名上限(0-无限制)') ?>

    <?= $form->field($model, 'allforpay')
        ->dropDownList(RegisterType::$memberInfo, ['prompt' => '请选择成员信息'])
        ->label('成员信息') ?>

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
