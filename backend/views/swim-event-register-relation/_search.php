<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\RegisterRelation;

/* @var $this yii\web\View */
/* @var $model backend\models\Search\RegisterRelationSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $prams array */
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

    <?= Html::input('text', 'eventName',
        isset($params['eventName']) ? $params['eventName'] : null,
        ['class' => 'form-control','placeholder'=> '培训名称']) ?>
    <?= $form->field($model, 'typename')->textInput(['placeholder' => '组别']) ?>
    <?= $form->field($model, 'order_no')->textInput(['placeholder' => '订单号']) ?>

    <?php echo $form->field($model, 'state')
        ->dropDownList(RegisterRelation::$stateList, ['prompt' => '支付状态', 'onchange' => '$(form).submit()'])
        ->label('支付状态')?>

    <?= $form->field($model, 'name')
        ->textInput(['class' => 'form-control', 'placeholder'=> '姓名']) ?>
    <?= $form->field($model, 'mobile')
        ->textInput(['class' => 'form-control', 'placeholder'=> '手机号']) ?>

        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>

            <?= Html::a('<i class="fa fa-download"></i>导出', ['export'] + $_GET, ['class' => 'btn btn-warning', 'type' => 'button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
