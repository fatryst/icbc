<?php
/**
 * description:
 * datetime: 2019/7/2 11:27
 * author: fa
 * version: 1.0
 */


namespace Fatryst\ICBCPay;


use Faker\Provider\Uuid;
use Fatryst\ICBCPay\src\DefaultIcbcClient;
use Fatryst\ICBCPay\src\IcbcConstants;

class ICBCPay
{
    private $appId;
    private $privateKey;
    private $signType;
    private $charset;
    private $format;
    private $icbcPulicKey;
    private $encryptKey;
    private $encryptType;
    private $ca;
    private $password;
    private $client;

    function __construct($appId = null, $privateKey = null, $icbcPulicKey = null, $signType = null, $encryptKey = null, $encryptType = null, $charset = null, $format = null, $ca = null, $password = null)
    {
        $this->appId = $appId ?? config('icbc.appId', null);
        $this->privateKey = $privateKey ?? config('icbc.privateKey', null);
        $this->icbcPulicKey = $icbcPulicKey ?? config('icbc.icbcPulicKey', null);
        $this->encryptKey = $encryptKey ?? config('icbc.encryptKey', null);
        $this->encryptType = $icbcPulicKey ?? config('icbc.encryptType', null);
        $this->password = $icbcPulicKey ?? config('icbc.password', null);
        $this->signType = $icbcPulicKey ?? config('icbc.signType', IcbcConstants::$SIGN_TYPE_RSA2);
        $this->charset = $icbcPulicKey ?? config('icbc.charset', IcbcConstants::$CHARSET_UTF8);
        $this->format = $icbcPulicKey ?? config('icbc.format', IcbcConstants::$FORMAT_JSON);
        $this->ca = preg_replace("/\s*|\t/", "", $ca) ?? null;

        $this->ca = $ca;
        $this->password = $password;

        $this->client = new DefaultIcbcClient($this->appId, $this->privateKey, $this->signType, $this->charset, $this->format, $this->icbcPulicKey, $this->encryptKey, $this->encryptType, $this->ca, $this->password);

    }

    /**
     * 二维码生成
     * @param $mer_id
     * @param $store_code
     * @param $out_trade_no
     * @param $order_amt
     * @param $trade_date
     * @param $trade_time
     * @param $pay_expire
     * @param $tporder_create_ip
     * @param $notify_flag
     * @param null $attach
     * @param string $sp_flag
     * @param string $notify_url
     * @return mixed
     * @throws \Exception
     */
    public function generate($mer_id, $store_code, $out_trade_no, $order_amt, $trade_date, $trade_time, $pay_expire, $tporder_create_ip, $notify_flag, $attach = null, $sp_flag = '0', $notify_url = '127.0.0.1')
    {
        $request = array(
            "serviceUrl" => config('icbc.url.qrcode.generate', null),
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "mer_id" => $mer_id,
                "store_code" => $store_code,
                "out_trade_no" => $out_trade_no,
                "order_amt" => $order_amt,
                "trade_date" => $trade_date,
                "trade_time" => $trade_time,
                "pay_expire" => $pay_expire,
                "notify_flag" => $notify_flag,
                "tporder_create_ip" => $tporder_create_ip,

                "attach" => $attach,
                "sp_flag" => $sp_flag,
                "notify_url" => $notify_url,
            )
        );


