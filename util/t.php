<?php
	function getaddr($num) 
	{
		//$hostname_conn = "mysql1403.ixwebhosting.com:3306";
		//$database_conn = "C360953_fangjun";
		$table_comm = "shortaddr"; 
		//$username_conn = "C360953_fangjun";
		//$password_conn = "Fangjun65320";
        
        $hostname_conn = "115.29.17.79";
        $database_conn = "icoding";
        $username_conn = "root";
        $password_conn = "fangjun";
        

		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if (!$conn){
//			die("failed to connect mysql:" . mysql_error());
			return "";
		}  
		mysql_select_db($database_conn, $conn);
		//database operation
		$sql = "SELECT * FROM $table_comm  WHERE id='$num'";  
		$result = mysql_query($sql, $conn); 
		if(!$result){   
			mysql_close($conn);
			return "";
		} else {    
			$row = mysql_fetch_array($result); 
			$content = stripslashes($row['addr']); 
			$ptime = time();
			$sql = "update $table_comm set pcount = pcount+1,lasttime=FROM_UNIXTIME($ptime) where id='$num'"; 
			if(!mysql_query($sql,$conn)){ 
			//	die("failed to insert data error:".mysql_error());
			}  
			mysql_close($conn);
			return $content;
		}
		return "";
	}
//	$strarray = str_shuffle("1234567890poiuytrewqasdfghjklmnbvcxzQWERTYUIOPLKJHGFDSAZXCVBNM");
	$strarray = "RQu32qwEZ8Gsl9h6oynkD0VFXaWixf4gKULTMzcPBtSAp51HCjI7dmNeJvOrYb";
	$c = count($strarray);
	$mapArray = array();
	for($i=0;$i<$c;$i++){
		$mapArray[$i] = $strarray[$i];
	}
	$code = $_GET["p"];
	if(strlen($code) == 3){
		$num = strpos($strarray, $code[0]*62*62) + strpos($strarray, $code[1])*62 + strpos($strarray, $code[2]);
		echo $num;
		$addr = getaddr($num);
		echo $addt;
		if(strlen($addr) > 0){
			//重定向浏览器 
			header("Location: ".$addr); 
			//确保重定向后，后续代码不会被执行 
			exit;
		}else{
			echo "亲，你进错页面了吧~~";
		}
	} 
?>