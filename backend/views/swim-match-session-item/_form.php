<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Match;
use backend\models\MatchSessionItem;
use backend\models\MatchSession;

/* @var $this yii\web\View */
/* @var $model backend\models\MatchSessionItem */
/* @var $form yii\widgets\ActiveForm */

$ssList = [];
if (!$model->isNewRecord) {
    $data = (new MatchSession())->getSsidList($model->matchid);
    foreach ($data as $v) {
        $ssList[$v['id']] = 'ID-' . $v['id'] . ' ' . $v['name'];
    }
}
?>

<div class="swim-match-session-item-form">

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

    <?= $form->field($model, 'ssid')->dropDownList($ssList, ['prompt' => '请选择场次'])->label('场次ID') ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('场次名称') ?>

    <?= $form->field($model, 'type')
        ->dropDownList(MatchSessionItem::$typeList, ['prompt' => '请选择比赛项目'])
        ->label('比赛项目') ?>

    <?= $form->field($model, 'gender')
        ->dropDownList(MatchSessionItem::$genderList, ['prompt' => '请选择参赛性别限制'])
        ->label('性别限制') ?>

    <?= $form->field($model, 'distance')->textInput()->label('游泳距离(m)') ?>

    <?= $form->field($model, 'agemin')->textInput()->label('最小参赛年龄') ?>

    <?= $form->field($model, 'agemax')->textInput()->label('最大参赛年龄') ?>

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
        $("#matchsessionitem-matchid").change(function() {
            var matchid = $(this).val();
            $("#matchsessionitem-ssid").html("<option value=\"0\">请选择场次</option>");
            if (matchid != "") {
                getSsid(matchid);
            }
        });

        //获取列表
        function getSsid(matchid)
        {
            var href = "' . \yii\helpers\Url::to(['/swim-match-session/get-ssid'], true). '";

            $.ajax({
                "type"  : "GET",
                "url"   : href,
                "data"  : {matchid : matchid},
                success : function(d) {
                    $("#matchsessionitem-ssid").append(d);
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
