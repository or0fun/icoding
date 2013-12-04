<?php
	
//wishmsgs 
	function data_gettodaymsg($fromuser){
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "today"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if (!$conn){
//			die("failed to connect mysql:" . mysql_error());
			return "";
		}  
		mysql_select_db($database_conn, $conn);
		mysql_query("set names 'utf8'");
		//database operation 
		$sql = "SELECT today.id as tid, msg FROM today, users  WHERE users.today_flag=today.flag and users.user='$fromuser'";  
		$result = mysql_query($sql, $conn); 
		if(!$result){   
			mysql_close($conn);
			return "";
		} else {    
			$row = mysql_fetch_array($result); 
			$content = $row['msg']; 
			$id = $row['tid']; 
			$sql = "update $table_comm set pcount = pcount+1 where id = '$id'";  
			$result = mysql_query($sql, $conn); 
			$sql = "update users set today_flag = today_flag+1 where user='$fromuser'";  
			$result = mysql_query($sql, $conn); 
			mysql_close($conn);
			return $content."[回复't'查看更多]";
		}
	}      
//wishmsgs 
	function data_getwishmsg($ptype){
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "wishmsg"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn);
			mysql_query("set names 'utf8'");
			//database operation 
			$sql = "SELECT msg,id FROM $table_comm  WHERE ptype='$ptype' ORDER BY RAND() limit 1";   
			if($result = mysql_query($sql, $conn)){    
				$row = mysql_fetch_array($result); 
				$content = $row['msg']; 
				$id = $row['id']; 
				$sql = "update $table_comm set pcount = pcount+1 where id = '$id'";  
				$result = mysql_query($sql, $conn); 
				mysql_close($conn);
				return $content;
			}
			mysql_close($conn);
		}  
		return "";
	}      
	//mood
	function data_getmood(){
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "mood"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if (!$conn){
//			die("failed to connect mysql:" . mysql_error());
			return "";
		}  
		mysql_select_db($database_conn, $conn);
		mysql_query("set names 'utf8'");
		//database operation
		$sql = "SELECT msg,id FROM $table_comm  ORDER BY RAND() limit 1";  
		$result = mysql_query($sql, $conn); 
		if(!$result){   
			mysql_close($conn);
			return "";
		} else {    
			$row = mysql_fetch_array($result); 
			$content = $row['msg']; 
			$id = $row['id']; 
			$sql = "update $table_comm set pcount = pcount+1 where id = '$id'";  
			$result = mysql_query($sql, $conn); 
			mysql_close($conn);
			return $content;
		}
		
	} 
	//jzw
	function data_getjzw(){
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "jzw"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if (!$conn){
//			die("failed to connect mysql:" . mysql_error());
			return "";
		}  
		mysql_select_db($database_conn, $conn);
		mysql_query("set names 'utf8'");
		//database operation
		$id = rand(0, 3600);
		$sql = "SELECT content,id FROM $table_comm where id > '$id' limit 1";  
		$result = mysql_query($sql, $conn); 
		if(!$result){   
			mysql_close($conn);
			return "";
		} else {    
			$row = mysql_fetch_array($result); 
			$content = $row['content']; 
			$id = $row['id']; 
			$sql = "update $table_comm set pcount = pcount+1 where id = '$id'";  
			$result = mysql_query($sql, $conn); 
			mysql_close($conn);
			return $content;
		}
		
	}
	//English
	function data_getEnglish(){
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "english"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if (!$conn){
//			die("failed to connect mysql:" . mysql_error());
			return "";
		}  
		mysql_select_db($database_conn, $conn);
		mysql_query("set names 'utf8'");
		//database operation
		$id = rand(0, 590);
		$sql = "SELECT english,chinese,id FROM $table_comm where id > '$id' limit 1";  
		$result = mysql_query($sql, $conn); 
		if(!$result){   
			mysql_close($conn);
			return "";
		} else {    
			$row = mysql_fetch_array($result); 
			$content = $row['english']."\n".$row['chinese']; 
			$id = $row['id']; 
			$sql = "update $table_comm set pcount = pcount+1 where id = '$id'";  
			$result = mysql_query($sql, $conn); 
			mysql_close($conn);
			$content = str_replace( '&#39;', "'", $content );
			return "【微英语】\n".$content;
		}
		
	}
	//kepu
	function data_getkepu(){
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "kepu"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if (!$conn){
//			die("failed to connect mysql:" . mysql_error());
			return "";
		}  
		mysql_select_db($database_conn, $conn);
		mysql_query("set names 'utf8'");
		//database operation
		$id = rand(0, 512);
		$sql = "SELECT content,id FROM $table_comm where id > '$id' limit 1";  
		$result = mysql_query($sql, $conn); 
		if(!$result){   
			mysql_close($conn);
			return "";
		} else {    
			$row = mysql_fetch_array($result); 
			$content = $row['content']; 
			$id = $row['id']; 
			$sql = "update $table_comm set pcount = pcount+1 where id = '$id'";  
			$result = mysql_query($sql, $conn); 
			mysql_close($conn);
			return $content;
		}
		
	}
	//jokes 
	function data_getjoke($fromuser){
		//连接
 		$hostname_conn = "mysql1403.ixwebhosting.com"; 
 		$port_conn = "3306"; 
		$database_conn = "C360953_fangjun";  
		$table_comm = "jokes"; 
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320";   
		
		$mysqli = mysqli_init();
		if (!$mysqli) {
			return '';
		} 
		if (!$mysqli->options(MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 0')) {
			return '';
		}

		if (!$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 3)) {
			return '';
		}

		if (!$mysqli->real_connect($hostname_conn,$username_conn,$password_conn,$database_conn,$port_conn)) {
			return '';
		} 
		$result=$mysqli->query("set names 'utf8'");
	//	$query="SELECT content,id FROM jokes  ORDER BY RAND() limit 1";  
		$query = "SELECT jokes.id as tid, content FROM jokes, users  WHERE users.joke_flag <= jokes.id and users.user='$fromuser' order by jokes.id limit 1"; 
		$result = $mysqli->query($query);
		if($result){    
			$row = $result->fetch_object();
			if($row){
				$content = $row->content;  
				$id = $row->tid; 
				$query = "update users set joke_flag =$id+1 where user='$fromuser'";  
				$result = $mysqli->query($query); 
				$query = "update jokes set pcount = pcount+1 where id = '$id'";  
				$result = $mysqli->query($query); 
				return $content; 
			}   
		} 
		$query = "select content,id from jokes where id >= (SELECT FLOOR(RAND() * (SELECT MAX(ID) FROM jokes))) order by id limit 1 ";
		$result = $mysqli->query($query);
		if(!$result){    
			return "";
		} else {    
			$row = $result->fetch_object();
			if($row){
				$content = $row->content;  
				$id = $row->id; 
				$query = "update jokes set pcount = pcount+1 where id = '$id'";  
				$result = $mysqli->query($query); 
				return $content; 
			}  
			return "";
		} 
	} 

	
	function data_insertjoke($content){
		//database connection
		$content = addslashes($content);
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
 			$sql = "INSERT INTO $table_comm (content,ptime)VALUES('$content', FROM_UNIXTIME($ptime) )"; 
			if(mysql_query($sql,$conn)){  
				return true;
			} else { 
			}
			mysql_close($conn);
		} 
		
		return false;
	} 
	function getending()
	{
		$i = rand(0, 100);
		if ($i % 20 == 0)
		{
			return "\n\n觉得好的话，就把我推荐给你的朋友吧~~\n";
		}
		return '';
	}
?>