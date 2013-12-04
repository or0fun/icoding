<?php

class webchat_youdao
{	
	//youdao dic
	function gettranslation($keyword){
		if(strlen(trim($keyword))==0){
			return "亲，请在'翻译'后面跟上你要翻译中文或英文就可以翻译啦,如：翻译 我爱你";
		}
		// 初始化一个 cURL 对象  
 		$curl = curl_init(); 
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, 'http://fanyi.youdao.com/openapi.do?keyfrom=baiwanlu&key=1990776413&type=data&doctype=json&version=1.1&q='.urlencode(trim($keyword)));
		// 设置header 
	 	curl_setopt($curl, CURLOPT_HEADER, 0);
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 设置cURL 参数，时间超时
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		// 运行cURL，请求网页 
		$data = curl_exec($curl); 
		// 关闭URL请求 
		curl_close($curl);  
		if($data == false)
				return "Oops!这破网太慢啦，没获取到...再试一遍吧~";
	   
		$Message = $keyword;
 		$data = json_decode($data, true); 
		//音标
		if($data["basic"]["phonetic"]){
		    $Message = $Message."[".$data["basic"]["phonetic"]."]";
		}
		$Message .= ":";
		for($i = 0; $i < count($data["translation"]);$i++){ 
		    $Message .= "\n".$data["translation"][$i];
		}
		if($data["web"]){ 
		    $c = count($data["web"]); 
		    if($c >= 0){
		    	$Message .= "\n【短语】\n"; 
		    	for($i = 0; $i < $c;$i++){ 
		    	    $Message .= $data["web"][$i]["key"]."\n";
					for($j = 0; $j < count($data["web"][$i]["value"]);$j++){
						$Message .=$data["web"][$i]["value"][$j]."\n"; 
					}  
				}
		    }
		}else{
			$Message = "【机器翻译-仅供参考】\n".$Message;
		}
		return $Message; 
	} 

}	
?>