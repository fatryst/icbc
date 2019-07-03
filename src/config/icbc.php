<?php
/**
 * description:
 * datetime: 2019/7/2 11:26
 * author: fa
 * version: 1.0
 */
return [

    'appId' => '',

    // APP应用私钥
    "privateKey" => "",

    // 网关公钥
    'icbcPulicKey' => "",


    // AES加密密钥，缺省为空''
    "encryptKey" => "",

    "signType" => "RSA",

    // v2
    'url' => [
        'qrcode' => [
            'generate' => 'https://gw.open.icbc.com.cn/api/qrcode/V2/generate',
            'pay' => 'https://gw.open.icbc.com.cn/api/qrcode/V2/pay',
            'query' => 'https://gw.open.icbc.com.cn/api/qrcode/V2/query',
            'reject' => 'https://gw.open.icbc.com.cn/api/qrcode/V2/reject',
            'reverse' => 'https://gw.open.icbc.com.cn/api/qrcode/V2/reverse',
            'reject_query' => 'https://gw.open.icbc.com.cn/api/qrcode/reject/query/V3',
        ]
    ]

];
