<?php

//$webchat_chengyuObj = new webchat_chengyu();
//echo $webchat_chengyuObj->getdata('一', 0);
class webchat_chengyu
{	
	public function getdata($keyword, $flag)
	{
		$curlPost = 'f_key='.urlencode(iconv("utf-8","gb2312", $keyword))
		.'&f_type=chengyu'
		.'&f_type2='.$flag;
		
		$ch = curl_init(); 
		curl_setopt($ch,CURLOPT_URL,'http://cy.5156edu.com/serach.php'); 
		curl_setopt($ch, CURLOPT_HEADER, 0);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost); 
		$data = curl_exec($ch); if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~';
		
		$re = '';
		$i = 0;
		while(strstr($data, '<u>')){
			$index = strpos($data, '<u>');
			$index2 = strpos($data, '</u>', $index);
			$word = substr($data, $index + 3, $index2 - $index - 3);
			$index = strpos($data, "<td width='80%'>", $index2);
			$index2 = strpos($data, '</td>', $index);
			$meaning = substr($data, $index + strlen("<td width='80%'>"), $index2 - $index - strlen("<td width='80%'>"));
			
			$re .= '【'.iconv("gbk","utf-8", $word).'】';
			$re .= "\n";
			$re .= iconv("gbk","utf-8", $meaning);
			$re .= "\n";
			$i++;
			if($i == 10)
				break;
			$data = substr($data, $index2);
		} 
		 
		return $re; 

	}
}

?>