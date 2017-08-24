<?php
function http_request($url,$data=null){
    //第一步：创建curl
    $ch = curl_init();
    //第二步：设置curl
    curl_setopt($ch,CURLOPT_URL,$url);

    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    //已文档流的形式返回数据
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    if(!empty($data)){
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    }
    //第三步：执行curl
    $output = curl_exec($ch);
    //第四步：关不curl资源
    curl_close($ch);
    return $output;
}