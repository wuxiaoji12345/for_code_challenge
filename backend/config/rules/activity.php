<?php
return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'activity',
        'pluralize' => false,
        'extraPatterns' => [
            'POST create' => 'create',
            'PUT update' => 'update',
            'OPTIONS update' => 'update',
            'POST genqrcode' => 'gen-qrcode',
            'GET balance' => 'balance',
            'GET statistics' => 'statistics',
            'POST copy' => 'copy-match',
            'GET category-list'=>'category-list',
            'POST edit'=>'edit'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'activity-register-type',
        'pluralize' => false,
        'extraPatterns' => [
            'POST copy' => 'copy'
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'activity-sub',
        'pluralize' => false,
        'extraPatterns' => [
            'POST edit'=>'edit',
            'PUT update' => 'update'
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'activity-sub-playerinfo',
        'pluralize' => false,
        'extraPatterns' => [
            'POST index'=>'index',
            'PUT update' => 'update',
            'GET export' => 'export'
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'activity-register-relation',
        'pluralize' => false,
        'extraPatterns' => [
            'OPTIONS checkin' => 'check-in',
            'POST checkin' => 'check-in',
            'POST refundnotify' => 'refundnotify',
            'PUT refundnotify' => 'refundnotify',
            'GET refundnotify' => 'refundnotify',
            'OPTIONS refund' => 'refund',
            'POST refund' => 'refund',
            'GET export' => 'export',
        ],
    ],

];