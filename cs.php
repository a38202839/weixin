<?php
//$redis = new Redis();
//
//$redis->connect('127.0.0.1',6379);
//$redis->auth('root');
//$redis->set('num',3);
////$redis->incr('num');
//$num = $redis->get('num');
//echo $num;
function http_request($url,$data=null){
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
    curl_close($ch);
    return $output;
}

        $keyword = urlencode('成都');
        $key = '7aea683f76f3dcee6064a626d9ed6f7f';
        $url = "http://v.juhe.cn/weather/index?format=2&cityname={$keyword}&key={$key}";
        $str = http_request($url);
        $json = json_decode($str);
        $city = $json->result->today->city;
        $weather = $json->result->today->weather;
        $temp = $json->result->today->temperature;
        $week = $json->result->today->week;
        $row = $json->result->today;
        $str1 ='';
        echo "<pre>";
        foreach($row as $k=>$v){
            if($k !='weather_id'){
                $str1 .= $v.',';
            }
        }
        var_dump($str1);

