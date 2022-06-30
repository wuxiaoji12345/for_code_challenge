<?php

use mdm\admin\components\MenuHelper;
use common\components\Menu;

?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->getIdentity()->username; ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> 已登陆 </a>
            </div>
        </div>

        <?php
            $callback = function ($menu) {
                return [
                    'label' => $menu['name'],
                    'url' => [$menu['route']],
                    'icon' => $menu['data'],
                    'items' => $menu['children']
                ];
            };
            $init = [
                ['label' => '菜单', 'options' => ['class' => 'header']],
                ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest]
            ];
            $menu = MenuHelper::getAssignedMenu(Yii::$app->user->id, null, $callback, true);
            echo Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                    'items' => array_merge($init, $menu),
                ]
            ) ?>

    </section>

</aside>
