<?php  

require_once "databaseutil.php";

	//database operations--------------------
	function secret_welcome(){ 
		$contentStr = //"许下你dē诺言，写下你想对ta说的话，送出对ta默默地祝福，留下你的抱怨你的委屈你的感激。\n"
		//."直到有一天ta在这里看到，或者哪一天你重新来这里找回回忆，都不失为一件让人愉快的事情。\n"
		//."留下一份回忆，也给ta一个惊喜！\n\n"
		//."你还可以在这里发布交友信息哦~很多人会看到的！！\n\n"
		//."【请回复以下相应字母】\n"	
		//."回复 ta 发表你想对ta说的心里话\n"
		//."回复 mm 找朋友找对象\n"
		//."回复 kk 查看最新发布\n"
		//."回复 kta 查看指定名字收到的告白\n\n"
		//."回复 h 查看所有功能\n\n"
		 "当想找人聊天时，我可以24小时陪你！\n\n"
		."当你嫌弃浏览器网页太慢太费流量时，我10秒内一定给你回答!\n\n"
		."聊天卖萌天气星座火车新闻查快递翻译笑话藏头诗等等，更多惊喜等你发现~\n\n"
		."更有每日一条精心挑选的文字，或励志或实用或开阔你的眼界!\n\n"
		."要是需要其他功能，还会及时特意为您订制!\n\n"
		."回复h  查看所有功能";

		return $contentStr;
	}
	function secret_ending(){ 
		$contentStr = "\n回复 ta 发表你想对ta说的心里话\n"
		."回复 mm 找朋友找对象\n"
		."回复 kk 查看最新发布\n"
		."回复 kta 查看指定名字收到的告白\n"	
		."查看所有功能回复 h ";

		return $contentStr;
	}
	function secret_ending2(){ 
		$contentStr = "\n回复 ta 发表你的心里话\n" 
		."回复 kk 继续查看\n" 
		."查看所有功能回复 h \n"
		."【推荐给你的TA,说不定TA会说出不敢对你说的话哦】\n";

		return $contentStr;
	} 
	function secret_surprise(){ 
		$contentStr = 
		"\n回复 ta 发表你想对ta说的心里话\n"
		."回复 mm 找朋友找对象\n"	
		."回复 kk 查看最新发布\n"
		."回复 kta 查看指定名字收到的告白\n"	
		."查看所有功能回复 h \n"
		."【推荐给你的TA,说不定TA会说出不敢对你说的话哦】\n";

		return $contentStr;
	} 
	//插入文本
	function secret_inserttext($fromuser, $keyword){ 
		 
		//database connection  
		$keyword = addslashes($keyword);  
		 
 		$hostname_conn = "mysql1403.ixwebhosting.com"; 
 		$port_conn = "3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320";  
		
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		$contetstr = ''; 
		if ($conn){ 
			mysql_select_db($database_conn, $conn);
			mysql_query("set names 'utf8'"); 
			$sql = "select secret_touser from users where user ='$fromuser'"; 
			$result = mysql_query($sql, $conn); 
			if($row = mysql_fetch_array($result)){ 
				$touser = $row['secret_touser'];  
				$sql = "insert into secretwords (user, touser, words) values('$fromuser','$touser', '$keyword')"; 
				$result = mysql_query($sql, $conn); 
				$sql = "update users set secretIndex = 0"; 
				$result = mysql_query($sql, $conn); 
			}
		}  
	}
	//插入对话的人
	function secret_inserttouser($fromuser, $touser){  
		 
 		$hostname_conn = "mysql1403.ixwebhosting.com"; 
 		$port_conn = "3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320";  
		
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		$contetstr = ''; 
		if ($conn){ 
			mysql_select_db($database_conn, $conn);
			mysql_query("set names 'utf8'"); 
			$sql = "update users set secret_touser = '$touser' where user ='$fromuser'"; 
			$result = mysql_query($sql, $conn); 
		} 
	}
	//获取对话的人
	function secret_gettouser($fromuser){  
		 
 		$hostname_conn = "mysql1403.ixwebhosting.com"; 
 		$port_conn = "3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320";  
		
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		$contetstr = ''; 
		if ($conn){ 
			mysql_select_db($database_conn, $conn);
			mysql_query("set names 'utf8'"); 
			$sql = "select secret_touser from users where user ='$fromuser'"; 
			$result = mysql_query($sql, $conn); 
			$row = mysql_fetch_array($result); 
			$contetstr = $row['secret_touser'];  
		} 
		return $contetstr;
	}
	//查看最新的话
	function secret_latestwords($fromuser){
		//database connection  		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		$contetstr = ''; 
		if ($conn){ 
			mysql_select_db($database_conn, $conn);
			mysql_query("set names 'utf8'");
			$secretIndex = 0;
 			$sql = "select secretIndex from users where user = '$fromuser'"; 
			$result = mysql_query($sql, $conn); 
			if($result){ 
				$row = mysql_fetch_array($result); 
				$secretIndex = $row['secretIndex'];  
				//database operation
				//$sql = "select touser, words, UNIX_TIMESTAMP(ptime) as ptime from secretwords order by id desc limit $secretIndex , 10";  
				$sql = "select pcount, secretwords.id as sid, city, sex, touser, words,UNIX_TIMESTAMP(secretwords.ptime) as pptime from secretwords, users where users.user= secretwords.user order by secretwords.id desc limit $secretIndex , 10";
				$result = mysql_query($sql, $conn); 
				$nn = 0;
				$idStack = array();
				if($result){
					date_default_timezone_set('PRC');
					while($row = mysql_fetch_array($result)){ 
						$city = $row['city'];  
						$sex = $row['sex'];  
						$touser = $row['touser'];  
						$words = $row['words']; 
						$ptime = $row['pptime']; 
						$pcount = intval($row['pcount'])+1; 
						array_push($idStack, $row['sid']);
						
						//if($sex == '0')
						//	$sex = '某女生';
						//else if($sex == '1')
						//	$sex = '某男生';
						//else
							$sex = '某人';
							
						
						if(strlen($words) > 0){
							$nn = $secretIndex  + 1;
							$contetstr .= "【第 $nn 条】\n";
							$contetstr .= date('Y-m-d H:i', $ptime)."\n";
							if(strlen(trim($touser)) == 0) {
								$contetstr .= "$city $sex 说：\n";
							}else {
								$contetstr .= "$city $sex \n对 $touser 说：\n";
							}
							$contetstr .= "$words \n";
							$contetstr .= "-------浏览次数($pcount)\n";
							
						}
						$secretIndex =  $secretIndex + 1; 
						if(mb_strlen($contetstr,'utf-8') > 350)
							break;
					}
				}
				$sql = "update users set secretIndex = $secretIndex where user = '$fromuser'"; 
				$result = mysql_query($sql, $conn); 
				$c = count($idStack);
				for($ii = 0; $ii < $c; $ii++){
					$sql = "update secretwords set pcount = pcount + 1 where id = '".$idStack[$ii]."'"; 
					$result = mysql_query($sql, $conn);  
				}
			} 
		}
		return $contetstr.secret_ending2();
	}
	
	//以名字查看
	function secret_wordsbyuser($touser){
		//database connection  		
 		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		$contetstr = '';
		if ($conn){ 
			mysql_select_db($database_conn, $conn);
			mysql_query("set names 'utf8'");
			$secretIndex = 0; 	
			$sql = "select pcount, secretwords.id as sid, city, sex, touser, words,UNIX_TIMESTAMP(secretwords.ptime) as pptime from secretwords, users where touser like '%$touser%' and users.user= secretwords.user order by secretwords.id desc";
			
		//	$sql = "select city, touser, words, UNIX_TIMESTAMP(ptime) as ptime from secretwords where touser like '%$touser%' order by id desc limit 50";
			 
			$result = mysql_query($sql, $conn); 
			$nn = 0;
			if($result){
				$idStack = array();
				date_default_timezone_set('PRC');
				while($row = mysql_fetch_array($result)){ 
					$city = $row['city'];  
					$sex = $row['sex'];  
					$touser = $row['touser'];  
					$words = $row['words']; 
					$ptime = $row['pptime'];
					$pcount = intval($row['pcount'])+1; 
					array_push($idStack, $row['sid']);
					
					if($sex == '0')
							$sex = '某女生';
						else if($sex == '1')
							$sex = '某男生';
						else
							$sex = '某人';
							
					if(strlen($words) > 0){
						$nn = $nn  + 1;
						$contetstr .= "【第 $nn 条】\n"; 
						$contetstr .= date('Y-m-d H:i', $ptime)."\n";
						$contetstr .= "$city $sex \n对 $touser 说：\n";
						$contetstr .= "$words \n";
						$contetstr .= "-------浏览次数($pcount)\n";
					}
				}
				$c = count($idStack);
				for($ii = 0; $ii < $c; $ii++){
					$sql = "update secretwords set pcount = pcount + 1 where id = '".$idStack[$ii]."'"; 
					$result = mysql_query($sql, $conn);  
				}
			}  
		}
		if(strlen($contetstr) == 0){
			$contetstr = "还没有发给 $touser 的告白哦~\n快叫$touser 的同学朋友也来关注我吧，兴许他们会对TA说出心里的告白哦~\n ";
		}
		return $contetstr.secret_ending();
	}
	//插入secret flag
	function secret_updateflag($fromuser, $flag){ 
			 
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
		 
		$sql = "update users set secret_flag = '$flag' where user = '$fromuser'"; 
		
		$result=$mysqli->query($sql);
		if (!$result) {  
			return '';
		} 
	} 
	
	//查询secret flag
	function secret_getflag($fromuser){ 
			 
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
		$sql = "select secret_flag from users where user = '$fromuser'"; 
		$result=$mysqli->query($sql);
		if($result) {
			if($row = $result->fetch_object()){ 
				$secret_flag = $row->secret_flag;  
				return $secret_flag;
			}else{
				d_insertuser($fromuser);
				return 0;
			}
		} 
		return 0;
	}   
?>