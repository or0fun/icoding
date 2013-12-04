<?php
//$wechat_94qingObj = new wechat_moreInfo();
//echo $wechat_94qingObj->getJZW(); 
//echo $wechat_94qingObj->getCTS3('我爱你'); 
//echo  $wechat_weatherObj->getairbycity('101030100');
class wechat_moreInfo
{
	//脑筋急转弯
	public function getJZW()
	{		 
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'http://api.94qing.com/?type=jzw');
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_REFERER, 'http://api.94qing.com/');
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.97 Safari/537.22"); 
		curl_setopt($curl, CURLOPT_COOKIE, 'ProtectCCSession=94A51462-AB4E-45c0-AE4D-7A621363AD00' ); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		$data = curl_exec($curl);
		curl_close($curl); 
		if($data == false)
			return "Oops!这破网太慢啦，请再试一遍~";
		if(strstr($data, 'QQLite'))
			return '';
		return "【脑筋急转弯】\n".$data;
	} 	  
	//绕口令
	public function getRKL()
	{		 
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'http://api.94qing.com/?type=rkl');
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_REFERER, 'http://api.94qing.com/');
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.97 Safari/537.22"); 		
		curl_setopt($curl, CURLOPT_COOKIE, 'ProtectCCSession=94A51462-AB4E-45c0-AE4D-7A621363AD00' ); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		$data = curl_exec($curl);
		curl_close($curl); 
		if($data == false)
			return "Oops!这破网太慢啦，请再试一遍~"; 
		if(strstr($data, 'QQLite'))
			return '';
		return "【绕口令】\n".$data;
	} 	  
	//生活小常识
	public function getXZS()
	{		 
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'http://api.94qing.com/?type=xzs');
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_REFERER, 'http://api.94qing.com/');
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.97 Safari/537.22"); 
		
		curl_setopt($curl, CURLOPT_COOKIE, 'ProtectCCSession=94A51462-AB4E-45c0-AE4D-7A621363AD00' ); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		$data = curl_exec($curl);
		curl_close($curl); 
		if($data == false)
			return "Oops!这破网太慢啦，请再试一遍~"; 
		if(strstr($data, 'QQLite'))
			return '';
		return "【生活小常识】\n".$data;
	} 
	//藏尾诗
	public function getCWS($keyword)
	{
		$curlPost = 'm=2&t=7&p=1&fs=1&em=0&i='.$keyword;
		
		$ch = curl_init(); 
		curl_setopt($ch,CURLOPT_URL,'http://cts.showku.com/response.aspx?action=1'); 
		curl_setopt($ch, CURLOPT_HEADER, 0);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost); 
		$data = curl_exec($ch); 
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~';
		//echo $data;
		
		$data = json_decode($data, true);
		$data = $data['Output'];
		if ($data != null)
		{
			$data = str_replace('<br/>', "\n", $data);		
			$wlen = mb_strlen($keyword,'utf-8');
			$dlen = mb_strlen($data,'utf-8');
			if ($dlen >= $wlen * 8)
				return $keyword."\n\n".$data."\n\n【再发一次可以获取不一样的藏头诗】";
		}
		return "~~~~(>_<)~~~~ 好难..我作不出这首诗...";
	}
	
	public function getCTS3($keyword)
	{
		$curl = curl_init();
		$keyword = iconv('utf-8', 'gbk', $keyword);
		curl_setopt($curl, CURLOPT_URL, 'http://labs.soso.com/app.q?app=makepoem&type=1&style=lover&of=auto&words=seven&w='.urlencode($keyword));
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.97 Safari/537.22"); 		
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		$data = curl_exec($curl);
		curl_close($curl);
		if($data == false)
			return "Oops!这破网太慢啦，请再试一遍~";
		$data = iconv('gbk', 'utf-8//IGNORE', $data);
		//echo $data;
		if(!strstr($data, '<div class="conter">'))
			return "~~~~(>_<)~~~~ 好难..我作不出这首诗...\n试试藏尾诗，输入: \ncws".$keyword;
		else
		{
			$index = strpos($data, '<div class="conter">');
			$index = strpos($data, '<span', $index);
			$index2 = strpos($data, '</div>', $index);
			$data = substr($data, $index, $index2 - $index);
			$data = str_replace('<br />', "\n", $data);
			$data = str_replace('<br/>', "\n", $data);		
			while(strstr($data, "<")){ 
				$tt = strpos($data, "<"); 
				$uu = strpos($data, ">", $tt); 
				$data = substr($data, 0, $tt).substr($data, $uu + 1);
			}		
			$i = 1;
			$data = "	".$data;
			while(strstr($data, "	")){ 
				$tt = strpos($data, "	"); 
				$ll = substr($data, $tt + 1);
				if(strlen($ll)> 10)
				{
					$data = substr($data, 0, $tt)."【第".$i."首】\n".substr($data, $tt + 1);
					$i++;
				}else
				{
					$data = substr($data, 0, $tt).substr($data, $tt + 1);
				}
			}
			return $data;
		}
			
	}
	public function getCTS2($keyword)
	{
		$curlPost = 'm=0&t=7&p=1&fs=1&em=0&i='.$keyword;
		
		$ch = curl_init(); 
		curl_setopt($ch,CURLOPT_URL,'http://cts.showku.com/response.aspx?action=1'); 
		curl_setopt($ch, CURLOPT_HEADER, 0);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost); 
		$data = curl_exec($ch); 
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~';
		//echo $data;
		
		$data = json_decode($data, true);
		$data = $data['Output'];
		if ($data != null)
		{
			$data = str_replace('<br/>', "\n", $data);		
			$wlen = mb_strlen($keyword,'utf-8');
			$dlen = mb_strlen($data,'utf-8');
			if ($dlen > $wlen * 8)
				return $keyword."\n\n".$data."\n\n【再发一次可以获取不一样的藏头诗】";
		}
		return "~~~~(>_<)~~~~ 好难..我作不出这首诗...\n试试藏尾诗，输入: \ncws".$keyword;
	}
	//藏头诗
	public function getCTS($keyword)
	{		 
		return $this->getCTS3($keyword);
		if(!strstr($keyword, '藏头诗'))
		{
			$keyword = '藏头诗'.$keyword;
		}
		$link = 'http://api.ajaxsns.com/api.php?key=free&appid=0&msg='.urlencode($keyword);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $link);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($curl, CURLOPT_REFERER, 'http://api.94qing.com/');
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.97 Safari/537.22"); 
		//curl_setopt($curl, CURLOPT_COOKIE, 'ProtectCCSession=94A51462-AB4E-45c0-AE4D-7A621363AD00' ); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		$data = curl_exec($curl);
		curl_close($curl); 
		if($data == false)
			return "Oops!这破网太慢啦，请再试一遍~"; 
		$data = json_decode($data, true);
		$data = $data['content'];
		$index = strpos($data, '{');
		$data = substr($data, $index);
		$data = str_replace('{br}', "\n", $data);
		
		return str_replace('藏头诗', '', $keyword).'  '."\n".$data."\n\n【再发一次可以获取不同的藏头诗】";
	} 	  
}
?>