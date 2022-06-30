<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Search\UserChannelExtraSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $params array */
?>

<div class="panel panel-default">
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

        <?= $form->field($model, 'user_channel_id')
            ->textInput(['placeholder' => 'user_channel_id']) ?>

        <?= Html::input('text', 'user_channel_id_encrypt',
            isset($params['user_channel_id_encrypt']) ? $params['user_channel_id_encrypt'] : null,
            ['class' => 'form-control', 'placeholder' => 'user_channel_id加密内容', 'style' => 'width:300px;']) ?>

        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
