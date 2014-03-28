<?php
/**
  * wechat php test
  */ 
include 'weixinlib/textmsg.php';
include 'weixinlib/secretmsg.php';
 
$wechatObj = new wechatCallbackapiTest();
$wechatObj->responseMsg('<xml><ToUserName><![CDATA[gh_3b9f2b7cbeb1]]></ToUserName>
<FromUserName><![CDATA[onJi-jkBg94_yLkoQLmlVFDTPqfk]]></FromUserName>
<CreateTime>1364050029</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[kk]]></Content>
<MsgId>5858550264762860409</MsgId>
</xml>');

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

    public function responseMsg($postStr)
    {
		//get post data, May be due to the different environments
	//	$postStr = $GLOBALS['HTTP_RAW_POST_DATA'];

      	//extract post data
		if (!empty($postStr)){ 
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); 
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
			$getMsgType = $postObj->MsgType;
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
						 <FuncFlag>1</FuncFlag>
						 </xml>";
			$msgType = 'text'; 
			$ctype = "text";
			if($getMsgType == 'text'){
				$keyword = trim($postObj->Content);
			//	$webchat_secretmsgObj = new webchat_secretmsg();
			//	list($contentStr, $ctype) = $webchat_secretmsgObj->dotext($keyword, $fromUsername);
			$contentStr = "dd";
			}
			//image
			else if($getMsgType == 'image'){				
				$msgType = 'text';
				$contentStr = '?，???分享?片~'; 
				//database
				d_inserttext($fromUsername,$PicUrl, $createTime, $contentStr, $time); 				
			} 
			//location
			else if($getMsgType == 'location'){ 
				$wechat_weatherObj = new wechat_weather();
				$contentStr = $wechat_weatherObj->getweatherbylocation($postObj->Label,$postObj->Location_X, $postObj->Location_Y); 
				$ctype = 'location';	
			} 
			//event
			else if($getMsgType == 'event'){
				if($postObj->Event == 'unsubscribe'){
					$this->othermsg($fromUsername,$toUsername, $postStr, $postObj->CreateTime, $getMsgType);
					d_setunsubscribe($fromUsername);
					$ctype = 'unsubscribe';
				}else if($postObj->Event == 'subscribe'){ 										
					
					$wechat_globleObj = new wechat_globle();
					$contentStr = $wechat_globleObj->welcome($fromUsername); 					
					$ctype = 'attention';	 
				}					
			} 
			//unsubscribe
			else if(strstr($postStr ,'unsubscribe')){ 
				d_setunsubscribe($fromUsername);
			} 
			//
			else{ 				
				$msgType = 'text';
				$contentStr = '?，主人?没教我听???的?~55555 可以打字??'; 
			}
											
			if(mb_strlen($contentStr,'utf-8') > 500){
				//set more
				$moretext = '(接上)'.mb_substr($contentStr, 500, mb_strlen($contentStr,'utf-8')-500, 'utf-8');
				$contentStr = mb_substr($contentStr,0, 500, 'utf-8').'【?看更多回?c】'; 
			} 							
			//回?消息
			$time = time(); 
			if($ctype == 'music'){
				$resultStr = sprintf($imageTpl, $fromUsername, $toUsername, $time, $contentStr, "http://baiwanlu.com/music.jpg", "http://fm.baidu.com"); 
			}else{
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr); 
			}
			echo $resultStr; 	
			
			//database 
		//	if(!strstr($contentStr, 'Oops!')){  
		//		d_inserttext($fromUsername, $postStr, $postObj->CreateTime, $contentStr, $time, $ctype, $moretext);
		//	}
        
		}else {
        	echo '?，?真的无??我???';
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