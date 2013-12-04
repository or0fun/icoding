<?php
/**
  * wechat php repeat
  */ 
include 'globle.php';
include 'chatutil.php'; 
include 'article.php'; 
include 'weather.php';
include 'simsimi.php';
include 'other.php';
include 'twitter.php';
include 'baidu.php';
include 'youdao.php';
include 'train.php';
include 'gecime.php';
include 'xingzuo.php';
include 'wiki.php';
include 'getbus.php';
include 'dream.php'; 
include 'express.php'; 
include 'namefight.php'; 
include 'chengyu.php'; 
include 'renpin.php'; 
include 'sina.php';
include 'google.php';  
include 'moreinfo.php';
include 'aibang.php';

class webchat_repeat
{ 
    public function responseMsg($fromusername)
    {
		//get post data, May be due to the different environments  
		$postStr = d_getpoststr($fromusername); 
		if(strlen($postStr) == 0)
			return '';

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
			
			if($getMsgType == 'text'){
				$keyword = trim($postObj->Content);
				$webchat_textmsgObj = new webchat_textmsg();
				list($contentStr, $ctype) = $webchat_textmsgObj->dotext($keyword, $fromUsername);
			}
			//image
			else if($getMsgType == 'image'){				
				$msgType = 'text';
				$contentStr = '亲，谢谢您分享图片~'; 
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
				$contentStr = '亲，主人还没教我听懂你说的话~55555 可以打字吗?'; 
			}
											
			if(mb_strlen($contentStr,'utf-8') > 500){
				//set more
				$moretext = '(接上)'.mb_substr($contentStr, 500, mb_strlen($contentStr,'utf-8')-500, 'utf-8');
				$contentStr = mb_substr($contentStr,0, 500, 'utf-8').'【查看更多回复c】'; 
			} 							
			//回复消息
			$time = time(); 
			if($ctype == 'music'){
				$resultStr = sprintf($imageTpl, $fromUsername, $toUsername, $time, $contentStr, "http://baiwanlu.com/music.jpg", "http://fm.baidu.com"); 
			}else{
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr); 
			}
			echo $resultStr; 	
			
			//database 
			if(!strstr($contentStr, 'Oops!')){  
				d_inserttext($fromUsername, $postStr, $postObj->CreateTime, $contentStr, $time, $ctype, $moretext);
			}
			exit;
        
		}else {
        	echo '亲，你真的无话对我讲吗?'; 
        }
    } 
}
?>