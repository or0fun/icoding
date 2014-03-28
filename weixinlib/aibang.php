<?php
//$webchat_aibang = new webchat_aibang(); 
//echo $webchat_aibang->getbustransfer('北京', '大兴','西直门'); 
  
class webchat_aibang
{
	function gethelp()
	{
		return "公交/地铁 线路查询，输入格式：公交 城市 起点 终点\n".
			"如输入："."\n公交 北京 东直门 西直门\n或直接打开http://t.cn/8sVU7Ow查询";
	}
	function getbustransfer($city, $start_addr, $end_addr)
	{
		if (strlen(trim($city)) == 0 || strlen(trim($start_addr)) == 0 
		||strlen(trim($end_addr)) == 0)
		{
			return $this->gethelp();
		}
		$link = "http://openapi.aibang.com/bus/transfer?app_key=f17cbe09837fb6f27fbd295320e607dd&alt=json&count=5".
		"&city=".urlencode($city)."&start_addr=".urlencode($start_addr)."&end_addr=".urlencode($end_addr);
		
		$ch = curl_init();   
		curl_setopt($ch, CURLOPT_URL, $link);   
		curl_setopt($ch, CURLOPT_HEADER, 0);    
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31"); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 4);  
		$content = curl_exec($ch);   
		curl_close($ch);   
		if($content == false)
			return "Oops!这网速也忒慢啦~请再输入一遍~可以直接打开http://baiwanlu.com/t/?p=RQ9查询";
		$Message = json_decode($content, true);
		$buses = $Message['buses']['bus'];
		$c = count($buses);
		if($c == 0)
		{
			return $Message['message']."\n\n".$this->gethelp();
		}
		$re = '';
		for($i = 0; $i < $c; $i++)
		{
			$t = $i + 1;
			$re .= "【路线".$t."】\n";
			$re .= "总距离：".$buses[$i]['dist']."米\n";
			$re .= "总时长：".$buses[$i]['time']."分钟\n";
			$segments = $buses[$i]['segments']['segment'];
			$t = count($segments);
			for($j = 0; $j < $t; $j++)
			{
				$re .= "步行".$segments[$j]['foot_dist']."米至[".$segments[$j]['start_stat']."]\n";				
				$re .= "乘坐[".$segments[$j]['line_name']."]\n";
				$re .= "途经[".$segments[$j]['stats']."]\n";
				$re .= "在[".$segments[$j]['end_stat']."]下车\n";
			}
			if ( $buses[$i]['last_foot_dist']> 0)
			{
				$re .= "步行".$buses[$i]['last_foot_dist']."至终点[$end_addr]\n";
			}
            $re .= "可以直接打开http://baiwanlu.com/t/?p=RQ9查询";
		}
		return $re;
	}
}

?>