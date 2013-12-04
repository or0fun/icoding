<?php 

//require_once 'textmsg.php';
require_once 'secretdb.php';
require_once "trans.php";
 

define('SECRET_KTA', 1); 
define('SECRET_TA', 2); 
define('SECRET_TEXT', 3); 
define('MM_TEXT', 4); 

class webchat_secretmsg
{
	public function dotext($keyword, $fromUsername)
	{
		$go = new Trans(); 
		$keyword = $go->t2c($keyword);  
		//database
		$ctype = 'general';
		$contentStr = '';
		$moretext = '';
				 
		if(strlen( $keyword ) > 0)
		{ 
			$flag = secret_getflag($fromUsername); 
			if($flag == SECRET_TEXT){
				secret_inserttext($fromUsername, $keyword);
				$user = secret_gettouser($fromUsername);
				$contentStr = "发送成功！\n如果TA也关注我，\n输入 @".$user." 就可以看到你的匿名纸条啦。快让身边的人也来吐露心声吧~~\n";
				$contentStr .= secret_ending();
				secret_updateflag($fromUsername, 0);
			}else{	  	 
				//查看最新
				if( preg_match("/^kk([\s\S]*)$/", trim(strtolower($keyword)), $match) ){ 
					$contentStr = secret_latestwords($fromUsername);  
					$ctype = 'kk';
				}
				else if($keyword == '有缘人'){
					$contentStr = secret_wordsbyuser(trim($match[1]));
				}
				//mm
				else if( preg_match("/^mm([\s\S]*)$/", trim(strtolower($keyword)), $match) ){ 						
					secret_updateflag($fromUsername, MM_TEXT);
					secret_inserttouser($fromUsername, '有缘人');
					$contentStr = "现在以@开头输入你想说的话, 可以说个笑话或者分享一段糗事啊或者分享你的心情，\n而且记得加上联系方式哦~ \nQQ、邮箱、微信号都行啦 这样有缘人才能联系上你~~"
					."\n\n输入  @有缘人  查看最新发布";
					$ctype = 'mm';
				}
				//查看ta
				else if( preg_match("/^kta([\s\S]*)$/", trim(strtolower($keyword)), $match) ){  
					secret_updateflag($fromUsername, SECRET_KTA);
					$contentStr = "以@开头，输入想要查看的的名字，如输入：\n@李小四";
					$ctype = 'kta';
				}
				//对她说
				else if(preg_match("/^ta([\s\S]*)$/", trim(strtolower($keyword)), $match)){ 
					secret_updateflag($fromUsername, SECRET_TA);
					$contentStr = "以@开头，先输入TA的名字，如输入：\n@李小四";
					$ctype = 'ta';
				}
				//某人名字
				else if(preg_match("/^@([\s\S]+)$/", trim($keyword), $match)){
					if(strlen(trim($match[1]))==0){
						$contentStr = "请在@后面加上名字哦~\n";
						$contentStr .= secret_ending();	
					}else if(trim($match[1]) == '有缘人'){
						$contentStr = secret_wordsbyuser(trim($match[1]));
					}else if($flag == MM_TEXT){
						secret_inserttext($fromUsername, trim($match[1]));
						$contentStr = "发送成功！\n你的有缘人看到了 一定会联系你的！！\n";
						$contentStr .= secret_ending();
						secret_updateflag($fromUsername, 0);
					}else if(mb_strlen(trim($match[1]),'utf-8') > 8){
						$touser = '有缘人';
						$index = strpos(trim($match[1]), ' ');
						if($index != null){
							$touser = substr(trim($match[1]), 0, $index);
						}else{
							$index = strpos(trim($match[1]), '，');
							if($index != null){
								$touser = substr(trim($match[1]), 0, $index);
							} 
						}
						secret_inserttouser($fromUsername, $touser);
						secret_inserttext($fromUsername, trim($match[1]));
						if($touser == '有缘人'){
							$contentStr = "发送成功！\n你的有缘人看到了 一定会联系你的！！\n";
						}else{ 
							$contentStr = "发送成功！\n如果TA也关注我，\n输入 @".$touser." 就可以看到你的匿名纸条啦。快让身边的人也来吐露心声吧~~\n";
						}
						$contentStr .= secret_ending();
						secret_updateflag($fromUsername, 0);
					}else if($flag == SECRET_KTA){
						$contentStr = secret_wordsbyuser(trim($match[1]));
					}else if($flag == SECRET_TA){
						secret_updateflag($fromUsername, SECRET_TEXT);
						secret_inserttouser($fromUsername, trim($match[1]));
						$contentStr = '现在输入你想说的话，可以留下署名哦 这样可能会有人回复你呢';
					}else{
						$contentStr = secret_wordsbyuser(trim($match[1]));
					//	$contentStr = secret_welcome();	
					}
					$ctype = '@';
				}
				//继续
				else if( strtolower($keyword) == 'jx'||
					strstr($keyword, "匿名小纸条")
				){ 
					$contentStr = secret_welcome();	
					$ctype = 'jx';
				}
				else{     
					$webchat_textmsgObj = new webchat_textmsg();
					list($contentStr, $ctype) = $webchat_textmsgObj->dotext($keyword, $fromUsername); 
					if($flag == MM_TEXT && mb_strlen($keyword,'utf-8') > 10){
						$touser = '有缘人';
						secret_inserttouser($fromUsername, $touser);
						secret_inserttext($fromUsername, $keyword);
						$contentStr .= "\n-----------\n发送成功！\n你的有缘人看到了 一定会联系你的！！\n"; 
						secret_updateflag($fromUsername, 0); 
					}					
				}
			}
		} 
		$namesArray = array( '傻逼','生殖器官','交配', '约炮');
		$contentStr = trim(str_replace($namesArray, '**', $contentStr));   
		return array($contentStr, $ctype);
	}
} 

?>