<?php
header('content-type:text/html;charset=utf-8');
include 'get_token.php';
$url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token={$access_token}";
$str = http_request($url);
$json = json_decode($str);
if($json->errmsg == 'ok'){
    echo '自定义菜单删除成功';
}