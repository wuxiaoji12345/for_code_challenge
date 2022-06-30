<?php

use yii\helpers\Html;
use kartik\file\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\MatchSession */
/* @var $matchModel backend\models\Match */

$this->title = '线下用户导入';
$this->params['breadcrumbs'][] = ['label' => '赛事场次管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="scrollable padder">
    <div class="row bg-light m-b">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold"><?= '赛事:' . $matchModel->title ?></header>
                <div class="panel-body">
                    <div class="form-group field-swimmatchsession-item col-sm-12">
                        <label class="control-label">参赛项目导入</label><br/><br/>
                        <div class="col-sm-2">
                            <?= Html::a('项目模版下载','/赛事场次项目模版.xlsx', ['class' => 'btn-lg btn-warning', 'download' => '赛事场次项目模版.xlsx']) ?>
                        </div>
                        <div class="col-sm-5" style="width:700px;">
                            <?=
                            FileInput::widget([
                                'name' => 'item',
                                'options' => [
                                    'accept' => '.csv, .xlsx, .xls',
                                    'multiple' => false
                                ],
                                'pluginOptions' => [
                                    'uploadUrl' => Url::to(['/swim-match-session/item-import-upload']),
                                    'uploadExtraData' => [
                                        'ssid' => $model->id,
                                    ],
                                    'showUpload' => true,
                                    'showPreview' => false,
                                    'showCaption' => true,
                                    'showRemove' => true,
                                ],
                                'pluginEvents' => [
                                    "fileuploaded" => "function(event, data, previewId, index) {
                                        var ret = data.response;
                                        alert(ret.msg);
                                    }",
                                    "fileuploaderror" => "function(event, data, msg) {
                                        alert('上传失败');
                                    }",
                                ],
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="panel-body">
                    <div class="form-group field-swimmatchsession-item col-sm-12">
                        <label class="control-label">线下报名导入</label><br/><br/>
                        <div class="col-sm-2">
                            <?= Html::a('报名模版下载', '/线下报名导入模版.xlsx', ['class' => 'btn-lg btn-warning', 'download' => '线下报名导入模版.xlsx']) ?>
                        </div>
                        <div class="col-sm-5" style="width:700px;">
                            <?=
                            FileInput::widget([
                                'name' => 'enroll',
                                'options' => [
                                    'accept' => '.csv, .xlsx, .xls',
                                    'multiple' => false
                                ],
                                'pluginOptions' => [
                                    'uploadUrl' => Url::to(['/swim-match-session/offline-enroll-import-upload']),
                                    'uploadExtraData' => [
                                        'ssid' => $model->id,
                                    ],
                                    'showUpload' => true,
                                    'showPreview' => false,
                                    'showCaption' => true,
                                    'showRemove' => true,
                                ],
                                'pluginEvents' => [
                                    "fileuploaded" => "function(event, data, previewId, index) {
                                        $(this).fileinput('reset');
                                        var ret = data.response;
                                        alert(ret.msg);
                                    }",
                                    "fileuploaderror" => "function(event, data, msg) {
                                        $(this).fileinput('reset');
                                        alert('上传失败');
                                    }",
                                ],
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>