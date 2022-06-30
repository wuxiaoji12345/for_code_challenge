<?php
return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'venue/stadium',
        'pluralize' => false,
        'extraPatterns' => [
            'POST edit' => 'edit',
            'POST check' => 'check',
            'POST transcode' => 'transcode',
            'POST sync' => 'sync',
            'GET list' => 'list',
            'POST detail' => 'detail',
            'PUT score-calc' => 'score-calc',
            'POST destroy'=>'destroy'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'venue/notice',
        'pluralize' => false,
        'extraPatterns' => [
            'POST edit' => 'edit',
            'POST check' => 'check',
            'POST transcode' => 'transcode',
            'POST sync' => 'sync',
            'GET list' => 'list',
            'POST detail' => 'detail',
            'PUT score-calc' => 'score-calc',
            'POST destroy'=>'destroy'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'venue/reserve-order',
        'pluralize' => false,
        'extraPatterns' => [
            'POST edit' => 'edit',
            'POST check' => 'check',
            'POST transcode' => 'transcode',
            'POST sync' => 'sync',
            'GET list' => 'list',
            'POST detail' => 'detail',
            'PUT score-calc' => 'score-calc',
            'POST destroy'=>'destroy',
            'POST import' => 'import',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'venue/reserve-order-detail',
        'pluralize' => false,
        'extraPatterns' => [
            'POST edit' => 'edit',
            'POST check' => 'check',
            'POST transcode' => 'transcode',
            'POST sync' => 'sync',
            'GET list' => 'list',
            'POST detail' => 'detail',
            'PUT score-calc' => 'score-calc',
            'POST destroy'=>'destroy'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'venue/statistics',
        'pluralize' => false,
        'extraPatterns' => [
            'POST reserve'=>'reserve',
            'POST exchange'=>'exchange',
            'POST in-out'=>'in-out'
        ]
    ],



];