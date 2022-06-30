<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\RegisterRelation;

/* @var $this yii\web\View */
/* @var $model backend\models\RegisterRelation */
/* @var $form yii\widgets\ActiveForm */
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

    <?= $form->field($model, 'state')
        ->dropDownList(RegisterRelation::$stateList, ['prompt' => '请选择状态'])
        ->label('赛事名称') ?>

    <div class="col-md-2">
    </div>
    <div class="col-md-4">
    <?= Html::submitButton('修改', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
