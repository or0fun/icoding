<?php
	function a_getarticle($fromUsername,$toUsername){ 
		if(!a_getaflag($fromUsername)){
			return "";
		} 
		$textTpl = "<xml>
				 <ToUserName><![CDATA[%s]]></ToUserName>
				 <FromUserName><![CDATA[%s]]></FromUserName>
				 <CreateTime>%s</CreateTime>
				 <MsgType><![CDATA[news]]></MsgType>
				 <ArticleCount>3</ArticleCount>
				 <Articles>
				 <item>
				 <Title><![CDATA[%s]]></Title> 
				 <Description><![CDATA[每天日第一条将回复你当天推荐文章，科技，新闻无所不有。]]></Description>
				 <PicUrl><![CDATA[%s]]></PicUrl>
				 <Url><![CDATA[%s]]></Url>
				 </item>
				 <item>
				 <Title><![CDATA[%s]]></Title>
				 <Description><![CDATA[description]]></Description>
				 <PicUrl><![CDATA[%s]]></PicUrl>
				 <Url><![CDATA[%s]]></Url>
				 </item>
				 <item>
				 <Title><![CDATA[%s]]></Title>
				 <Description><![CDATA[description]]></Description>
				 <PicUrl><![CDATA[%s]]></PicUrl>
				 <Url><![CDATA[%s]]></Url>
				 </item>
				 </Articles>
				 <FuncFlag>1</FuncFlag>
				</xml> ";
		$time = time();
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn);
			mysql_query("set names 'utf8'");
			//database operation
 			$sql = "select title,picurl,url from articlelist where flag = '1'"; 
			$result = mysql_query($sql,$conn);
			if($result){   
				$title = array();
				$picurl = array();
				$url = array();
				$ii = 0;
				while($row = mysql_fetch_array($result)){
					$title[$ii] = $row["title"];
					$picurl[$ii] = $row["picurl"];
					$url[$ii] = $row["url"];
					$ii++;
				} 
				if($ii == 0){
					mysql_close($conn);
					return "";
				} 
				$sql = "update articlelist set pcount=pcount+1 where flag = '1'"; 
				$result = mysql_query($sql,$conn);
				$sql = "update users set aflag = '0' where user = '$fromUsername'"; 
				$result = mysql_query($sql,$conn);
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 
					$title[0], $picurl[0],$url[0],$title[1], $picurl[1],$url[1],$title[2], $picurl[2],$url[2]);  
				mysql_close($conn);
				return $resultStr;
			}  
		
			mysql_close($conn);	
		}
		return "";
	}
	//判断是否已获取推荐图文
	function a_getaflag($fromUsername){
		$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
		$database_conn = "C360953_fangjun";   
		$username_conn = "C360953_fangjun";
		$password_conn = "Fangjun65320"; 
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		if ($conn){ 
			mysql_select_db($database_conn, $conn);
			mysql_query("set names 'utf8'");
			//database operation
 			$sql = "select id from users where user = '$fromUsername' and aflag='1'"; 
			$result = mysql_query($sql,$conn);
			if($result){   
				if(mysql_fetch_array($result)){ 
					mysql_close($conn);
					return true; 
				}
			}
			mysql_close($conn);
		}
		return false;
	}

?>