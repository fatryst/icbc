<?php

namespace Fatryst\ICBCPay\src;

class IcbcCa
{
    public static function sign($content, $privatekey, $password)
    {

        if (!extension_loaded('infosec')) {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                dl('php_infosec.dll');
            } else {
                dl('infosec.so');
            }
        } else {
            //echo "loaded infosec module success <br/>";
        }


        $plaint = $content;
        if (strlen($plaint) <= 0) {
            echo "WARNING : no source data input";
            throw new Exception("no source data input");
        }
        /*		$keyfile=$keyfilepath;
                if(strlen($keyfile) <= 0)
                {
                    echo "WARNING : no key data input<br/>";
                    exit();
                }*/
        //read private key from file
        //$fd = "pri-CEA.key";
        /*		$fp = fopen($keyfile,"rb");
                if($fp == NULL)
                {
                    echo "open file error<br/>";
                    exit();
                }

                fseek($fp,0,SEEK_END);
                $filelen=ftell($fp);
                fseek($fp,0,SEEK_SET);
                $contents = fread($fp,$filelen);
                fclose($fp);*/
        $contents = base64_decode($privatekey);
        $key = substr($contents, 2);
        //echo "key:",base64_encode($key),"\n";

        $pass = $password;
        if (strlen($pass) <= 0) {
            echo "WARNING : no key password input";
            throw new Exception("no key password input");
        } else {

            $signature = sign($plaint, $key, $pass);
            $code = current($signature);
            $len = next($signature);
            $signcode = base64enc($code);
            return current($signcode);
            /*			echo "signature : ",current($signcode),"\n";
                    echo "signature len: ",$len,"\n";*/
        }

    }


    public static function verify($content, $publicKey, $password)
    {
        return false;
    }
}

//echo IcbcCa::sign("nihao",'BKlZsKQJ0eG9UDtOJxl2rl8uzVClQzBUsaq9BBB7KlaBUn7NplThEKAbmGFUmcqIqvzU6shLqJUb58iZGUEiRx4SyjL8cKL7lTy1n6nVA6aMwJcfPIn5uZ5ynNW8n9m/5piy/lSkWpTAwebeqeSKYiXzeWtsuFmptSZkOjtsymsjgTfY60AkCBw1rGtJ08NXvN50u6QaFdeggX+++MDCK5W7ZC2SOvtkatkIKlrBlj0Gnri7O+Y7Ul44ysBDB4mhbZJuqffzBAJyDk0r3Q9WTaOg4aBSlyyp2lwfxiJ0SvZJJMS3jLdlWXTepVSE/IgVIHXlNRtkccr9Y3eY4XN6G6qVRViSTqmckYnZZFZWesVqihvP2U32aYsRWR4TIq/im77aFKCWMgOrOCVttV981AKHIn1kC1r6O/x9k2GAuadF5bGcb0tjhEJV+ijoFhI0Syi7D9ZS5F/MVCq0B/8MAC+pFZOxXtfAJDtz/M0x1t2H82gk9xATPgNq9CVPzNtNnQOvE1HWmn2ZDSCmR7IkSA8EEN5pqz8QYRvs3O8tFechYztifM1+TBs5KciN4FjZZhiE3+vbBSFboxte40yaLgZE0cm3ZDSHhaflDb5f3C162kF2Nbi9fG4Se3DKHyH1HRZfwDpWmB5MUjBb5j+GNxp+7KtCAinxFEthYeMmkIKMwXolZnlxdrO0BdySJSekrTgIbihtjmp9racWhiCxVX8ThaTGCEP1+6fz6bUmk2IZ2yaYo93KkZxcXmkO0sHFKZZ8Y+hiBk12EOQO8mKfFhYKIJ/s4SViqYOe+kL/T49enAmaue2j+5UDRUSN0xR3oOzhPb9o/OfXCPTdxCtyAQX36oTPnRsouMiy3PW33iWcKPx7gD/fTEct5Qz/t6FBx5orz7EnG1TEEbTp+GzvZm0h4DPQ04jEjHJcb94r0EbdsOZrItyv1+krWtAvpS3IiHZkuTyRe7awrgaZRWWNohynpCjAIDhrJ/pdYzTKhpS/lJTkMQxsD3lLaNh5MqnQYwkdn53Geb6zwo6VQRYVFlyw9zmPptMHleauQtJjCx5pYgaCRt176BkxZnQTiF3BFKNsEEQhs3b/AKY4BqCszwXaj28BprZ9i/rpx9l9GgdVA0vVFgbXmceXIMTWlSdbI9wIdQ5pPtBiEUpnNF2vc6mB3VwQ2GY78NztzRlJRrPA2xgvYu34iGtnEjiyxQt+2C6gIq9xsc9rMWfyQRpcm0lQ0xkZ0R9xC8QtaoSmcbLs1XtZoDiPCmIL44NLMt7t3vM+jD8tId/0WYqJge/vBsVuKXOwa4KaTVUHkBfWfIwIaKz19eYq/zi/2hDDxlbLBz92wtDg/i/Uoe3jH/HCJ2hXmH+jaUQD/Z8+GKVK476+6Xe6K7VAKr7/qc9RIljby+/fm4ghwBMAkLf9IFWoOU+ZBGdN3HLl4x8OHIz2QnjG5NRsKebzYg39kYVX8/50z9tHVxTvJYVgJ5n+IbrFnHgKw5GdCtHjuWXRAkS2Nq/r1N9t5jRtuyt0kVslGk0nMES7hVXxXGqKm2cHJzjd3/npbONYGxK4TxyPccDs8MRfYEuuQpgoL/IMdA==',"12345678");
