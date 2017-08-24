<?php
/**
  * wechat php test
  */
include 'weix1.php';
include 'Wechat.class.php';
//define your token
define("TOKEN", "weixin");

class wechatCallbackapiTest extends Wechat
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);//防止XXE攻击
                //对XML数据进行解析生成simplexml对象
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
              	//微信客户端的openid
                $fromUsername = $postObj->FromUserName;
                //微信公众平台
                $toUsername = $postObj->ToUserName;
                //微信客户端向公众平台发送的关键词
                $keyword = trim($postObj->Content);
                //时间戳
                $time = time();
                //定义文本消息模版
             global $tmp_arr;

            if($keyword == '多图文'){
                $msgType='news';
//                $pdo = new PDO("mysql:host=localhost;dbname=wechat",'root','root');
//                $sql = 'select * from wc_article limit 4';
//                $res = $pdo->query($sql);
//               var_dump($res);
                $link = mysql_connect('localhost','root','root');
                mysql_query('use wechat');
                mysql_query('set names utf8');
                $sql = "select * from wc_article limit 4";
                $res = mysql_query($sql);
                $num = 4;
                $str = '';
                while ($row = mysql_fetch_assoc($res)){
                    $str .= "<item>
                            <Title><![CDATA[{$row['title']}]]></Title>
                            <Description><![CDATA[{$row['description']}]]></Description>
                            <PicUrl><![CDATA[{$row['picurl']}]]></PicUrl>
                            <Url><![{$row['url']}]></Url>
                            </item>";
                }
                $resultStr = sprintf($tmp_arr['news'],$fromUsername,$toUsername,$time,$msgType,$num,$str);
                echo $resultStr;
                die;
            }

            $newcont = $postObj->Content;
                    switch($postObj->MsgType){
//                        case 'text':
//                            $msgType='text';
//                            $contentStr = $postObj->Content;
//                            $resultStr = sprintf($tmp_arr['text'],$fromUsername,$toUsername,$time,$msgType,$contentStr);
//                            echo $resultStr;
//                            break;
                case 'voice' || 'text':
                    $redis = new Redis();
                    $redis->connect('localhost', 6379);
                    $redis->auth('root');
                    $strus = $redis->get($fromUsername . 'key');
                    if($keyword == '天气' || $strus) {
                        if ($keyword == '天气') {
                            $contentStr = '请输入城市查询天气情况';
                            $redis->set($fromUsername . 'key', $fromUsername . '天气');
                            $redis->expire($fromUsername . 'key', 60);
                        } else {
                            $strus = $redis->get($fromUsername . 'key');
                            if ($strus == $fromUsername . '天气') {
                                $keyword = urlencode($keyword);
                                $key = '7aea683f76f3dcee6064a626d9ed6f7f';
                                $url = "http://v.juhe.cn/weather/index?format=2&cityname={$keyword}&key={$key}";
                                $str = $this->http_request($url);
                                $json = json_decode($str);
                                $row = $json->result->today;
                                
                                    $res1 = '';
                                    foreach ($row as $k => $v) {
                                        if ($k != 'weather_id') {
                                            $res1 .= $v . '^.^';
                                        }
                                    }
                                    $contentStr = "天气情况:{$res1}";
                                } else {
                                    $contentStr = '请输入关键词：天气';
                                }
                            }

                            $msgType = 'text';
                            $resultStr = sprintf($tmp_arr['text'], $fromUsername, $toUsername, $time, $msgType, $contentStr);
                            echo $resultStr;

                    }else {
                        $res = $postObj->Recognition;
                        $url = "http://www.tuling123.com/openapi/api";
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        $data = array(
                            'key' => 'e33363e46d2145e8b92a049cb6eb1092',
                            'info' => $res,
                            'userid' => '123456'
                        );
                        $data = json_encode($data);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type:application/json',
                            'Content-Length:' . strlen($data)
                        ));
                        $str = curl_exec($ch);
                        curl_close($ch);
                        $json = json_decode($str);
                        $msgType = 'text';
                        $contentStr = $json->text;
                        $resultStr = sprintf($tmp_arr['text'], $fromUsername, $toUsername, $time, $msgType, $contentStr);
                        echo $resultStr;
                    }
                    break;
                        case 'image':
                            $msgType='text';
                            $contentStr = '您发送的是图片消息！';
                            $resultStr = sprintf($tmp_arr['text'],$fromUsername,$toUsername,$time,$msgType,$contentStr);
                            echo $resultStr;
                            break;
//                        case 'text':
//                            $res = $postObj->Recognition;
//                            $url = "http://www.tuling123.com/openapi/api";
//                            $ch = curl_init();
//                            curl_setopt($ch,CURLOPT_URL,$url);
//                            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//                            curl_setopt($ch,CURLOPT_POST,1);
//                            $data=array(
//                                'key'=>'e33363e46d2145e8b92a049cb6eb1092',
//                                'info'=>$res,
//                                'userid'=>'123456'
//                            );
//                            $data = json_encode($data);
//                        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
//                        curl_setopt($ch,CURLOPT_HTTPHEADER,array(
//                            'Content-Type:application/json',
//                            'Content-Length:'.strlen($data)
//                        ));
//                        $str = curl_exec($ch);
//                        curl_close($ch);
//                        $json=json_decode($str);
//                            $msgType='text';
//                            $contentStr =$json->text;
//                            $resultStr = sprintf($tmp_arr['text'],$fromUsername,$toUsername,$time,$msgType,$contentStr);
//                            echo $resultStr;
//                            break;
                        case 'event':
                            if($postObj->Event == 'subscribe'){
                                $msgType = 'text';
                                $contentStr = '感谢关注php学院';
                                $resultStr = sprintf($tmp_arr['text'],$fromUsername,$toUsername,$time,$msgType,$contentStr);
                        echo $resultStr;
                            }
                        if($postObj->Event == 'CLICK' && $postObj->EventKey =='V1001_TODAY_MUSIC'){
                            $msgType = "music";
                            $title = '烹爱';
                            $descripton = '花间提壶方大厨';
                            $url = 'http://www.youhaerma.top/music.mp3';
                            $hqurl = 'http://www.youhaerma.top/music.mp3';
                            $resultStr = sprintf($tmp_arr['music'], $fromUsername, $toUsername, $time, $msgType, $title,$descripton,$url,$hqurl);
                            echo $resultStr;
                        }
                        break;
                }
        	exit;
        }
    }
		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

$wechatObj = new wechatCallbackapiTest();
$wechatObj->responseMsg();//开启自动回复功能