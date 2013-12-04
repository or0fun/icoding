<?php
$wechat_weatherObj = new wechat_weather();
//echo $wechat_weatherObj->getweatherbycode('101030100'); 
//echo  $wechat_weatherObj->getairbycity('101030100');
class wechat_weather
{
	public function getweather($fromuser, $keyword)
	{							
		$vowels = array('预报','情况','气温','温度','天气', '：', ':', ',', "'", "\"", ';', '-', '+', '明天','的','未来','三天','两天','四天','一星期','七天','六天','五天','1');
		$cityname = str_replace($vowels, '', $keyword);  
		$cityname = trim($cityname);  
		$contentStr = '';
		if(strlen($cityname) == 0) 
			$weather = $this->getweatherbycode($this->getcodebyuser($fromuser));
		else  
			$weather = $this->getweatherbycity($cityname);
		if(strlen($weather) == 0){ 
			if(strlen($cityname) == 0)
				$contentStr = "亲爱的，可不可以在天气前面加上城市名称，如输入：杭州天气";
			else
				$contentStr = "亲爱的，暂时找不到'".$cityname."'天气预报哦，试试县级或市级城市，用全称，格式如:杭州天气";
		}else{
			//$contentStr = $weather."\nhttp://baiwanlu.com/w.htm";
			$weather = str_replace("转", " 转 ", $weather);
			$weather = str_replace("晴", "☀", $weather);
			$weather = str_replace("雪", "⛄", $weather);
			$weather = str_replace("多云", "☁", $weather);
			$weather = str_replace("阴", "☁", $weather);
			$weather = str_replace("雨", "☔", $weather);
			$contentStr = $weather;//."\n\n发送位置就可以查天气";
		} 
		$contentStr .= "\n\n-------------\n查空气，如输入:\n杭州空气";
		return $contentStr;
		
	}
	
	public function getweatherbylocation($label,$location_X, $location_Y)
	{	
		$contentStr = ''; 
		if(strlen($label) == 0 || strstr($label, '中国') === false){ 
			$code = $this->getcitycode($location_X, $location_Y);  
			if($code == 0){
				$contentStr = "亲爱的，暂时找不到该地区的天气预报哦，试试别的地区";
			}else{			
				$weather = $this->getweatherbycode($code); 
				if(strlen($weather) == 0){ 
					$contentStr = "亲爱的，暂时找不到该地区的天气预报哦，试试别的地区";
				}else{
					$contentStr = $weather;//"\nhttp://baiwanlu.com/w.htm"; 
				}
			}
		}
		else{
			$code = $this->getcodebylocation($label,$location_X, $location_Y); 
			$weather = $this->getweatherbycode($code); 
			if(strlen($weather) == 0){ 
				$contentStr = "亲爱的，暂时找不到该地区的天气预报哦，试试别的地区";
			}else{
				$contentStr = $weather;//."\nhttp://baiwanlu.com/w.htm"; 
			}
		}
		$contentStr .= "\n\n-------------\n查空气，如输入:\n杭州空气";
		return $contentStr; 
	}
	
	public function getair($keyword)
	{ 
		$cityname = str_replace('空气', '', $keyword);  
		$cityname = trim($cityname);  
		$contentStr = '';
		if(strlen($cityname) >0){
			$air = $this->getairbycity($cityname);
			if(strlen($air) == 0){ 
				$contentStr = "亲爱的，暂时找不到'".$cityname."'的空气数据哦，试试别的城市吧";
			}else{
				$contentStr = $air;
			}
		}else{									
			$contentStr = '亲，我太笨，看不懂你的输入哦, 查空气数据的话输入地名+空气就可以啦~如输入:杭州空气';
		}
		$contentStr .= "\n".'<a href="http://baiwanlu.com/air.htm">了解PM2.5和AQI</a>';
		
		return $contentStr;
	}
	
