<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);
$rules = require(__DIR__ . '/rules/index.php');

$config = [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'timeZone' => 'Asia/Shanghai',
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'as access' => [
        'class' => 'backend\components\AccessControl',
        'allowActions' => ['*']
    ],
    'modules' => [
        'training' => [
            'class' => 'backend\modules\training\Module',
        ],
        'wsaf' => [
            'class' => 'backend\modules\wsaf\Module',
        ],
        'referee' => [
            'class' => 'backend\modules\referee\Module',
        ],
        'venue' => [
            'class' => 'backend\modules\venue\Module',
        ],
    ],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemChildTable' => '{{%bk_item_child}}',
            'itemTable' => '{{%bk_item}}',
            'assignmentTable' => '{{%bk_assignment}}',
            'ruleTable' => '{{%bk_rule}}',
            'defaultRoles' => ['通用权限']
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'file' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'info', 'warning'],
                ],
//                'graylog' => [
//                    'class' => 'nex\graylog\GraylogTarget',
//                    'levels' => ['error', 'warning', 'info'],
//                    'categories' => ['application'],
//                    'logVars' => [], // This prevent yii2-debug from crashing ;)
//                    'host' => $params['graylog_host'],
//                    'facility' => $params['graylog_facility'],
//                    'additionalFields' => [
//                        'user-ip' => function ($yii) {
//                            return $yii->request->getUserIP();
//                        },
//                        'headers' => function ($yii) {
//                            return $yii->request->headers;
//                        },
//                        'method' => function ($yii) {
//                            return $yii->request->method;
//                        },
//                        'url' => function ($yii) {
//                            return $yii->request->url;
//                        },
//                        'body' => function ($yii) {
//                            return VarDumper::dumpAsString($yii->request->bodyParams);
//                        },
//                        'response' => function ($yii) {
//                            return VarDumper::dumpAsString($yii->response->data);
//                        },
//                        'responseCode' => function ($yii) {
//                            return VarDumper::dumpAsString($yii->response->data['success']);
//                        },
//                        'tag'=>function($yii){
//                            return $yii->user->id;
//                        }
//                    ]
//                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'public/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => []
        ],
        'user' => [
            'identityClass' => 'backend\models\User',
            'enableAutoLogin' => true,
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if( $response->isSuccessful){
                    Yii::info(implode(
                        ":",
                        [
                            Yii::$app->request->getUserIP(),
                            Yii::$app->request->pathInfo
                        ]
                    ));
                }else{
                    Yii::error(Yii::$app->getErrorHandler()->exception);
                }

                if ($response->format == 'html' || (Yii::$app->request->get('format') && Yii::$app->request->get('format') == 'html')) {
                    echo $response->data;
                    exit;
                }

                if ($response->format == 'raw' || (Yii::$app->request->get('format') && Yii::$app->request->get('format') == 'raw')) {
                    return $response;
                }
                //处理204
                if ($response->statusCode == 204) {
                    $response->data = [
                        'success' => $response->isSuccessful,
                    ];
                    $response->statusCode = 200;
                }
                if ($response->data !== null  && empty(Yii::$app->request->get('suppress_response_code'))) {
                    $response->data = [
                        'success' => $response->isSuccessful,
                        'data' => $response->data,
                        'statusCode' => $response->statusCode,
                        'message' => $response->statusText
                    ];
                    $response->statusCode = 200;
                }
            },
        ],
    ],
    'params' => $params,
    'language' => 'zh-CN',
];

$config['bootstrap'][] = 'admin';
$config['modules']['admin'] = [
    'class' => 'mdm\admin\Module',
    'layout' => 'left-menu',
];

return $config;
