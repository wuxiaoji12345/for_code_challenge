<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Search\AddressSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="swim-address-search panel panel-default">
    <div class="panel-body">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        "options" => ['class' => 'form-inline'],
        'fieldConfig' => [
            'template' => "{input}",
            'labelOptions' => [
                'class' => 'control-label'
            ]
        ]
    ]); ?>
    <?= $form->field($model, 'name')
        ->textInput(['class' => 'form-control', 'placeholder'=> '场馆名称']) ?>

    <div class="form-group">
        <?= Html::submitButton('筛选', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>
