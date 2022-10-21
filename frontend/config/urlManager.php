<?php
return [
    'class' => 'yii\web\UrlManager',
    'hostInfo' => $params['frontendHostInfo'],
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'cache' => false,
    'rules' => [
        '' => 'site/index',
        '<_a:contact>' => 'contact/<_a>',
        'auth/signup' => 'auth/signup/signup',
        'auth/<_a:login|logout>' => 'auth/auth/<_a>',
        'auth/signup/<_a:[\w-]+>' => 'auth/signup/<_a>',
        'auth/reset' => 'auth/reset/request',

        ['pattern' => 'yandex-market', 'route' => 'market/index', 'suffix' => '.xml'],

        ['pattern' => 'sitemap', 'route' => 'sitemap/index', 'suffix' => '.xml'],
        ['pattern' => 'sitemap-<target:[a-z-]+>-<start:\d+>', 'route' => 'sitemap/<target>', 'suffix' => '.xml'],
        ['pattern' => 'sitemap-<target:[a-z-]+>', 'route' => 'sitemap/<target>', 'suffix' => '.xml'],

        'cabinet' => 'cabinet/default/cabinet',
        'wishlist' => 'cabinet/wishlist/wishlist',
        'cabinet/<_c:[\w\-]+>' => 'cabinet/<_c>/cabinet',
        'cabinet/<_c:[\w\-]+>/<id:\d+>' => 'cabinet/<_c>/view',
        'cabinet/<_c:[\w\-]+>/<_a:[\w-]+>' => 'cabinet/<_c>/<_a>',
        'cabinet/<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => 'cabinet/<_c>/<_a>',

        'catalog' => 'shop/catalog/catalog',
        ['class' => 'frontend\urls\CategoryUrlRule'],
        'catalog/<id:\d+>' => 'shop/catalog/product',

        'cart' => 'shop/cart/cart',

        'checkout' => 'shop/checkout/checkout',

        'blog' => 'blog/post/blog',
        'blog/tag/<slug:[\w\-]+>' => 'blog/post/tag',
        'blog/<id:\d+>' => 'blog/post/post',
        'blog/<id:\d+>/comment' => 'blog/post/comment',
        'blog/<slug:[\w\-]+>' => 'blog/post/category',

        ['class' => 'frontend\urls\PageUrlRule'],

        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
    ],
];