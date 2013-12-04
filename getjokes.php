<?php
	function insertkepumsg()
	{
		$msg = array("");
    	$c = count($msg);
		for($i = 0; $i < $c; $i++)
		{
			insertkepu($msg[$i]);
		}
	}
	//获取微英语
	function getEnglish()
	{
		date_default_timezone_set('PRC');
		for($i = 1000; $i < 1000;$i++)
		{
			$dateStr = date("Y-m-d",strtotime("-".$i." day"));
			$url = 'http://xue.youdao.com/w?method=tinyEngData&date='.$dateStr;
			$curl = curl_init(); 
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);	
			curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31"); 
		 
			$content = curl_exec($curl);
			curl_close($curl);
			if($content == false)
				return 'Oops!这破网太慢啦，请再试一遍~'; 
			
			$index = strpos($content, 'sen:"');	
			if ($index == false)
			{
				continue;
			}
			$index2 = strpos($content, '"', $index + 6);
			$sen = substr($content, $index + 5, $index2 - $index - 5);			
			
			$index = strpos($content, 'trans:"');	
			if ($index == false)
			{
				continue;
			}	
			$index2 = strpos($content, '"', $index + 8);
			$trans = substr($content, $index + 7, $index2 - $index - 7);
			
			insertEnglish($sen, $trans);
			echo $sen."\n".$trans;
		} 
	}
	function getkepu()
	{
		echo 'array(';
		for($i = 40; $i < 60;$i++)
		{
			echo '"';
			$url = 'http://www.chazidian.com/kepu_'.$i."/";
			$curl = curl_init(); 
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);	
			curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31"); 
		
			//curl_setopt($curl, CURLOPT_TIMEOUT, 4);
			$content = curl_exec($curl);
			curl_close($curl);
			if($content == false)
				return 'Oops!这破网太慢啦，请再试一遍~'; 
			
			$index = strpos($content, '<div class="article_title">');
			$index = strpos($content, '<h1>', $index);			
			$index2 = strpos($content, '</h1>', $index);
			$title = substr($content, $index + 4, $index2 - $index - 4);
			
			$index = strpos($content, '<div class="article_detail">');
			$index = strpos($content, '>', $index);			
			$index2 = strpos($content, '<', $index + 1);	
			$detail = substr($content, $index + 1, $index2 - $index - 1);
			
			//insertkepu($title."\n".$detail);
			echo $title."\\n".$detail;
			echo '",';
		} 
		echo ')';
	}
	function getjoke($num){
		for($i = 0; $i <$num; ){
			$xml = simplexml_load_file('http://www.djdkx.com/open/baidu'); 
			$value = $xml->item->display->content1;
			$vowels = array("&nbsp;", "<br/>","\s","\t"," ");	
			$value = str_replace($vowels, "", $value); 
			$ptype = $xml->item->display->link[1]->attributes()->linkcontent; 
			if(strpos($value, '...') == false){ 
 				inserttable($value,  $ptype);
				$i++;
			} 
		}
	}
	function getjzw(){  
		for($i = 65; $i < 74;$i++)
		{
			$url = 'http://www.2345.com/jzw/'.$i.'.htm';
			$curl = curl_init(); 
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_TIMEOUT, 4);
			$content = curl_exec($curl);
			curl_close($curl);
			if($content == false)
				return 'Oops!这破网太慢啦，请再试一遍~'; 
			$content = mb_convert_encoding($content, "UTF-8", "GBK"); 
			$index2 = 0;
			while(strstr($content, 'color="#008080">'))
			{
				$index = strpos($content, 'color="#008080">');
				$index2 = strpos($content, '<', $index);
				$re = substr($content, $index + 16, $index2 - $index - 16)."\n";
				$index = strpos($content, "MM_popupMsg('", $index2);
				$index2 = strpos($content, "'", $index + 14);
				$re .= substr($content, $index + 13, $index2 - $index - 13);
				$content = substr($content, $index2);
				$re = str_replace('&nbsp;',' ',$re);
				inserttablejzw($re);
			}
			while(strstr($content, 'color=#008080>'))
			{
				$index = strpos($content, 'color=#008080>');
				$index2 = strpos($content, '<', $index);
				$re = substr($content, $index + 14, $index2 - $index - 14)."\n";
				$index = strpos($content, "MM_popupMsg('", $index2);
				$index2 = strpos($content, "'", $index + 14);
				$re .= substr($content, $index + 13, $index2 - $index - 13);
				$content = substr($content, $index2);
				$re = str_replace('&nbsp;',' ',$re);
				inserttablejzw($re);
			}
		} 
	}
	function getjokes($num) {
		for($i = 0; $i <=$num; $i++){
			$curl = curl_init(); 
			curl_setopt($curl, CURLOPT_URL, 'http://www.haha365.com/xy_joke/index_'.$i.'.htm'); 
			curl_setopt($curl, CURLOPT_HEADER, 0); 
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($curl, CURLOPT_TIMEOUT, 4); 
			$data = curl_exec($curl);  
			curl_close($curl);
			if($data == null)
				continue;
			while(strstr($data, '<div id="endtext">')){
				$index = strpos($data, '<div id="endtext">');
				$index2 = strpos($data, '</div>', $index);
				$len = strlen('<div id="endtext">');
				$joke = substr($data, $index + $len, $index2 - $index - $len);
				$joke = str_replace('&ldquo;', '"', $joke);
				$joke = str_replace('&rdquo;', '"', $joke); 
				$joke = str_replace('　', '', $joke);
				$joke = str_replace('&hellip;', '...', $joke);
				$joke = str_replace('<br />', '', $joke);
				$joke = str_replace("<p>", '"', $joke);
				$joke = str_replace("</p>", '"', $joke);
				$joke = trim($joke);
				$data = substr($data, $index2);
				$joke = iconv("gb2312","utf-8", $joke);
				echo $joke."\n\n";
 				inserttable($joke,  "恋爱"); 
			}
		}
    }		
	
	function inserttable($content, $ptype){
		//database connection
	//	$content = addslashes($content);
		echo $content."\n\n";
		$ptype = addslashes($ptype);
		$ptime = time();  
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "jokes"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn);
			mysql_query("set names 'utf8'");
			//database operation
 			$sql = "INSERT INTO $table_comm (content,ptype,ptime)VALUES('$content', '$ptype', FROM_UNIXTIME($ptime) )"; 
			if(!mysql_query($sql,$conn)){ 
			//	die("failed to insert data error:".mysql_error());
			} else { 
			}
			mysql_close($conn);
		}else{
		//	die("failed to open database error:".mysql_error());
		}
	} 
	//微英语
	function insertEnglish($sen, $trans){
		//database connection
		$ptype = addslashes($ptype);
		$ptime = time();  
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "english"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn);
			mysql_query("set names 'utf8'");
			//database operation
 			$sql = "INSERT INTO $table_comm (english,chinese,ptime)VALUES('$sen','$trans', FROM_UNIXTIME($ptime) )"; 
			if(!mysql_query($sql,$conn)){ 
			} 
			mysql_close($conn);
		}
	} 
	function insertkepu($content){
		//database connection
	//	$content = addslashes($content);
	//	echo $content."\n\n";
		$ptype = addslashes($ptype);
		$ptime = time();  
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "kepu"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn);
			mysql_query("set names 'utf8'");
			//database operation
 			$sql = "INSERT INTO $table_comm (content,ptime)VALUES('$content', FROM_UNIXTIME($ptime) )"; 
			if(!mysql_query($sql,$conn)){ 
			//	die("failed to insert data error:".mysql_error());
			} else { 
			}
			mysql_close($conn);
		}else{
		//	die("failed to open database error:".mysql_error());
		}
	} 
	function inserttablejzw($content){
		//database connection
	//	$content = addslashes($content);
		echo $content."\n\n";
		$ptype = addslashes($ptype);
		$ptime = time();  
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "jzw"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn);
			mysql_query("set names 'utf8'");
			//database operation
 			$sql = "INSERT INTO $table_comm (content,ptime)VALUES('$content', FROM_UNIXTIME($ptime) )"; 
			if(!mysql_query($sql,$conn)){ 
			//	die("failed to insert data error:".mysql_error());
			} else { 
			}
			mysql_close($conn);
		}else{
		//	die("failed to open database error:".mysql_error());
		}
	} 
	 set_time_limit(0); 
	 //getjoke(1000); 
	 getjokes(200);
	 //insertkepumsg();
	 //echo getkepu();
	 getEnglish();
 ?>