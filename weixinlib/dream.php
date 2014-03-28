<?php
//$webchat_dreamObj = new webchat_dream();
//echo $webchat_dreamObj->analysis('大海');
    
class webchat_dream
{
	public function analysis($searchwords)
	{		
		if(mb_strlen($searchwords,'utf-8') > 8){ 
			$searchwords = mb_substr($contentStr, 0, 8, 'utf-8');
		}
		if(strlen(trim($searchwords))==0){
			$contentStr = "亲，要解梦吗？只要先输入'梦见'两个字，\n再后面加上你的梦境可以啦，如：梦见老师";
		}else{						
			$contentStr = $this->get2($searchwords);  
			if(strlen($contentStr) == 0){
				$contentStr = 'Sorry.这个梦我解不了'."/::D".'可以说的简单点，比如：梦见大海'; 
			}else{ 
				$contentStr = $contentStr."\n【仅供参考】";
			}
		}		
		return  $contentStr;
	}
	public function get2($words)
	{  
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'http://zhougongjiemeng.1518.com/search2.php?word='.urlencode($words));
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 2);
		$data = curl_exec($curl);
		curl_close($curl);
		if($data == false)
			return "Oops!这破网太慢啦，请再试一遍~";
        if(strstr($data, "302 Moved")) {
            $index = strpos($data, '/zhougongjiemeng-');
            $link = trim(substr($data, $index));
        }else{
            $index = strpos($data, '<a href="/zhougongjiemeng-');
            $index2 = strpos($data, '"', $index + 10);
            $link = substr($data, $index + 9, $index2 - $index - 9);
        }
        $url = "http://zhougongjiemeng.1518.com".$link;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 2);
        $data = curl_exec($curl);
        curl_close($curl);
        if($data == false)
            return "Oops!这破网太慢啦，请再试一遍~";
        $index = strpos($data, '<dd>');
        $index2 = strpos($data, '</dd>', $index);
        $re = substr($data, $index + 4, $index2 - $index - 4);
        
		$re = str_replace("<br />", '', $re);
		$re = mb_convert_encoding($re, "UTF-8", "gbk");
		return $re;
	} 
	function get($words)
	{  
		// 初始化一个 cURL 对象  
		$curl = curl_init();
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, 'http://www.zgjm.org/plus/search.php?q='.urlencode(iconv("UTF-8", "gb2312", $words)));
		// 设置header
		curl_setopt($curl, CURLOPT_HEADER, 1);
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 设置cURL 参数，时间超时
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		// 运行cURL，请求网页
		$data = curl_exec($curl);
		// 关闭URL请求
		curl_close($curl);
		if($data == false)
			return "Oops!这破网太慢啦，请再试一遍~";   
		$in = strpos($data, '<dd>');
		$in2 = strpos($data, '</dd>', $in + 4);
		$tmp = substr($data, $in, $in2 - $in);
		$re = '';
		while(strstr($tmp, '<')){
			$index = strpos($tmp, '<');
			$index2 = strpos($tmp, '>');
			if($index == 0){
				$tmp = substr($tmp, $index2+1);
			}else{
				$re .= substr($tmp, 0, $index);
				$tmp = substr($tmp, $index2+1);
			}
		}
		if(strlen($tmp) > 0)
			$re .= $tmp; 
		return  $re;
	} 	    

}
?>