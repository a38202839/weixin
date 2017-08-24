<?php
   class Wechat{
      protected function access_token(){
          $appid = 'wxb6b5b4585ec1d4f9';
          $secret = 'a3ee87f23ef67cd9873c6490e291a5fe';
          $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
          $ch = curl_init();
          //第二步：设置curl
          curl_setopt($ch,CURLOPT_URL,$url);

          curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
          curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
          //已文档流的形式返回数据
          curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//          if(!empty($data)){
//              curl_setopt($ch,CURLOPT_POST,1);
//              curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
//          }
          //第三步：执行curl
          $output = curl_exec($ch);

          //第四步：关不curl资源
          curl_close($ch);
          $json = json_decode($output);
          $access_token = $json->access_token;
          return $access_token;
      }
      protected function http_request($url,$data=null){
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
   }
