<?php

    define("TOKEN", "hope");
    $obj = new Weixin();


    if(!isset($_GET['echostr'])) {

        $obj->receive();

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

                echo $_GET['echostr'];

            } else {

                return false;
            }
        }

        public function receive() {
            $obj = file_get_contents("php://input");
            $postSql = simplexml_load_string($obj, 'SimpleXMLElement', LIBXML_NOCDATA);

            $this->logger("接收: \n".$obj);

            if(!empty($postSql)) {

                switch(trim($postSql->MegType)) {
                    case "text" :
                        $result = $this->receiveText($postSql);

                        if(!empty($result)) {

                            echo $result;

                        } else {

                            $xml="<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";


                            echo $result=sprintf($xml,$postSql->FromUserName,$postSql->ToUserName,time(),$postSql->MsgType,"没有这条文本信息!");
                        }
                }
            }
        }

        private function receiveText($postSql){
            $content=trim($postSql->Content);


            if(strstr($content,"你好")){
                $xml="<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    </xml>";


            $result=sprintf($xml,$postSql->FromUserName,$postSql->ToUserName,time(),$postSql->MsgType,"hello");

            return $result;

            }

        }

        private function logger($content) {

            $logSize = 100000;
            $log = "log.txt";

            if(file_exists($log) && filesize($log) > $logSize) {
                unlink($log);
            }

            file_put_contents($log, date('H:i:s')." ".$content."\n", FILE_APPEND);
        }
    }
