<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BackendUser */
/* @var $form yii\widgets\ActiveForm */


$this->title = '修改密码';
$this->params['breadcrumbs'][] = ['label' => '我的', 'url' => ['/']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="scrollable padder">
    <div class="row bg-light m-b">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold"><?= Html::encode($this->title) ?></header>
                <div class="panel-body">
                    <div class="user-form">

                        <?php $form = ActiveForm::begin([
                            'options' => [
                                'class' => 'form-horizontal'
                            ],
                            'fieldConfig' => [
                                'template' => "{label}\n<div class=\"col-sm-4\">{input}</div><div class=\"help-block\">{error}</div>",
                                'labelOptions' => [
                                    'class' => 'col-sm-2 control-label'
                                ]
                            ]
                        ]); ?>

                        <div class="form-group field-signupform-password required">
                            <label class="col-sm-2 control-label" for="signupform-password">旧密码</label>
                            <div class="col-sm-4">
                                <input type="password" id="signupform-password" class="form-control" name="ModifyForm[old-password]" aria-required="true">
                            </div>
                            <div class="help-block"><div class="help-block"></div></div>
                        </div>
                        <div class="form-group field-signupform-password required">
                            <label class="col-sm-2 control-label" for="signupform-password">新密码 </label>
                            <div class="col-sm-4">
                                <input type="password" id="signupform-password" class="form-control" name="ModifyForm[new-password]" aria-required="true">
                            </div>
                            <div class="help-block"><div class="help-block"></div></div>
                        </div>
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-4">
                        <?= Html::submitButton('修改', ['class' => 'btn btn-success']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </section>
        </div>
    </div>
</section>
