<?php
/**
 * Created by PhpStorm.
 * User: meteor
 * Date: 16/5/17
 * Time: 上午10:26
 */


    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'http://news.baidu.com');
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_exec($ch);

    curl_close($ch);