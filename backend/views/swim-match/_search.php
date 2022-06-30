<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Search\MatchSearch */
/* @var $form yii\widgets\ActiveForm */
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

    <?= $form->field($model, 'title')->textInput(['placeholder'=> '赛事标题']) ?>

        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
