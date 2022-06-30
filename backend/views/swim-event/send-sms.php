<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\file\FileInput;
use kartik\daterange\DateRangePicker;
use common\models\Region;
use yii\helpers\Url;
use backend\assets\AppAsset;
use common\models\MatchCategory;
use yii\helpers\ArrayHelper;
use dosamigos\ckeditor\CKEditor;
use budyaga\cropper\Widget;
use kucha\ueditor\UEditor;

/* @var $this yii\web\View */
/* @var $model common\models\Match */
/* @var $form yii\widgets\ActiveForm */
$this->title="短信提醒";
$this->params['breadcrumbs'][] = ['label' => '赛事列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="panel panel-<?=AppAsset::BOX_CLASS?>">
       	<div class="panel-heading <?=AppAsset::BOX_BORDER?>">
        	<h3 class="panel-title"><?=$this->title?></h3>
        </div>
        <div class="panel-body">
<div class="match-form">
    <?php $form = ActiveForm::begin(); ?>



	<?php 
    	echo Html::tag('div',Html::tag('label','接收短信号码(短信以为逗号隔开)').Html::textarea('mobiles',$mobiles,['class'=>'form-control','rows'=>7,'placeholder'=>'请输入接收短信的手机号码,多个以逗号分隔' ]),['class'=>'form-group']);
    ?>
    <div class="alert alert-warning alert-dismissible">
        <h4><i class="icon fa fa-warning"></i> 提醒!</h4>
        短信内容必须包含签名,即【签名文字】。签名文字长度为2~8个字符。
    </div>


    <?php

    	echo Html::tag('div',Html::tag('label','短信内容').Html::textarea('content',$content,['class'=>'form-control js-change-length','rows'=>7,'placeholder'=>'请输入短信内容',/*'maxlength'=>210*/]),['class'=>'form-group']);
	?>

    <!--<p class="tr textarea-pop orange js-show-length">还可以输入<i class="js-num">200</i>个字</p>-->


    <div class="form-group text-center">
        <?= Html::submitButton( '发送',['class'=>'btn btn-danger','data-confirm'=>'确定发送?']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div></div>
<?php /*$this->beginBlock('myjs') */?><!--
    change_length_fun=function(obj,num){
        $(obj).on('keyup',function(){
            var len = $(obj).val();
            $(".js-num").text(num-len.length);
            if(len.length>=num){
                $(obj).siblings(".js-show-length").find(".js-num").text(0);
                $(obj).val(len.substring(0,num));
            }
        });
    }
  change_length_fun(".js-change-length",210);
  var len = $('.js-change-length').val();
  $(".js-num").text(210-len.length);
<?php /*$this->endBlock()*/?>
--><?php /*$this->registerJs($this->blocks['myjs'])*/?>