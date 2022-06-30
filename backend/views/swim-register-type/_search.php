<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Search\RegisterTypeSearch */
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

    <?= $form->field($model, 'title')->textInput(['placeholder' => '组别名']) ?>

        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
