<?php
return [
    'name'  => '春岚药业',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'timeZone' => 'Asia/Shanghai',
    'layout'=>'layout',
    'charset' => 'utf-8',
    'language' => 'zh-CN',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'wechat' => [
            'appid'=>'wx1199e133eb72505f',
            'appsecret'=>'57d1e7a2e211e6b80de6e1ad99d09427',
            'class' => 'common\components\Wechat'
        ]
    ],
];
