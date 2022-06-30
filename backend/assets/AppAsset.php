<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    const  BOX_CLASS  =   'default';
    const  PANELCOLOR  =   'aqua';
    const  BOX_BORDER  =   'with-border';
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public static function addSchedule($view)
    {
        $view->registerCssFile('/calendar/fullcalendar.min.css');
        $view->registerCssFile('/calendar/jquery.qtip.min.css');
        $view->registerJsFile('/calendar/jquery.qtip.min.js',[AppAsset::className(),'depends' => 'backend\assets\AppAsset']);
        $view->registerJsFile('/calendar/moment.min.js',[AppAsset::className(),'depends' => 'backend\assets\AppAsset']);
        $view->registerJsFile('/calendar/fullcalendar.min.js',[AppAsset::className(),'depends' => 'backend\assets\AppAsset']);
        $view->registerJsFile('/calendar/zh-cn.js',[AppAsset::className(),'depends' => 'backend\assets\AppAsset']);

    }
}
