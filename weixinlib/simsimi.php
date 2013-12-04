<?php 
 
//$webchat_wikiObj = new webchat_simsimi();
//echo $contentStr = $webchat_wikiObj->chat('爱情');
class webchat_simsimi{ 
	public function chat($keyword){ 
		
		$result = $this->s_chatting($keyword);
		if(strlen($result) == 0)
		{
			return $this->ajaxsns($keyword);
		}else
		{
			return $result;
		}
		
 		$url = "http://www.simsimi.com/talk.htm?lc=ch";    
  
		$ch = curl_init();   
		curl_setopt($ch, CURLOPT_URL, $url);   
		curl_setopt($ch, CURLOPT_HEADER, 1);   
		curl_setopt($ch,CURLOPT_HTTPHEADER,array (
		"Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
		"Accept-Charset:ISO-8859-1,utf-8;q=0.7,*;q=0.3",
		"Accept-Encoding:gzip,deflate,sdch",
		"Accept-Language:zh-CN,zh;q=0.8"));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 2);  
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.97 Safari/537.22");
		$content = curl_exec($ch);   
		curl_close($ch);   
		if($content == false)
			return "我真心喜欢上你了~";
		list($header, $body) = explode("\r\n\r\n", $content);   
		preg_match("/set\-cookie:([^\r\n]*)/i", $header, $matches);    
		$cookie = $matches[1];   
		$urll = 'http://www.simsimi.com/func/req?msg='.urlencode($keyword).'&lc=ch';  
	  
		$ch = curl_init();   
		curl_setopt($ch, CURLOPT_URL, $urll);   
		curl_setopt($ch, CURLOPT_HEADER, 0);   
		curl_setopt($ch,CURLOPT_HTTPHEADER,array (
		"Accept:application/json, text/javascript, */*; q=0.01",
		"Accept-Charset:GBK,utf-8;q=0.7,*;q=0.3",
		"Accept-Encoding:gzip,deflate,sdch",
		"Accept-Language:zh-CN,zh;q=0.8"));
		curl_setopt($ch, CURLOPT_REFERER, $url);   
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
		curl_setopt($ch, CURLOPT_COOKIE, $cookie); 
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.97 Safari/537.22"); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 2);  
		$content = curl_exec($ch);   
		curl_close($ch);   
		if($content == false)
			return "";
		 
		$Message = json_decode($content, true); 
		if($Message['result']=='100'){
			$re = $Message['response'];
			if(strlen($re) == 0)
				return "";
			if(strstr($re, "搜")&&strstr($re, "微")&&strstr($re, "信"))
			{
				return "";;  
			}
			if(strstr($re, "推荐")&&strstr($re, "微")&&strstr($re, "信"))
			{
				return "";  
			}
			if(strstr($re, "关注")&&strstr($re, "微")&&strstr($re, "信"))
			{
				return "";  
			}
			if(strstr($re, "微")&&strstr($re, "信"))
			{
				return "";  
			}
			if(strstr($re, "加Q"))
			{
				return "";  
			}
			if(strstr($re, "扣扣"))
			{
				return "";  
			}
			if(strstr($re, "QQ"))
			{
				return "";  
			}
			if(strstr($re, 'http://developer.simsimi.com'))
				$re = '不要再跟我聊天啦，让我休息一下吧 好不好 你还是等我主人回来跟他聊吧~~'."\n回复 help  查看相关功能";
				
			$re = str_replace('小黄鸡','iCoding', $re);
			$re = str_replace('simsimi','iCoding', $re);
			$re = str_replace('鸡','iCoding', $re);
			return $re;
		}else{ 
			return "";
		}		 
		
		return "你说什么呀..."; 
	}  
	//simsimi trial key
	public function s_chatting($keyword){ 
		 
		if ( $keyword<>'' ){   
 			$keys = array('1461c254-d455-482c-98ee-877106c4ed8a', 'a3144000-0445-4723-a4d0-04e2d19a34e5');
			$curl = curl_init(); 
			curl_setopt($curl, CURLOPT_URL, 'http://sandbox.api.simsimi.com/request.p?key='.$keys[rand(0, 1)].'&lc=ch&ft=1.0&text='.$keyword); 
			curl_setopt($curl, CURLOPT_HEADER, 0); 
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($curl, CURLOPT_TIMEOUT, 4); 
			$data = curl_exec($curl);  
			curl_close($curl); 
			if($data == false)
				return "";
				 
			$Message = json_decode($data, true);  
			if($Message['result']=='100'){
				$re = $Message['response'];
				if(strlen($re) == 0)
					return "";
				if(strstr($re, "搜")&&strstr($re, "微")&&strstr($re, "信"))
				{
					return "";;  
				}
				if(strstr($re, "推荐")&&strstr($re, "微")&&strstr($re, "信"))
				{
					return "";  
				}
				if(strstr($re, "关注")&&strstr($re, "微")&&strstr($re, "信"))
				{
					return "";  
				}
				if(strstr($re, "微")&&strstr($re, "信"))
				{
					return "";  
				}
				if(strstr($re, "加Q"))
				{
					return "";  
				}
				if(strstr($re, "扣扣"))
				{
					return "";  
				}
				if(strstr($re, "QQ"))
				{
					return "";  
				}
				$re = str_replace('小黄鸡','iCoding', $re);
				$re = str_replace('simsimi','iCoding', $re);
				//$re = str_replace('鸡','iCoding', $re);
				if(strstr($re, 'http://developer.simsimi.com'))
					$re = "不要再跟我聊天啦，让我休息一下吧 \n试试其他功能吧。查看所有功能回复help";
				return $re;
			}else{ 
				return "";
			}
		}
		return "你说什么呀...";
	} 
	
	//ajaxsns
	public function ajaxsns($keyword){ 
		 
		if ( $keyword <> '' ){   
			$curl = curl_init(); 
			$link = 'http://api.ajaxsns.com/api.php?key=free&appid=0&msg='.urlencode($keyword);
			curl_setopt($curl, CURLOPT_URL, $link); 
			curl_setopt($curl, CURLOPT_HEADER, 0); 
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($curl, CURLOPT_TIMEOUT, 4); 
			$data = curl_exec($curl);  
			curl_close($curl); 
			if($data == false)
				return "";
				 
			$Message = json_decode($data, true);  
			if($Message['result']=='0'){
				$re = $Message['content'];
				if(strlen($re) == 0)
					return "";
				if(strstr($re, "菲菲"))
				{
					return "";;  
				}
				$re = str_replace('小黄鸡','iCoding', $re);
				$re = str_replace('simsimi','iCoding', $re);
				$re = str_replace('{br}',"\n", $re);
				return $re;
			}else{ 
				return "";
			}
		}
		return "你说什么呀...";
	} 
}	
?>