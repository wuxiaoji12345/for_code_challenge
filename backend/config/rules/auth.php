<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'user',
        'except' => ['delete', 'view'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET info' => 'info',
            'OPTIONS login' => 'login',
            'GET mp-login' => 'mp-login',
            'GET bind-mp-info' => 'bind-mp-info',
            'POST unbind-mp' => 'unbind-mp',
            'POST login' => 'login',
            'POST get_info' => 'get-info',
            'POST edit' => 'edit',
            'OPTIONS get_info' => 'get-info',
            'GET get_info' => 'get-info',
            'POST changepassword' => 'change-password',
            'OPTIONS changepassword' => 'change-password',
            'POST address' => 'address',
            'POST wsaf-login' => 'wsaf-login',
            'POST wsaf-login-sports' => 'wsaf-login-sports',
            'POST wsaf-login-sports2' => 'wsaf-login-sports2',
            'POST hp-login' => 'hp-login',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'auth-item',
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'GET view' => 'view',
            'POST create' => 'create',
            'POST update' => 'update',
            'POST delete' => 'delete',
            'GET pid-list' => 'pid-list',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'auth-role',
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'GET view' => 'view',
            'POST create' => 'create',
            'POST update' => 'update',
            'POST delete' => 'delete',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'auth-role-item',
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'GET view' => 'view',
            'POST create' => 'create',
            'POST update' => 'update',
            'POST delete' => 'delete',
            'GET role-auth-item-list' => 'role-auth-item-list',
            'POST batch-update' => 'batch-update',
            'GET role-auth-item-action'=>'role-auth-item-action',
            'POST role-auth-item-update' => 'role-auth-item-update',
        ]
    ],
];