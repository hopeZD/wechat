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

                switch (trim($postSql->MsgType)) {

                    case "text" :
                        $result = $this->receiveText($postSql);


                        if (!empty($result)) {
                            echo $result;

                        } else {

                            $xml = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";

                            echo $result = sprintf($xml, $postSql->FromUserName, $postSql->ToUserName, time(), $postSql->MsgType, "没有这条文本消息");
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

            } else if(strstr($content, "单图文"))  {

                $result = $this->receiveImage($postSql);

            } else if (strstr($content, "多图文")) {

                $result = $this->receiveImages($postSql);

            } else if (strstr($content, "图片")) {

                $result = $this->receiveMedia($postSql);
            }


            $this->logger("发送图文消息: \n".$result);

            return $result;

        }

        //图片消息
        private function receiveMedia($postSql) {

            $xml = "<xml>
                 <ToUserName><![CDATA[%s]]></ToUserName>
                 <FromUserName><![CDATA[%s]]></FromUserName>
                 <CreateTime>%s</CreateTime>
                 <MsgType><![CDATA[%s]]></MsgType>
                 <PicUrl><![CDATA[%s]]></PicUrl>
                 <MediaId><![CDATA[%s]]></MediaId>
                 <MsgId>%s</MsgId>
                 </xml>";

            $result=sprintf($xml,$postSql->FromUserName,$postSql->ToUserName,time(),$postSql->MsgType, 'image', 
                'http://ww2.sinaimg.cn/large/005usUragw1edeexbv1euj30dw0aeq4k.jpg',);


        }

        //单图文消息
        private function receiveImage($postSql) {

            $xml = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <ArticleCount>1</ArticleCount>
                <Articles>
                <item>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[%s]]></Description>
                <PicUrl><![CDATA[%s]]></PicUrl>
                <Url><![CDATA[%s]]></Url>
                </item>
                </Articles>
                </xml>";

            $result=sprintf($xml, $postSql->FromUserName, $postSql->ToUserName,time(), "news", "hello妹子",
                   "疯狂牛仔裤,哈哈哈!!!", "http://ww2.sinaimg.cn/large/005usUragw1edeexbv1euj30dw0aeq4k.jpg",
                   "http://image.baidu.com/");

            return $result;

        }

        //多图文
        private function receiveImages($postSql) {

            $content = array();
            $content[] = array("Title" => "hello妹子",
                "Description" => "疯狂牛仔裤,哈哈哈!!!",
                "PicUrl" => "http://ww2.sinaimg.cn/large/005usUragw1edeexbv1euj30dw0aeq4k.jpg",
                "Url" => "http://image.baidu.com/");

            $content[] = array("Title" => "hello妹子",
                "Description" => "疯狂牛仔裤,哈哈哈!!!",
                "PicUrl" => "http://ww2.sinaimg.cn/large/005usUragw1edeexbv1euj30dw0aeq4k.jpg",
                "Url" => "http://image.baidu.com/");

            $content[] = array("Title" => "hello妹子",
                "Description" => "疯狂牛仔裤,哈哈哈!!!",
                "PicUrl" => "http://ww2.sinaimg.cn/large/005usUragw1edeexbv1euj30dw0aeq4k.jpg",
                "Url" => "http://image.baidu.com/");

            $str = "<item>
                <Title><![CDATA[%s]]></Title> 
                <Description><![CDATA[%s]]></Description>
                <PicUrl><![CDATA[%s]]></PicUrl>
                <Url><![CDATA[%s]]></Url>
                </item>";

            $news="";
            foreach ($content as $newArray) {
                $news.=sprintf($str,$newArray['Title'],$newArray['Description'],$newArray['PicUrl'],$newArray['Url']);
            }

            $xml = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <ArticleCount>%s</ArticleCount>
                <Articles>
                    $news
                </Articles>
                </xml> ";

            $result = sprintf($xml, $postSql->FromUserName, $postSql->ToUserName,time(), "news", count($content));

            return $result;
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
