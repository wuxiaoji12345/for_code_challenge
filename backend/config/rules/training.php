<?php
return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'training/training',
        'pluralize' => false,
        'extraPatterns' => [
            'POST import' => 'import',
            'POST edit' => 'edit',
            'POST check' => 'check',
            'POST transcode' => 'transcode',
            'POST sync' => 'sync',
            'GET list' => 'list',
            'GET detail' => 'detail',
            'PUT score-calc' => 'score-calc',
            'POST destroy'=>'destroy'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'training/course',
        'pluralize' => false,
        'extraPatterns' => [
            'POST import' => 'import',
            'POST edit' => 'edit',
            'POST check' => 'check',
            'POST destroy' => 'destroy',
            'POST sync' => 'sync',
            'GET list' => 'list',
            'PUT score-calc' => 'score-calc',
            'GET index'=>'index'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'training/period',
        'pluralize' => false,
        'extraPatterns' => [
            'POST import' => 'import',
            'POST edit' => 'edit',
            'POST check' => 'check',
            'POST transcode' => 'transcode',
            'POST sync' => 'sync',
            'GET list' => 'list',
            'PUT score-calc' => 'score-calc',
            'GET index'=>'index',
            'POST destroy'=>'destroy',
            'POST done'=>'done'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'training/order',
        'pluralize' => false,
        'extraPatterns' => [
            'POST import' => 'import',
            'POST edit' => 'edit',
            'POST check' => 'check',
            'POST transcode' => 'transcode',
            'POST sync' => 'sync',
            'GET list' => 'list',
            'PUT score-calc' => 'score-calc',
            'GET index'=>'index'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'training/periodresult',
        'pluralize' => false,
        'extraPatterns' => [
            'POST import' => 'import',
            'POST edit' => 'edit',
            'POST check' => 'check',
            'POST transcode' => 'transcode',
            'POST sync' => 'sync',
            'GET list' => 'list',
            'PUT score-calc' => 'score-calc',
            'GET index'=>'index'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'training/perioduser',
        'pluralize' => false,
        'extraPatterns' => [
            'POST import' => 'import',
            'POST edit' => 'edit',
            'POST check' => 'check',
            'POST transcode' => 'transcode',
            'POST sync' => 'sync',
            'GET list' => 'list',
            'PUT score-calc' => 'score-calc',
            'GET index'=>'index',
            'GET info'=>'info',
            'POST pass'=>'pass'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'training/white-user',
        'pluralize' => false,
        'extraPatterns' => [
            'POST import' => 'import',
            'GET list' => 'list',
            'POST edit' => 'edit',
            'POST destroy'=>'destroy',
            'GET search' => 'search',
        ]
    ],
];