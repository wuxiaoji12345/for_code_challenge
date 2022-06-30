<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'sports',
        'pluralize' => false,
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'sports-item',
        'pluralize' => false,
        'extraPatterns' => [
            'GET settings' => 'settings'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'sports-item-sub',
        'pluralize' => false,
        'extraPatterns' => [
            'GET template' => 'template',
            'POST points' => 'points',
            'POST points-rule' => 'points-rule'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'sports-item-user',
        'pluralize' => false,
        'extraPatterns' => [
            'POST import' => 'import',
            'GET export' => 'export',
            'POST check' => 'check',
            'POST transcode' => 'transcode',
            'POST sync' => 'sync',
            'GET json' => 'json',
            'PUT score-calc' => 'score-calc'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'sports-item-sub-category',
        'pluralize' => false,
    ],
];
