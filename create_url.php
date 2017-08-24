<?php
include 'get_token.php';
$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
$appid='wxb6b5b4585ec1d4f9';
$secret = 'a3ee87f23ef67cd9873c6490e291a5fe';
$contentStr = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxb6b5b4585ec1d4f9&redirect_uri=http://www.youhaerma.top/userinfo.php&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
//$contentStr = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxb6b5b4585ec1d4f9&redirect_uri=http://www.youhaerma.top/userinfo.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
$contentStr = urlencode($contentStr);
$contentStr_arr = array('content'=>$contentStr);
$reply_arr = array('touser'=>"oFN_q1EMIDneRfxxzoy09toXZXdI",'msgtype'=>'text','text'=>$contentStr_arr);
$data = json_encode($reply_arr);
$data = urldecode($data);
http_request($url,$data);


