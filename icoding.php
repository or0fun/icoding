<?php
/**
  * wechat php test
  */ 
require_once 'weixinlib/textmsg.php';
require_once 'weixinlib/secretmsg.php';

//define your token
define('TOKEN', 'icoding520');  
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET['echostr'];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
			$this->responseMsg();
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS['HTTP_RAW_POST_DATA'];

      	//extract post data
		if (!empty($postStr)){ 
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); 
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
			$getMsgType = $postObj->MsgType;
			$PicUrl = '';
			$textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>0</FuncFlag>
						</xml>";  
			$imageTpl = "<xml>
						 <ToUserName><![CDATA[%s]]></ToUserName>
						 <FromUserName><![CDATA[%s]]></FromUserName>
						 <CreateTime>%s</CreateTime>
						 <MsgType><![CDATA[news]]></MsgType>
						 <ArticleCount>1</ArticleCount>
						 <Articles>
						 <item>
						 <Title><![CDATA[%s]]></Title> 
						 <Description><![CDATA[]]></Description>
						 <PicUrl><![CDATA[%s]]></PicUrl>
						 <Url><![CDATA[%s]]></Url>
						 </item> 
						 </Articles>
						 <FuncFlag>0</FuncFlag>
						 </xml>";
			$musicTpl = "<xml>
						 <ToUserName><![CDATA[%s]]></ToUserName>
						 <FromUserName><![CDATA[%s]]></FromUserName>
						 <CreateTime>%s</CreateTime>
						 <MsgType><![CDATA[music]]></MsgType>
						 <Music>
						 <Title><![CDATA[%s]]></Title>
						 <Description><![CDATA[%s]]></Description>
						 <MusicUrl><![CDATA[%s]]></MusicUrl>
						 <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
						 </Music>
						 <FuncFlag>0</FuncFlag>
						 </xml>";
			$msgType = 'text'; 			
			$ctype = 'text';
			$contentStr = '(*^__^*) 嘻嘻……';
			$moretext = '';
			
			if($getMsgType == 'text'){
				//取消自动回复
			//	if(d_isautoreply($fromUsername) == 0){ 		
			//		$ctype = 'type';
			//		$contentStr = '正在输入...'; 
			//	}else
				{
					$keyword = trim($postObj->Content);
				//	$contentStr = $keyword; 
				//	$webchat_textmsgObj = new webchat_textmsg();
				//	list($contentStr, $ctype) = $webchat_textmsgObj->dotext($keyword, $fromUsername);
					$webchat_secretmsgObj = new webchat_secretmsg();
					list($contentStr, $ctype) = $webchat_secretmsgObj->dotext($keyword, $fromUsername); 
				}
			}
			//image
			else if($getMsgType == 'image'){				
				$ctype = 'image';
				$contentStr = '亲，谢谢您分享图片~'; 
				$PicUrl = $postObj->PicUrl;				
			} 
			//location
			else if($getMsgType == 'location'){ 
				$wechat_weatherObj = new wechat_weather();
			//	$contentStr = $wechat_weatherObj->getweatherbylocation($postObj->Label,$postObj->Location_X, $postObj->Location_Y); 
				d_setposition( $fromUsername, $postObj->Location_X.','.$postObj->Location_Y);
			//	$webchat_baiduObj = new webchat_baidu();
			//	$contentStr = $webchat_baiduObj->poi($postObj->Location_X, $postObj->Location_Y);
			
				$re = '以"找"字开头，后面跟上关键词，搜索周边你要去的地方'."如输入：\n".
					  '找饭店'."\n".
					  '找KTV'."\n".
					  '找电影院'."\n".
					  '找ATM'."\n".
					  '找银行'."\n"; 	
			//	$contentStr .= "\n--------------\n";	
				$contentStr = $re;
				
				$ctype = 'location';	
			} 
			//event
			else if($getMsgType == 'event'){
				if($postObj->Event == 'unsubscribe'){ 
					d_setunsubscribe($fromUsername);
					$contentStr ='你不要我了..';
					$ctype = 'unsubscribe';
				}else if($postObj->Event == 'subscribe'){ 			
					
					$wechat_globleObj = new wechat_globle();
					$contentStr = $wechat_globleObj->welcome($fromUsername);
	 
				    $contentStr = secret_welcome().$contentStr;	 	
					$ctype = 'attention';	 
				}					
			} 
			//event
			else if($getMsgType == 'voice'){
				$motion = array("我好喜欢你/:@>/:<@", "你的声音真好听/:B-)","我会一直陪着你的/::>","好吧 其实根本我听不懂，我只能理解文字",
				"爱我就常来找我哦/::,@","嘿嘿 声音很动听/::D","/::)","/::P","/::$","/:,@-D","/:,@P", "我喜欢你","好吧 我承认我都听不懂 要不您打字吧");
				$contentStr = $motion[rand(0, count($motion)-1)];
				$ctype = 'voice'; 		
			} 
			//unsubscribe
			else if(strstr($postStr ,'unsubscribe')){ 
				d_setunsubscribe($fromUsername);
				$contentStr ='你不要我了..';
			} 
			//
			else{ 				
				$msgType = 'text';
				$motion = array("/:@>/:<@", "/:B-)","/::>","/::,@","/::D","/::)","/::P","/::$","/:,@-D","/:,@P", "我喜欢你", "我会一直陪着你的");
				$contentStr = $motion[rand(0, count($motion)-1)];
				$ctype = 'motion'; 
			}
			
			///////////////////////////////////////////////////////
			if(strstr($contentStr, 'Oops')){ 
				$contentStr .= "\n【重新获取回复m】";
			}
			//过长，截取
			if($ctype != 'imagesearch' && mb_strlen($contentStr,'utf-8') > 570){ 
				$index = mb_strpos($contentStr, "\n\n", 300,'utf-8');
				if($index === false || $index > 570){ 
					$index = mb_strpos($contentStr, "\n", 400,'utf-8');
					if($index === false || $index > 570){
						$index = 570;
					}
				}
				$moretext = mb_substr($contentStr, $index, mb_strlen($contentStr,'utf-8')-$index, 'utf-8');
				$contentStr = mb_substr($contentStr,0, $index, 'utf-8')." \n 【查看更多回复c】"; 
			} 							
			////////////////////////////////////////回复消息
			$time = time(); 
			if($ctype == 'music' && !strstr($contentStr, 'Oops!'))
			{//点歌
				$index = strpos($contentStr, '*');
				$index2 = strpos($contentStr, '*', $index + 1); 
				$index3 = strpos($contentStr, '*', $index2 + 1); 
				$title = substr($contentStr, 0, $index);
				$link = substr($contentStr, $index+1, $index2-$index-1);
				$des = substr($contentStr, $index2+1, $index3-$index2-1);
				$lrclink = substr($contentStr, $index3+1);
				$resultStr = sprintf($musicTpl, $fromUsername, $toUsername, $time, $title, $des, $link, $lrclink); 
			} 
			else if($ctype == 'voice')
			{
				$filetime = time(); //得到时间戳
				$filename = date("YmdHis",$filetime).rand(100,999).".mp3";//图片保存的路径目录
				$resultStr = sprintf($musicTpl, $fromUsername, $toUsername, $time, '听我说', '', 
				"http://icymint.me/icoding/".$filename, "http://icymint.me/icoding/".$filename); 
			}
			else if($ctype == 'image') 
			{//分享图片				
				list($username, $link) = d_getimage($fromUsername);
				$resultStr = sprintf($imageTpl, $fromUsername, $toUsername, $time, $username,  $link, $link); 
			} 
			else if ($ctype == 'imagesearch') 
			{//图片搜索
				$resultStr = $contentStr;
			}
			else
			{//其他
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr); 
			}
			if(!(strstr($contentStr, '正在输入') && d_isautoreply($fromUsername) == 0))
				echo $resultStr; 	
			/*if ($ctype == 'voice') 
			{
				$get_file = file_get_contents($contentStr);
				$fp = @fopen($filename,  "w"); //以写方式打开文件
				@fwrite($fp, $get_file);  
				fclose($fp);
			}*/
			//插入数据库 
			if ($ctype == 'image'){
				d_inserttext($fromUsername, $PicUrl, $postObj->CreateTime, $resultStr, $time, $ctype, $moretext);
			}else if ($ctype == 'share'){
				d_inserttext($fromUsername, $postObj->Content, $postObj->CreateTime, $resultStr, $time, $ctype, $moretext);
			}
			else if(!strstr($contentStr, 'Oops!')){  
				d_inserttext($fromUsername, $postStr, $postObj->CreateTime, $resultStr, $time, $ctype, $moretext);
			}else{
				d_insertpoststr($fromUsername, $postStr);
			}			
        
		}else {
        	echo '亲，你真的无话对我讲吗?';
        	exit;
        }
    }
		
	private function checkSignature()
	{
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}   
}
?>