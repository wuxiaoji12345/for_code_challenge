<?php

use kartik\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\MatchCategory;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model common\models\search\MatchSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="match-search">


<?php $form =  ActiveForm::begin([
    'method'=>'get',
    'type' => ActiveForm::TYPE_INLINE,
    'fieldConfig' => ['autoPlaceholder'=>false],

]); ?>


<?php $form->field($model, 'date_range', [
        'addon'=>['prepend'=>['content'=>'<i class="glyphicon glyphicon-calendar"></i>']],
        'options'=>['class'=>'drp-container form-group']
    ])->widget(DateRangePicker::classname(), [
        'useWithAddon'=>true,
    	'convertFormat'=>true,
        'pluginOptions'=>[        
            'locale'=>['format' => 'Y-m-d','separator'=>'--'],
        ],
        'pluginEvents'=>[
            "apply.daterangepicker" => "function() { jQuery('form').submit();}",
            
        ]
    ]);
 ?>


<?= $form->field($model,'keywords')->textInput(['onchange'=>"$(form).submit()",'placeholder'=>'赛事名称/地点'])?>

    <div class="form-group">
    <?=Html::a('日历查看', ['index','type'=>2], ['data-pjax'=>0, 'class'=>'btn btn-default'])?>
    </div>
    <div class="form-group">

    <?=Html::a('新增赛事',['create'],['class'=>'btn btn-success'])?>
</div>
<?php ActiveForm::end(); ?>
</div>