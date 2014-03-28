<?php  

require_once "databaseutil.php";
require_once "mysqlHelper.php";
    
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
		."让你更省流量，让生活更方便!\n\n"
		."聊天卖萌天气星座火车公交新闻查快递翻译笑话藏头诗等等，更多惊喜等你发现~\n\n"
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
		$contetstr = ''; 
		$sql = "select secret_touser from users where user ='$fromuser'"; 
		$mysqlHelperObj = new mysqlHelper();  
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];  
			$touser = $row['secret_touser'];  
			$sql = "insert into secretwords (user, touser, words) values('$fromuser','$touser', '$keyword')"; 
			$mysqlHelperObj->execute($sql);
			$sql = "update users set secretIndex = 0"; 
			$mysqlHelperObj->execute($sql);
		}  
	}
	//插入对话的人
	function secret_inserttouser($fromuser, $touser){
		$sql = "update users set secret_touser = '$touser' where user ='$fromuser'"; 
		$mysqlHelperObj = new mysqlHelper();  
		$mysqlHelperObj->execute($sql);
	}
	//获取对话的人
	function secret_gettouser($fromuser){  
		 
		$contetstr = ''; 
		$sql = "select secret_touser from users where user ='$fromuser'"; 
		$mysqlHelperObj = new mysqlHelper();  
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];  
			$contetstr = $row['secret_touser'];  
		} 
		return $contetstr;
	}
	//查看最新的话
	function secret_latestwords($fromuser){
		//database connection
		$contetstr = ''; 
		$secretIndex = 0;
 		$sql = "select secretIndex from users where user = '$fromuser'"; 
		$mysqlHelperObj = new mysqlHelper();  
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){   
			$row = $rows[0];  
			$secretIndex = $row['secretIndex'];
			//$sql = "select touser, words, UNIX_TIMESTAMP(ptime) as ptime from secretwords order by id desc limit $secretIndex , 10";  
			$sql = "select pcount, secretwords.id as sid, city, sex, touser, words,UNIX_TIMESTAMP(secretwords.ptime) as pptime from secretwords, users where users.user= secretwords.user order by secretwords.id desc limit $secretIndex , 10";
		
			$nn = 0;
			date_default_timezone_set('PRC');
			$idStack = array();
			$rows = $mysqlHelperObj->queryValueArray($sql);
			if($rows != ""){
				$len = count($rows);
				for($i = 0; $i < $len; $i++) { 
					$row = $rows[$i];
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
				$sql = "update users set secretIndex = $secretIndex where user = '$fromuser'";
				$mysqlHelperObj->execute($sql);
				$c = count($idStack);
				for($ii = 0; $ii < $c; $ii++){
					$sql = "update secretwords set pcount = pcount + 1 where id = '".$idStack[$ii]."'"; 
					$mysqlHelperObj->execute($sql);
				}
			} 
		}
		return $contetstr.secret_ending2();
	}
	
	//以名字查看
	function secret_wordsbyuser($touser){
		//database connection  		
		$contetstr = '';
		$secretIndex = 0; 	
		$sql = "select pcount, secretwords.id as sid, city, sex, touser, words,UNIX_TIMESTAMP(secretwords.ptime) as pptime from secretwords, users where touser like '%$touser%' and users.user= secretwords.user order by secretwords.id desc";
			
		//	$sql = "select city, touser, words, UNIX_TIMESTAMP(ptime) as ptime from secretwords where touser like '%$touser%' order by id desc limit 50";
			 
		$nn = 0;
		date_default_timezone_set('PRC');
		$mysqlHelperObj = new mysqlHelper();  
		$rows = $mysqlHelperObj->queryValueArray($sql);
		if($rows != ""){
			$len = count($rows);
			for($i = 0; $i < $len; $i++) { 
				$row = $rows[$i];
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
				$mysqlHelperObj->execute($sql);
			}
		}
		if(strlen($contetstr) == 0){
			$contetstr = "还没有发给 $touser 的告白哦~\n快叫$touser 的同学朋友也来关注我吧，兴许他们会对TA说出心里的告白哦~\n ";
		}
		return $contetstr.secret_ending();
	}
	//插入secret flag
	function secret_updateflag($fromuser, $flag){ 
		$sql = "update users set secret_flag = '$flag' where user = '$fromuser'"; 
		$mysqlHelperObj = new mysqlHelper();  
		$result = $mysqlHelperObj->execute($sql);
		if (!$result) {  
			return '';
		} 
	} 
	
	//查询secret flag
	function secret_getflag($fromuser){ 
		$sql = "select secret_flag from users where user = '$fromuser'"; 
		$mysqlHelperObj = new mysqlHelper();  
		$value = $mysqlHelperObj->queryValue($sql, "secret_flag");
		if($value != ""){
			return $value;
		}else{
			d_insertuser($fromuser);
		} 
		return 0;
	}   
?>