<?php
$ch = curl_init();
$url = 'http://www.baidu.com';
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
$ouput=curl_exec($ch);
if($ouput === false){
    echo curl_exec($ch);
}else{
    echo $ouput;
}
curl_close($ch);