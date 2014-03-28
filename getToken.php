<?php
	require_once 'weixinlib/mysqlHelper.php';
	
//	$access_tokenObj = new access_token();
//	$access_tokenObj->create_menu();
	class access_token {
	
		public function getTokenFromDataBase(){
			date_default_timezone_set('Asia/Shanghai');
			$mysqlHelperObj = new mysqlHelper();
			$sql = "select token, tokenTime from icoding order by id desc limit 1";
			$rows = $mysqlHelperObj->queryValueArray($sql);
			if($rows == ''){
				return '';
			}
			$token = $rows[0]['token'];
			$tokenTime = $rows[0]['tokenTime'];
			if(time() - $tokenTime < 7000){
				return $token;
			}
			return '';
		}
		public function setTokenIntoDataBase($token){
			date_default_timezone_set('Asia/Shanghai');
			$mysqlHelperObj = new mysqlHelper();
			$sql = "insert into icoding (token, tokenTime) values('$token','".time()."')";
			$mysqlHelperObj->execute($sql);
		}
		public function getToken() {
			$appid = "wx79a2ebad77ab6d2b";
			$secret = "bf1054a7f4f652165d1f69f727fb2af0";
		
			$token = $this->getTokenFromDataBase();
			if($token == ''){
				$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
				$curl = curl_init(); 
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);	
				curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31");		 
				$content = curl_exec($curl);
				curl_close($curl);
				$data = json_decode($content, true);
				$token = $data['access_token'];
				$this->setTokenIntoDataBase($token);
			}
			return $token;
		}
		public function setUserInfo($openID){
			$token = $this->getToken();
			if($token == ''){
				return '';
			}
			$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$openID;
			$curl = curl_init(); 
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);	
			curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31");		 
			$content = curl_exec($curl);
			curl_close($curl);
			$data = json_decode($content, true);
			$mysqlHelperObj = new mysqlHelper();
			$sql = "select id from users where user='".$openID."'";
			$id = $mysqlHelperObj->queryValue($sql, 'id');
			if($id == ''){
				$sql = "insert into users (user) values('".$openID."')";
				$mysqlHelperObj->execute($sql);
			}
			$sql = "update users set name='".$data['nickname'].
			"', city='".$data['city'].
			"',province='".$data['province'].
			"',sex='".$data['sex'].
			"',country='".$data['country'].
			"',headimgurl='".addslashes($data['headimgurl']).
			"', where user='".$openID."'";
			$mysqlHelperObj->execute($sql);
			return 'true';
			
		}
		public function create_menu() {
			$token = $this->getToken();
			$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$token;
			$curlPost = '{
							 "button":[
							 {	
								  "type":"click",
								  "name":"每日一条",
								  "key":"iCoding_ONE"
							  },
							  {
								   "type":"click",
								   "name":"微英语",
								   "key":"iCoding_ENGLISH"
							  },
							  {
								   "name":"无聊",
								   "sub_button":[
								   {	
									   "type":"click",
									   "name":"爆笑",
									   "key":"iCoding_JOKE"
									},
									{
									   "type":"click",
									   "name":"脑筋急转弯",
									   "key":"iCoding_JZW"
									},
									{
									   "type":"click",
									   "name":"小科普",
									   "key":"iCoding_WHY"
									},
									{
									   "type":"click",
									   "name":"头条新闻",
									   "key":"iCoding_NEWS"
									},
									{
									   "type":"click",
									   "name":"随机点歌",
									   "key":"iCoding_MUSIC"
									}]
							   }]
						 }';
			$curl = curl_init(); 
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);	
			curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31");		 
			curl_setopt($curl, CURLOPT_POST, 1); 
			curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost); 
			$content = curl_exec($curl);
			curl_close($curl);
			echo $content;
		}
	}
 ?>