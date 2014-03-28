<?php
	
require_once "trans.php";

class webchat_twitter
{	 
	function getkey($contents){   
		$contents = iconv("UTF-8", "gbk", $contents);
		$rows = strip_tags($contents); 
		$arr = array(' ',' ',"\s", "\r\n", "\n", "\r", "\t", ">", "“", "”"); 
		$qc_rows = str_replace($arr, '', $rows); 
		if(strlen($qc_rows) > 2400){ 
			$qc_rows = substr($qc_rows, '0', '2400'); 
		}   
		
		// 初始化一个 cURL 对象  
		$curl = curl_init();
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, 'http://keyword.discuz.com/related_kw.html?title='.$qc_rows.'&ics=gbk&ocs=gbk');
		// 设置header
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 设置cURL 参数，时间超时
		curl_setopt($curl, CURLOPT_TIMEOUT, 1);
		// 运行cURL，请求网页
		$data = curl_exec($curl);
		// 关闭URL请求
		curl_close($curl); 
		if($data === FALSE){
			$key_array[0] = $contents; 
			return $key_array; 
		}
	//	$data = @implode('', file("http://keyword.discuz.com/related_kw.html?title=$qc_rows&ics=gbk&ocs=gbk"));  
		$data = iconv("gbk", "UTF-8",  $data); 
		$key_array = array(); 
		if(preg_match_all("/<kw>(.*)A\[(.*)\]\](.*)><\/kw>/",$data, $out, PREG_SET_ORDER))
		{		
			$c = count($out); 
			for($i = 0; $i < $c; $i++){  
				if($out[$i][2])
					$key_array[$i] = $out[$i][2]; 
			} 
		}
		if(count($key_array) == 0){
			$key_array[0] = $contents;
		}
		return $key_array;  
	} 
	//twitter
	public function gettwitter($keyword){ 
	//	$key_array = $this->getkey($keyword); 
		$original_url = 'http://search.twitter.com/search.json?lang=zh&rpp=100&result_type=mixed&q=';
	/*	$c = count($key_array);
		$keylist = '';
		$mainkey = '';
		$mainkey_len = 0; 
		foreach($key_array as $key)
		{
			if(mb_strlen($key,'utf-8') > $mainkey_len){
				$mainkey = $key;
				$mainkey_len = mb_strlen($key,'utf-8');
			}
			$keylist .= urlencode($key).'%20';
		} */  
		if(mb_strlen($keyword,'utf-8') > 15)
			return '说这么长，我都不知道怎么回复你了~~';
		$keylist = urlencode($keyword);
	/*	$c = mb_strlen($keyword,'utf-8');
		for($i = 0; $i < $c; $i++){ 
			$key = mb_substr($contentStr, $i, 1, 'utf-8');
			$keylist .= urlencode($key).'%20';
		}*/
		$twitter = $this->getRT($original_url.'RT%20'.$keylist, ''); 
		if(strlen($twitter) == 0){ 
			$keylist = '';
			$c = mb_strlen($keyword,'utf-8') / 2;
			if($c > 4)
				$c = 4;
			for($i = 0; $i < $c; $i += 2){ 
				$key = mb_substr($keyword, $i, 2, 'utf-8');
				$keylist .= urlencode($key).'%20';
			} 
			$twitter = $this->get($original_url.$keylist);
		}
		if(strlen($twitter) == 0){ 
			$keylist = urlencode(mb_substr($keyword, 0, 2, 'utf-8'));
			$twitter = $this->get($original_url.$keylist);
		}
		if(strlen($twitter) == 0)
			$twitter ='哎呀呀~';  
	//	$go = new Trans(); 
	//	$twitter = $go->t2c($twitter); 
		$twitter = str_replace('twitter','微博', $twitter);
		$twitter = str_replace('推特','微博', $twitter);
		return $twitter;
	} 
	//twitter
	function getRT($url, $keyword){
		// 初始化一个 cURL 对象  
 		$curl = curl_init();
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, $url);
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
			return "Oops!这网速也忒慢啦~请再输入一遍~";  
		$Message = json_decode($data, true); 
		$Message = $Message["results"];  
		$mc = count($Message);
		$sindex = rand(0,count($Message)-1);
		if($mc > 0){  
			$sindex = rand(0,count($Message)-1);
			for($ti = 0; $ti < $mc; $ti++){
				$sindex++;
				$tmp = $Message[$sindex%$mc]["text"]; //取得内容
				//RT 必须在 关键词前面
				$index = strpos($tmp, "RT");
				if($index === false)
					continue;
			/*	$index2 = strpos($tmp, $keyword);
				if($index === false)
					continue;
				if($index > 0 && $index < $index2){ 
					$tmp = substr($tmp, 0, $index);	
				}else{
					continue;
				}*/				
				
				$tmp = substr($tmp,$index);	
				$tmp = $this->removedust($tmp);
				//是否有中文
				if(!preg_match("/[\x7f-\xff]+/", $tmp)){					
					continue;
				}
				if(strlen($tmp) > 0)
					return $tmp;
			}
			$tmp = $Message[rand(0,count($Message)-1)]["text"]; 
			$tmp = $this->removedust($tmp);
			
			if(strlen($tmp) > 0)
				return $tmp;
			return  '就这样吧~~';
		}else{
			return "";
		}
	} 
	
	//twitter
	function get($url){
		// 初始化一个 cURL 对象  
 		$curl = curl_init();
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, $url);
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
			return "Oops!这网速也忒慢啦~请再输入一遍~";  
		$Message = json_decode($data, true); 
		$Message = $Message["results"];  
		$mc = count($Message);
		$sindex = rand(0,count($Message)-1);
		if($mc > 0){  
			$sindex = rand(0,count($Message)-1); 
			$tmp = $Message[rand(0,count($Message)-1)]["text"]; 
			$tmp = $this->removedust($tmp);
			
			if(strlen($tmp) > 0)
				return $tmp;
			return  '就这样吧~~';
		}else{
			return "";
		}
	} 

	function removedust($tmp)
	{
		//去除@
		while(strstr($tmp, '@')){
			$index = strpos($tmp, '@');
			$index2 = strpos($tmp, ' ', $index); 
			if($index2 === false)
				$index2 = strlen($tmp); 
			$tmp = str_replace(substr($tmp, $index, $index2 - $index), '', $tmp);
		}
		//去除RT
		$tmp = str_replace('RT', '', $tmp); 
		 		
		//去除http链接	
		while(strstr($tmp, 'http')){
			$index = strpos($tmp, 'http');
			$index2 = strpos($tmp, ' ', $index);
			if($index2 === false)
				$index2 = strlen($tmp); 
			$tmp = str_replace(substr($tmp, $index, $index2 - $index ), '', $tmp);
		}
		//去除（
		while(strstr($tmp, '(')){
			$index = strpos($tmp, '(');
			$index2 = strpos($tmp, ')', $index);
			if($index2 === false)
				$index2 = strlen($tmp);
			else 
				$index2 = $index2 + 1;
			$tmp = str_replace(substr($tmp, $index, $index2 - $index), '', $tmp);
		}
		//去除（
		while(strstr($tmp, '[')){
			$index = strpos($tmp, '[');
			$index2 = strpos($tmp, ']', $index);
			if($index2 === false)
				$index2 = strlen($tmp);
			else 
				$index2 = $index2 + 1;
			$tmp = str_replace(substr($tmp, $index, $index2 - $index ), '', $tmp);
		}
		//去除#
		while(strstr($tmp, '#')){
			$index = strpos($tmp, '#');
			$index2 = strpos($tmp, ' ', $index);
			if($index2 === false)
				$index2 = strlen($tmp); 
			$tmp = str_replace(substr($tmp, $index, $index2 - $index), '', $tmp);
		}
		//去除#
		while(strstr($tmp, 't.co')){
			$index = strpos($tmp, 't.co');
			$index2 = strpos($tmp, ' ', $index);
			if($index2 === false)
				$index2 = strlen($tmp); 
			$tmp = str_replace(substr($tmp, $index, $index2 - $index), '', $tmp);
		}
		return $tmp;
	} 
}   
 
?>