<?php

use kartik\detail\DetailView;
use backend\assets\AppAsset;
use common\models\Region;

/* @var $this yii\web\View */
/* @var $model common\models\Match */


?>
<div class="match-view">
    <?= DetailView::widget([
        'id'=>'matchdetail',
        'model' => $model,
        'mode' => 'view',
        'bordered' => false,
        'responsive' => true,
        'enableEditMode'=>false,
        'panel' => [
            'heading'=>'<h3 class="panel-title">赛事信息</h3>',
            'type'=>AppAsset::BOX_CLASS,
            'after'=>false,
            'before'=>false,
        ],
        'attributes' => [
            [
            'columns' => [
                [
                    'attribute'=>'title',
                    'valueColOptions'=>['style'=>'width:30%']
            
                ],
                [
                    'attribute'=>'category_id',
                    'value'=>$model->category->title,
                    'valueColOptions'=>['style'=>'width:30%']
                ],
             ],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'date_range',
                        'valueColOptions'=>['style'=>'width:30%']
                
                    ],
                    [
                        'attribute'=>'reg_date_range',
                        'valueColOptions'=>['style'=>'width:30%']
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'address',
                        'valueColOptions'=>['style'=>'width:100%'],
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'intro',
                        'format'=>'raw',
                        'valueColOptions'=>['style'=>'width:100%'],
                    ],
                   
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'icon',
                        'format'=>'image',
                        'valueColOptions'=>['style'=>'width:100%'],
                    ],
                   
                ],
            ]
            // 'icon:image',
        ],
    ]) ?>

</div>
