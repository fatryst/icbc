<?php

namespace Fatryst\ICBCPay\src;

use Exception;

class RSA
{
    /**
     * @param $content
     * @param $privateKey
     * @param $algorithm
     * @return string
     * @throws Exception
     */
    public static function sign($content, $privateKey, $algorithm)
    {
        if (IcbcConstants::$SIGN_SHA1RSA_ALGORITHMS == $algorithm) {
            openssl_sign($content, $signature, "-----BEGIN PRIVATE KEY-----\n" . $privateKey . "\n-----END PRIVATE KEY-----",OPENSSL_ALGO_SHA1);
        } elseif (IcbcConstants::$SIGN_SHA256RSA_ALGORITHMS == $algorithm) {
            openssl_sign($content, $signature, "-----BEGIN PRIVATE KEY-----\n" . $privateKey . "\n-----END PRIVATE KEY-----", OPENSSL_ALGO_SHA256);
        } else {
            throw new Exception("Only support OPENSSL_ALGO_SHA1 or OPENSSL_ALGO_SHA256 algorithm signature!");
        }
        return base64_encode($signature);
    }

    /**
     * @param $content
     * @param $signature
     * @param $publicKey
     * @param $algorithm
     * @return int
     * @throws Exception
     */
    public static function verify($content, $signature, $publicKey, $algorithm)
    {
        if (IcbcConstants::$SIGN_SHA1RSA_ALGORITHMS == $algorithm) {
            return openssl_verify($content, base64_decode($signature), "-----BEGIN PUBLIC KEY-----\n" . $publicKey . "\n-----END PUBLIC KEY-----", OPENSSL_ALGO_SHA1);
        } elseif (IcbcConstants::$SIGN_SHA256RSA_ALGORITHMS == $algorithm) {
            return openssl_verify($content, base64_decode($signature), "-----BEGIN PUBLIC KEY-----\n" . $publicKey . "\n-----END PUBLIC KEY-----", OPENSSL_ALGO_SHA256);
        } else {
            throw new Exception("Only support OPENSSL_ALGO_SHA1 or OPENSSL_ALGO_SHA256 algorithm signature verify!");
        }
    }
}