# ICBC
工行 商户收单服务 二维码扫码支付

###一、 安装  
1. 安装  
```composer require fatryst/icbc```

2. 发布配置文件`icbc.php`  
```php artisan vendor:publish --provider="Fatryst\ICBCPay\ICBCPayServiceProvider"```  
发布时 config/permission.php 配置文件 包含：
```angular2
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
```
在 Laravel 5.5 及以上版本，服务提供商将自动获得注册。 在旧版本的框架中，只需在 config/app.php 文件中添加服务提供者即可：
```angular2
'providers' => [
    // ...
    Fatryst\ICBCPay\ICBCPayServiceProvider::class,
];
```

###二、 使用
1. 根据需要修改配置文件，配置文件中的配置均为必填，不能为空
2. 初始化对象(不填参数时默认使用配置文件中的参数)  
`$icbc = new ICBCPay(params..)`   

3. 实现api
 - 二维码生成  
 `$icbc->generate(params..)`
 - 二维码被扫支付  
 `$icbc->pay(params..)`
 - 二维码查询  
 `$icbc->query(params..)`
 - 二维码退款  
 `$icbc->reject(params..)`
 - 二维码冲正  
 `$icbc->reverse(params..)`
 - 二维码退款查询  
 `$icbc->reject_query(params..)`
 
具体参数详情访问官网：  
[工行|开放平台](https://open.icbc.com.cn/icbc/apip/api_list.html?productId=P0039 "工行|开放平台")
 

