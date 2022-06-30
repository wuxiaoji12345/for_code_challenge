<?php

use backend\models\Address;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\UserChannelExtra;
use common\helpers\Utils;

/* @var $this yii\web\View */
/* @var $model backend\models\UserChannelExtra */
/* @var $form yii\widgets\ActiveForm */

$options = ($model->isNewRecord) ? '' : ' disabled="true" ';
$encrypt = ($model->isNewRecord) ? '' : Utils::ecbEncrypt(Yii::$app->params['channelIDKey'], $model->user_channel_id);
//$encrypt = ($model->isNewRecord) ? '' : $model->user_channel_id;
?>

<div class="swim-user-channel-extra-form">

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

    <div class="form-group field-userchannelextra-user_channel_id_encrypt">
        <label class="col-sm-2 control-label" for="userchannelextra-user_channel_id_encrypt">用户渠道id加密内容</label>
        <div class="col-sm-4">
            <input type="text" id="user_channel_id_encrypt" class="form-control" <?php echo $options; ?>
                   name="user_channel_id_encrypt" aria-required="true" aria-invalid="false"
                   value="<?= $encrypt; ?>"
            >
        </div>
        <div class="help-block"></div>
    </div>

    <?= $form->field($model, 'realname')->textInput()->label('真实姓名') ?>

    <?= $form->field($model, 'is_checker')->dropDownList(UserChannelExtra::$checkerList, ['是否为场馆检查员'])->label('是否为场馆检查员') ?>
    <?= $form->field($model, 'is_super_checker')->dropDownList(UserChannelExtra::$SuperCheckerList, ['是否为超级检查员'])->label('是否为超级检查员') ?>

    <?= $form->field($model, 'is_owner')->dropDownList((new Address())->dropdownList(), ['prompt' => '请选择'])->label('所属场馆(可选)') ?>

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
