<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Search\ScoreEnrollSearch */
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

    <?= $form->field($model, 'ssid')->textInput(['placeholder' => '场次id']) ?>

    <?= Html::input('text', 'itemName',
        isset($params['itemName']) ? $params['itemName'] : null,
        ['class' => 'form-control','placeholder'=> '项目名称']) ?>

    <?php  echo $form->field($model, 'name')->textInput(['placeholder' => '姓名']) ?>

    <?php echo $form->field($model, 'phone')->textInput(['placeholder' => '手机号']) ?>

        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
