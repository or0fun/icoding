<?php
 
//$wechat_trainObj = new wechat_train();
//echo  $wechat_trainObj->getzhanzhan('安庆西','福州'); 
 class wechat_train
 {
	public function getcheci($checi){   
		$curl = curl_init();
		// 设置你需要抓取的URL
		curl_setopt($curl, CURLOPT_URL, 'http://wap.huoche.com/checi/'.$checi);
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
			echo "Oops!这网速也忒慢啦~请再输入一遍~";
		$str = $content;  
		if(strstr($str, "出错啦")){
			return "Sorry,找不到".$checi."列车时刻信息.请确认车次输入正确。\n也可能此次列车已停开。\n也可以试试站站查询，如输入：火车 北京 杭州";
		}
		$index = strpos($str, "<h2>") + 4;	

		$index2 = strpos($str, '<', $index );
		$train = substr($str, $index, $index2-$index);
		$index = strpos($str, '>', $index2);
		$index2 = strpos($str, '<', $index );
		$path = trim(substr($str, $index+1, $index2-$index-1));
		$index = strpos($str, '>', $index2);
		$index = strpos($str, '>', $index+1);
		$index2 = strpos($str, '<', $index );
		$long = trim(substr($str, $index+1, $index2-$index-1));
		$index = strpos($str, '>', $index2);
		$index2 = strpos($str, '<', $index );
		$dur = trim(substr($str, $index+1, $index2-$index-1));
		  
		$re = $train."\n".$path."\n";
		$re .= $long."\n";
		$re .= $dur."\n"; 
		$re .= "站名 到时 开时"."\n"; 
		
		$tt = strpos($str, "<table", $index2); 
		$uu = strpos($str, "table>", $tt+6 ); 
		$str = substr($str, $tt, $uu-$tt);   
		$i = 1;
		while(strstr($str, "<td>")){ 
			$index = strpos($str, "<td>");  
			$index2 = strpos($str, "td>", $index+4 );  
			$tmp = substr($str, $index+4, $index2-$index-6); 
			$str = substr($str, $index2); 
			if(strstr($tmp, ">")){ 
				$tt = strpos($tmp, ">"); 
				$uu = strpos($tmp, "<", $tt); 
				$tmp = substr($tmp, $tt+1, $uu-$tt-1);
			}
			$re .= $tmp; 
			if($i%4 == 0){ 
				$re .="\n"; 
			}else{
				$re .= " ";
			}
			$i++; 
		}
		return $re;	 
	}    
	
	public function getzhanzhan($k1, $k2){ 
	
		$suffix = "\n\n直接回复车次查询时刻表,如输入：T31\n\n";
		$wenwen = "--------------\n试试提问,如输入：\n提问 $k1 到 $k2 ";
		$url = 'http://wap.huoche.com/zhanzhan.php?k1='.urlencode($k1).'&k2='.urlencode($k2); 
	 
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
		$content = curl_exec($curl); 
		// 关闭URL请求
		curl_close($curl); 
		if($content == false)
			echo "Oops!这网速也忒慢啦~请再输入一遍~";

		$str = $content;  
		if(strstr($str, "没有直达列车！")){
			return "不好意思,".$k1."到".$k2."没有直达列车！输入'中转 $k1 $k2'可查询中转方案.有时由于网络因素不能回复，请多试几次或稍后再试。见谅。"
			.$suffix.$wenwen;
		}
		if(strstr($str, "没有找到出发站点")){
			return "不好意思,没有找到列车出发站点,确定你输入的车站名正确!".$suffix.$wenwen;
		}
		if(strstr($str, "没有找到目的站点")){
			return "不好意思,没有找到列车目的站点,确定你输入的车站名正确!".$suffix.$wenwen;
		} 
		$re = "";
		$index = strpos($str, "共有") + 4;	
		$index = strpos($str, '>', $index);
		$index2 = strpos($str, '<', $index );
		$num = trim(substr($str, $index+1, $index2-$index-1));
		if(strlen(trim($num))==0){
			return "Oops!这网速也忒慢啦~请再输入一遍~";
		} 
		$re = "$k1 到 $k2 \n共有 $num 趟列车"."\n\n"; 
		
		$tt = strpos($str, "<table", $index2); 
		$uu = strpos($str, "table>", $tt+6 ); 
		$str = substr($str, $tt, $uu-$tt);    
		while(strstr($str, "<tr")){ 
			$index = strpos($str, "<tr");  
			$index2 = strpos($str, "tr>", $index+3 ); 
			$tmp = substr($str, $index+3, $index2-$index-6); 
			$str = substr($str, $index2); 
			
			if(strstr($tmp, "checi")){
				$index = strpos($tmp, "checi");  
				$index2 = strpos($tmp, "/", $index+6 );  
				$checi = "车次 ".substr($tmp, $index+6, $index2-$index-6);  
				$index = strpos($tmp, "运行时间：");  
				$len = substr($tmp, $index + strlen("运行时间："), 5);
				$len = str_replace(':', '小时', $len)."分";
				$checi .= "\n运行时间:".$len;
				$re .= $checi."\n";
			}
			if(strstr($tmp, "发站：")){
				$index = strpos($tmp, "发站：");  
				$index2 = strpos($tmp, '<td class="dingpiao">', $index+5 );  
				$tmp = substr($tmp, $index, $index2-$index); 
				
				$index = strpos($tmp, "zhan");  
				$index2 = strpos($tmp, "/", $index+5 );  
				$zhan = substr($tmp, $index+5, $index2-$index-5); 
				$tmp = substr($tmp, $index2);
				$index = strpos($tmp, "&nbsp;&nbsp;");  
				$ptime = " ".substr($tmp, $index + strlen("&nbsp;&nbsp;"), 5);
				$re .= $zhan." ".$ptime."开\n";
				$index = strpos($tmp, "zhan");  
				$index2 = strpos($tmp, "/", $index+5 );  
				$zhan = substr($tmp, $index+5, $index2-$index-5); 
				$tmp = substr($tmp, $index2);
				$index = strpos($tmp, "&nbsp;&nbsp;");  
				$ptime = " ".substr($tmp, $index + strlen("&nbsp;&nbsp;"), 5);
				$tmp = substr($tmp, $index + strlen("&nbsp;&nbsp;"));
				$re .= $zhan." ".$ptime."到\n";
				$index = strpos($tmp, "<td>");  
				$piaojia = substr($tmp, $index + 4); 
				$piaojia = str_replace(' ', '', $piaojia);
				$piaojia = str_replace('	', '', $piaojia);
				$piaojia = str_replace('<br/>', "", $piaojia);
				$re .= "票价：\n".$piaojia."\n";
			}  
		}
		return $re.$suffix;	 
	}      
	
	public function getlieche($k1, $k2){ 
	
		$suffix = '【直接回复车次如T31就能查询时刻表哦~】';
		$url = 'http://lieche.huoche.com/zhanzhan.php?k1='.urlencode($k1).'&k2='.urlencode($k2); 
			
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
		$content = curl_exec($curl); 
		// 关闭URL请求
		curl_close($curl); 
		if($content == false)
			return 'Oops!这网速也忒慢啦~请再输入一遍~'.$suffix;

		$str = $content;
		$re = '';  
		if(strstr($str, '中转方案')){ 
			$re = $k1.'到'.$k2.'没有直达列车！以下是中转方案'."\n"; 
			while(strstr($str, '的方案')){
				$index = strpos($str, '的方案'); 
				$index = strpos($str, '>', $index); 
				$index2 = strpos($str, '<', $index );
				$fangan = "【中转方案】\n".trim(substr($str, $index+1, $index2-$index-1))."\n"; //方案
				
				$index = strpos($str, '<strong>', $index2);
				$index2 = strpos($str, '<', $index+8 );
				$haoshi = '总计耗时'.trim(substr($str, $index+8, $index2-$index-8))."\n";  //耗时
					
				$index = strpos($str, '<strong>', $index2 );
				$index2 = strpos($str, '<', $index+8 );
				$checi = trim(substr($str, $index+8, $index2-$index-8));  //车次
					
				$index = strpos($str, '<td>', $index2 );
				$index2 = strpos($str, '<', $index+4 );
				$checi .= trim(substr($str, $index+4, $index2-$index-4))."\n";  //站 
					
				$index = strpos($str, "\">", $index2 );
				$index2 = strpos($str, '<', $index+2 );
				$fadao = trim(substr($str, $index+2, $index2-$index-2))."开 ";  //发时
					
				$index = strpos($str, "\">", $index2 );
				$index2 = strpos($str, '<', $index+2 );
				$fadao .= trim(substr($str, $index+2, $index2-$index-2))."到\n";  //到时
				
				$index = strpos($str, "<strong>", $index2 );
				$index2 = strpos($str, '<', $index+8 );
				$zhongzhuan = trim(substr($str, $index+8, $index2-$index-8));  //中转
				$index = strpos($str, "<span>", $index2 );
				$index2 = strpos($str, '<', $index+6 );
				$zhongzhuan .= trim(substr($str, $index+6, $index2-$index-6))."\n";  //中转 
				 
				$re .= $fangan;
				$re .= $haoshi;
				$re .= $checi;
				$re .= $fadao;
				$re .= $zhongzhuan;
				
				$index = strpos($str, "<strong>", $index2 );
				$index2 = strpos($str, '<', $index+8 );
				$checi = trim(substr($str, $index+8, $index2-$index-8));  //车次
					
				$index = strpos($str, "<td>", $index2 );
				$index2 = strpos($str, '<', $index+4 );
				$checi .= trim(substr($str, $index+4, $index2-$index-4))."\n";  //站 
					
				$index = strpos($str, "\">", $index2 );
				$index2 = strpos($str, '<', $index+2 );
				$fadao = trim(substr($str, $index+2, $index2-$index-2))."开 ";  //发时
					
				$index = strpos($str, "\">", $index2 );
				$index2 = strpos($str, '<', $index+2 );
				$fadao .= trim(substr($str, $index+2, $index2-$index-2))."到\n";  //到时
				 
				$re .= $checi;
				$re .= $fadao; 
				
				$str = substr($str, $index2); 
			}
		}
		else{
			$index = strpos($str, "train_count\">"); 
			$index2 = strpos($str, '<', $index );
			$num = trim(substr($str, $index+13, $index2-$index-13));
			$re = "$k1 到 $k2 共有 $num 趟列车"."\n"; 
			
			$tt = strpos($str, "<tbody"); 
			$uu = strpos($str, "tbody>", $tt+6 ); 
			$str = substr($str, $tt, $uu - $tt); 
		 
			while(strstr($str, "<tr")){ 
				$index = strpos($str, "<tr");  
				$index2 = strpos($str, "tr>", $index+4 ); 
				$tmp = substr($str, $index+4, $index2-$index-4); 
				$str = substr($str, $index2);    //截取需要处理的字符串
				 
				$index = strpos($tmp, "a2\">");  
				$index2 = strpos($tmp, "<", $index+4 );  
				$checi = substr($tmp, $index+4, $index2-$index-4);  //车次
				
				$index = strpos($tmp, "a5'>", $index2);  
				$index2 = strpos($tmp, "<", $index+4 );  
				$zhan1 = substr($tmp, $index+4, $index2-$index-4);  //出发站
				
				$index = strpos($tmp, "a5\">", $index2);  
				$index2 = strpos($tmp, "<", $index+4 );  
				$zhan2 = substr($tmp, $index+4, $index2-$index-4);  //到达站
				
				$index = strpos($tmp, "fashi\" >", $index2);  
				$index2 = strpos($tmp, "<", $index+8 );  
				$zhan1 .= substr($tmp, $index+8, $index2-$index-8).'开';  //出发时间
					
				$index = strpos($tmp, "daoshi\" >", $index2);  
				$index2 = strpos($tmp, "<", $index+9 );  
				$zhan2 .= substr($tmp, $index+9, $index2-$index-9).'到';  //到达时间
					
				$index = strpos($tmp, "trline\">", $index2);  
				$index2 = strpos($tmp, "<", $index+8 );  
				$checi .= "运行时间:".substr($tmp, $index+8, $index2-$index-8);  //运行时间
					 
				$re .= $checi."\n";
				$re .= $zhan1."\n";
				$re .= $zhan2."\n";
			} 
		}
		return $re.$suffix;	 
	}      

}	
?>