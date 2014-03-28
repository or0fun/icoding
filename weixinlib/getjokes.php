<?php
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
		for($i = 1; $i < 10;$i++)
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
				return 'Oops!ÕâÆÆÍøÌ«ÂýÀ²£¬ÇëÔÙÊÔÒ»±é~'; 
			$content = mb_convert_encoding($content, "UTF-8", "GBK"); 
			$index2 = 0;
			while(strstr($content, 'color=#008080>'))
			{
				$index = strpos($content, 'color=#008080>');
				$index2 = strpos($content, '<', $index);
				$re = substr($content, $index + 14, $index2 - $index - 14)."\n";
				$index = strpos($content, "MM_popupMsg('", $index2);
				$index2 = strpos($content, "'", $index + 14);
				$re .= substr($content, $index + 13, $index2 - $index - 13);
				$content = substr($content, $index2);
				inserttablejzw($re);
			}
		} 
	}
	function getjokes($num) {
		for($i = 2; $i <=$num; $i++){
			$curl = curl_init(); 
			curl_setopt($curl, CURLOPT_URL, 'http://www.haha365.com/er_joke/index_'.$i.'.htm'); 
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
				$joke = str_replace('¡¡', '', $joke);
				$joke = str_replace('&hellip;', '...', $joke);
				$joke = str_replace('<br />', '', $joke);
				$joke = str_replace("<p>", '"', $joke);
				$joke = str_replace("</p>", '"', $joke);
				$joke = trim($joke);
				$data = substr($data, $index2);
				$joke = iconv("gb2312","utf-8", $joke);
				echo $joke."\n\n";
 				inserttable($joke,  "å„¿ç"); 
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
	 //set_time_limit(0); 
	 //getjoke(1000); 
	 //getjokes(2);
	 //getjzw();
 ?>