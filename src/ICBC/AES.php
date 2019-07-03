<?php

namespace Fatryst\ICBCPay\src;

class AES
{
    /**
     * This was AES-128 / CBC / PKCS5Padding
     * return base64_encode string
     * @param string $plaintext
     * @param string $key
     * @return string
     * @author Terry
     */
    public static function AesEncrypt($plaintext, $key = null)
    {
        $plaintext = trim($plaintext);
        if ($plaintext == '') return '';
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);


        //PKCS5Padding
        $padding = $size - strlen($plaintext) % $size;
        // 添加Padding
        $plaintext .= str_repeat(chr($padding), $padding);


        $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        $key = self::substr($key, 0, mcrypt_enc_get_key_size($module));
        $iv = str_repeat("\0", $size);      //java里面的16个空数组对应的是\0.
        /* Intialize encryption */
        mcrypt_generic_init($module, $key, $iv);


        /* Encrypt data */
        $encrypted = mcrypt_generic($module, $plaintext);


        /* Terminate encryption handler */
        mcrypt_generic_deinit($module);
        mcrypt_module_close($module);
        return base64_encode($encrypted);
    }

    /**
     * Returns the length of the given string.
     * If available uses the multibyte string function mb_strlen.
     * @param string $string the string being measured for length
     * @return integer the length of the string
     */
    private static function strlen($string)
    {
        return extension_loaded('mbstring') ? mb_strlen($string, '8bit') : strlen($string);
    }


    /**
     * Returns the portion of string specified by the start and length parameters.
     * If available uses the multibyte string function mb_substr
     * @param string $string the input string. Must be one character or longer.
     * @param integer $start the starting position
     * @param integer $length the desired portion length
     * @return string the extracted part of string, or FALSE on failure or an empty string.
     */
    private static function substr($string, $start, $length)
    {
        return extension_loaded('mbstring') ? mb_substr($string, $start, $length, '8bit') : substr($string, $start, $length);
    }

    /**
     * This was AES-128 / CBC / PKCS5Padding
     * @param string $encrypted base64_encode encrypted string
     * @param string $key
     * @return string
     * @throws CException
     * @author Terry
     */
    public static function AesDecrypt($encrypted, $key = null)
    {
        if ($encrypted == '') return '';
        $ciphertext_dec = base64_decode($encrypted);
        $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        $key = self::substr($key, 0, mcrypt_enc_get_key_size($module));

        $iv = str_repeat("\0", 16);    //解密的初始化向量要和加密时一样。
        /* Initialize encryption module for decryption */
        mcrypt_generic_init($module, $key, $iv);


        /* Decrypt encrypted string */
        $decrypted = mdecrypt_generic($module, $ciphertext_dec);


        /* Terminate decryption handle and close module */
        mcrypt_generic_deinit($module);
        mcrypt_module_close($module);
        $a = rtrim($decrypted, "\0");


        return rtrim($decrypted, "\0");
    }
}

//echo AES::AesEncrypt("haha","mkeymkeymkeymkey");
?>