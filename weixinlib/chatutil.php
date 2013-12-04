<?php 

	function chat_ischat($fromuser){ 
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun"; 
		$username_conn = "C360953_fangjun"; 
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if (!$conn){ 
			return "";
		}  
		mysql_select_db($database_conn, $conn); 
		//database operation
		$sql = "select * from users where user='$fromuser' and flag > '0'";  
		$result = mysql_query($sql, $conn);  
		if($result){      
			$row = mysql_fetch_array($result);
			if($row){
				mysql_close($conn);
				return true;
			}
			mysql_close($conn);
			return false; 
		}
	}
	function chat_getmsg($fromuser){ 
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun"; 
		$username_conn = "C360953_fangjun"; 
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if (!$conn){ 
			return "";
		}  
		mysql_select_db($database_conn, $conn); 
		//database operation
		$sql = "select * from msglist where user='$fromuser' and flag='1'";  
		$result = mysql_query($sql, $conn);  
		if($result){      
			$msg = "";
			while($row = mysql_fetch_array($result)){
				$msg .= $row['msg'];	 
			}
			if(strlen($msg) > 0){
				$sql = "update msglist set flag = 0 where user='$fromuser' and flag='1'";  
				$result = mysql_query($sql, $conn); 
				mysql_close($conn);
				if(chat_ischat($fromuser)){
					$msg .= "[以'-'开头就可回复ta,如'-你好']";
				}
				else {
					$msg .= "[对方已断开，你可以用'-'开头发信息给有缘人，如'-你在哪呀']";
				}
			}
			return $msg;
		}
	}
	
	function chat_insertmsg($touser, $msg){ 
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
		//database operation
		$sql = "insert into msglist (msg,user,flag) values('$msg','$touser','1')";  
		$result = mysql_query($sql, $conn);  
		mysql_close($conn);
	}
	
	function chat_getname($fromuser){ 
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun"; 
		$username_conn = "C360953_fangjun"; 
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if (!$conn){ 
			return "";
		}  
		mysql_select_db($database_conn, $conn); 
		//database operation
		$sql = "SELECT name FROM users  WHERE user='$fromuser'";  
		$result = mysql_query($sql, $conn); 
		if($result){      
			$row = mysql_fetch_array($result);
			$name = $row['name'];	 
			mysql_close($conn);
			return $name;
		}
	}
	//断开有缘人
	function chat_breakchat($fromuser){ 
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if (!$conn){ 
			return "";
		}  
		mysql_select_db($database_conn, $conn); 
		//database operation
		$sql = "SELECT flag FROM users  WHERE user='$fromuser'";  
		$result = mysql_query($sql, $conn); 
		if($result){      
			$row = mysql_fetch_array($result);
			$flag = $row['flag'];	 
			$sql = "select user1, user2 from chatlist where id='$flag'";  
			$result = mysql_query($sql, $conn);   
			if($result){      
				$row = mysql_fetch_array($result);
				$user1 = $row["user1"];
				$user2 = $row["user2"]; 
				$ptime = time();
				$sql = "update users set flag='0' where user='$user1' or user='$user2'";  
				$result = mysql_query($sql, $conn);
				$sql = "update chatlist set flag='0',btime='FROM_UNIXTIME($ptime)' where id='$flag'";  
				$result = mysql_query($sql, $conn);
			}	
		}
		mysql_close($conn);
	}
	//配置有缘人
	function chat_setchat($fromuser){
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if (!$conn){ 
			die("failed to connect mysql:" . mysql_error());
			return "";
		}  
		mysql_select_db($database_conn, $conn);
		mysql_query("set names 'utf8'");
		//database operation
		$sql = "SELECT flag FROM users  WHERE user='$fromuser' and flag > '0'";  
		$result = mysql_query($sql, $conn); 
		if($result){   
			$row = mysql_fetch_array($result); 
			if($row){
				$flag = $row['flag'];	  
				$sql = "SELECT user1,user2 FROM chatlist WHERE id='$flag'";  
				$result = mysql_query($sql, $conn); 
				if(!$result){   
					mysql_close($conn);
					return "";
				} else {    
					$row = mysql_fetch_array($result); 
					$user1 = $row['user1'];	 
					$user2 = $row['user2']; 
					mysql_close($conn);
					if($user1 == $fromuser){   
						return $user2;
					}else{  
						return $user1;
					}  
				}
			}else{ 
				$sql = "SELECT user FROM users  WHERE flag = '0'";  
				$result = mysql_query($sql, $conn); 
				if($result){  
					$users = array();
					$ii = 0;
					while ($row = mysql_fetch_array($result)){
						$users[$ii] = $row['user'];	 
						$ii++;
					}				
					if($ii > 0){
						$touser = $users[rand(0, $ii-1)]; 
						
						$sql = "insert into chatlist (user1,user2) values('$fromuser','$touser')";  
						$result = mysql_query($sql, $conn); 
						if(!$result){ 
							echo mysql_error();
							die("failed to insert data error:".mysql_error());
						}
						$sql = "update users set flag=(select id from chatlist order by id desc limit 1) where user='$fromuser' or user='$touser'";  
						$result = mysql_query($sql, $conn); 
						if(!$result){ 
						//	echo mysql_error();
						//	die("failed to insert data error:".mysql_error());
						}
						
						mysql_close($conn);
						return $touser;
					}
				}else{ 
				//	echo mysql_error();
				//	die("failed to insert data error:".mysql_error());
				}
			}
		} else{
		//	echo mysql_error();
		//	die("failed to insert data error:".mysql_error());
		}
		mysql_close($conn);
		return "";
	} 
?>