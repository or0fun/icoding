<?php

	function bus_get($city, $bus){	
		// 初始化一个 cURL 对象
		$curl = curl_init();
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, 'http://sjz.bus.58.com/x_'.urlencode($bus));
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
		
		$index = strpos($data, '<div class="stop_info">');
		$index2 = strpos($data, '<div class="stop_info">', $index +2);
		$index3 = strpos($data, '<div class="c">', $index2 +2);
		$upline = substr($data, $index, $index2-$index);
		$downline = substr($data, $index2, $index3-$index2);
		$re = "【$bus".'上行】'."\n";
		$index = strpos($upline, '首末车');
		$index2 = strpos($upline, '<', $index);
		$re .= substr($upline, $index, $index2-$index)."\n";
		$s = 0;
		while(strstr($upline, '/">')){
			++$s;
			$index = strpos($upline, '/">');
			$index2 = strpos($upline, '<', $index);
			$re .= $s." ".substr($upline, $index+3, $index2-$index-3)."\n";
			$upline = substr($upline, $index2);
		} 
		$re .= "【$bus".'下行】'."\n";
		$index = strpos($downline, '首末车');
		$index2 = strpos($downline, '<', $index);
		$re .= substr($downline, $index, $index2-$index)."\n";
		$s = 0;
		while(strstr($downline, '/">')){
			++$s;
			$index = strpos($downline, '/">');
			$index2 = strpos($downline, '<', $index);
			$re .= $s." ".substr($downline, $index+3, $index2-$index-3)."\n";
			$upline = substr($downline, $index2);
		} 
		return $re;
	}  
?>