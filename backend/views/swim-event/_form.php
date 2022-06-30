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
use froala\froalaeditor\FroalaEditorWidget;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model common\models\Match */
/* @var $form yii\widgets\ActiveForm */
?>
    <div class="panel panel-<?= AppAsset::BOX_CLASS ?>">
        <div class="panel-heading <?= AppAsset::BOX_BORDER ?>">
            <h3 class="panel-title"><?= $this->title ?></h3>
        </div>
        <div class="panel-body">
            <div class="match-form">
                <?php $form = ActiveForm::begin([
                    'type' => ActiveForm::TYPE_HORIZONTAL,
                    'action' => isset($action) ? $action : '',
                ]); ?>

                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>


                <?= $form->field($model, 'imgurl')->widget(Widget::className(), [
                    'uploadUrl' => Url::toRoute('/upload/upload-photo'),
                    'width' => 800,
                    'height' => 500,
                    'cropAreaWidth' => 400,
                    'cropAreaHeight' => 250,
                    'thumbnailWidth' => 400,
                    'thumbnailHeight' => 250,

                ]) ?>



                <?= $form->field($model, 'date_range', [
                    'addon' => ['prepend' => ['content' => '<i class="glyphicon glyphicon-calendar"></i>']],
                    'options' => ['class' => 'drp-container form-group']
                ])->widget(DateRangePicker::classname(), [
                    'useWithAddon' => true,
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'locale' => ['format' => 'Y-m-d', 'separator' => '--'],
                    ]
                ]);
                ?>
                <?= $form->field($model, 'reg_date_range', [
                    'addon' => ['prepend' => ['content' => '<i class="glyphicon glyphicon-calendar"></i>']],
                    'options' => ['class' => 'drp-container form-group']
                ])->widget(DateRangePicker::classname(), [
                    'useWithAddon' => true,
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'timePicker' => true,
                        'timePickerIncrement' => 15,
                        'locale' => ['format' => 'Y-m-d H:i', 'separator' => '--'],
                        'showDropdowns' => true


                    ]
                ]);
                ?>
                <?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'intro')->widget(FroalaEditorWidget::className(), [
                    'model' => $model,
                    'attribute' => 'intro',
                    'clientPlugins' => [
                        'align', 'char_counter', 'code_beautifier', 'code_view', 'colors',
                        'draggable', 'emoticons', 'entities', 'file', 'font_family',
                        'font_size', 'fullscreen', 'image', 'image_manager', 'inline_style',
                        'line_breaker', 'link', 'lists', 'paragraph_format', 'paragraph_style',
                        'quick_insert', 'quote', 'save', 'table', 'url', 'video', 'help',
                        'special_characters', 'word_paste'

                    ],
                    'clientOptions' => [
                        'imageDefaultWidth' => 0,
                        'iframe' => false,
                        'height' => 200,
                        'language' => 'zh_cn',
                        'imageUploadParam' => 'image',
                        'imageUploadURL' => \yii\helpers\Url::to(['upload/upload/'])
                    ],

                ]) ?>
                <?= $form->field($model, 'area')->widget(\chenkby\region\Region::className(), [
                    'model' => $model,
                    'url' => Url::toRoute(['/region/get-region']),
                    'province' => [
                        'attribute' => 'province_id',
                        'items' => Region::getRegion(),
                        'options' => ['class' => 'form-control col-xs-4 form-control-inline', 'prompt' => '选择省份']
                    ],
                    'city' => [
                        'attribute' => 'city_id',
                        'items' => Region::getRegion($model['province_id']),
                        'options' => ['class' => 'form-control form-control-inline', 'prompt' => '选择城市']
                    ],
                    'district' => [
                        'attribute' => 'district_id',
                        'items' => Region::getRegion($model['city_id']),
                        'event' => 'changedistrice()',
                        'options' => ['class' => 'form-control form-control-inline district_id', 'prompt' => '选择县/区'],
                    ]
                ]); ?>


                <div class="form-group ">
                    <label class="control-label col-md-2" for="match-weight">详细地址</label>
                    <div class="col-md-10">
                        <div class="input-group" id="showmodal">
                            <?= Html::textInput('showaddress', $model->address, ['readonly' => true, 'disabled' => 'true', 'class' => 'form-control']) ?>
                            <div class="input-group-addon">修改</div>
                        </div>
                    </div>

                </div>


                <?php
                Modal::begin([
                    'header' => '详细地址',
                    'size' => Modal::SIZE_LARGE,
                    'id' => 'mapmodal',
                ]);
                ?>
                <?= Html::activeHiddenInput($model, 'msid') ?>


                <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
                <input type="text" id="search" class="form-control" placeholder="请输入关键字搜索">
                <div class="widget-maps" id="map" style="position:relative;height: 500px;border:1px solid #ccc;"></div>
                <?= Html::activeHiddenInput($model, 'latitude', ['id' => 'lat']) ?>
                <?= Html::activeHiddenInput($model, 'longitude', ['id' => 'lng']) ?>

                <div class="form-group text-center margin">
                    <?= Html::button('确定', ['class' => 'btn btn-primary closemodal']) ?>

                </div>


                <?php
                Modal::end();
                ?>
                <?= Html::activeHiddenInput($model, 'msid', ['value' => Yii::$app->request->get('msid')]) ?>
                <div class="form-group text-center margin">
                    <?= Html::submitButton($model->isNewRecord ? CREATE : UPDATE, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>

<?php $this->beginBlock('js') ?>
    var adcode  =   "";
    var district_id = '<?= $model->district_id; ?>';

    function changedistrice() {
    console.log('change');
    }

    var longitude = '<?= $model->longitude; ?>';
    var latitude = '<?= $model->latitude ?>';
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['js'], \yii\web\View::POS_END); ?>
<?php $this->beginBlock('myjs') ?>

    $(".closemodal").on('click',function(){
    var address    =   $("input[name='Match[address]']").val();
    $("input[name='showaddress']").val(address);
    $("#mapmodal").modal('hide');
    })
    $("#showmodal").on('click', function () {
    if (district_id) {
    $.ajax({
    url: '/region/getadcode',
    data: {'id':district_id},
    success: function (res) {
    adcode  =   res.adcode;
    initGDmap(longitude, latitude,adcode);
    setTimeout(function(){
    $("#mapmodal").modal('show');
    },200)
    },
    dataType: 'json'
    });
    }else{
    initGDmap(longitude, latitude,adcode);
    setTimeout(function(){
    $("#mapmodal").modal('show');
    },200)
    }
    })

<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['myjs']); ?>
<?php $this->registerCss('
.amap-sug-result{
    z-index:9999;
}
') ?>