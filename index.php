<?php

    define("TOKEN", "hope");
    $obj = new Weixin();

    if(!isset($_GET['echostr'])) {

        //$obj->receive();

    } else {
        $obj->checkSignature();
    }


    class Weixin {

        public function checkSignature() {

            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];
            $token = TOKEN;

            $tmpArr = array($token, $timestamp, $nonce);
            sort($tmpArr, SORT_STRING);
            $tmpStr = implode($tmpArr);
            $tmpStr = sha1($tmpStr);

            if($tmpStr == $signature) {
                return $_GET['echostr'];
            } else {
                return false;
            }
        }
    }
