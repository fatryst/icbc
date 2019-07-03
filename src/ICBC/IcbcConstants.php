<?php

namespace Fatryst\ICBCPay\src;

class IcbcConstants
{
    public static $SIGN_TYPE = "sign_type";

    public static $SIGN_TYPE_RSA = "RSA";

    public static $SIGN_TYPE_RSA2 = "RSA2";

    public static $SIGN_TYPE_SM2 = "SM2";

    public static $SIGN_TYPE_CA = "CA";

    public static $SIGN_SHA1RSA_ALGORITHMS = "SHA1WithRSA";

    public static $SIGN_SHA256RSA_ALGORITHMS = "SHA256WithRSA";

    public static $ENCRYPT_TYPE_AES = "AES";

    public static $APP_ID = "app_id";

    public static $FORMAT = "format";

    public static $TIMESTAMP = "timestamp";

    public static $SIGN = "sign";

    public static $APP_AUTH_TOKEN = "app_auth_token";

    public static $CHARSET = "charset";

    public static $NOTIFY_URL = "notify_url";

    public static $RETURN_URL = "return_url";

    public static $ENCRYPT_TYPE = "encrypt_type";

    // -----===-------///

    public static $BIZ_CONTENT_KEY = "biz_content";

    /** 默认时间格式 **/
    public static $DATE_TIME_FORMAT = "Y-m-d H:i:s";//java版"yyyy-MM-dd HH:mm:ss"

    /** Date默认时区 **/
    //public static $DATE_TIMEZONE = "Etc/GMT+8";//java版GMT+8
    public static $DATE_TIMEZONE = "Asia/Shanghai";//东八区

    /** UTF-8字符集 **/
    public static $CHARSET_UTF8 = "UTF-8";

    /** GBK字符集 **/
    public static $CHARSET_GBK = "GBK";

    /** JSON 应格式 */
    public static $FORMAT_JSON = "json";

    /** XML 应格式 */
    public static $FORMAT_XML = "xml";

    public static $CA = "ca";

    public static $PASSWORD = "password";

    public static $RESPONSE_BIZ_CONTENT = "response_biz_content";

    /** 消息唯一编号 **/
    public static $MSG_ID = "msg_id";

    /** sdk版本号在header中的key */
    public static $VERSION_HEADER_NAME = "APIGW-VERSION";

}

