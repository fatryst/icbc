<?php

namespace Fatryst\ICBCPay\src;

use Exception;

class WebUtils
{
    private static $version = "v2_20170324";

    public static function doGet($url, $params, $charset)
    {
        $headers = array();
        $headers[IcbcConstants::$VERSION_HEADER_NAME] = self::$version;
        $getUrl = self::buildGetUrl($url, $params, $charset);


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $getUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 8000);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 30000);


        $response = curl_exec($ch);
        $resinfo = curl_getinfo($ch);
        curl_close($ch);

        if ($resinfo["http_code"] != 200) {
            throw new Exception("response status code is not valid. status code: " . $resinfo["http_code"]);
        }

        return $response;
    }

    public static function doPost($url, $params, $charset)
    {
        $headers = array();
        $headers[] = 'Expect:';
        $headers[IcbcConstants::$VERSION_HEADER_NAME] = self::$version;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 8000);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 30000);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);


        $response = curl_exec($ch);
        $resinfo = curl_getinfo($ch);
        curl_close($ch);
        if ($resinfo["http_code"] != 200) {
            throw new Exception("response status code is not valid. status code: " . $resinfo["http_code"]);
        }
        return $response;
    }

    public static function buildGetUrl($strUrl, $params, $charset)
    {
        if ($params == null || count($params) == 0) {
            return $strUrl;
        }
        $buildUrlParams = http_build_query($params);
        if (strrpos($strUrl, '?', 0) != (strlen($strUrl) + 1)) { //最后是否以？结尾
            return $strUrl . '?' . $buildUrlParams;
        }
        return $strUrl . $buildUrlParams;
    }

    public static function buildOrderedSignStr($path, $params)
    {
        $isSorted = ksort($params);
        $comSignStr = $path . '?';

        $hasParam = false;
        foreach ($params as $key => $value) {
            if (null == $key || "" == $key || null == $value || "" == $value) {

            } else {
                if ($hasParam) {
                    $comSignStr = $comSignStr . '&';
                } else {
                    $hasParam = true;
                }
                $comSignStr = $comSignStr . $key . '=' . $value;
            }
        }

        return $comSignStr;
    }

    public static function buildForm($url, $params)
    {
        $buildedFields = self::buildHiddenFields($params);
        return '<form name="auto_submit_form" method="post" action="' . $url . '">' . "\n" . $buildedFields . '<input type="submit" value="立刻提交" style="display:none" >' . "\n" . '</form>' . "\n" . '<script>document.forms[0].submit();</script>';
    }

    public static function buildHiddenFields($params)
    {
        if ($params == null || count($params) == 0) {
            return '';
        }

        $result = '';
        foreach ($params as $key => $value) {
            if ($key == null || $value == null) {
                continue;
            }
            $buildfield = self::buildHiddenField($key, $value);
            $result = $result . $buildfield;
        }
        return $result;
    }

    public static function buildHiddenField($key, $value)
    {
        return '<input type="hidden" name="' . $key . '" value="' . preg_replace('/"/', '&quot;', $value) . '">' . "\n";
    }
}
