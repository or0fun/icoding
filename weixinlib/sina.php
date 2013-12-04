<?php 

//$webchat_sinaObject = new webchat_sina(); 
//echo $webchat_sinaObject->hotweibo();	
//echo $webchat_sinaObject->hotnews();	

class webchat_sina
{
	public function hotnews(){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'http://www.baidu.com/s?tn=baiduwb&rtt=2&cl=2&rn=5&ie=utf-8&bs=%E5%A4%B4%E6%9D%A1%E6%96%B0%E9%97%BB&f=8&rsv_bp=1&wd=%E5%A4%B4%E6%9D%A1%E6%96%B0%E9%97%BB%E8%A6%81%E9%97%BB%E5%9B%9E%E9%A1%BE&rsv_n=2&inputT=761');
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		$data = curl_exec($curl);
		curl_close($curl); 
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~'; 
		  
		$index = strpos($data, '<em>头条新闻要闻回顾</em>】');
		$index2 = strpos($data, '。', $index);
		$len = strlen('<em>头条新闻要闻回顾</em>】');
		$tmp = substr($data, $index + $len, $index2 - $index - $len + strlen('。'));
		while(strstr($tmp, '<')){
			$index = strpos($tmp, '<'); 
			$index2 = strpos($tmp, '>', $index);  
			$tmp = substr($tmp, 0, $index).substr($tmp, $index2 + 1); 
		}
		$tmp = str_replace(';', "；\n\n", $tmp);
		$tmp = str_replace('。', "。\n\n", $tmp);
		return "【今日头条新闻回顾】\n\n".$tmp;
	}
	public function hotweibo(){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'http://hot.weibo.com/');
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		$data = curl_exec($curl);
		curl_close($curl); 
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~'; 
			
		$re = "【最热微博】\n";
		for($i = 1; $i <= 5; $i++){
			$re .= 'TOP '.$i."---\n";
			$index_0 = strpos($data, 'TOP '.$i);
			//user
			$index = strpos($data, '<div class="WB_info"', $index_0);
			$index2 = strpos($data, '</div>', $index);
			$tmp = substr($data,$index, $index2 - $index);
			while(strstr($tmp, '<')){
				$index = strpos($tmp, '<'); 
				$index2 = strpos($tmp, '>', $index);  
				$tmp = substr($tmp, 0, $index).substr($tmp, $index2 + 1); 
			}
			$re .= $tmp."\n";
			//微博内容
			$index = strpos($data, '<div class="WB_text"', $index_0);
			$index2 = strpos($data, '</div>', $index);
			$text = substr($data, $index, $index2 - $index);
			while(strstr($text, '<')){
				$index = strpos($text, '<'); 
				$index2 = strpos($text, '>', $index);  
				$text = substr($text, 0, $index).substr($text, $index2 + 1); 
			}
			//时间
			$index = strpos($data, '<div class="WB_from', $index_0);
			$index2 = strpos($data, '</a>', $index);
			$date = substr($data,$index, $index2 - $index);
			while(strstr($date, '<')){
				$index = strpos($date, '<'); 
				$index2 = strpos($date, '>', $index);  
				$date = substr($date, 0, $index).substr($date, $index2 + 1); 
			}
			$re .= $date." 发布\n";
			$re .= $text."\n";			
			//配图
			$index = strpos($data, '<ul class="WB_media_list', $index_0);
			$index2 = strpos($data, '</ul>', $index);
			$pics = substr($data, $index, $index2 - $index);
			if(strstr($pics, '<li>')){
				$index = strpos($pics, '<li>'); 
				$index2 = strpos($pics, '</li>', $index);  
				$pic = substr($pics, $index, $index2 - $index);
				//$pics = substr($pics, $index2);	
				
				$index = strpos($pic, 'src="'); 
				$index2 = strpos($pic, '.jpg', $index);  
				if($index2 > 0) { 
					$url = substr($pic, $index + 5, $index2 - $index - 1); 
					$re .= '配图'."\n";	
					$re .= $url." \n";
				}
			}
			
			$data = substr($data, $index_0);		
		}
		return $re;
	}
}