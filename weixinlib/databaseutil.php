<?php 
	 
	require_once 'mysqlHelper.php';
	require_once 'webHelper.php';
	//database operations--------------------
	//插入文本
	function d_inserttext($fromuser, $keyword, $createTime, $content, $ptime, $ctype="general", $moretext=""){ 
		
		if($ctype == "train" && strlen($moretext) == 0){
			return;
		}
		$totaltime = 5 - (time() - $createTime);
		if($totaltime < 0)
			return;
			
		//database connection
		$fromuser = addslashes($fromuser); 
		$keyword = addslashes($keyword);  
		$content = addslashes($content);   
		
		$mysqlHelperObj = new mysqlHelper();
		if($ctype != "train"){
			$sql = "INSERT INTO wxmsg (fromuser,msg,reply,revtime,reptime,ptype)VALUES('$fromuser','$keyword','$content',FROM_UNIXTIME($createTime),FROM_UNIXTIME($ptime),'$ctype')"; 
			$result = $mysqlHelperObj->execute($sql);
			if (!$result) {  
				return;
			} 
		}
		
		if(strlen($moretext) > 0){
			$moretext = addslashes($moretext); 
			$sql = "update users set more_flag='1', moretext='$moretext' where user = '$fromuser'"; 
			$result = $mysqlHelperObj->execute($sql);
			if (!$result) { 
				$sql = "INSERT INTO users (user, ptime)VALUES('$fromuser', FROM_UNIXTIME($ptime) )"; 
				$result = $mysqlHelperObj->execute($sql);
				$sql = "update users set more_flag='1', moretext='$moretext' where user = '$fromuser'"; 
				$result = $mysqlHelperObj->execute($sql);
				return;
			} 
		}   
	}
	//插入位置
	function d_insertlocation($fromuser, $Location_X, $Location_Y, $Scale, $Label){
		$fromuser = addslashes($fromuser);
		$Label = addslashes($Label); 
		$ptime = time();
		
		$mysqlHelperObj = new mysqlHelper();
 		$sql = "INSERT INTO userlocation (user,Location_X,Location_Y,Scale,Label, ptime)VALUES('$fromuser', '$Location_X', '$Location_Y',$Scale, $Label, FROM_UNIXTIME($ptime) )"; 
		$mysqlHelperObj->execute($sql);	
	}
	//插入用户
	function d_insertuser($fromuser){
		d_setsubscribe($fromuser);
		$mysqlHelperObj = new mysqlHelper();
 		$sql = "select count(id) as cc from users"; 
		$num = $mysqlHelperObj->queryValue($sql, 'cc');
		return $num;
	} 
	//获取分享内容
	function d_getshare($fromuser){		
		$mysqlHelperObj = new mysqlHelper();
		$sql = "select msg,fromuser from wxmsg where fromuser <> '$fromuser' and ptype='share' ".
		        " order by RAND() limit 1";  
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0]; 
			$msg = stripslashes($row['msg']);  
			$user = stripslashes($row['fromuser']); 
			$sql = "select city, name from users where user='$user'"; 
			$rows = $mysqlHelperObj->queryValueArray($sql);
			if($rows != ""){   
				$row = $rows[0];
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
		$mysqlHelperObj = new mysqlHelper(); 
		$sql = "select msg,fromuser from wxmsg where fromuser <> '$fromuser' and ptype='image' ".
		        " order by RAND() limit 1";
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];
			$image = stripslashes($row['msg']);  
			$user = stripslashes($row['fromuser']); 
			$sql = "select city, name from users where user='$user'"; 
			$rows = $mysqlHelperObj->queryValueArray($sql);
			if($rows != ""){   
				$row = $rows[0];
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
		$mysqlHelperObj = new mysqlHelper();
		$sql = "select repeat_str from users where user='$fromuser'";  
		 
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];	
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
		$mysqlHelperObj = new mysqlHelper();
		$mysqlHelperObj->execute($sqlstr);
	}
	 
	//我是谁
	function d_getusername($fromUsername){ 
		$mysqlHelperObj = new mysqlHelper();
		$sql = "SELECT sex,city, name FROM users  WHERE user='".$fromUsername."'";  
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0]; 
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
		$sql = "select id from shortaddr where addr='$url'"; 
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];
			$content = $row['id']; 
			return $content;
		}else{
			$sql = "insert into shortaddr(addr, ptype)values('$url', '$ptype')"; 
			if($mysqlHelperObj->execute($sql)){
				$sql = "select id from shortaddr order by id desc limit 1"; 
				$rows = $mysqlHelperObj->queryValueArray($sql);
				if($rows != ""){   
					$row = $rows[0]; 
					$content = $row['id']; 
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
		$mysqlHelperObj = new mysqlHelper();
		$re = '';
 		$sql = "select count(id) as cc from users where subscribe='1'"; 
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];
			$num = $row["cc"];
			$re .= $num;
			$sql = "select count(id) as cc from wxmsg"; 
			$rows = $mysqlHelperObj->queryValueArray($sql);
			if($rows != ""){   
				$row = $rows[0];
				$num = $row["cc"]; 
				$re .= ",".$num;  
			}
		}
		return $re;
	} 
	//获取更多
	function d_getmore($fromuser){
		$more = '';
		$mysqlHelperObj = new mysqlHelper();
 		$sql = "select moretext  from users where  user = '$fromuser' and more_flag='1'"; 
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];
			$more = $row["moretext"]; 
			$sql = "update users set more_flag='0' where user = '$fromuser'"; 
			$mysqlHelperObj->execute($sql);
		}
		if(strlen($more) > 0) 
			return "(接上)\n".stripslashes($more);
			
		$contentStr = '就这么多啦~';
		$motion = array("/:@>/:<@", "/:B-)","/::>","/::,@","/::D","/::)","/::P","/::$","/:,@-D","/:,@P");
		$contentStr .= $motion[rand(0, count($motion)-1)]; 
		return $contentStr;
	} 
	function d_setmore($fromuser, $str){
		$str = addslashes($str);
		$mysqlHelperObj = new mysqlHelper();
 		$sql = "update users set more_flag='1', moretext='$str' where user = '$fromuser'"; 
		$mysqlHelperObj->execute($sql);
	} 
	
	//订阅
	function d_setsubscribe($fromuser){
		$mysqlHelperObj = new mysqlHelper();
 		$sql = "update users set subscribe='1' where user = '$fromuser'";
		$mysqlHelperObj->execute($sql);
        $ptime = time();
        $sql = "insert into users (user, ptime)values('$fromuser', FROM_UNIXTIME($ptime) )";
        $mysqlHelperObj->execute($sql);
		
	}
	//取消订阅
	function d_setunsubscribe($fromuser){
		$mysqlHelperObj = new mysqlHelper();
		$ptime = time();
 		$sql = "update users set subscribe='0', unsubscribe= FROM_UNIXTIME($ptime)  where user = '$fromuser'"; 
		$mysqlHelperObj->execute($sql);
	}  
	
	function d_isautoreply($fromuser){
		$mysqlHelperObj = new mysqlHelper();
		$autoreply = 1;
 		$sql = "select autoreply  from users where  user = '$fromuser' "; 
		$mysqlHelperObj = new mysqlHelper();
		$value = $mysqlHelperObj->queryValue($sql, "autoreply");
		if($value != ""){
			$autoreply = $value;
		}else {		
			//d_setsubscribe($fromuser);
            //$mysqlHelperObj = new mysqlHelper();
            $sql = "update users set subscribe='1' where user = '$fromuser'";
            $mysqlHelperObj->execute($sql);
            //if($mysqlHelperObj->execute($sql) == false) {
                $ptime = time();
                $sql = "insert into users (user, ptime)values('$fromuser', FROM_UNIXTIME($ptime) )";
                $mysqlHelperObj->execute($sql);
            //}

		}  
		return $autoreply;
	}
	
	function d_setnewsflag($fromuser, $rn){
 		$sql = "update users set news_flag='$rn' where user = '$fromuser'"; 
		$mysqlHelperObj = new mysqlHelper();
		$mysqlHelperObj->execute($sql);
	} 
	function d_getnewsflag($fromuser){
		$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
		$rn = 0;  
 		$sql = "select news_flag  from users where  user = '$fromuser' "; 
		$mysqlHelperObj = new mysqlHelper();
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];
			$rn = $row["news_flag"];
		}
		return $rn;
	} 
	function d_setnewsstr($fromuser, $news_str){
		$news_str = addslashes($news_str); 
 		$sql = "update users set news_str='$news_str' where user = '$fromuser'"; 
		$mysqlHelperObj = new mysqlHelper();
		$mysqlHelperObj->execute($sql);
	} 
	function d_getnewsstr($fromuser){
 		$sql = "select news_str  from users where  user = '$fromuser' "; 
		$mysqlHelperObj = new mysqlHelper();
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];
			$news = $row["news_str"];
		}
		return stripslashes($news);
	} 
	
	
	function d_setzhidaostr($fromuser, $zhidao_str, $flag){
		
 		$news_str = addslashes($zhidao_str);
		if($flag == 0)
			$sql = "update users set zhidao_str='$zhidao_str' where user = '$fromuser'"; 
		else if($flag == 1)
			$sql = "update users set zhidao_str2='$zhidao_str' where user = '$fromuser'"; 
		$mysqlHelperObj = new mysqlHelper();
		$mysqlHelperObj->execute($sql);
	} 
	function d_getzhidaostr($fromuser, $flag){
		$news = '';
		if($flag == 0){
			$sql = "select zhidao_str  from users where  user = '$fromuser' "; 
			$mysqlHelperObj = new mysqlHelper();
			$rows = $mysqlHelperObj->queryValueArray($sql);
			if($rows != ""){   
				$row = $rows[0];
				$news = $row["zhidao_str"];
			}
		}
		else if($flag == 1){
			$sql = "select zhidao_str2  from users where  user = '$fromuser' ";  
			$mysqlHelperObj = new mysqlHelper();
			$rows = $mysqlHelperObj->queryValueArray($sql);
			if($rows != ""){   
				$row = $rows[0];
				$news = $row["zhidao_str2"];
			}
		}
		return stripslashes($news);
	} 
	//随机获取每日一条
	function d_getonestr(){		
 		$sql = "select words, time, id  from one where id >= (SELECT FLOOR(RAND() * (SELECT MAX(ID) FROM one))) order by RAND() limit 1"; 
		$mysqlHelperObj = new mysqlHelper();
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];
			$onestr = $row["time"]."\n".$row["words"];
			$id = $row["id"];
			$sql = "update one set pcount = pcount+1 where id = '$id'";  
			$mysqlHelperObj->execute($sql);
		}
		return stripslashes($onestr);
	} 
	function d_getonestrBydate($date){	
		$onestr = '';
 		$sql = "select words, time, id  from one where time = '$date'  order by RAND() limit 1"; 
		$mysqlHelperObj = new mysqlHelper();
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];
			$onestr = $row["time"]."\n".$row["words"];
			$id = $row["id"];
			$sql = "update one set pcount = pcount+1 where id = '$id'";  
			$mysqlHelperObj->execute($sql);
		}
		return stripslashes($onestr);
	} 
	function d_getonestrBywords($words){		
		$onestr = '';
 		$sql = "select words, time, id  from one where words  LIKE '%$words%' or time LIKE '%$words%' order by RAND()  limit 1"; 
		$mysqlHelperObj = new mysqlHelper();
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];
			$onestr .= $row["time"]."\n".$row["words"]."\n";
			$id = $row["id"];
			$sql = "update one set pcount = pcount+1 where id = '$id'"; 
			$mysqlHelperObj->execute($sql); 
		}
		return stripslashes($onestr);
	} 
	/////////////////////////////////////////////////////////////////////位置
	function d_setposition($fromuser, $position){
		$news_str = addslashes($zhidao_str);
		$sql = "update users set position='$position' where user = '$fromuser'";  
		$mysqlHelperObj = new mysqlHelper();
		$mysqlHelperObj->execute($sql);
	} 
	function d_getposition($fromuser){
		$position = '';
		$sql = "select position  from users where  user = '$fromuser' "; 
		$mysqlHelperObj = new mysqlHelper();
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];
			$position = $row["position"];
		}
		return stripslashes($position);
	} 
	
	/////////////////////////////////////////////////////////////////////通用
	function d_setvalue($fromuser, $key, $value){
		$news_str = addslashes($zhidao_str);  
		$sql = "update users set $key='$value' where user = '$fromuser'";  
		$mysqlHelperObj = new mysqlHelper();
		$mysqlHelperObj->execute($sql);
	} 
	function d_setvalues($fromuser, $key, $value, $key1, $value1){
		$news_str = addslashes($zhidao_str);  
		$sql = "update users set $key='$value', $key1='$value1' where user = '$fromuser'";  
		$mysqlHelperObj = new mysqlHelper();
		$mysqlHelperObj->execute($sql);
	} 
	function d_setvalues_3($fromuser, $key, $value, $key1, $value1, $key2, $value2){
		
		$news_str = addslashes($zhidao_str);
		$sql = "update users set $key='$value', $key1='$value1', $key2='$value2' where user = '$fromuser'";  
		$mysqlHelperObj = new mysqlHelper();
		$mysqlHelperObj->execute($sql);
	} 
	function d_getvalue($fromuser, $key){
		
		$key = '';
		$sql = "select $key  from users where  user = '$fromuser' "; 
		$mysqlHelperObj = new mysqlHelper();
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];
			$key = $row["$key"];
		}
		return stripslashes($key);
	} 
	function d_getvalues($fromuser, $key, $key1){
	
		$value = '';
		$value1 = '';
		
 		$sql = "select  $key , $key1  from users where  user = '$fromuser' ";
		$mysqlHelperObj = new mysqlHelper();
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];		
			$value = $row["$key"];
			$value1 = $row["$key1"];
		}
		return array(stripslashes($value),stripslashes($value1)) ;
	} 
	function d_getvalues_3($fromuser, $key, $key1, $key2){
		
		$value = '';
		$value1 = '';
		$value2 = '';
		
		$sql = "select  $key , $key1, $key2  from users where  user = '$fromuser' "; 
		$mysqlHelperObj = new mysqlHelper();
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];
			$value = $row["$key"];
			$value1 = $row["$key1"];
			$value2 = $row["$key2"];
		}
		return array(stripslashes($value),stripslashes($value1), stripslashes($value2)) ;
	} 
?>