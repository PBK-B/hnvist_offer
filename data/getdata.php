<?php

$userList = array();
$databash = array();
$webdata = get_url_phone("http://zs.hnvist.cn/column/pc/details/1600850944608.shtml");
$webdata = str_replace('</tr>', '</tr>' . PHP_EOL, $webdata);
$preg = '#<tr>(.*)</tr>#';
preg_match_all($preg, $webdata, $list);
print_r(count($list[1]));
foreach ($list[1] as $value) {
    $userinfo = array();
    $value = str_replace('</td>', '</td>' . PHP_EOL, $value);
    $preg = '#<td .*>(.*)</td>#';
    preg_match_all($preg, $value, $infos);
    $userinfo = ['offer_id' => $infos[1][0], 'user_name' => $infos[1][1], 'user_sex' => $infos[1][2], 'user_sex' => $infos[1][2], 'identity_end' => $infos[1][3], 'courier_id' => $infos[1][4]];
    array_push($userList, $userinfo);
    // print_r(json_encode($userinfo, JSON_UNESCAPED_UNICODE) . PHP_EOL);
}

set_file_text("./data.json", json_encode($userList, JSON_UNESCAPED_UNICODE));
print_r(PHP_EOL);

set_file_text('./webdata.log', $webdata);

function get_url_phone($url, $post = 0, $referer = 0, $cookie = 0, $header = 0, $ua = 0, $nobaody = 0, $ifjosn = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $httpheader[] = 'Accept: */*';
    $httpheader[] = 'Accept-Encoding: gzip,deflate,sdch';
    $httpheader[] = 'Accept-Language: zh-CN,zh;q=0.8';
    $httpheader[] = 'Connection: close';
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    if ($post) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        if ($ifjosn == 1) {
            $httpheader[] = 'Content-Type: application/json; charset=UTF-8';
        } else {
            $httpheader[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
        }
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
    if ($header) {
        curl_setopt($ch, CURLOPT_HEADER, true);
    }
    if ($cookie) {
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }
    if ($referer) {
        if ($referer == 1) {
            curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
        } else {
            curl_setopt($ch, CURLOPT_REFERER, $referer);
        }
    }
    if ($ua) {
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    } else {
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 7.0; MI 10s Plus Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/64.0.3282.137 Mobile Safari/537.36');
    }
    if ($nobaody) {
        curl_setopt($ch, CURLOPT_NOBODY, 1);
    }
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $ret = curl_exec($ch);
    curl_close($ch);

    return $ret;
}

function set_file_text($file, $text)
{
    $myfile = fopen($file, 'w') or die('Unable to open file!');
    fwrite($myfile, $text);
    fclose($myfile);
}
