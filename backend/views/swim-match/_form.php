<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use crazydb\ueditor\UEditor;
use kartik\switchinput\SwitchInput;
use kartik\datetime\DateTimePicker;
use backend\models\Match;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Match */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="swim-match-form">

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

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-left">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">基本信息</a></li>
            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">简介/免责声明</a></li>
            <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">小程序码</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <br/><br/><br/>
                <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder'=> '赛事标题'])
                    ->label('标题') ?>

                <div class="form-group field-swimmatch-imgurl">
                    <label class="col-sm-2 control-label" for="swimmatch-imgurl">赛事图片</label>
                    <div class="col-sm-4" style="width:550px;">
                        <?=
                        FileInput::widget([
                            'name' => 'SwimMatch[imgurl]',
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

                <div class="form-group field-swimmatch-reg_start_end_time">
                    <label class="col-sm-2 control-label" for="swimmatch-reg_start_end_time">报名开始/结束时间</label>
                    <div class="col-sm-4">
                        <?=
                        DateTimePicker::widget([
                            'model' => $model,
                            'attribute' => 'reg_start_time',
                            'options' => ['placeholder' => '注册开始时间', 'class' => 'col-sm-4'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd hh:ii:ss'
                            ]
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-4">
                        <?=
                        DateTimePicker::widget([
                            'model' => $model,
                            'attribute' => 'reg_end_time',
                            'options' => ['placeholder' => '注册结束时间', 'class' => 'col-sm-4'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd hh:ii:ss'
                            ]
                        ]);
                        ?>
                    </div>
                </div>
                <div class="form-group field-swimmatch-start_end_time">
                    <label class="col-sm-2 control-label" for="swimmatch-start_end_time">赛事开始/结束时间</label>
                    <div class="col-sm-4">
                        <?=
                        DateTimePicker::widget([
                            'model' => $model,
                            'attribute' => 'start_time',
                            'options' => ['placeholder' => '赛事开始时间', 'class' => 'col-sm-4'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd hh:ii:ss'
                            ]
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-4">
                        <?=
                        DateTimePicker::widget([
                            'model' => $model,
                            'attribute' => 'end_time',
                            'options' => ['placeholder' => '赛事结束时间', 'class' => 'col-sm-4'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd hh:ii:ss'
                            ]
                        ]);
                        ?>
                    </div>
                </div>

                <?= $form->field($model, 'weight')->textInput()->textInput(['placeholder'=> '权重'])->label('权重') ?>

                <input type="hidden" id="modifyPublish" class="form-control" name="SwimMatch[publish]"
                       value="<?= $model->publish; ?>">
                <div class="form-group field-swimmatch-publish">
                    <label class="col-sm-2 control-label" for="swimmatch-publish">发布状态</label>
                    <div class="col-sm-4" style="margin-left: 15px;">
                        <?=
                        SwitchInput::widget([
                            'name' => 'publish-plugin',
                            'value' => ($model->publish == Match::PUBLISH_YES) ? true : false,
                            'pluginOptions'=>[
                                'handleWidth' => 10,
                                'onText'=>'是',
                                'offText'=>'否',
                                'onColor' => 'success',
                                'offColor' => 'danger',
                            ],
                            'pluginEvents' => [
                                "switchChange.bootstrapSwitch" => "function(event, state) {
                        $('#modifyPublish').val(state ? 1 : 2);
                    }"
                            ],
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab_2">
                <br/><br/><br/>
                <div class="form-group field-swimmatch-intro">
                    <label class="col-sm-2 control-label" for="swimmatch-intro">简介</label>
                    <div class="col-sm-4" style="width:700px;">
                        <?=
                        UEditor::widget([
                            'model' => $model,
                            'attribute' => 'intro',
                            'config' => [
                                'autoHeightEnabled' => false,
                                'serverUrl' => ['/ueditor/index'],
                            ]
                        ]);
                        ?>
                    </div>
                </div>

                <div class="form-group field-swimmatch-disclaimer">
                    <label class="col-sm-2 control-label" for="swimmatch-disclaimer">免责申明</label>
                    <div class="col-sm-4" style="width:700px;">
                        <?=
                        UEditor::widget([
                            'model' => $model,
                            'attribute' => 'disclaimer',
                            'config' => [
                                'autoHeightEnabled' => false,
                                'serverUrl' => ['/ueditor/index'],
                            ]
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab_3">
                <br/><br/><br/>
                <div class="form-group field-swimmatch-qrcode">
                    <label class="col-sm-2 control-label" for="swimmatch-qrcode">赛事小程序码</label>
                    <div class="col-sm-4" style="width:700px;">
                        <?=
                        FileInput::widget([
                            'name' => 'SwimMatch[qrcode]',
                            'options' => [
                                'accept' => 'image/*',
                                'multiple' => false
                            ],
                            'pluginOptions' => [
                                'initialPreview'=>[
                                    $model->qrcode
                                ],
                                'initialPreviewAsData'=>true,
                                'showUpload' => false,
                            ]
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
