<?php
return [

    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'user-channel',
        'pluralize' => false,
        'extraPatterns' => [
            'POST set-point' => 'set-point',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'bk-user',
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'GET view' => 'view',
            'POST create' => 'create',
            // 'POST update' => 'update',
            'POST delete' => 'delete',
            'GET user-role-list' => 'user-role-list',
            'POST assign-role' => 'assign-role',
            'POST login' => 'login',
            'GET menu' => 'menu',
            'GET user-info' => 'user-info',
            'POST modify-info' => 'modify-info',
            'POST destory'=>'destory'
        ]
    ]
];