<?php
  
//$webchat_constellationObj = new webchat_constellation();
//echo $contentStr = $webchat_constellationObj->xingzuo('双子'); 

function constellation_isxingzuo($xingzuo){ 
	$xingzuoArray = array('白羊','金牛','双子','巨蟹','狮子','处女','天秤','天蝎',
		'射手','摩羯','水瓶','双鱼', '天平', '天枰', '魔羯', '牧羊','天称');
		
	foreach($xingzuoArray as $key){
		if(strstr($xingzuo, $key)){ 
			return true;
		}
	}
	return false;  
}
	
class webchat_constellation
{	//xingzuo
	function xingzuovs($xing1, $xing2){	
		if(strstr($xing1, "天平")){
			$xing1 = "天秤座";
		} 
		if(strstr($xing1, "魔羯")){
			$xing1 = "摩羯座";
		}
		if(strstr($xing1, "牧羊")){
			$xing1 = "白羊座";
		}
		if(strstr($xing2, "天平")){
			$xing2 = "天秤座";
		} 
		if(strstr($xing2, "魔羯")){
			$xing2 = "摩羯座";
		}
		if(strstr($xing2, "牧羊")){
			$xing2 = "白羊座";
		}
		
		if(!strstr($xing1, "座")){
			$xing1 .= "座";
		}
		if(!strstr($xing2, "座")){
			$xing2 .= "座";
		}
		
		//database operation
		$sql = "SELECT msg,id FROM xingzuo where (xing2='$xing1' and xing1='$xing2') or (xing1='$xing1' and xing2='$xing2')";  
		$mysqlHelperObj = new mysqlHelper();
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];
			$msg = $row['msg'];  
			$id = $row['id']; 
			$sql = "update $table_comm set pcount = pcount+1 where id = '$id'";  
			$mysqlHelperObj->execute($sql); 
			
