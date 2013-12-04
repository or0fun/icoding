<?php 
	
require_once "trans.php";
//$webchat_wikiObject = new webchat_wiki(); 
//echo $webchat_wikiObject->getnongli2('20130908');	
//echo $webchat_wikiObject->getnongli('','','');	
class webchat_wiki
{	
	public function getnongli2($date){
		$year = substr($date, 0, 4);
		$month = substr($date, 4, 2);
		$day = substr($date, 6);
		return $this->getnongli($year, $month, $day);
	}
	public function getyinli($year, $month, $day){
		date_default_timezone_set('PRC');
		if ($year == '')
			$year = date('Y');
		if ($month == '')
			$month = date('m');
		if ($day == '')
			$day = date('d');
		if (strlen($month) == 1)
			$month = '0'.$month;
		if (strlen($day) == 1)
			$day = '0'.$day;	
		$url = 'http://tools.2345.com/api/app/god/'.$year.'/'.$month.'/'.$year.$month.$day.'.js';
		 
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 4); 
		$data = curl_exec($curl);
		curl_close($curl);
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~';
		$index = strpos($data, 'gongli:"');
		$index2 = strpos($data, '"', $index + 10);
		$re = substr($data, $index + 8, $index2 - $index - 8)."\n"; 
		$index = strpos($data, 'nongli:"');
		$index2 = strpos($data, '"', $index + 10);
		$re .= substr($data, $index + 8, $index2 - $index - 8)."\n";
		$re = str_replace(" ", "\n", $re);
		$index = strpos($data, 'suici:"');
		$index2 = strpos($data, '"', $index + 10);
		$re .= substr($data, $index + 7, $index2 - $index - 7)."\n";
		$re .= "【宜】";
		$index = strpos($data, 'yi:"');
		$index2 = strpos($data, '"', $index + 10);
		$re .= substr($data, $index + 4, $index2 - $index - 4)."\n";
		$re .= "【忌】";
		$index = strpos($data, 'ji:"');
		$index2 = strpos($data, '"', $index + 10);
		$re .= substr($data, $index + 4, $index2 - $index - 4)."\n";
		return $re;
	}
	public function getnongli($year, $month, $day){ 
		return $this->getyinli($year, $month, $day);
		date_default_timezone_set('PRC');
		if ($year == '')
			$year = date('Y');
		if ($month == '')
			$month = date('m');
		if ($day == '')
			$day = date('d');
		if (strlen($month) == 1)
			$month = '0'.$month;
		if (strlen($day) == 1)
			$day = '0'.$day;
			
		$url = 'http://www.jiriba.com/'.$year.'-'.$month.'-'.$day.'.html';
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 4); 
		$data = curl_exec($curl);
		curl_close($curl);
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~'; 
		$re = '';
		//公历
		$index = strpos($data, '<div class="title mt20">');
		$index2 = strpos($data, '</div>', $index);
		$len = strlen('<div class="title mt20">');
		$tmp = substr($data, $index + $len, $index2 - $index - $len);
		$re .= $tmp."\n\n";
		//农历
		$index = strpos($data, '<div class="r_title">农历', $index2);
		$index2 = strpos($data, '</div>', $index);
		$len = strlen('<div class="r_title">');
		$tmp = substr($data, $index + $len, $index2 - $index - $len);
		$tt = strpos($tmp, ' ');
		$ttmp = substr($tmp, 0, $tt)."\n".trim(substr($tmp, $tt));
		$re .= $ttmp."\n";
		//岁次
		$index = strpos($data, '<div class="r_title">', $index2);
		$index2 = strpos($data, '</div>', $index);
		$len = strlen('<div class="r_title">');
		$tmp = substr($data, $index + $len, $index2 - $index - $len);
		$re .= $tmp."\n";
		//宜
		$index = strpos($data, '<div class="l_title">宜：', $index2);
		$index2 = strpos($data, '</div>', $index);
		$index = strpos($data, '<div class="r_title">', $index2);
		$index2 = strpos($data, '</div>', $index);
		$len = strlen('<div class="r_title">');
		$tmp = substr($data, $index + $len, $index2 - $index - $len);
		$re .= '宜：'.$tmp."\n";
		//宜
		$index = strpos($data, '<div class="l_title">忌：', $index2);
		$index2 = strpos($data, '</div>', $index);
		$index = strpos($data, '<div class="r_title">', $index2);
		$index2 = strpos($data, '</div>', $index);
		$len = strlen('<div class="r_title">');
		$tmp = substr($data, $index + $len, $index2 - $index - $len);
		$re .= '忌：'.$tmp."\n";
		
		$re = str_replace('&nbsp;',' ', $re);
		return $re;
	}
	
	public function gettoday()
	{ 	
		date_default_timezone_set('PRC');
		$NOW = date('n').'月'.date('j').'日'; 
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'http://zh.wikipedia.org/wiki/'.urlencode($NOW));
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		$data = curl_exec($curl);
		curl_close($curl); 
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~';  
		 
		$re_date = $this->filterchar('<p>', '</p>', $data)."\n"; 
		
		$go = new Trans(); 
		$data = $go->t2c($data); 
		
		$re = '';
		$in = 0; 
		$textarray = array("【发生在这一天的大事】", "\n【这天出生的名人】", "\n【这天逝世的名人】", "\n【节日、风俗习惯】");
		$textarray2 = array("编辑段落：大事记", "编辑段落：出生", "编辑段落：逝世", "编辑段落：节", "编辑段落：");
		
		for($i = 0; $i < 4; $i++){ 
			$re_tmp = $textarray[$i]; 
			$in = strpos($data, $textarray2[$i]); 
			$in2 = strpos($data, $textarray2[$i+1], $in + 4); 
			$tmp = substr($data, $in, $in2 - $in);  //选定区域  
			$ttmp = '';
			while(strstr($tmp, '<li>')) {
				$index = strpos($tmp, '<li>');
				$index2 = strpos($tmp, '</li>', $index);
				$ttmp = substr($tmp, $index, $index2 - $index)."\n".$ttmp;
				$tmp = substr($tmp, $index2);
			}
			while(strstr($ttmp, '<')){
				$index = strpos($ttmp, '<');
				$index2 = strpos($ttmp, '>', $index);
				if ( $index2 == -1 || $index2 >= strlen($ttmp)) {
					$ttmp = substr($ttmp, 0, $index);
				}else {
					$ttmp = substr($ttmp, 0, $index).substr($ttmp, $index2 + 1); 
				}
			}
			$data = substr($data, $in2+1); 
			$ttmp = $re_tmp."\n".$ttmp;
			if($i < 3){
				$re .= $ttmp;
			}else{ 
				$re = $ttmp."\n".$re;
			}  
		}
		
		//$re = $re_date.str_replace(' ',"\n", $re);
		$re = $re_date.$re;
		 
		$contentStr = $re; 
		return $contentStr;
	} 
	
	public function filterchar($str1, $str2, $data){
		$re_tmp = ''; 
		$in = strpos($data, $str1);
		$in2 = strpos($data, $str2, $in);
		$tmp = substr($data, $in, $in2 - $in); 
		while(strstr($tmp, '<')){
			$index = strpos($tmp, '<');
			$index2 = strpos($tmp, '>');
			if($index == 0){
				$tmp = substr($tmp, $index2+1);
			}else{
				$re_tmp .= substr($tmp, 0, $index);
				$tmp = substr($tmp, $index2+1);
			}
		}
		$re_tmp .= $tmp;
		return $re_tmp;
	}
	
	public function getwiki($searchwords, $flag)
	{		 
		//敏感词
		$wechat_globleObj = new wechat_globle();
		$namesArray = $wechat_globleObj->getwikiarray();
					
		$arrarycount = count($namesArray);
		for($i=0;$i<$arrarycount;$i++){ 
			if(strstr($searchwords, $namesArray[$i])){
				return '要不问点别的好不好...'; 
			}
		}
		$contentStr = $this->getwikiContent($searchwords, $flag); 
	 
		if(strlen($contentStr) == 0 || strstr($contentStr, '维基百科目前还没有与上述标题相同的条目。') !== false){ 
			if (preg_match("/^[a-zA-Z0-9]+$/", $searchwords)){
				$searchwords = strtoupper($searchwords);
				$contentStr = $this->getwikiContent($searchwords, $flag); 
				if(strlen($contentStr) == 0 || strstr($contentStr, '维基百科目前还没有与上述标题相同的条目。') !== false){
					$contentStr = "亲，这个我得想想^-^\n\n----------\n试试回复：\n名词 $searchwords \n";
				}
			}else{
				$contentStr = "亲，这个我得想想^-^\n\n----------\n试试回复：\n名词 $searchwords \n";
			}
		} 
		$contentStr = str_replace('&quot;','"', $contentStr);
		return $contentStr;
	}
	
	public function getwikiContent($searchwords, $flag)
	{	 
		if($flag){
			$contentStr = $this->getcontent_zh($searchwords); 
		}else{
			$contentStr = $this->getextracts_zh($searchwords); 
			if(preg_match("/^REDIRECT(.*)$/", $contentStr, $match)){
				$contentStr = "亲,你是说". $match[1]."吗？\n如果是， 就请回复：\n?".$match[1];
				//$contentStr = $this->getextracts_zh($match[1]);
			}else{ 
				if(preg_match("/^重定向(.*)$/", $contentStr, $match)){		
				$contentStr = "亲,你是说". $match[1]."吗？\n如果是， 就请回复：\n?".$match[1];	
					//$contentStr = $this->getextracts_zh($match[1]);
				} 
			}
		}
		if(strlen($contentStr) == 0){
			$contentStr = $this->getcontent_en($searchwords);
		}
		$go = new Trans(); 
		$contentStr = $go->t2c($contentStr); 
		
		return $contentStr; 
	}
	
	function getcontent_zh($keyword){  
		// 初始化一个 cURL 对象  
		$curl = curl_init();
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, 'http://zh.wikipedia.org/wiki/'.urlencode($keyword));
		// 设置header
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		//使用自动跳转
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
		// 设置cURL 参数，时间超时
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		// 运行cURL，请求网页
		$data = curl_exec($curl);
		// 关闭URL请求
		curl_close($curl); 
		if($data == false){ 
			$go = new Trans; 
			$keyword = $go->c2t($keyword); 
			$re = $this->getextracts_zh($keyword);  
			if(preg_match("/REDIRECT/", trim($re))){  
				$index = strpos($re, ' ');
				$index2 = strpos($re, ' ', $index + 1);
				if($index2 === false)
					$keyword = substr($re, $index+1);
				else
					$keyword = substr($re, $index+1, $index2-$index-1);
				$re = $this->getextracts_zh($keyword); 
			} 
			return $re;
		}  
		$content = '';
		while(true){
			$index = strpos($data, '<p>');
			if($index === false)
				break;
			$index2 = strpos($data, '</p>', $index);
			if($index2 === false)
				break;
			$tmp = substr($data, $index+3, $index2 - $index - 3);
			$data = substr($data, $index2);
			$re = '';	 
			while(strstr($tmp, '<')){ 
				$index = strpos($tmp, '<');
				$index2 = strpos($tmp, '>', $index);
				$tmp = substr($tmp, 0, $index).substr($tmp, $index2+1);
			}
			$re = $tmp;
			
			if(strstr($re, '[') !== false){
				$tmp = $re;
				$re = '';	
				while(strstr($tmp, '[')){
					$index = strpos($tmp, '[');
					$index2 = strpos($tmp, ']');
					if($index == 0){
						$tmp = trim(substr($tmp, $index2+1));
					}else{
						$re .= trim(substr($tmp, 0, $index));
						$tmp = substr($tmp, $index2+1);
					}  
				}
			}
			
			
		//	$re = str_replace(' ',"\n", $re);
			if(strpos($re, '坐标：') === 0)
				continue;
			else
				$content .= $re."\n";
		}
		return $content;
		
	} 
	
	function getcontent_en($keyword){   
		$re = $this->getextracts_en($keyword); 
		if(preg_match("/REDIRECT/", trim($re))){  
			$index = strpos($re, ' ');
			$index2 = strpos($re, 'This is a redirect', $index + 1);
			if($index2 === false){
				$index2 = strpos($re, ' ', $index + 1);
				if($index2 === false){
					$keyword = substr($re, $index+1);
				}else{
					$keyword = substr($re, $index+1, $index2-$index-1); 
				}
			}
			else
				$keyword = substr($re, $index+1, $index2-$index-1);  
			$re = $this->getextracts_en($keyword); 
		} 
		return $re;
	} 
	
	function getextracts_en($keyword){  
		// 初始化一个 cURL 对象  
		$curl = curl_init();
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, 'http://en.wikipedia.org/w/api.php?action=query&prop=extracts&format=xml&exintro=1&titles='.urlencode($keyword));
		// 设置header
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 设置cURL 参数，时间超时
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		// 运行cURL，请求网页
		$data = curl_exec($curl);
		// 关闭URL请求
		curl_close($curl); 
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~';  
		$re = '';	
		$tmp = $data;
		while(strstr($tmp, '&lt;')!== false){
			$index = strpos($tmp, '&lt;');
			$index2 = strpos($tmp, '&gt;');
			if($index == 0){
				$tmp = trim(substr($tmp, $index2+4));
			}else{
				$re .= trim(substr($tmp, 0, $index));
				$tmp = substr($tmp, $index2+4);
			}  
		}
		$tmp = $re; 
		if(strstr($tmp, '<') !== false)
			$re = '';
		while(strstr($tmp, '<') !== false){
			$index = strpos($tmp, '<'); 
			$index2 = strpos($tmp, '>'); 
			if($index == 0){
				$tmp = trim(substr($tmp, $index2+1));
			}else{
				$re .= trim(substr($tmp, 0, $index)); 
				$tmp = substr($tmp, $index2+1);
			}  
		} 
		if(strlen($tmp) > 0)
			$re = $tmp; 
		return $re;
	} 
	
	function getextracts_zh($keyword){  
		// 初始化一个 cURL 对象  
		$curl = curl_init();
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, 'http://zh.wikipedia.org/w/api.php?action=query&prop=extracts&format=xml&exintro=1&titles='.urlencode($keyword));
		// 设置header
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 设置cURL 参数，时间超时
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		// 运行cURL，请求网页
		$data = curl_exec($curl);
		// 关闭URL请求
		curl_close($curl); 
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~';  
		 
		$re = '';	
		$tmp = $data;
		while(strstr($tmp, '&lt;')!== false){
			$index = strpos($tmp, '&lt;');
			$index2 = strpos($tmp, '&gt;');
			if($index == 0){
				$tmp = trim(substr($tmp, $index2+4));
			}else{
				$re .= trim(substr($tmp, 0, $index));
				$tmp = substr($tmp, $index2+4);
			}  
		} 
		
		$tmp = $re; 
		if(strstr($tmp, '<'))
			$re = '';
		while(strstr($tmp, '<')){
		
			$index = strpos($tmp, '<');
			$index2 = strpos($tmp, '>', $index); 
			$tmp = substr($tmp, 0, $index).substr($tmp, $index2+1);
			 
		}  
		$re = $tmp;  
		 
		return $re;
	} 
}
//$webchat_wikiObj = new webchat_wiki();
//echo $contentStr = $webchat_wikiObj->getextracts_zh('爱情');
?>