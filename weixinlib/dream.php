<?php
 
class webchat_dream
{
	public function analysis($searchwords)
	{		
		if(mb_strlen($searchwords,'utf-8') > 8){ 
			$searchwords = mb_substr($contentStr, 0, 8, 'utf-8');
		}
		if(strlen(trim($searchwords))==0){
			$contentStr = "�ף�Ҫ������ֻҪ������'�μ�'�����֣�\n�ٺ����������ξ����������磺�μ���ʦ";
		}else{						
			$contentStr = $this->get($searchwords);  
			if(strlen($contentStr) == 0){
				$contentStr = 'Sorry.������ҽⲻ��'."/::D".'����˵�ļ򵥵㣬���磺�μ���'; 
			}else{ 
				$contentStr = $contentStr."\n�������ο���";
			}
		}		
		return  iconv("gb2312", "UTF-8", $contentStr);
	}
	function get($words)
	{  
		// ��ʼ��һ�� cURL ����  
		$curl = curl_init();
		// ��������Ҫץȡ��URL 
		curl_setopt($curl, CURLOPT_URL, 'http://www.zgjm.org/plus/search.php?q='.urlencode(iconv("UTF-8", "gb2312", $words)));
		// ����header
		curl_setopt($curl, CURLOPT_HEADER, 1);
		// ����cURL ������Ҫ�������浽�ַ����л����������Ļ�ϡ�
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// ����cURL ������ʱ�䳬ʱ
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		// ����cURL��������ҳ
		$data = curl_exec($curl);
		// �ر�URL����
		curl_close($curl);
		if($data == false)
			return "Oops!������̫������������һ��~";   
		$in = strpos($data, '<dd>');
		$in2 = strpos($data, '</dd>', $in + 4);
		$tmp = substr($data, $in, $in2 - $in);
		$re = '';
		while(strstr($tmp, '<')){
			$index = strpos($tmp, '<');
			$index2 = strpos($tmp, '>');
			if($index == 0){
				$tmp = substr($tmp, $index2+1);
			}else{
				$re .= substr($tmp, 0, $index);
				$tmp = substr($tmp, $index2+1);
			}
		}
		if(strlen($tmp) > 0)
			$re .= $tmp; 
		return  $re;
	} 	    

}
?>