			$msg = '【仅供娱乐，祝有情人终成眷属】'.$msg;
			return $msg;
		}
		return "";
	} 
	
	public function getxingzuo($xingzuo)
	{
		$xingzuoArray = array('白羊','金牛','双子','巨蟹','狮子','处女','天秤','天蝎',
			'射手','摩羯','水瓶','双鱼', '天平', '天枰', '魔羯', '牧羊','天称');
		
		foreach($xingzuoArray as $key){
			if(strstr($xingzuo, $key)){ 
				$xingzuo = $key;
			}
		} 
		if($xingzuo == '天平'){
			$xingzuo = '天秤';
		} 
		if($xingzuo == '天称'){
			$xingzuo = '天秤';
		}
		if($xingzuo == '天枰'){
			$xingzuo = '天秤';
		}
		if($xingzuo == '魔羯'){
			$xingzuo = '摩羯';
		}
		if($xingzuo == '牧羊'){
			$xingzuo = '白羊';
		} 
		return $xingzuo;
	}
	public function getimage($xingzuo) {	
		$xingzuoArray = array('白羊','金牛','双子','巨蟹','狮子','处女','天秤','天蝎','射手','摩羯','水瓶','双鱼');
		$xingzuoimages = array('♈','♉','♊','♋','♌','♍','♎','♏','♐','♑','♒','♓');
		for($i = 0; $i < 12; $i++) {
			if (strstr($xingzuo, $xingzuoArray[$i])) {
				return $xingzuoimages[$i];
			}
		}
		return '';
	}
	//Chinese to English
	public function getname($xingzuo)
	{ 
		$xingzuoArray = array('白羊','金牛','双子','巨蟹','狮子','处女','天秤','天蝎','射手','摩羯','水瓶','双鱼');
		$xingzuoArray2 = array('aries','taurus','gemini','cancer','leo','virgo','libra','scorpio','sagittarius','capricorn','aquarius','pisces'); 
		if(strstr($xingzuo, '座')){
			$xingzuo = str_replace('座','',$xingzuo);
		}
		if($xingzuo == '天平'){
			$xingzuo = '天秤';
		}
		if($xingzuo == '天枰'){
			$xingzuo = '天秤';
		}
		if($xingzuo == '魔羯'){
			$xingzuo = '摩羯';
		}
		if($xingzuo == '牧羊'){
			$xingzuo = '白羊';
		}
		for($ii = 0; $ii < 12; $ii++){  
			if(strstr($xingzuo, $xingzuoArray[$ii])){  
				return $xingzuoArray2[$ii];
			}
		} 
		return $xingzuoArray2[0];
	}
	
	public function xingzuo($keyword){ 
		$contentStr = '';
		if(preg_match("/^(.*)周运势$/", trim($keyword), $match)){ 
			$contentStr = $this->byweek($this->getxingzuo(trim($match[1]))); 
			if(strlen($contentStr)==0){
				$contentStr = '如若需要查看星座周运势，请在周运势前面加上星座名称，如输入：白羊周运势';
			}
			$contentStr = str_replace('&lt;br&gt;','',$contentStr);
			return $contentStr;
		}  
		else if(preg_match("/^(.*)月运势$/", trim($keyword), $match)){ 
			$contentStr = $this->bymonth($this->getxingzuo(trim($match[1]))); 
			if(strlen($contentStr)==0){
				$contentStr = '如若需要查看星座月运势，请在月运势前面加上星座名称，如输入：白羊月运势';
			}
			$contentStr = str_replace('&lt;br&gt;','',$contentStr);
			return $contentStr;
		}  
		else if(preg_match("/^(.*)年运势$/", trim($keyword), $match)){ 
			$contentStr = $this->byyear($this->getxingzuo(trim($match[1]))); 
			if(strlen($contentStr)==0){
				$contentStr = '如若需要查看星座年运势，请在年运势前面加上星座名称，如输入：白羊年运势';
			}
			$contentStr = str_replace('&lt;br&gt;','',$contentStr);
			return $contentStr; 
		}
		else{
			$contentStr = $this->byday($this->getxingzuo($keyword)); 
		}	

		if(strlen($contentStr)==0){
			$contentStr = '如若需要查看星座周运势，请在周运势前面加上星座名称，如输入：白羊周运势';
		}
		$contentStr = str_replace('&lt;br&gt;','',$contentStr);
		return $contentStr;		
	} 
	
	public function byday($xingzuo)
	{ 
		$msg = $this->fromdb($xingzuo, 'dmsg');
		if(strlen($msg) > 0 )
			return $msg;
		
		$urljs = 'http://vip.astro.sina.com.cn/astro/view/'.$this->getname($xingzuo).'/day/';
		
		// 初始化一个 cURL 对象  
		$curl = curl_init();
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, $urljs);
		// 设置header
		curl_setopt($curl, CURLOPT_HEADER, 0);
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 设置cURL 参数，时间超时
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		// 运行cURL，请求网页
		$content = curl_exec($curl);
		// 关闭URL请求
		curl_close($curl);
		if($content == false)
			return 'Oops!这破网太慢啦，请再试一遍~';
		 
		$re = "【".$xingzuo."座".$this->getimage($xingzuo)."今日运势】\n";
		$words = array('健康指数','商谈指数','幸运颜色','幸运数字','速配星座');
		for($i=0;$i<5;$i++){ 
			$index = strpos($content, $words[$i].'</h4><p>'); 
			$len = strlen($words[$i].'</h4><p>'); 
			$index2 = strpos($content, '<', $index+$len); 
			$re .= $words[$i].':'.substr($content, $index+$len, $index2 - $index - $len)."\n";  
		} 
		
		$index = strpos($content, "lotconts\">");
		$index2 = strpos($content, "</div>",$index);
		$len = strlen("lotconts\">");
		$re .= substr($content, $index+$len, $index2 - $index - $len);
		
		$this->insertdb($xingzuo, 'dmsg', $re);
		
		return $re."\n\n查看周运势，回复\n$xingzuo 周运势\n查看月运势，回复\n$xingzuo 月运势";
	}
	//周运势
	public function byweek($xingzuo)
	{ 
		//连接
		$msg = $this->fromdb($xingzuo, 'wmsg');
		if(strlen($msg) > 0 )
			return $msg;
		
		$urljs = 'http://vip.astro.sina.com.cn/astro/view/'.$this->getname($xingzuo).'/weekly/';
		
		// 初始化一个 cURL 对象  
		$curl = curl_init();
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, $urljs);
		// 设置header
		curl_setopt($curl, CURLOPT_HEADER, 0);
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 设置cURL 参数，时间超时
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		// 运行cURL，请求网页
		$content = curl_exec($curl);
		// 关闭URL请求
		curl_close($curl);
		if($content == false)
			return 'Oops!这破网太慢啦，请再试一遍~';
		//$content = file_get_contents($urljs);  
		$re = "【".$xingzuo."座".$this->getimage($xingzuo)."本周运势】\n";
		
		$index = strpos($content, "notes\">"); 
		$len = strlen("notes\">"); 
		$index2 = strpos($content, '<', $index+$len); 
		$re .= substr($content, $index+$len, $index2 - $index - $len)."\n"; 
		
		$index = strpos($content, '整体运势'); 
		$index = strpos($content, '<p>', $index);  
		$index2 = strpos($content, '</p>', $index+3); 
		$re .= "【整体运势】\n".substr($content, $index+3, $index2 - $index - 3)."\n";  
		
		$index = strpos($content, '爱情运势'); 
		$index = strpos($content, '<em>', $index);  
		$index2 = strpos($content, '<', $index+4); 
		$re .= "【爱情运势】\n".substr($content, $index+4, $index2 - $index - 4)."\n"; 
		$index = strpos($content, '<p>', $index);  
		$index2 = strpos($content, '</p>', $index+3); 
		$re .= substr($content, $index+3, $index2 - $index - 3)."\n"; 
		$index = strpos($content, '<em>', $index);  
		$index2 = strpos($content, '<', $index+4); 
		$re .= substr($content, $index+4, $index2 - $index - 4)."\n"; 
		$index = strpos($content, '<p>', $index);  
		$index2 = strpos($content, '<', $index+3); 
		$re .= substr($content, $index+3, $index2 - $index - 3)."\n"; 
		
		$index = strpos($content, '健康运势'); 
		$index = strpos($content, '<p>', $index);  
		$index2 = strpos($content, '</p>', $index+3); 
		$re .= "【健康运势】\n".substr($content, $index+3, $index2 - $index - 3)."\n"; 

		$index = strpos($content, '工作学业运'); 
		$index = strpos($content, '<p>', $index);  
		$index2 = strpos($content, '</p>', $index+3); 
		$re .= "【工作学业运】\n".substr($content, $index+3, $index2 - $index - 3)."\n"; 		

		$index = strpos($content, '性欲指数'); 
		$index = strpos($content, '<p>', $index);  
		$index2 = strpos($content, '</p>', $index+3); 
		$re .= "【性欲指数】\n".substr($content, $index+3, $index2 - $index - 3)."\n"; 	

		$index = strpos($content, '红心日'); 
		$index = strpos($content, '<p>', $index);  
		$index2 = strpos($content, '<', $index+3); 
		$re .= "【红心日】\n".substr($content, $index+3, $index2 - $index - 3)." "; 
		$index = strpos($content, '>', $index2);  
		$index2 = strpos($content, '<', $index+1); 	
		$re .= substr($content, $index+1, $index2 - $index - 1)."\n"; 	

		$index = strpos($content, '黑梅日'); 
		$index = strpos($content, '<p>', $index);  
		$index2 = strpos($content, '<', $index+3); 
		$re .= "【黑梅日】\n".substr($content, $index+3, $index2 - $index - 3)." "; 
		$index = strpos($content, '>', $index2);  
		$index2 = strpos($content, '<', $index+1); 	
		$re .= substr($content, $index+1, $index2 - $index - 1)."\n"; 				
		 
		$this->insertdb($xingzuo, 'wmsg', $re);
		
		return $re."\n\n查看月运势，回复\n$xingzuo 月运势\n查看年运势，回复\n$xingzuo 年运势"; 
	
	}
	//月运势
	public function bymonth($xingzuo)
	{ 	
		//连接
		$msg = $this->fromdb($xingzuo, 'mmsg');
		if(strlen($msg) > 0 )
			return $msg;
		
		$urljs = 'http://vip.astro.sina.com.cn/astro/view/'.$this->getname($xingzuo).'/monthly/';
		// 初始化一个 cURL 对象  
		$curl = curl_init();
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, $urljs);
		// 设置header
		curl_setopt($curl, CURLOPT_HEADER, 0);
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 设置cURL 参数，时间超时
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		// 运行cURL，请求网页
		$content = curl_exec($curl);
		// 关闭URL请求
		curl_close($curl);
		if($content == false)
			return 'Oops!这破网太慢啦，请再试一遍~';
		//$content = file_get_contents($urljs);  
		$re = "【".$xingzuo."座".$this->getimage($xingzuo)."本月运势】\n";
		
		$index = strpos($content, '整体运势'); 
		$index = strpos($content, '<p>', $index);  
		$index2 = strpos($content, '</p>', $index+3); 
		$re .= "【整体运势】\n".substr($content, $index+3, $index2 - $index - 3)."\n";  
		
		$index = strpos($content, '爱情运势');   
		$index = strpos($content, '<p>', $index);  
		$index2 = strpos($content, '</p>', $index+3); 
		$re .= "【爱情运势】\n".substr($content, $index+3, $index2 - $index - 3)."\n"; 
		 			
		$index = strpos($content, '投资理财运'); 
		$index = strpos($content, '<p>', $index);  
		$index2 = strpos($content, '</p>', $index+3); 
		$re .= "【投资理财运】\n".substr($content, $index+3, $index2 - $index - 3)."\n"; 

		$index = strpos($content, '解压方式'); 
		$index = strpos($content, '<p>', $index);  
		$index2 = strpos($content, '</p>', $index+3); 
		$re .= "【解压方式】\n".substr($content, $index+3, $index2 - $index - 3)."\n"; 		

		$index = strpos($content, '开运小秘诀'); 
		$index = strpos($content, '<p>', $index);  
		$index2 = strpos($content, '</p>', $index+3); 
		$re .= "【开运小秘诀】\n".substr($content, $index+3, $index2 - $index - 3)."\n"; 	
		
		$this->insertdb($xingzuo, 'mmsg', $re);
		return $re;
	}
	//年运势
	public function byyear($xingzuo)
	{ 
		$msg = $this->fromdb($xingzuo, 'ymsg');
		if(strlen($msg) > 0 )
			return $msg;
	
	 	$urljs = 'http://vip.astro.sina.com.cn/astro/view/'.$this->getname($xingzuo).'/year/';
		
		// 初始化一个 cURL 对象  
		$curl = curl_init();
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, $urljs);
		// 设置header
		curl_setopt($curl, CURLOPT_HEADER, 0);
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 设置cURL 参数，时间超时
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		// 运行cURL，请求网页
		$content = curl_exec($curl);
		// 关闭URL请求
		curl_close($curl);
		if($content == false)
			return 'Oops!这破网太慢啦，请再试一遍~';
		//$content = file_get_contents($urljs);  
		$re = "【".$xingzuo."座".$this->getimage($xingzuo)."本年运势】\n";
		
		$index = strpos($content, "notes\">"); 
		$len = strlen("notes\">"); 
		$index2 = strpos($content, '<', $index+$len); 
		$re .= substr($content, $index+$len, $index2 - $index - $len)."\n"; 
		
		$index = strpos($content, '整体概况'); 
		$index = strpos($content, '<p>', $index);  
		$index2 = strpos($content, '</p>', $index+3); 
		$re .= "【整体概况】\n".substr($content, $index+3, $index2 - $index - 3)."\n";  
		
		$index = strpos($content, '功课学业'); 
		$index = strpos($content, '<p>', $index);  
		$index2 = strpos($content, '</p>', $index+3); 
		$re .= "【功课学业】\n".substr($content, $index+3, $index2 - $index - 3)."\n"; 
		
		$index = strpos($content, '工作职场'); 
		$index = strpos($content, '<p>', $index);  
		$index2 = strpos($content, '</p>', $index+3); 
		$re .= "【工作职场】\n".substr($content, $index+3, $index2 - $index - 3)."\n"; 
		
		$index = strpos($content, '金钱理财'); 
		$index = strpos($content, '<p>', $index);  
		$index2 = strpos($content, '</p>', $index+3); 
		$re .= "【金钱理财】\n".substr($content, $index+3, $index2 - $index - 3)."\n"; 
		
		$index = strpos($content, '恋爱婚姻'); 
		$index = strpos($content, '<p>', $index);  
		$index2 = strpos($content, '</p>', $index+3); 
		$re .= "【恋爱婚姻】\n".substr($content, $index+3, $index2 - $index - 3)."\n";    		
		 
		$this->insertdb($xingzuo, 'ymsg', $re); 
			
		return $re; 
	}
	
	public function fromdb($name, $type){
		  
		$msg = '';
		date_default_timezone_set('PRC'); 
		if($type == 'ymsg'){
			$t = date('Y');
			$sql="SELECT $type as msg FROM astrological where name = '$name' and y ='$t'";  
		}
		else if($type == 'mmsg'){
			$t = date('n');
			$sql="SELECT $type as msg FROM astrological where name = '$name' and m ='$t'";  
		}
		else if($type == 'wmsg'){ 
			$z = date('z');
			$t = date('w');
			$sql="SELECT $type as msg FROM astrological where name = '$name' and w < $t  and wd < $z - 7";  
		}
		else if($type == 'dmsg'){
			$t = date('z');
			$sql="SELECT $type as msg FROM astrological where name = '$name' and d ='$t'";  
		}
		$mysqlHelperObj = new mysqlHelper();
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];
			$msg = $row['msg'];
		}
		return $msg;
	}
	
	public function insertdb($name, $type, $msg){
		date_default_timezone_set('PRC'); 
		if($type == 'ymsg'){
			$t = date('Y');
			$sql="update astrological set $type = '$msg', y='$t' where name = '$name'"; 
		}
		else if($type == 'mmsg'){
			$t = date('n');
			$sql="update astrological set $type = '$msg', m='$t' where name = '$name'"; 
		}
		else if($type == 'wmsg'){ 
			$z = date('z');
			$t = date('w');
			$sql="update astrological set $type = '$msg', w='$t', wd='$z' where name = '$name'"; 
		}
		else if($type == 'dmsg'){
			$t = date('z');
			$sql="update astrological set $type = '$msg', d='$t' where name = '$name'"; 
		}
		$mysqlHelperObj = new mysqlHelper();
		return $mysqlHelperObj->execute($sqlstr);
	}
}
?>