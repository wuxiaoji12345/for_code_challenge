<?php
return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'wsaf/wsafauth',
        'pluralize' => false,
        'extraPatterns' => [
            'POST login' => 'login',
            'POST config' => 'config',
        ]
    ],
];