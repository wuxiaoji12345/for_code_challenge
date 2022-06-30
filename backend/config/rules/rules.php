<?php
return [

    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'match',
        'pluralize' => false,
        'extraPatterns' => [
            'POST create' => 'create',
            'PUT update' => 'update',
            'OPTIONS update' => 'update',
            'POST genqrcode' => 'gen-qrcode',
            'GET balance' => 'balance',
            'GET statistics' => 'statistics',
            'POST copy' => 'copy-match',
            'POST wsaf-matchs' => 'wsaf-matchs',
            'POST check' => 'check',
            'POST check-file'=>'check-file',
            'POST edit'=>'edit'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'purchase',
        'pluralize' => false,
        'extraPatterns' => [
            'GET list' => 'list',
            'GET detail' => 'detail',
//            'OPTIONS update' => 'update',
            'POST make-order' => 'make-order',
            'POST submit-order' => 'submit-order',
            'POST pay-order' => 'pay-order',
            'POST order-detail' => 'order-detail',
            'POST update-order-state' => 'update-order-state',
            'POST my-order' => 'my-order',
            'POST user-address' => 'user-address',
            'POST update-pay-channel' => 'update-pay-channel',
            'POST mbind-user' => 'mbind-user',
            'POST mpay-order' => 'mpay-order',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'times-track',
        'pluralize' => false,
        'extraPatterns' => [
            'OPTIONS remove' => 'remove',
            'PUT remove' => 'remove',
            'POST create-mp-code' => 'create-mp-code',
            'GET export-orient-code' => 'export-orient-code',
            'GET orient-match-list' => 'orient-match-list'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'enroll-info',
        'pluralize' => false,
        'extraPatterns' => [
            'GET export' => 'export-tmpl',
            'POST import' => 'import-tmpl',
            'OPTIONS import' => 'import-tmpl',
            'POST gencert' => 'gen-cert',
            'GET attributes' => 'attributes',
            'POST change-track' => 'change-track',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'enroll-group-info',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'schedule',
        'pluralize' => false,
        'extraPatterns' => [
            'POST create' => 'create',
            'PUT update' => 'update',
            'OPTIONS update' => 'update',
            'GET schedulematchs' => 'schedule-match-list'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'site',
        'pluralize' => false,
        'extraPatterns' => [
            'POST upload' => 'upload',
            'POST oss-signature' => 'oss-signature',
            'GET upload' => 'upload',
            'OPTIONS upload' => 'upload',
            'GET download' => 'download',
            'POST gen-qrcode' => 'gen-qrcode',
            'GET gen-data' => 'gen-data',
            'GET get-oss-object' => 'get-oss-object',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'enterprise-info',
        'pluralize' => false,
        'extraPatterns' => [
            'GET info_by_token' => 'info-by-token'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'region',
        'pluralize' => false,
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'administrative',
        'pluralize' => false,
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'match-detail-info',
        'pluralize' => false,
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'register-info',
        'extraPatterns' => [
            'GET tmpl' => 'export-tmpl',
            'OPTIONS import' => 'import-tmpl',
            'POST import' => 'import-tmpl',
        ],
        'pluralize' => false,
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'activity-register-info',
        'extraPatterns' => [
            'GET tmpl' => 'export-tmpl',
            'OPTIONS import' => 'import-tmpl',
            'POST import' => 'import-tmpl',
        ],
        'pluralize' => false,
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'register-type',
        'pluralize' => false,
        'extraPatterns' => [
            'POST copy' => 'copy'
        ],
    ],

    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'banner',
        'extraPatterns' => [
            'GET get-banners' => 'get-banners',
            'GET get-positions' => 'get-positions',
        ],
        'pluralize' => false,
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'register-relation',
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
        'pluralize' => false,
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'attrs',
        'pluralize' => false,
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'match-image',
        'pluralize' => false,
        'extraPatterns' => [
            'OPTIONS remove' => 'remove',
            'POST remove' => 'remove',
            'POST batch-remove' => 'batch-remove',
            'POST upload' => 'upload',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'match-image-config',
        'pluralize' => false,
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'match-func-mp',
        'pluralize' => false,
        'extraPatterns' => [
            'POST functions' => 'mp-index',
            'GET functions' => 'mp-index',
            'POST edit'=>'edit'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'enroll-checkin-config',
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'OPTIONS search' => 'search',
            'POST search' => 'search',
            'OPTIONS view' => 'view',
            'POST view' => 'config-view',
            'OPTIONS checkin' => 'check-in',
            'POST checkin' => 'check-in',
            'POST get-all-enrolls' => 'enrolls',
            'POST get-enroll-info' => 'enroll-info',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'match-balance',
        'pluralize' => false,
        'except' => ['delete', 'udpate', 'create'],
        'extraPatterns' => [
            'POST apply' => 'apply'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'design-template',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'timing-states',
        'pluralize' => false,
        'extraPatterns' => [
            'GET enroll-group-rank-least-gender' => 'enroll-group-rank-least-gender',
            'GET enrolls' => 'enroll-states',
            'GET index-desc' => 'index-desc',
            'POST all-states'=>'all-states',
            'GET enroll-scores-iaaf'=>'enroll-scores-iaaf',
            'GET gen-enroll-scores'=>'gen-enroll-scores',
            'POST enroll-group-rank'=>'enroll-group-rank',

        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'orient-states',
        'pluralize' => false,
        'extraPatterns' => [
            'GET enrolls' => 'enroll-states'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'score-rank-config',
        'pluralize' => false,
        'extraPatterns' => [
            'GET fields' => 'fields',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'design-template-category',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'mall-goods',
        'pluralize' => false,
        'extraPatterns' => [
            'POST check-goods' => 'check-goods',
        ]
    ], [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'mall-order',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'mall-goods-category',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'mall-goods-inventory',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'express',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'enterprise-message-batch',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'enroll-checkin-config',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'sms',
        'pluralize' => false,
        'extraPatterns' => [
            'POST send-sms' => 'send-sms',
            'GET sms-params' => 'sms-params',
            'GET get-template' => 'sms-template',
            'POST send-batch-sms' => 'send-batch-sms'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'match-invitecode',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'lottery',
        'pluralize' => false,
        'extraPatterns' => [
            'POST get-temp-data' => 'get-temp-data',
            'POST get-users' => 'get-users',
            'POST save-data' => 'save-data',
            'POST export' => 'export',
            'GET export1' => 'export1',
            'POST reset' => 'reset',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'sponsor-product-sku',
        'pluralize' => false,
        'extraPatterns' => [
            'GET filters' => 'filters',
            'POST apply' => "apply",
            'POST apply-new' => "apply-new",
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'sponsor-product-sku-apply',
        'pluralize' => false,
        'extraPatterns' => [
            'POST handle-apply' => "handle-apply",
            'GET matchs' => 'matchs'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'material',
        'pluralize' => false,
        'extraPatterns' => [
            'GET categorys' => 'categorys',
            'GET types' => 'types',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'material-category',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'message-batch',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'message',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'project-category',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'material-type',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'material-unit',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'project',
        'pluralize' => false,
        'extraPatterns' => [
            'POST submit' => 'submit',
            'GET approve-list'=>'approve-list'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'project-material',
        'pluralize' => false,
        'extraPatterns' => [
            'POST batch-create' => 'batch-create',
            'POST batch-delete' => 'batch-delete',
            'GET  project-offer-list'=>'project-offer-list',
            'GET  export-offer'=>'export-offer',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'match-result',
        'pluralize' => false,
        'extraPatterns' => [
            'GET  index'=>'index',
            'POST save-group'=>'save-group',
            'POST delete-group'=>'delete-group',
            'GET  group-list'=>'group-list',
            'GET  group-detail'=>'group-detail',
            //'GET  convert-excel'=>'convert-excel',
            'POST upload-result'=>'upload-result',
            'GET cert-detail' => 'cert-detail',
            'POST save-cert'=>'save-cert',
            'GET  cert-list'=>'cert-list',
            'GET  user-cert'=>'user-cert',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'match-result-image',
        'pluralize' => false,
        'extraPatterns' => [
            'GET  index'=>'index',
            'POST save-group'=>'save-group',
            'POST delete-group'=>'delete-group',
            'GET  group-list'=>'group-list',
            'GET  group-detail'=>'group-detail',
            'POST save-image'=>'save-image',
            'POST delete-image'=>'delete-image',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'jackpot',
        'pluralize' => false,
        'extraPatterns' => [
            'GET  index'=>'index',
            'PUT update'=>'update',
            'POST create'=>'create',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'jackpot-order',
        'pluralize' => false,
        'extraPatterns' => [
            'GET  index'=>'index',
            'PUT update'=>'update',
            'GET  export'=>'export',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'questions',
        'pluralize' => false,
        'extraPatterns' => [
            'GET  index'=>'index',
            'PUT update'=>'update',
            'POST create'=>'create',
            'POST import' => 'import',
            'GET config' => 'config',
            'PUT config-update'=>'config-update',
            'POST config-create'=>'config-create',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'questions-user',
        'pluralize' => false,
        'extraPatterns' => [
            'GET  index'=>'index',
            'PUT update'=>'update',
            'GET  export'=>'export',
            'GET  list'=>'list',
            'POST destroy'=>'destroy',
            'POST user-detail'=>'user-detail'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'wx-template',
        'pluralize' => false,
        'extraPatterns' => [
            'GET  index'=>'index',
            'POST create'=>'create',
            'PUT update'=>'update',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'enterprise',
        'pluralize' => false,
        'extraPatterns' => [
            'GET  index'=>'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'image-watermark',
        'pluralize' => false,
        'extraPatterns' => [
            'GET  index'=>'index',
            'POST  save-template'=>'save-template',
            'POST  delete-template'=>'delete-template',
            'GET  select-list'=>'select-list',
        ]
    ],


    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'configuration-management',
        'pluralize' => false,
        'extraPatterns' => [
            'POST  car-list'=>'car-list',
            'POST  test'=>'test',
            'POST  add-car'=>'add-car',
            'POST  delete-car'=>'delete-car',
            'POST  add-room'=>'add-room',
            'POST  room-list'=>'room-list',
            'POST  delete-room'=>'delete-room',
            'POST  ticket-declaration-list'=>'ticket-declaration-list',
            'POST  room-declaration-list'=>'room-declaration-list',
            'POST  project-type'=>'project-type',
            'POST  company-ticket-info'=>'company-ticket-info',
            'POST  room-distribution-list'=>'room-distribution-list',
            'POST  company-room-distribution-user'=>'company-room-distribution-user',
            'POST  company-room-declaration-user'=>'company-room-declaration-user',
            'POST  room-distribution'=>'room-distribution',
            'POST  free-car-list'=>'free-car-list',
            'POST  car-declaration-distribution'=>'car-declaration-distribution',
            'POST  company-car-use'=>'company-car-use',
            'POST  car-use-info'=>'car-use-info',
            'POST  car-info-list'=>'car-info-list',
            'POST  company-list'=>'company-list',
            'POST  car-distribution'=>'car-distribution',
            'POST  import-room-declaration-user'=>'import-room-declaration-user',
            'POST  cancel-use-car'=>'cancel-use-car',
            'POST  room-declaration-static'=>'room-declaration-static',
            'POST  room-declaration-info'=>'room-declaration-info',
            'POST  export-ticket-declaration-list'=>'export-ticket-declaration-list',
            'GET  export-ticket-declaration-list'=>'export-ticket-declaration-list',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'guarantee-operate',
        'pluralize' => false,
        'extraPatterns' => [
            'POST  add-ticket-declaration'=>'add-ticket-declaration',
            'POST  ticket-declaration-list'=>'ticket-declaration-list',
            'POST  edit-ticket-declaration'=>'edit-ticket-declaration',
            'POST  delete-ticket-declaration'=>'delete-ticket-declaration',
            'POST  user-list'=>'user-list',
            'POST  edit-user'=>'edit-user',
            'POST  add-car-declaration'=>'add-car-declaration',
            'POST  delete-car-declaration'=>'delete-car-declaration',
            'POST  car-declaration-list'=>'car-declaration-list',
            'POST  server-list'=>'server-list',
            'POST  add-room-declaration'=>'add-room-declaration',
            'POST  delete-room-declaration'=>'delete-room-declaration',
            'POST  company-room-declaration-list'=>'company-room-declaration-list',
            'POST  get-company-info'=>'get-company-info',
            'POST  submit-ticket-declaration'=>'submit-ticket-declaration',
            'POST  room-declaration-info'=>'room-declaration-info',
            'POST  car-declaration-info'=>'car-declaration-info',
            'POST  wechat-login'=>'wechat-login',
            'POST  delete-user'=>'delete-user',
            'POST  import-user'=>'import-user',
            'POST  import-ticket-declaration'=>'import-ticket-declaration',
            'POST  accept-ticket-declaration'=>'accept-ticket-declaration',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'service-management',
        'pluralize' => false,
        'extraPatterns' => [
            'GET  index'=>'index',
            'POST  project-type'=>'project-type',
            'POST  server-list'=>'server-list',
            'POST  create-server'=>'create-server',
            'POST  delete-server'=>'delete-server',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'enterprise-system-config',
        'pluralize' => false,
        'extraPatterns' => [
            'GET  index'=>'index',
            'PUT update'=> 'update',
            'POST edit'=>'edit'
        ]
    ],
];