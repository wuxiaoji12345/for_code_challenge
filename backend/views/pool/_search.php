<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Search\PoolSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel panel-default">
    <header class="panel-heading">
        搜索
    </header>
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

        <?= $form->field($model, 'sid')->textInput(['placeholder'=> '场馆id']) ?>

    <?= $form->field($model, 'name')->textInput(['placeholder'=> '泳池名称']) ?>

        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
