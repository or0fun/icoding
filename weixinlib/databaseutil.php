<?php 
	 
	//database operations--------------------
	//插入文本
	function d_inserttext($fromuser, $keyword, $createTime, $content, $ptime, $ctype="general", $moretext=""){ 
		
		if($ctype == "train" && strlen($moretext) == 0){
			return;
		}
		//database connection
		$fromuser = addslashes($fromuser); 
		$keyword = addslashes($keyword);  
		$content = addslashes($content);   
		 
 		$hostname_conn = "mysql1403.ixwebhosting.com"; 
 		$port_conn = "3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "wxmsg"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$totaltime = 5 - (time() - $createTime);
		if($totaltime < 0)
			return;
		
		$mysqli = mysqli_init();
		if (!$mysqli) { 
			return; 
		} 
		if (!$mysqli->options(MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 0')) {  
			return;
		} 
		if (!$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, $totaltime)) {  
			return;
		} 
		if (!$mysqli->real_connect($hostname_conn,$username_conn,$password_conn,$database_conn,$port_conn)) {  
			return;
		}
		$result=$mysqli->query("set names 'utf8'");
		if (!$result) {  
			return;
		}
		if($ctype != "train"){
			$sql = "INSERT INTO wxmsg (fromuser,msg,reply,revtime,reptime,ptype)VALUES('$fromuser','$keyword','$content',FROM_UNIXTIME($createTime),FROM_UNIXTIME($ptime),'$ctype')"; 
			$result=$mysqli->query($sql);
			if (!$result) {  
				return;
			} 
		}
		
		if(strlen($moretext) > 0){
			$moretext = addslashes($moretext); 
			$sql = "update users set more_flag='1', moretext='$moretext' where user = '$fromuser'"; 
			$result=$mysqli->query($sql);
			if (!$result) { 
				$sql = "INSERT INTO users (user, ptime)VALUES('$fromuser', FROM_UNIXTIME($ptime) )"; 
				$result=$mysqli->query($sql);
				$sql = "update users set more_flag='1', moretext='$moretext' where user = '$fromuser'"; 
				$result=$mysqli->query($sql);
				return;
			} 
		}   
	}
	//插入位置
	function d_insertlocation($fromuser, $Location_X, $Location_Y, $Scale, $Label){
		//database connection
		$fromuser = addslashes($fromuser);
		$Label = addslashes($Label); 
		$ptime = time();
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "userlocation"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn);
			mysql_query("set names 'utf8'");
			//database operation
 			$sql = "INSERT INTO $table_comm (user,Location_X,Location_Y,Scale,Label, ptime)VALUES('$fromuser', '$Location_X', '$Location_Y',$Scale, $Label, FROM_UNIXTIME($ptime) )"; 
			if(!mysql_query($sql,$conn)){ 
			//	die("failed to insert data error:".mysql_error());
			} else { 
			}
			mysql_close($conn);
		}else{
		//	die("failed to open database error:".mysql_error());
		}
	}
	//插入用户
	function d_insertuser($fromuser){
		//database connection
		$fromuser = addslashes($fromuser); 
		$ptime = time();
			 
 		$hostname_conn = "mysql1403.ixwebhosting.com"; 
 		$port_conn = "3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "users"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320";   
		
		$mysqli = mysqli_init();
		if (!$mysqli) { 			 
			return '';
		} 
		if (!$mysqli->options(MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 0')) {  			 
			return '';
		} 
		if (!$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 4)) {  			 
			return '';
		} 
		if (!$mysqli->real_connect($hostname_conn,$username_conn,$password_conn,$database_conn,$port_conn)) {  			 
			return '';
		}
		$result=$mysqli->query("set names 'utf8'");
		if (!$result) {   
			return '';
		}
		 
		$sql = "INSERT INTO $table_comm (user, ptime)VALUES('$fromuser', FROM_UNIXTIME($ptime) )"; 
		
		$result=$mysqli->query($sql);
		if (!$result) {  
			return '';
		}
 		$sql = "select count(id) as cc from users"; 
		$result=$mysqli->query($sql);
		if (!$result) {  
			return '';
		}
		$row = $result->fetch_object();
		if($row){
			return $row->cc;  
		} 
		return '';
	} 
	//获取分享内容
	function d_getshare($fromuser){		
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if (!$conn){ 
			return "";
		}  
		mysql_select_db($database_conn, $conn);
		mysql_query("set names 'utf8'"); 
		$sql = "select msg,fromuser from wxmsg where fromuser <> '$fromuser' and ptype='share' ".
		        " order by RAND() limit 1";  
		$result = mysql_query($sql, $conn); 
		if($result){   
			$row = mysql_fetch_array($result); 
			$msg = stripslashes($row['msg']);  
			$user = stripslashes($row['fromuser']); 
			$sql = "select city, name from users where user='$user'"; 
			$result = mysql_query($sql, $conn); 
			if($result){   
				$row = mysql_fetch_array($result);
				$city = stripslashes($row['city']);  
				$name = stripslashes($row['name']);
				if ($city == ''){
					$city = '神秘地方';
				}
				$username = "感谢分享~\n下面随机内容分享自\n【".$city.' 的 "'.$name.'" 】'."\n".$msg;
				return $username;
			}
			mysql_close($conn); 
		}
		return "感谢分享~";
	 
	}	
	//获取图片链接
	function d_getimage($fromuser){		
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if (!$conn){ 
			return "";
		}  
		mysql_select_db($database_conn, $conn);
		mysql_query("set names 'utf8'"); 
		$sql = "select msg,fromuser from wxmsg where fromuser <> '$fromuser' and ptype='image' ".
		        " order by RAND() limit 1";  
		$result = mysql_query($sql, $conn); 
		if($result){   
			$row = mysql_fetch_array($result); 
			$image = stripslashes($row['msg']);  
			$user = stripslashes($row['fromuser']); 
			$sql = "select city, name from users where user='$user'"; 
			$result = mysql_query($sql, $conn); 
			if($result){   
				$row = mysql_fetch_array($result);
				$city = stripslashes($row['city']);  
				$name = stripslashes($row['name']);
				if ($city == ''){
					$city = '神秘地方';
				}
				$username = "您随机收到的图片分享自\n【".$city.' 的 "'.$name.'" 】';
				return array($username, $image);
			}
			mysql_close($conn); 
		}
		return array("你好", "http://imgsrc.baidu.com/baike/pic/item/342ac65c1038534327fc37a39213b07eca808805.jpg");
	 
	}	
	//获取需要重复的信息
	function d_getpoststr($fromuser){		
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "users"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if (!$conn){ 
			return "";
		}  
		mysql_select_db($database_conn, $conn);
		mysql_query("set names 'utf8'");
		//database operation
		$sql = "select repeat_str from users where user='$fromuser'";  
		$result = mysql_query($sql, $conn); 
		if(!$result){   
			mysql_close($conn);
			return "";
		} else {    
			$row = mysql_fetch_array($result); 
			$repeat_str = stripslashes($row['repeat_str']);  
			mysql_close($conn);
			if(strlen($repeat_str) > 0){
				return $repeat_str;
			}
			return "";
		}
	 
	}
	//插入需要重复的信息
	function d_insertpoststr($fromuser, $poststr){ 
		 		
		$poststr = addslashes($poststr);  
		$sql = "update users set repeat_str='$poststr' where user='$fromuser'"; 
		d_execute($sql);
	 
	}
	
	//执行
	function d_execute($sqlstr){
	
		$hostname_conn = "mysql1403.ixwebhosting.com"; 
 		$port_conn = "3306"; 
		$database_conn = "C360953_fangjun";  
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320";   
		
		$mysqli = mysqli_init();
		if (!$mysqli) { 			 
			return '';
		} 
		if (!$mysqli->options(MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 0')) {  			 
			return '';
		} 
		if (!$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 4)) {  			 
			return '';
		} 
		if (!$mysqli->real_connect($hostname_conn,$username_conn,$password_conn,$database_conn,$port_conn)) {  			 
			return '';
		}
		$result=$mysqli->query("set names 'utf8'");
		if (!$result) {   
			return '';
		}
		 
		$sql = $sqlstr; 
		
		$result=$mysqli->query($sql); 
	}
	 
	//我是谁
	function d_getusername($fromUsername){ 
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "users"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if (!$conn){
			return "";
		}  
		mysql_select_db($database_conn, $conn);
		mysql_query("set names 'utf8'");
		//database operation
		$sql = "SELECT sex,city, name FROM $table_comm  WHERE user='".$fromUsername."'";  
		$result = mysql_query($sql, $conn); 
		if(!$result){   
			mysql_close($conn);
			return "";
		} else {    
			$row = mysql_fetch_array($result); 
			$sex = $row['sex']; 
			if($sex == '1')
				$sex = '大帅哥';
			else if($sex == '0')
				$sex = '大美女';
			$city = $row['city'];
			mysql_close($conn);
			if(strlen($sex) > 0){
				return $city."的". $row['name'].$sex;
			}
			return "";
		}
	}
	//short addr
	function d_getmaxnum($url, $ptype) 
	{
		$url = addslashes($url);
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "shortaddr"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if (!$conn){
		//	die("failed to connect mysql:" . mysql_error());
			return -1;
		}  
		mysql_select_db($database_conn, $conn);  
		mysql_query("set names 'utf8'");  
		$sql = "select id from $table_comm where addr='$url'"; 
		if($result=mysql_query($sql,$conn)){  
			if($row = mysql_fetch_array($result)){ 
				$content = $row['id']; 
				mysql_close($conn);
				return $content;
			}else{
				$sql = "insert into $table_comm(addr, ptype)values('$url', '$ptype')"; 
				if(!mysql_query($sql,$conn)){ 
				//	die("failed to insert data error:".mysql_error());
				}  
				$sql = "select id from $table_comm order by id desc limit 1"; 
				$result = mysql_query($sql, $conn); 
				if(!$result){   
					mysql_close($conn);
					return -1;
				} else {    
					$row = mysql_fetch_array($result); 
					$content = $row['id']; 
					mysql_close($conn);
					return $content;
				} 
			} 
		}
		
		return -1;
	}  
	//获取段地址
	function d_getshortaddr($word, $ptype="today"){
		$strarray = "RQu32qwEZ8Gsl9h6oynkD0VFXaWixf4gKULTMzcPBtSAp51HCjI7dmNeJvOrYb";
		$c = strlen($strarray);
		$mapArray = array();
		for($i=0;$i<$c;$i++){
			$mapArray[$i] = $strarray[$i];
		} 
		$word = iconv("utf-8","gb2312",$word);
		$n = d_getmaxnum("http://baike.baidu.com/list-php/dispose/searchword.php?word=".urlencode($word)."&pic=1", $ptype);
		
		$re = '';
		while($n >= 1){
			$re = $mapArray[(int)((int)$n)%62].$re;
			$n = (int)((int)$n / 62); 
		}
		while(strlen($re) < 3){
			$re = 'R'.$re;
		}
		if(strlen($re) > 0){
			return "http://baiwanlu.com/t.php?p=".$re; 
		}
		return "";
	} 
	//获取段地址
	function d_getshorta($url, $ptype){
		$strarray = "RQu32qwEZ8Gsl9h6oynkD0VFXaWixf4gKULTMzcPBtSAp51HCjI7dmNeJvOrYb";
		$c = strlen($strarray);
		$mapArray = array();
		for($i=0;$i<$c;$i++){
			$mapArray[$i] = $strarray[$i];
		}  
		$n = d_getmaxnum($url, $ptype);
		
		$re = '';
		while($n >= 1){
			$re = $mapArray[(int)((int)$n)%62].$re;
			$n = (int)((int)$n / 62); 
		}
		while(strlen($re) < 3){
			$re = 'R'.$re;
		}
		if(strlen($re) > 0){
			return "http://baiwanlu.com/t.php?p=".$re; 
		}
		return "";
	} 
	//管理员获取数据库信息
	function d_getdatabase(){
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");
			//database operation
			$re = '';
 			$sql = "select count(id) as cc from users where subscribe='1'"; 
			if($result =mysql_query($sql,$conn)){  
				$row = mysql_fetch_array($result);
				$num = $row["cc"];
				$re .= $num;
				$sql = "select count(id) as cc from wxmsg"; 
				if($result = mysql_query($sql,$conn)){  
					$row = mysql_fetch_array($result);
					$num = $row["cc"]; 
					$re .= ",".$num;
				}  
			}
			mysql_close($conn);
			return $re;
		}
		return "";
	} 
	//获取更多
	function d_getmore($fromuser){
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn);  
			mysql_query("set names 'utf8'");  
			//database operation
			$more = '';
 			$sql = "select moretext  from users where  user = '$fromuser' and more_flag='1'"; 
			if($result =mysql_query($sql,$conn)){  
				$row = mysql_fetch_array($result);
				$more = $row["moretext"]; 
				$sql = "update users set more_flag='0' where user = '$fromuser'"; 
				$result = mysql_query($sql,$conn);
			}
			mysql_close($conn);
			if(strlen($more) > 0) 
				return "(接上)\n".stripslashes($more);
		} 
		$contentStr = '就这么多啦~';
		$motion = array("/:@>/:<@", "/:B-)","/::>","/::,@","/::D","/::)","/::P","/::$","/:,@-D","/:,@P");
		$contentStr .= $motion[rand(0, count($motion)-1)]; 
		return $contentStr;
	} 
	function d_setmore($fromuser, $str){
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");  
			$str = addslashes($str);
			//database operation 
 			$sql = "update users set more_flag='1', moretext='$str' where user = '$fromuser'"; 
			$result =mysql_query($sql,$conn);
			mysql_close($conn);
		}
	} 
	//取消订阅
	function d_setunsubscribe($fromuser){
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");  
			//database operation 
			$ptime = time();
 			$sql = "update users set subscribe='0', unsubscribe= FROM_UNIXTIME($ptime)  where user = '$fromuser'"; 
			$result =mysql_query($sql,$conn);
			mysql_close($conn);
		}
	}  
	
	function d_isautoreply($fromuser){
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn);  
			mysql_query("set names 'utf8'");  
			//database operation
			$autoreply = 1;
 			$sql = "select autoreply  from users where  user = '$fromuser' "; 
			if($result =mysql_query($sql,$conn)){  
				$row = mysql_fetch_array($result);
				if($row)
					$autoreply = $row["autoreply"];
				else{ 
					$ptime = time();
					$sql = "INSERT INTO users (user, ptime)VALUES('$fromuser', FROM_UNIXTIME($ptime) )";  
					$result= mysql_query($sql,$conn);
				}
			}
			mysql_close($conn);   
			return $autoreply; 
		} 
		return 1;
	}
	
	function d_setnewsflag($fromuser, $rn){
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");  
			//database operation 
 			$sql = "update users set news_flag='$rn' where user = '$fromuser'"; 
			$result =mysql_query($sql,$conn);
			mysql_close($conn);
		}
	} 
	function d_getnewsflag($fromuser){
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		$rn = 0;
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");  
 			$sql = "select news_flag  from users where  user = '$fromuser' "; 
			if($result =mysql_query($sql,$conn)){  
				$row = mysql_fetch_array($result);
				$rn = $row["news_flag"];
			}
			mysql_close($conn);
		}
		return $rn;
	} 
	function d_setnewsstr($fromuser, $news_str){
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");  
			
			$news_str = addslashes($news_str);  
			//database operation 
 			$sql = "update users set news_str='$news_str' where user = '$fromuser'"; 
			$result =mysql_query($sql,$conn);
			mysql_close($conn);
		}
	} 
	function d_getnewsstr($fromuser){
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		$news = '';
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");  
 			$sql = "select news_str  from users where  user = '$fromuser' "; 
			if($result =mysql_query($sql,$conn)){  
				$row = mysql_fetch_array($result);
				$news = $row["news_str"];
			}
			mysql_close($conn);
		}
		return stripslashes($news);
	} 
	
	
	function d_setzhidaostr($fromuser, $zhidao_str, $flag){
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");  
			
			$news_str = addslashes($zhidao_str);  
			//database operation 
			if($flag == 0)
				$sql = "update users set zhidao_str='$zhidao_str' where user = '$fromuser'"; 
			else if($flag == 1)
				$sql = "update users set zhidao_str2='$zhidao_str' where user = '$fromuser'"; 
			$result =mysql_query($sql,$conn);
			mysql_close($conn);
		}
	} 
	function d_getzhidaostr($fromuser, $flag){
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		$news = '';
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'"); 
			if($flag == 0){
				$sql = "select zhidao_str  from users where  user = '$fromuser' "; 
				if($result =mysql_query($sql,$conn)){  
					$row = mysql_fetch_array($result);
					$news = $row["zhidao_str"];
				}
			}
			else if($flag == 1){
				$sql = "select zhidao_str2  from users where  user = '$fromuser' ";  
				if($result =mysql_query($sql,$conn)){  
					$row = mysql_fetch_array($result);
					$news = $row["zhidao_str2"];
				}
			}
				
			mysql_close($conn);
		}
		return stripslashes($news);
	} 
	//随机获取每日一条
	function d_getonestr(){		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		$onestr = '';
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");  
 			$sql = "select words, time, id  from one where id >= (SELECT FLOOR(RAND() * (SELECT MAX(ID) FROM one))) order by RAND() limit 1"; 
			if($result = mysql_query($sql,$conn)){  
				$row = mysql_fetch_array($result);
				$onestr = $row["time"]."\n".$row["words"];
				$id = $row["id"];
				$query = "update one set pcount = pcount+1 where id = '$id'";  
				$result = mysql_query($sql,$conn); 
			}
			mysql_close($conn);
		}
		return stripslashes($onestr);
	} 
	function d_getonestrBydate($date){		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		$onestr = '';
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");  
 			$sql = "select words, time, id  from one where time = '$date'  order by RAND() limit 1"; 
			if($result = mysql_query($sql,$conn)){  
				$row = mysql_fetch_array($result);
				$onestr = $row["time"]."\n".$row["words"];
				$id = $row["id"];
				$query = "update one set pcount = pcount+1 where id = '$id'";  
				$result = mysql_query($sql,$conn); 
			}
			mysql_close($conn);
		}
		return stripslashes($onestr);
	} 
	function d_getonestrBywords($words){		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		$onestr = '';
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");  
 			$sql = "select words, time, id  from one where words  LIKE '%$words%' or time LIKE '%$words%' order by RAND()  limit 1"; 
			if($result = mysql_query($sql,$conn)){  
				if($row = mysql_fetch_array($result)){
					$onestr .= $row["time"]."\n".$row["words"]."\n";
					$id = $row["id"];
					$query = "update one set pcount = pcount+1 where id = '$id'";  
					mysql_query($sql,$conn); 
				}
			}
			mysql_close($conn);
		}
		return stripslashes($onestr);
	} 
	/////////////////////////////////////////////////////////////////////位置
	function d_setposition($fromuser, $position){
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");  
			
			$news_str = addslashes($zhidao_str);  
			//database operation  
			$sql = "update users set position='$position' where user = '$fromuser'";  
			$result =mysql_query($sql,$conn);
			mysql_close($conn);
		}
	} 
	function d_getposition($fromuser){
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		$position = '';
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");  
			$sql = "select position  from users where  user = '$fromuser' "; 
			if($result =mysql_query($sql,$conn)){  
				$row = mysql_fetch_array($result);
				$position = $row["position"];
			} 
			mysql_close($conn);
		}
		return stripslashes($position);
	} 
	
	/////////////////////////////////////////////////////////////////////通用
	function d_setvalue($fromuser, $key, $value){
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");  
			
			$news_str = addslashes($zhidao_str);  
			//database operation  
			$sql = "update users set $key='$value' where user = '$fromuser'";  
			$result =mysql_query($sql,$conn);
			mysql_close($conn);
		}
	} 
	function d_setvalues($fromuser, $key, $value, $key1, $value1){
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");  
			
			$news_str = addslashes($zhidao_str);  
			//database operation  
			$sql = "update users set $key='$value', $key1='$value1' where user = '$fromuser'";  
			$result =mysql_query($sql,$conn);
			mysql_close($conn);
		}
	} 
	function d_setvalues_3($fromuser, $key, $value, $key1, $value1, $key2, $value2){
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");  
			
			$news_str = addslashes($zhidao_str);  
			//database operation  
			$sql = "update users set $key='$value', $key1='$value1', $key2='$value2' where user = '$fromuser'";  
			$result =mysql_query($sql,$conn);
			mysql_close($conn);
		}
	} 
	function d_getvalue($fromuser, $key){
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		$key = '';
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");  
			$sql = "select $key  from users where  user = '$fromuser' "; 
			if($result =mysql_query($sql,$conn)){  
				$row = mysql_fetch_array($result);
				$key = $row["$key"];
			} 
			mysql_close($conn);
		}
		return stripslashes($key);
	} 
	function d_getvalues($fromuser, $key, $key1){
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		$value = '';
		$value1 = '';
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");  
			$sql = "select  $key , $key1  from users where  user = '$fromuser' "; 
			if($result =mysql_query($sql,$conn)){  
				$row = mysql_fetch_array($result);
				$value = $row["$key"];
				$value1 = $row["$key1"];
			} 
			mysql_close($conn);
		}
		return array(stripslashes($value),stripslashes($value1)) ;
	} 
	function d_getvalues_3($fromuser, $key, $key1, $key2){
		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		$value = '';
		$value1 = '';
		$value2 = '';
		if ($conn){ 
			mysql_select_db($database_conn, $conn); 
			mysql_query("set names 'utf8'");  
			$sql = "select  $key , $key1, $key2  from users where  user = '$fromuser' "; 
			if($result =mysql_query($sql,$conn)){  
				$row = mysql_fetch_array($result);
				$value = $row["$key"];
				$value1 = $row["$key1"];
				$value2 = $row["$key2"];
			} 
			mysql_close($conn);
		}
		return array(stripslashes($value),stripslashes($value1), stripslashes($value2)) ;
	} 
?>