	public function getcitycode($Location_X, $Location_Y){  
		// 初始化一个 cURL 对象  
		$curl = curl_init();
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, 'http://api.map.baidu.com/geocoder?output=json&location='.$Location_X.','.$Location_Y.'&key=e34dc472e7e12902b88b8a9137b23da8');
		// 设置header
		curl_setopt($curl, CURLOPT_HEADER, 0);
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 设置cURL 参数，时间超时
		curl_setopt($curl, CURLOPT_TIMEOUT, 2);
		// 运行cURL，请求网页
		$data = curl_exec($curl);
		// 关闭URL请求
		curl_close($curl); 
		if($data == false)
			return "Oops!这破网太慢啦，请再试一遍~"; 
		$Message = json_decode($data, true);  
		if($Message['status'] == 'OK'){   
			$code = $this->getcodebymessage($Message['result']['addressComponent']['district']); 
			if(strlen($code) > 0)
				return $code; 
			$code = $this->getcodebymessage($Message['result']['addressComponent']['city']); 
			if(strlen($code) > 0)
				return $code; 
		}
		return 0;
	} 	 
	public function getcodebymessage($cityname)
	{		 
		$code = $this->getcode($cityname);
		if(strlen($code) == 0)
		{	  
			if(str_replace("市","",$cityname) != $cityname){
				$code  = $this->getcode(str_replace("市","",$cityname));  
			} 
		}   
		if(strlen($code) == 0)
		{	  
			if(str_replace("县","",$cityname) != $cityname){
				$code = $this->getcode(str_replace("县","",$cityname)); 
			}
		}
		if(strlen($code) == 0)
		{	  
			if(str_replace("区","",$cityname) != $cityname){
				$code = $this->getcode(str_replace("区","",$cityname)); 
			}
		}
		if(strlen($code) == 0)
		{	 
			return "";
		}    
		return $code;
	}
	
	public function getcodebylocation($label,$Location_X, $Location_Y){  
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "cities"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320";   
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn);	 
			mysql_query("set names 'utf8'");
			if(preg_match('/市(.*)县/',$label,$result)){
				$cityname = $result['1'];
				if(mb_strlen($cityname,'utf-8') == 1)
					$cityname .= '县';
				$sql = "select code from $table_comm where city like '%$cityname%'"; 
				if($result = mysql_query($sql,$conn)){
					if($row = mysql_fetch_array($result)){
						$code = $row["code"];
						mysql_close($conn);
						return $code;
					} 
				}
			}
			if(preg_match('/市(.*)区/',$label,$result)){
				$cityname = $result['1'];
				if(mb_strlen($cityname,'utf-8') == 1)
					$cityname .= '区';
				$sql = "select code from $table_comm where city like '%$cityname%'"; 
				if($result = mysql_query($sql,$conn)){
					if($row = mysql_fetch_array($result)){
						$code = $row["code"];
						mysql_close($conn);
						return $code;
					} 
				}
			} 
			if(preg_match('/市(.*)市/',$label,$result)){
				$cityname = $result['1'];
				if(mb_strlen($cityname,'utf-8') == 1)
					$cityname .= '市';
				$sql = "select code from $table_comm where city like '%$cityname%'"; 
				if($result = mysql_query($sql,$conn)){
					if($row = mysql_fetch_array($result)){
						$code = $row["code"];
						mysql_close($conn);
						return $code;
					} 
				}
			} 
			if(preg_match('/区(.*)市/',$label,$result)){
				$cityname = $result['1'];
				if(mb_strlen($cityname,'utf-8') == 1)
					$cityname .= '市';
				$sql = "select code from $table_comm where city like '%$cityname%'"; 
				if($result = mysql_query($sql,$conn)){
					if($row = mysql_fetch_array($result)){
						$code = $row["code"];
						mysql_close($conn);
						return $code;
					} 
				}
			} 
			if(preg_match('/省(.*)市/',$label,$result)){
				$cityname = $result['1'];
				if(mb_strlen($cityname,'utf-8') == 1)
					$cityname .= '市';
				$sql = "select code from $table_comm where city like '%$cityname%'"; 
				if($result = mysql_query($sql,$conn)){
					if($row = mysql_fetch_array($result)){
						$code = $row["code"];
						mysql_close($conn);
						return $code;
					} 
				}
			} 
			if(preg_match('/国(.*)市/',$label,$result)){
				$cityname = $result['1'];
				if(mb_strlen($cityname,'utf-8') == 1)
					$cityname .= '市';
				$sql = "select code from $table_comm where city like '%$cityname%'"; 
				if($result = mysql_query($sql,$conn)){
					if($row = mysql_fetch_array($result)){
						$code = $row["code"];
						mysql_close($conn);
						return $code;
					} 
				}
			} 
			if(preg_match('/区(.*)$/',$label,$result)){
				$cityname = $result['1'];
				$sql = "select code from $table_comm where city like '%$cityname%'"; 
				if($result = mysql_query($sql,$conn)){
					if($row = mysql_fetch_array($result)){
						$code = $row["code"];
						mysql_close($conn);
						return $code;
					} 
				}
			} 
			$cityname = g_getcityname($Location_X, $Location_Y);
			if($result = mysql_query($sql,$conn)){
				$sql = "select code from $table_comm where city like '%$cityname%'"; 
				if($row = mysql_fetch_array($result)){
					$code = $row["code"];
					mysql_close($conn);
						return $code;
				} 
			} 
			mysql_close($conn);
		}
		return 0;
	}
	
	public function getcode($cityname){  
	 
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "cities"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320";  
		$cityname = str_replace("+","",trim($cityname));
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn);	 
			mysql_query("set names 'utf8'");
			$sql = "select code from $table_comm where city = '$cityname'";  
			if($result = mysql_query($sql,$conn)){
				if($row = mysql_fetch_array($result)){
					$code = $row["code"]; 
					mysql_close($conn);
					return $code;
				} 
			}
			mysql_close($conn);
		}
		return "";
	}
	
	public function getcodebyuser($fromuser){  
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "cities"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320";   
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn);	 
			mysql_query("set names 'utf8'"); 
			$sql = "select code from $table_comm where city in (select city from users where user='$fromuser')";  
			if($result = mysql_query($sql,$conn)){
				if($row = mysql_fetch_array($result)){
					$code = $row["code"];
					mysql_close($conn);
					return $code;
				} 
			}
			mysql_close($conn);
		}
		return "";
	}
	 
	public function getweatherbycity($cityname){ 
		$code = $this->getcode($cityname);
		if(strlen($code) == 0)
		{	  
			if(str_replace("市","",$cityname) != $cityname){
				$code  = $this->getcode(str_replace("市","",$cityname));  
			} 
		}   
		if(strlen($code) == 0)
		{	  
			if(str_replace("县","",$cityname) != $cityname){
				$code = $this->getcode(str_replace("县","",$cityname)); 
			}
		}
		if(strlen($code) == 0)
		{	  
			if(str_replace("区","",$cityname) != $cityname){
				$code = $this->getcode(str_replace("区","",$cityname)); 
			}
		}
		if(strlen($code) == 0)
		{	 
			return "";
		}    
		return $this->getweatherbycode($code);
	}
	 
	public function getweatherbycode($code){
		$capitals = array("101010100"/*北京*/,"101030100"/*天津*/,"101210101"/*杭州*/,"101020100"/*上海*/,
		"101200101"/*武汉*/,"101090101"/*石家庄*/,"101060101"/*长春*/,"101070201"/*大连*/,"101230201"/*厦门*/,
		"101050101"/*哈尔滨*/,"101040100"/*重庆*/,"101110101"/*西安*/,"101230101"/*福州*/,"101130101"/*乌鲁木齐*/,
		"101150101"/*西宁*/,"101160101"/*兰州*/,"101140101"/*拉萨*/,"101290101"/*昆明*/,"101300101"/*南宁*/,
		"101260101"/*贵阳*/,"101310101"/*海口*/,"101280101"/*广州*/,"101170101"/*银川*/,"101080101"/*呼和浩特*/,
		"101070101"/*沈阳*/,"101240101"/*南昌*/,"101190101"/*南京*/,"101120101"/*济南*/,"101220101"/*合肥*/,
		"101180101"/*郑州*/,"101250101"/*长沙*/,"101100101"/*太原*/,"101270101"/*成都*/);
		//foreach($capitals as $key){
		//	if(strstr($code, $key)){ 
		//		return $this->getweatherbycode2($code);
		//	}
		//}
		$weekdays = array("周日","周一","周二","周三","周四","周五","周六");  
		if(strlen($code) == 0 || $code == 0)
		{	 
			return "";
		}    		
		// 初始化一个 cURL 对象  
		$curl = curl_init();
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, 'http://m.weather.com.cn/data/'.$code.'.html');
		// 设置header
		curl_setopt($curl, CURLOPT_HEADER, 0);
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 设置cURL 参数，时间超时
		curl_setopt($curl, CURLOPT_TIMEOUT, 3);
		// 运行cURL，请求网页
		$data = curl_exec($curl);
		// 关闭URL请求
		curl_close($curl);
		if($data == false)
			return "Oops!这破网太慢啦，请再试一遍~"; 
		$Message = json_decode($data, true); 
		
		date_default_timezone_set('PRC');
		$weeknum = date('w');
		switch($Message["weatherinfo"]["week"]){
			case '星期日':
			$weeknum = 0;
			break;
			case '星期一':
			$weeknum = 1;
			break;
			case '星期二':
			$weeknum = 2;
			break;
			case '星期三':
			$weeknum = 3;
			break;
			case '星期四':
			$weeknum = 4;
			break;
			case '星期五':
			$weeknum = 5;
			break;
			case '星期六':
			$weeknum = 6;
			break;
		}
		$weather = "【今日预报】\n".$Message["weatherinfo"]["city"].'，'.$Message["weatherinfo"]["temp1"].'，'.$Message["weatherinfo"]["weather1"].'，'
		.$Message["weatherinfo"]["wind1"]."，紫外线".$Message["weatherinfo"]["index_uv"]
		."，舒适指数 ".$Message["weatherinfo"]["index_co"]."，".$Message["weatherinfo"]["index_d"];
		
		
		// 初始化一个 cURL 对象  
		$curl = curl_init();
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, 'http://www.weather.com.cn/data/sk/'.$code.'.html');
		// 设置header
		curl_setopt($curl, CURLOPT_HEADER, 1);
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 设置cURL 参数，时间超时
		curl_setopt($curl, CURLOPT_TIMEOUT, 1);
		// 运行cURL，请求网页
		$data = curl_exec($curl);
		// 关闭URL请求
		curl_close($curl);
		if($data !== false){ 
			$index = strpos($data, '{');
			$data = substr($data, $index, strlen($data)-2); 
			$Message2 = json_decode($data, true);  
			  
			$weather .= "\n【天气实况】\n发布时间:".$Message2["weatherinfo"]["time"]."\n气温".$Message2["weatherinfo"]["temp"].'°C，'
			.$Message2["weatherinfo"]["WD"].$Message2["weatherinfo"]["WS"].'，湿度'.$Message2["weatherinfo"]["SD"];
		}
		$weather .= "\n———未来五天———\n"
		."【".$weekdays[($weeknum+1)%7]."】".$Message["weatherinfo"]["weather2"].", ".$Message["weatherinfo"]["temp2"].", ".$Message["weatherinfo"]["wind2"]."\n"
		."【".$weekdays[($weeknum+2)%7]."】".$Message["weatherinfo"]["weather3"].", ".$Message["weatherinfo"]["temp3"].", ".$Message["weatherinfo"]["wind3"]."\n"
		."【".$weekdays[($weeknum+3)%7]."】".$Message["weatherinfo"]["weather4"].", ".$Message["weatherinfo"]["temp4"].", ".$Message["weatherinfo"]["wind4"]."\n"
		."【".$weekdays[($weeknum+4)%7]."】".$Message["weatherinfo"]["weather5"].", ".$Message["weatherinfo"]["temp5"].", ".$Message["weatherinfo"]["wind5"]."\n"
		."【".$weekdays[($weeknum+5)%7]."】".$Message["weatherinfo"]["weather6"].", ".$Message["weatherinfo"]["temp6"].", ".$Message["weatherinfo"]["wind6"];
		
		return $weather; 
	}
	
	public function getCitypinyin($city){
		//连接
 		$hostname_conn = "mysql1403.ixwebhosting.com"; 
 		$port_conn = "3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "cityair"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320";   
		
		$mysqli = mysqli_init();
		if (!$mysqli) {
			return '';
		} 
		if (!$mysqli->options(MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 0')) {
			return '';
		}

		if (!$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 0)) {
			return '';
		}

		if (!$mysqli->real_connect($hostname_conn,$username_conn,$password_conn,$database_conn,$port_conn)) {
			return '';
		} 
		$result=$mysqli->query("set names 'utf8'");
		$query="select pinyin from cityair where city='$city'";
		$result = $mysqli->query($query);
		if (!$result) {
			return '';
		} 
		while($row = $result->fetch_object( )) {
			return $row->pinyin;
		}
		return "";
		
	}
	
	public function getairbycity($city){// 初始化一个 cURL 对象 
	
		$pinyin = $this->getCitypinyin($city);
	    //$pinyin = "wuhan";
		if(strlen($pinyin) == 0){
			return 'Sorry.暂时没有城市'.$city.'的空气数据。请试试其他城市。';
		}
 		$curl = curl_init();
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, 'http://m.pm2d5.com/pm/'.$pinyin.'.html');//http://192.168.11.2:9055/httpproxy/id=1359957010
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
			return "Oops!这破网太慢啦，请再试一遍~";
  
		$result = '';
		$index = strpos($data, '<strong>');
		$index2 = strpos($data, '</strong>', $index);
		$title = substr($data, $index + 8, $index2 - $index - 8);  
		
		$index = strpos($data, '首要污染物', $index2);
		$index2 = strpos($data, '<font', $index);
		$aqi = '';
		if ($index !== false){
			$aqi = substr($data, $index, $index2 - $index); 
			$aqi = str_replace('<br />', "\n", $aqi); 
			$aqi = str_replace('&nbsp;', "\n", $aqi); 
		}
		 
		$index = strpos($data, '更新：', $index2);
		$index2 = strpos($data, '<', $index);
		$updatetime = substr($data, $index, $index2 - $index); 
		
		$result .= $title."\n".$aqi."\n".$updatetime."\n";
		$words = array('各监测站点实时数据', '最近一周空气质量指数' );
		$i = 0;
		while(strstr($data, '<ul class="list2">')){
			$index = strpos($data, '<ul class="list2">');
			$index2 = strpos($data, '</ul>', $index);
			$list = substr($data, $index, $index2 - $index);		
			$data = substr($data, $index2);
			
			$station = $words[$i]."\n";
			while(strstr($list, '<li class="clear">')){ 
				$index = strpos($list, '<li class="clear">');
				$index2 = strpos($list, '</li>', $index);
				$tmp = substr($list, $index, $index2-$index);
				$list = substr($list, $index2); 
			  
				while(strstr($tmp, '<')){
					$index = strpos($tmp, '<'); 
					$index2 = strpos($tmp, '>', $index);  
					$tmp = substr($tmp, 0, $index).substr($tmp, $index2 + 1); 
				}
				$station .= $tmp;
				$station .= "\n"; 
			}   
			$result .= $station."\n";
			$i++; 
		}
		 
		return $result; 
	}
 
 
	public function getweatherbycode2($code){ 
		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, 'http://www.weather.com.cn/weather/'.$code.'.shtml');
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		$data = curl_exec($curl);
		curl_close($curl);
		if($data == false)
			return "Oops!这破网太慢啦，请再试一遍~"; 
		$re = '';
		
		//<!--Date start-->
		$index = strpos($data, '今天是');
		$index2 = strpos($data, '<', $index);
		$date = str_replace("\n",'', substr($data, $index, $index2 - $index))."\n";
		//$re .= $date;
		
		//<!--Pic start-->
		$index = strpos($data, '<div class="weatherTopleft" >');
		$index = strpos($data, '<h1>', $index);
		$index2 = strpos($data, '<span>', $index);
		$index3 = strpos($data, '</span>', $index);
		$city = str_replace("\n",'', substr($data, $index + 4, $index2 - $index - 4).
		substr($data, $index2 + 6, $index3 - $index2 - 6))."\n"; 
		$re .=  $city;
		
		//日出日落
		$index = strpos($data, '<dt><a>', $index3);
		$index2 = strpos($data, '</a>', $index);
		$sunset = substr($data, $index + 7, $index2 - $index - 7)."\n";
		
		$index = strpos($data, ';">', $index2);
		$index2 = strpos($data, '<', $index);
		$sunset .= substr($data, $index + 3, $index2 - $index - 3)."-"; 
		
		$index = strpos($data, ';">', $index2);
		$index2 = strpos($data, '<', $index);
		$sunset .= substr($data, $index + 3, $index2 - $index - 3)."\n";		
		//$re .=  $sunset;
				
		//天气		
		$index = strpos($data, '【天气综述】</span>&nbsp;', $index2);
		if ( $index > 0){
			$index = strpos($data, '：', $index);
			$len = strlen('：');
			$index2 = strpos($data, '<', $index + $len);
			$weather = "【天气综述】\n".substr($data, $index + $len, $index2 - $index - $len)."\n";	
			$re .=  $weather; 
		}
		
		$index = strpos($data, '【最新天气实况】</span>', $index2); 
		if ( $index > 0){
			$len = strlen('【最新天气实况】</span>');
			$index2 = strpos($data, '<', $index + $len);
			$weather = "【最新天气实况】\n".substr($data, $index + $len, $index2 - $index - $len)."\n";
			$re .=  $weather;
        }			
		
		//逐6小时预报
		$index = strpos($data, '<h1 class="weatheH1">', $index2); 
		$len = strlen('<h1 class="weatheH1">');
		$index2 = strpos($data, '<', $index + $len);
		$weather6h = str_replace("\n",'', substr($data, $index + $len, $index2 - $index - $len))."\n";
		$weather6h = str_replace("    ",'', $weather6h);
		$weather6h = str_replace(" (","\n(", $weather6h);
		$weather6h = str_replace("&nbsp;",' ', $weather6h);
		$index2 = strpos($data, '</div>', $index); 
		$tmp = substr($data, $index, $index2 - $index);
 
		while(strstr($tmp, '<table')){
			$_index = strpos($tmp, '<table'); 
			$_index2 = strpos($tmp, '</table>', $_index); 
			$_tmp = substr($tmp, $_index, $_index2 - $_index); 
			$tmp = substr($tmp, $_index2);
			
			$_index = strpos($_tmp, '<th>'); 
			$_index2 = strpos($_tmp, '<', $_index + 4);
			$weather6h .= "【".substr($_tmp, $_index + 4, $_index2 - $_index - 4)."】\n";//时间		
			$_index = strpos($_tmp, '_blank">', $_index2); 
			$_index2 = strpos($_tmp, '<', $_index + 8);
			$weather6h .= str_replace("\n",'', substr($_tmp, $_index + 8, $_index2 - $_index - 8))."\n";	//天气
			$_index = strpos($_tmp, '_blank">', $_index2); 
			$_index2 = strpos($_tmp, '<', $_index + 8);
			$weather6h .= str_replace("\n",'', substr($_tmp, $_index + 8, $_index2 - $_index - 8))."~";	//温度
			$_index = strpos($_tmp, '_blank">', $_index2); 
			$_index2 = strpos($_tmp, '<', $_index + 8);
			$weather6h .= str_replace("\n",'', substr($_tmp, $_index + 8, $_index2 - $_index - 8))."\n";
			$_index = strpos($_tmp, '_blank">', $_index2); 
			$_index2 = strpos($_tmp, '<', $_index + 8);
			$weather6h .= str_replace("\n",'', substr($_tmp, $_index + 8, $_index2 - $_index - 8))."\n";//风向
			$_index = strpos($_tmp, '<span>', $_index2);  
			if( $_index > 0){
				$_index2 = strpos($_tmp, '<', $_index + 6);
				$weather6h .= substr($_tmp, $_index + 6, $_index2 - $_index - 6)."\n";//降水量
			}
			$weather6h = str_replace('        ','', $weather6h);
		}
		
		$re .=  "-------------\n".$weather6h; 
		 
		$weatherfuture = ''; 
		
		for($i = 2; $i < 7; $i++){
			$index = strpos($data, 'day '.$i.'-->', $index2); 
			$index = strpos($data, '<table', $index); 
			$index2 = strpos($data, '</table>', $index);
			$tmp = substr($data, $index + 7, $index2 - $index - 7);//未来天气  
		
			$ii = 0;
			while(strstr($tmp, '<tr>')){
				$_index = strpos($tmp, '<tr>'); 
				$_index2 = strpos($tmp, '</tr>', $_index);
				$_tmp = substr($tmp, $_index + 4, $_index2 - $_index - 4)."\n";//未来天气
				$tmp = substr($tmp, $_index2);
				while(strstr($_tmp, '<')){
					$__index = strpos($_tmp, '<'); 
					$__index2 = strpos($_tmp, '>', $__index); 
					$tt = trim(str_replace("\n", " ", substr($_tmp, 0, $__index)));
					if ($ii == 0 && strlen(trim($tt)) > 0){ 
						$tt = '【'.$tt.'】*';
						$ii = 1;
					}else{
						$tt .= ' ';
					}
					$_tmp = $tt.trim(str_replace("\n", " ", substr($_tmp, $__index2 + 1))); 
				}
				$weatherfuture .= str_replace("*", "\n", $_tmp)."\n"; 
			}
		}
		$index = strpos($data, '<!--day 7-->', $index2); 
		$index = strpos($data, '<table', $index); 
		$index2 = strpos($data, '</table>', $index);
		$tmp = substr($data, $index + 7, $index2 - $index - 7);//未来天气 
		$index = strpos($tmp, '<script'); 
		$index2 = strpos($tmp, 'noscript>', $index); 
		$tmp = substr($tmp, 0, $index).substr($tmp, $index2 + 9);
		
		$ii = 0;
		while(strstr($tmp, '<tr>')){
			$_index = strpos($tmp, '<tr>'); 
			$_index2 = strpos($tmp, '</tr>', $_index);
			$_tmp = substr($tmp, $_index + 4, $_index2 - $_index - 4)."\n";//未来天气
			$tmp = substr($tmp, $_index2);
			while(strstr($_tmp, '<')){
				$__index = strpos($_tmp, '<'); 
				$__index2 = strpos($_tmp, '>', $__index); 
				$tt = trim(str_replace("\n", " ", substr($_tmp, 0, $__index)));
				if ($ii == 0 && strlen(trim($tt)) > 0){
					$tt = "【".$tt."】*";
					$ii = 1;
				}else{
					$tt .= ' ';
				}
				$_tmp = $tt.trim(str_replace("\n", " ", substr($_tmp, $__index2 + 1))); 
			}
			//$weatherfuture .= str_replace("*", "\n", $_tmp)."\n"; 
		}
		$re .=  "---未来5天天气---\n".$weatherfuture; 
		return $re;
	}
}
?>