<?php
/**
 * description:
 * datetime: 2019/7/2 11:26
 * author: fa
 * version: 1.0
 */
return [

    // app id
    'appId' => '',

    //商户线下档案编号(特约商户12位，特约部门15位)
    'mer_id' => '',

    //e生活档案编号
    'store_code' => '',

    // APP应用私钥
    "privateKey" => "",

    // 网关公钥
    'icbcPulicKey' => "",

    // AES加密密钥，缺省为空''
    "encryptKey" => "",

    // 签名方式
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
