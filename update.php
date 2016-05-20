<?php
/**
 * Created by PhpStorm.
 * User: meteor
 * Date: 16/5/17
 * Time: 上午10:26
 */


    function http_curl($url, $data = null) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_HEADER, 0);

        if(!empty($data)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        //禁止curl资源直接输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


        $opt = curl_exec($ch);

        curl_close($ch);

        return $opt;
    }

    function get_token() {

        $appid = "wxdb5a0ddedad0093d";
        $secret = "d4624c36b6795d1d99dcf0547af5443d";
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";

        $json = http_curl($url);

        $result = json_decode($json);

        return $result->access_token;

    }

    $token = get_token();

    $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$token}";

    $data=<<<php
        {
         "button":[
         {	
              "type":"click",
              "name":"今日歌曲",
              "key":"V1001_TODAY_MUSIC"
          },
          {
               "name":"菜单",
               "sub_button":[
               {	
                   "type":"view",
                   "name":"搜索",
                   "url":"http://www.soso.com/"
                },
                {
                   "type":"view",
                   "name":"视频",
                   "url":"http://v.qq.com/"
                },
                {
                   "type":"click",
                   "name":"赞一下我们",
                   "key":"V1001_GOOD"
                }]
           }]
     }
php;

    $result=http_curl($url,$data);

    var_dump($result);
?>

