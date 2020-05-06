<?php
return [
    'official' => [
        'appId' => env('WEIXIN_OFFICIAL_KEY',''),
        'appSecret' => env('WEIXIN_OFFICIAL_SECRET',''),
        'token' => env('WEIXIN_OFFICIAL_TOKEN',''),
        'aesKey' => env('WEIXIN_OFFICIAL_AES_KEY',''),
        'redirectUri' => env('WEIXIN_OFFICIAL_REDIRECT_URI','')
    ],
    'miniapp' => [
        'appId' => env('WEIXIN_MINIAPP_APPID',''),
        'appSecret' => env('WEIXIN_MINIAPP_SECRET',''),
    ],
    'pay' => [
        'appId' => env('WEIXIN_PAYMENT_APPID',''),
        'mchId' => env('WEIXIN_PAYMENT_MCHID',''),
        'isvAppId' => env('WEIXIN_PAYMENT_ISV_APPID',''),
        'isvMchId' => env('WEIXIN_PAYMENT_ISV_MCHID',''),
        'isvKey' => env('WEIXIN_PAYMENT_ISV_KEY',''),
        'isvCertPath' => env('WEIXIN_PAYMENT_ISV_CERT_PATH',''),
        'isvKeyPath' => env('WEIXIN_PAYMENT_ISV_KEY_PATH',''),
        'notifyUrl' => env('WEIXIN_PAYMENT_NOTIFY_URL',''),
        'spbillCreateIp' =>  env('WEIXIN_PAYMENT_SPBILL_CREATE_IP','')
    ]
];
