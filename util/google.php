<?php 
$webchat_googleObj = new webchat_google();
echo $webchat_googleObj->getimages($_GET['q'], $_GET['n']);
class webchat_google
{	
	//image 
	public function getimages($keyword, $fromUsername){
	
		$imageTpl_header = "<xml>
					 <ToUserName><![CDATA[%s]]></ToUserName>
					 <FromUserName><![CDATA[gh_3b9f2b7cbeb1]]></FromUserName>
					 <CreateTime>%s</CreateTime>
					 <MsgType><![CDATA[news]]></MsgType>
					 <ArticleCount>%d</ArticleCount>
					 <Articles>";
		$imageTpl_item = "<item>
					 <Title><![CDATA[%s]]></Title> 
					 <Description><![CDATA[%s]]></Description>
					 <PicUrl><![CDATA[%s]]></PicUrl>
					 <Url><![CDATA[%s]]></Url>
					 </item>";
		$imageTpl_tail = "</Articles>
						<FuncFlag>0</FuncFlag>
						 </xml>";	
		if(strlen(trim(	$keyword) ) == 0)
			$keyword = '美女';
 		$curl = curl_init();  
		$link = 'https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q='.urlencode($keyword).'&userip=INSERT-USER-IP';
		//echo $link;
		curl_setopt($curl, CURLOPT_URL, $link); 
	 	curl_setopt($curl, CURLOPT_HEADER, 0);  
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_REFERER, 'http://icymint.me');
		curl_setopt($curl, CURLOPT_TIMEOUT, 4); 
		$data = curl_exec($curl);  
		curl_close($curl);  
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~';
		
		$Message = json_decode($data, true);
		$responseData = $Message['responseData']['results'];
		$c = count($responseData);
		if($c == 0)
		{
			return '找不到你要的图片哦，看看别的吧~';
		}
		$time = time();
		$re = sprintf($imageTpl_header, $fromUsername, $time, $c);
		for($i = 0; $i < $c; $i++)
		{
			//$re .= sprintf($imageTpl_item, '【'.$keyword.'】点击查看大图', '', $responseData[$i]["url"],
			//		$responseData[$i]["url"]); 
			$temp = $responseData[$i]["titleNoFormatting"];
			$temp = str_replace("胸","*",$temp);
			$temp = str_replace("傻逼","**",$temp);
			$temp = str_replace("私房","**",$temp);
			$temp = str_replace("写真","**",$temp);			
			$re .= sprintf($imageTpl_item, "[点击查看大图] ", '', $responseData[$i]["url"],
					$responseData[$i]["url"]); 
		}
		$re .= $imageTpl_tail;
		
		return $re;
	}
}
?>