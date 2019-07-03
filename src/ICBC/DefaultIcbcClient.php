<?php

namespace Fatryst\ICBCPay\src;

use Exception;

class DefaultIcbcClient
{
    public $appId;
    public $privateKey;
    public $signType;
    public $charset;
    public $format;
    public $icbcPulicKey;
    public $encryptKey;
    public $encryptType;
    public $ca;
    public $password;

    function __construct($appId, $privateKey, $signType, $charset, $format, $icbcPulicKey, $encryptKey, $encryptType, $ca, $password)
    {
        $this->appId = $appId;
        $this->privateKey = $privateKey;
        if ($signType == null || $signType == "") {
            $this->signType = IcbcConstants::$SIGN_TYPE_RSA;
        } else {
            $this->signType = $signType;
        }

        if ($charset == null || $charset == "") {
            $this->charset = IcbcConstants::$CHARSET_UTF8;
        } else {
            $this->charset = $charset;
        }

        if ($format == null || $format == "") {
            $this->format = IcbcConstants::$FORMAT_JSON;
        } else {
            $this->format = $format;
        }

        $this->icbcPulicKey = $icbcPulicKey;
        $this->encryptKey = $encryptKey;
        $this->encryptType = $encryptType;
        $this->password = $password;
        // 去除签名数据及证书数据中的空格
        if ($ca != null && $ca != "") {
            $ca = preg_replace("/\s*|\t/", "", $ca);
        }
        $this->ca = $ca;
    }

    function execute($request, $msgId, $appAuthToken)
    {
        $params = $this->prepareParams($request, $msgId, $appAuthToken);

        //发送请求
        //接收响应
        if ($request["method"] == "GET") {
            $respStr = WebUtils::doGet($request["serviceUrl"], $params, $this->charset);
        } elseif ($request["method"] == "POST") {
            $respStr = WebUtils::doPost($request["serviceUrl"], $params, $this->charset);
        } else {
            throw new Exception("Only support GET or POST http method!");
        }
        $respBizContentArr = json_decode($respStr, true)[IcbcConstants::$RESPONSE_BIZ_CONTENT];
        dd($respBizContentArr);

        if ($respBizContentArr['return_code'] != 0) {
            throw new Exception($respBizContentArr['return_msg']);
        }
        //增加了对传回报文中含有中文字符以及反斜杠的转换(json_encode(str,JSON_UNESCAPED_UNICODE(240)+JSON_UNESCAPED_SLASHES(80)=320))
        $respBizContentStr = json_encode($respBizContentArr, 320);
        $sign = json_decode($respStr, true)[IcbcConstants::$SIGN];
        //解析响应
        $passed = IcbcSignature::verify($respBizContentStr, $this->signType, $this->icbcPulicKey, $this->charset, $sign, $this->password);

        if (!$passed) {
            throw new Exception("icbc sign verify not passed!");
        }
        if ($request["isNeedEncrypt"]) {
            $respBizContentStr = IcbcEncrypt::decryptContent(substr($respBizContentStr, 1, strlen($respBizContentStr) - 2), $this->encryptType, $this->encryptKey, $this->charset);
        }
        //返回解析结果
        return $respBizContentStr;
    }


    function prepareParams($request, $msgId, $appAuthToken)
    {
        $bizContentStr = json_encode($request["biz_content"]);

        $path = parse_url($request["serviceUrl"], PHP_URL_PATH);

        $params = array();

        if (isset($request["extraParams"]) && $request["extraParams"] != null) {
            $params = array_merge($params, $request["extraParams"]);
        }

        $params[IcbcConstants::$APP_ID] = $this->appId;
        $params[IcbcConstants::$SIGN_TYPE] = $this->signType;
        $params[IcbcConstants::$CHARSET] = $this->charset;
        $params[IcbcConstants::$FORMAT] = $this->format;
        $params[IcbcConstants::$CA] = $this->ca;
        $params[IcbcConstants::$APP_AUTH_TOKEN] = $appAuthToken;
        $params[IcbcConstants::$MSG_ID] = $msgId;

        date_default_timezone_set(IcbcConstants::$DATE_TIMEZONE);
        $params[IcbcConstants::$TIMESTAMP] = date(IcbcConstants::$DATE_TIME_FORMAT);

        if ($request["isNeedEncrypt"]) {
            if ($bizContentStr != null) {
                $params[IcbcConstants::$ENCRYPT_TYPE] = $this->encryptType;
                $params[IcbcConstants::$BIZ_CONTENT_KEY] = IcbcEncrypt::encryptContent($bizContentStr, $this->encryptType, $this->encryptKey, $this->charset);
            }
        } else {
            $params[IcbcConstants::$BIZ_CONTENT_KEY] = $bizContentStr;
        }

        $strToSign = WebUtils::buildOrderedSignStr($path, $params);

        $signedStr = IcbcSignature::sign($strToSign, $this->signType, $this->privateKey, $this->charset, $this->password);

        $params[IcbcConstants::$SIGN] = $signedStr;
        return $params;

    }

    function JSONTRANSLATE($array)
    {
        foreach ($array as $key => $value) {
            $array[$key] = urlencode($value);
        }
        return json_encode($array);
    }

    function encodeOperations($array)
    {

        foreach ((array)$array as $key => $value) {

            if (is_array($value)) {

                $this->encodeOperations($array[$key]);

            } else {

                $array[$key] = urlencode(mb_convert_encoding($value, 'UTF-8', 'GBK'));

            }

        }

        return $array;
    }
}
