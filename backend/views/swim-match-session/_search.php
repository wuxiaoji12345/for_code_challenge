<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Search\MatchSessionSearch */
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

    <?= Html::input('text', 'matchName',
        isset($params['matchName']) ? $params['matchName'] : null,
        ['class' => 'form-control','placeholder'=> '赛事名称']) ?>

    <?= $form->field($model, 'name')->textInput(['placeholder'=> '场次名称']) ?>

    <?= Html::input('text', 'addressName',
        isset($params['addressName']) ? $params['addressName'] : null,
        ['class' => 'form-control','placeholder'=> '场馆名称']) ?>

        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