        $msgId = Uuid::uuid();
        $resp = $this->client->execute($request, $msgId, '');
        return json_decode($resp, true);
    }

    /**
     * 二维码退款
     * @param $mer_id
     * @param $reject_no
     * @param $reject_amt
     * @param null $cust_id
     * @param null $out_trade_no
     * @param null $order_id
     * @param null $oper_id
     * @return mixed
     * @throws \Exception
     */
    public function reject($mer_id, $reject_no, $reject_amt, $cust_id = null, $out_trade_no = null, $order_id = null, $oper_id = null)
    {
        $request = array(
            "serviceUrl" => config('icbc.url.qrcode.reject', null),
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "mer_id" => $mer_id,
                "reject_no" => $reject_no,
                "reject_amt" => $reject_amt,
                "cust_id" => $cust_id,
                "out_trade_no" => $out_trade_no,    //该字段非必输项,out_trade_no和order_id选一项上送即可
                "order_id" => $order_id,            //该字段非必输项,out_trade_no和order_id选一项上送即可
                "oper_id" => $oper_id,
            )
        );
        $msgId = Uuid::uuid();
        $resp = $this->client->execute($request, $msgId, '');
        return json_decode($resp, true);
    }


    /**
     * 二维码查询
     * @param $mer_id
     * @param null $cust_id
     * @param null $out_trade_no
     * @param null $order_id
     * @return mixed
     * @throws \Exception
     */
    public function query($mer_id, $cust_id = null, $out_trade_no = null, $order_id = null)
    {
        $request = array(
            "serviceUrl" => config('icbc.url.qrcode.query', null),
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "mer_id" => $mer_id,
                "cust_id" => $cust_id,              //该字段非必输项
                "out_trade_no" => $out_trade_no,    //该字段非必输项,out_trade_no和order_id选一项上送即可
                "order_id" => $order_id,            //该字段非必输项,out_trade_no和order_id选一项上送即可
            )
        );
        $msgId = Uuid::uuid();
        $resp = $this->client->execute($request, $msgId, '');
        return json_decode($resp, true);
    }

    /**
     * 二维码被扫支付
     * @param $qr_code
     * @param $mer_id
     * @param $out_trade_no
     * @param $order_amt
     * @param $trade_date
     * @param $trade_time
     * @return mixed
     * @throws \Exception
     */
    public function pay($qr_code, $mer_id, $out_trade_no, $order_amt, $trade_date, $trade_time)
    {
        $request = array(
            "serviceUrl" => config('icbc.url.qrcode.pay', null),
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "qr_code" => $qr_code,
                "mer_id" => $mer_id,
                "out_trade_no" => $out_trade_no,
                "order_amt" => $order_amt,
                "trade_date" => $trade_date,
                "trade_time" => $trade_time
            )

        );
        $msgId = Uuid::uuid();
        $resp = $this->client->execute($request, $msgId, '');
        return json_decode($resp, true);
    }

    /**
     * 二维码冲正
     * @param $mer_id
     * @param $out_trade_no
     * @param null $cust_id
     * @param null $order_id
     * @param null $reject_no
     * @param null $reject_amt
     * @param null $oper_id
     * @return mixed
     * @throws \Exception
     */
    public function reverse($mer_id, $out_trade_no, $cust_id = null, $order_id = null, $reject_no = null, $reject_amt = null, $oper_id = null)
    {
        $request = array(
            "serviceUrl" => config('icbc.url.qrcode.reverse', null),
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "mer_id" => $mer_id,
                "out_trade_no" => $out_trade_no,
                "cust_id" => $cust_id,              //该字段非必输项
                "order_id" => $order_id,            //该字段非必输项
                "reject_no" => $reject_no,          //该字段非必输项
                "reject_amt" => $reject_amt,        //该字段非必输项
                "oper_id" => $oper_id               //该字段非必输项
            )

        );
        $msgId = Uuid::uuid();
        $resp = $this->client->execute($request, $msgId, '');
        return json_decode($resp, true);
    }

    /**
     * 二维码退款查询
     * @param $mer_id
     * @param $out_trade_no
     * @param $order_id
     * @param $reject_no
     * @param null $cust_id
     * @return mixed
     * @throws \Exception
     */
    public function rejectQuery($mer_id, $out_trade_no, $order_id, $reject_no, $cust_id = null)
    {
        $request = array(
            "serviceUrl" => config('icbc.url.qrcode.reject_query', null),
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "mer_id" => $mer_id,
                "out_trade_no" => $out_trade_no,
                "order_id" => $order_id,
                "reject_no" => $reject_no,
                "cust_id" => $cust_id
            )
        );
        $msgId = Uuid::uuid();
        $resp = $this->client->execute($request, $msgId, '');
        return json_decode($resp, true);
    }
}