<?php
return [
    'class' => 'yii\web\UrlManager',
    'hostInfo' => $params['backendHostInfo'],
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '' => 'site/index',
        '<_a:login|logout>' => 'site/<_a>',
        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',

        'shop' => 'shop/default/cabinet',
        'shop/<_c:[\w\-]+>' => 'shop/<_c>/index',
        'shop/<_c:[\w\-]+>/<id:\d+>' => 'shop/<_c>/view',
        'shop/<_c:[\w\-]+>/<_a:[\w-]+>' => 'shop/<_c>/<_a>',
        'shop/<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => 'shop/<_c>/<_a>',
    ],
];