<?php
/**
 * Created by PhpStorm.
 * User: meteor
 * Date: 16/5/17
 * Time: 上午10:26
 */


    function http_curl($url) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        //curl_setopt($ch, CURLOPT_HEADER, 0);

        //禁止curl资源直接输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $opt = curl_exec($ch);

        curl_close($ch);

        return $opt;

    }

    function get_token() {

        $appid = "wxdb5a0ddedad0093d";
        $secret= "d4624c36b6795d1d99dcf0547af5443d";

        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";

        $json = http_curl($url);

        $result = json_encode($json);

        return $result;
    }

    $token = get_token();
    var_dump($token->access_token);

    $type = 'image';

    $url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token={$token}&type={$type}";