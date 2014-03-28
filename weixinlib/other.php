<?php
	
	require_once 'mysqlHelper.php';
//wishmsgs 
	function data_gettodaymsg($fromuser){
		$sql = "SELECT today.id as tid, msg FROM today, users  WHERE users.today_flag=today.flag and users.user='$fromuser'";
		$mysqlHelperObj = new mysqlHelper();  
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0]; 
			$content = $row['msg']; 
			$id = $row['tid']; 
			$sql = "update today set pcount = pcount+1 where id = '$id'";  
			$mysqlHelperObj->execute($sql);
			$sql = "update users set today_flag = today_flag+1 where user='$fromuser'";  
			$mysqlHelperObj->execute($sql);
			return $content."[回复't'查看更多]";
		}
	}      
//wishmsgs 
	function data_getwishmsg($ptype){
		$sql = "SELECT msg,id FROM wishmsg WHERE ptype='$ptype' ORDER BY RAND() limit 1";   
			
		$mysqlHelperObj = new mysqlHelper();  
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];
			$content = $row['msg']; 
			$id = $row['id']; 
			$sql = "update wishmsg set pcount = pcount+1 where id = '$id'";  
			$mysqlHelperObj->execute($sql);
			return $content;
		}
		return "";
	}      
	//mood
	function data_getmood(){
		$sql = "SELECT msg,id FROM mood  ORDER BY RAND() limit 1"; 
		$mysqlHelperObj = new mysqlHelper();   
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];  
			$content = $row['msg']; 
			$id = $row['id']; 
			$sql = "update mood set pcount = pcount+1 where id = '$id'";  
			$mysqlHelperObj->execute($sql);
			return $content;
		}
		
	} 
	//jzw
	function data_getjzw(){
		$id = rand(0, 3600);
		$sql = "SELECT content,id FROM jzw where id > '$id' limit 1";  
		$mysqlHelperObj = new mysqlHelper();  
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];  
			$content = $row['content']; 
			$id = $row['id']; 
			$sql = "update jzw set pcount = pcount+1 where id = '$id'";  
			$mysqlHelperObj->execute($sql);
			return $content;
		}
		
	}
	//English
	function data_getEnglish(){
		$id = rand(0, 590);
		$sql = "SELECT english,chinese,id FROM english where id > '$id' limit 1"; 
		$mysqlHelperObj = new mysqlHelper();   
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0]; 
			$content = $row['english']."\n".$row['chinese']; 
			$id = $row['id']; 
			$sql = "update english set pcount = pcount+1 where id = '$id'";  
			$mysqlHelperObj->execute($sql);
			$content = str_replace( '&#39;', "'", $content );
			return "【微英语】\n".$content;
		}
		
	}
	//kepu
	function data_getkepu(){
		$id = rand(0, 512);
		$sql = "SELECT content,id FROM kepu where id > '$id' limit 1";  
		$mysqlHelperObj = new mysqlHelper();  
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0]; 
			$content = $row['content']; 
			$id = $row['id']; 
			$sql = "update kepu set pcount = pcount+1 where id = '$id'";  
			$mysqlHelperObj->execute($sql);
			return $content;
		}
		
	}
	//jokes 
	function data_getjoke($fromuser){
	//	$query="SELECT content,id FROM jokes  ORDER BY RAND() limit 1";  
		$query = "SELECT jokes.id as tid, content FROM jokes, users  WHERE users.joke_flag <= jokes.id and users.user='$fromuser' order by jokes.id limit 1"; 
		
 		$mysqlHelperObj = new mysqlHelper();
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];
			$content = $row['content'];
			$id = $row['tid'];
			$sql = "update users set joke_flag =$id+1 where user='$fromuser'";  
			$mysqlHelperObj->execute($sql);
			$sql = "update jokes set pcount = pcount+1 where id = '$id'";  
			$mysqlHelperObj->execute($sql);
			return $content;
		} 
		$sql = "select content,id from jokes where id >= (SELECT FLOOR(RAND() * (SELECT MAX(ID) FROM jokes))) order by id limit 1 ";
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];
			$content = $row['content'];
			$id = $row['id'];
			$sql = "update jokes set pcount = pcount+1 where id = '$id'";  
			$mysqlHelperObj->execute($sql);
			return $content; 
		}  
		return "";
	} 

	
	function data_insertjoke($content){
		$content = addslashes($content);
		$ptype = addslashes($ptype);
		$ptime = time();  
		$sql = "INSERT INTO jokes (content,ptime)VALUES('$content', FROM_UNIXTIME($ptime) )"; 
		$mysqlHelperObj = new mysqlHelper();
		return $mysqlHelperObj->execute($sql);
	} 
	function getending()
	{
		$i = rand(0, 100);
		if ($i % 20 == 0)
		{
			return "\n\n觉得好的话，就把我推荐给你的朋友吧~~\n";
		}
		if ($i % 8 == 0)
		{
			return "\n\n觉得好的话，恳求您能点开链接资助我们，让iCoding能正常运行，走得更远，谢谢！http://t.cn/8sypmIy\n";
		}

		return '';
	}
?>