<?php
return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'referee/apply',
        'pluralize' => false,
        'extraPatterns' => [
            'GET list' => 'list',
            'POST edit' => 'edit',
            'POST check' => 'check',
            'POST destroy'=>'destroy'
        ]
    ],
];