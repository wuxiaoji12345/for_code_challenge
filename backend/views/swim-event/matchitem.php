<?php 
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="col-lg-4 col-xs-6 col-md-4">
  <!-- small box -->
  <div class="box box-solid bg-aqua">   
    <div class="box-body border-radius-none">
      <a  style="color:#FFF;" href="<?=Url::to(['match/update','id'=>$model->id])?>">
       <h4 style="min-height:38px;"><?=$model->mtitle?></h4>
       <p><span class="fa fa-home"></span>&nbsp;&nbsp;&nbsp;总指挥:<?=$model->user->fullname?></p>
       <p><span class="fa  fa-users"></span>&nbsp;&nbsp;&nbsp;合作方:<?=$model->partner?$model->partner:"无"?></p>
       <p><span class="fa fa-calendar"></span>&nbsp;&nbsp;&nbsp;起止日期:<?=date("Y-m-d",$model->start_time)?>-<?=date("Y-m-d",$model->end_time)?></p>
       <p><span class="fa fa-calendar"></span>&nbsp;&nbsp;&nbsp;报名起止日期:<?=date("Y-m-d",$model->reg_start_time)?>-<?=date("Y-m-d",$model->reg_end_time)?></p>
       <p style="min-height:40px;"><span class="fa fa-map-marker"></span>&nbsp;&nbsp;&nbsp;地点:<?=$model->address?></p>
    </a> 
    </div> 
    <div class="box-footer no-border text-xs">
    
        <div class="row">
            <div class="col-xs-4 text-center label">
                    <?=Html::a('删除',Url::to(['match/delete','id'=>$model->id]),['data-confirm'=>"确定要删除吗?",'data-method'=>'post'])?>
            </div>

            <div class="col-xs-4 text-center label">
                    <?=Html::a('任务管理',Url::to(['task/index','matchid'=>$model->id]))?>
            </div>
            <div class="col-xs-4 text-center label">
                    <?=Html::a('组别信息',Url::to(['register-type/index','matchid'=>$model->id]))?>
            </div>
            <div class="col-xs-4 text-center label">
                    <?=Html::a('报名信息',Url::to(['register-relation/index','matchid'=>$model->id]))?>
            </div>
            <div class="col-xs-4 text-center label">
                    <?=Html::a('统计分析',Url::to(['statistics/statistics','matchid'=>$model->id]))?>
            </div>
            <div class="col-xs-4 text-center label">
                    <?=Html::a('计时管理',Url::to(['times-group/index','matchid'=>$model->id]))?>
            </div>
            <div class="col-xs-4 text-center label">
                    <?=Html::a('检录管理',Url::to(['checkin/index','aid'=>$model->id]))?>
            </div>
            <div class="col-xs-4 text-center label">
                    <?=Html::a('赛道管理',Url::to(['match-routes/index','matchid'=>$model->id]))?>
            </div>
            <div class="col-xs-4 text-center label">
                    <?=Html::a('成绩展示',Url::to(['/frontend/match-time-display/everyurl','mid'=>$model->id,'limit'=>20]))?>
            </div>
            <div class="col-xs-4 text-center label">
                    <?=Html::a('账户信息',Url::to(['/backend/register-group-account/index','aid'=>$model->id]))?>
            </div>
            <div class="col-xs-4 text-center label">
                    <?=Html::a('码管理',Url::to(['/backend/match-coupon','aid'=>$model->id]))?>
            </div>
            <div class="col-xs-4 text-center label text-red">
                    <?=Html::a('报名渠道',Url::to(['/backend/seo','categoryid'=>$model->category_id]),['class'=>'text-red'])?>
            </div>
        </div>
    </div>
    
    </div>
</div>
<?php 
 $this->registerCss('
  .label{font-size:1em;}
');
?>


