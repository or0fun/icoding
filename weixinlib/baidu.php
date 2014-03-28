<?php  
//$webchat_baiduObj = new webchat_baidu();
//echo $webchat_baiduObj->getPOI('31.246513,121.544087','饭店',0);
//echo $webchat_baiduObj->getwenwen2('http://wenwen.soso.com/z/q241607829.htm?w=%B0%AE%C7%E9%CA%C7%CA%B2%C3%B4&amp;spi=1&amp;sr=8&amp;w8=%E7%88%B1%E6%83%85%E6%98%AF%E4%BB%80%E4%B9%88&amp;qf=20&amp;rn=2396118&amp;qs=4&amp;sid=0a8804ad00005566517bdb1cdc1c94a2&amp;uid=0&amp;ch=w.search.8');
//$webchat_baiduObj->getbaike('爱情');
//echo $webchat_baiduObj->getzhidao('爱情', '', 1);
//echo $webchat_baiduObj->getask('http://www.120ask.com/question/2008/4699411.htm');
//echo $webchat_baiduObj->getnews("倒计时", 0, "");

require_once "webHelper.php";

class webchat_baidu
{	
	//获取新闻 
	public function getnews($keyword, $rn, $fromuser){   
		if(strlen(trim(	$keyword) ) == 0)
			return '查看新闻，请在新闻后面加上关键字，如：新闻 爱情';
		$countrn = (($rn+3) / 10 + 1)*10;
 		$curl = curl_init();  
		curl_setopt($curl, CURLOPT_URL, 'http://news.baidu.com/ns?word='.urlencode($keyword).'&pn='.$rn.
                    '&ie=utf-8&tn=newstitle&from=news&sr=0&&clk=sortbytime&cl=2&ct=0&prevct=1&rn=3');
	 	curl_setopt($curl, CURLOPT_HEADER, 0);  
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 4); 
		$data = curl_exec($curl);  
		curl_close($curl);  
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~';
	 
		$index = strpos($data, '<ul>');
		$index2 = strpos($data, '</ul>', $index);
		$data = substr($data, $index, $index2 - $index);
		$tmp = $data;
		$re = "";
		$more = true;
		for($i = $rn; $i < $rn+3; $i++){
			$index = strpos($tmp, '<h3 class="c-title">');
			if($index === false){
				$more = false;
				break;
			}
			$index = strpos($tmp, '<a href="', $index);

			$index2 = strpos($tmp, '"', $index + 10); 
			$link = substr($tmp, $index + 9, $index2 - $index - 9); 
			
			$index = strpos($tmp, '>', $index2);
			$index2 = strpos($tmp, '</a>', $index); 
			$words = substr($tmp, $index+1, $index2 - $index - 1);  
			$words = str_replace('<font color=#C60A00>', '', $words);
			$words = str_replace('</font>', '', $words); 
			$words = str_replace('<em>', '', $words);
			$words = str_replace('</em>', '', $words);
			
			$index = strpos($tmp, '<span class="c-author">', $index2);
			$index2 = strpos($tmp, '</span>', $index); 
			$news = substr($tmp, $index + strlen('<span class="c-author">'), $index2 - $index - strlen('<span class="c-author">'));
			
			$news = str_replace('&nbsp;', "\n", $news);
			
			//$re .= $news."\n"." <a href=\"".$link."\" >".$words."</a>";
			$re .=$news."<a href=\"".$link."\" >".$words."</a>";
			$tmp = substr($tmp, $index2); 
			
		}  
		if(strlen($re) == 0)
			return 'Sorry！木有相关新闻哦~';
			
		d_setnewsflag($fromuser, $rn+3);
		$re = str_replace('&quot;', '"', $re);
		if($more)
			$re .= "\n【获取更多回复p】"; 
		return $re; 
	}
	//获取百度知道答案摘要，正常有两个答案，否则就一个
	public function getzhidao($keyword, $fromuser, $flag){
		
		$url = 'http://zhidao.baidu.com/search?lm=0&rn=10&pn=0&fr=search&ie=utf-8&word='.urlencode($keyword).'&f=sug';
		$webHelperObj = new webHelper();
		$data = $webHelperObj->get($url);
		
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~';
		
		/////////////处理百科，百度经验
		if(strstr($data, "<dl class=\"line\">")){
			$re = mb_convert_encoding($data, "UTF-8", "GBK");
		
			$index = strpos($re, '<dl class="line">');
			$index2 = strpos($re, '</dl>', $index);
			$re = trim(substr($re, $index, $index2 - $index));
			
			$index = strpos($re, '<a href="');
			$index2 = strpos($re, '"', $index + 15);
			$link = trim(substr($re, $index+9, $index2 - $index-9));
				
			$index = strpos($re, '<p>', $index2);
            if($index != false) {
                $index2 = strpos($re, '</p>', $index);
                $re = trim(substr($re, $index + 3, $index2 - $index - 3));
            }else{
                $index = strpos($re, '<dd', $index2);
                $index2 = strpos($re, '</dd>', $index);
                $re = trim(substr($re, $index, $index2 - $index));
            }
			
			$re = str_replace('<em>','', $re);
			$re = str_replace('</em>','', $re);
			$re = str_replace('&lt;','<', $re);
			$re = str_replace('&gt;','>', $re);
			
			if(strstr($re, '...')){
				d_setzhidaostr($fromuser, $link, 0);
				$re .= "\n【获取详细回复a】";
			}
			$answers = $re;
		}
		else {
			/////////////处理答案
			preg_match_all("|<dl class=\"dl\" data-fb=\"([\s\S]*)<\/dl>|U", $data, $ar); 
			$c = count($ar[0]);
			$n = 0;
			$link = '';   
			$answers = ''; //回复变量
			if($c == 0){
				preg_match_all("|<span class=\"date\">([\s\S]{20,})<\/p>|U", $data, $ar); 
				$c = count($ar[0]); 
				if($c == 0){
					return '';
				} 
				//答案一
				$n = $this->getrand($c-1);
				$re = $ar[1][$n];
				
				$index = strpos($re, '<'); 
				$solve = substr($re, 0,  $index); 
				
				$index = strpos($re, '<p class="ans">');
				$re = substr($re, $index + strlen('<p class="ans">')); 
				
				$index = strpos($re, '<a');
				if($index !== false){ 
					$index = strpos($re, 'href=', $index);
					$index2 = strpos($re, 'target', $index);
					$link = substr($re, $index + 6, $index2 - $index - 6 - 2);
					$re = substr($re, 0, strpos($re, '<a')); 
				}
				$re = str_replace('<em>','', $re);
				$re = str_replace('</em>','', $re);
				$re = str_replace('&lt;','<', $re);
				$re = str_replace('&gt;','>', $re);
				if(strlen($link) > 0){
					d_setzhidaostr($fromuser, $link, 0);
					$re .= "\n【获取详细回复a】";
				}
				//不是提问
				if($flag == 0)
					return $re;
					
				//if(strlen($solve) > 0)
				//	$re = '回答时间：'.$solve."\n".$re;		
				$answers .= $re;
				
				//不是提问
				if($flag == 0)
					return $answers;
					
				//答案二
				$n = ($n + 1)%$c;
				$re = $ar[1][$n];
				
				$index = strpos($re, '<'); 
				$solve = substr($re, 0,  $index);  
				
				$index = strpos($re, '<p class="ans">');
				$re = substr($re, $index + strlen('<p class="ans">'));
				
				$index = strpos($re, '<a');
				if($index !== false){ 
					$index = strpos($re, 'href=', $index);
					$index2 = strpos($re, 'target', $index);
					$link = substr($re, $index + 6, $index2 - $index - 6 - 2);
					$re = substr($re, 0, strpos($re, '<a')); 
				}
				$re = str_replace('<em>','', $re);
				$re = str_replace('</em>','', $re);
				$re = str_replace('&lt;','<', $re);
				$re = str_replace('&gt;','>', $re);
				if(strlen($link) > 0){
					d_setzhidaostr($fromuser, $link, 1);
					$re .= "\n【获取详细回复b】";
				}
				//if(strlen($solve) > 0)
				//	$re = '回答时间：'.$solve."\n".$re;		
				$answers .= "\n\n-----------------\n\n";
				$answers .= $re;
				
			}
			else{  
				//答案一
				$n = $this->getrand($c-1);
				$index = 0; 
				$re = $ar[1][$n];
				$re = $this->handlezhidao($re);
				if(strstr($re, '...')){
					d_setzhidaostr($fromuser, $link, 0);
					$re .= "\n【获取详细回复a】";
				}
				//不是提问
				if($flag == 0)
					return $re;
				 
				$answers .= $re;
				
				//答案二
				$n = ($n + 1)%$c;
				$index = 0; 
				$re = $ar[1][$n]; 
				$re = $this->handlezhidao($re);
				
				if(strstr($re, '...')){
					d_setzhidaostr($fromuser, $link, 1);
					$re .= "\n【获取详细回复b】";
				}
				
				//合并两个答案
				$answers .= "\n\n-----------------\n\n";
				$answers .= $re;
				
			}
		}
		$answers = $this->getcontent($answers);
		return $answers."\n\n☆回答的不对的话，换种表达方式☆";
	}  
	
	public function handlezhidao($re) {
	
		$re = mb_convert_encoding($re, "UTF-8", "GBK");
		
		$index = strpos($re, '<a href="', $index);
		$index2 = strpos($re, '"', $index + 10);
		$link = trim(substr($re, $index+9, $index2 - $index-9));
			
		$index = strpos($re, '<dd class="dd answer">', $index2);
		$index2 = strpos($re, '</dd>', $index);
		$len = strlen("<dd class=\"dd answer\"><i class=\"i-answer-text\">答：</i>");
		$re = trim(substr($re, $index + $len, $index2 - $index - $len));
		
		$re = str_replace('<em>','', $re);
		$re = str_replace('</em>','', $re);
		$re = str_replace('&lt;','<', $re);
		$re = str_replace('&gt;','>', $re);
		
		return $re;
	}
	//百度知道，通过链接，获取内容
	public function getzhidao2($fromuser, $flag){  
		$link = d_getzhidaostr($fromuser, $flag);
		if(strlen($link) == 0)
			return '你想说什么呀';
		if(strstr($link, 'soso.com'))
			return $this->getwenwen2($link);
		if(strstr($link, '120ask.com'))
			return $this->getask($link);
		if(strstr($link, 'jingyan.baidu.com'))
			return $this->getjingyan($link);
			
		$webHelperObj = new webHelper();
		$data = $webHelperObj->get($link);
		
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~';
		$index = 0;
		if( preg_match("/answer-([\d]+)/", $link, $ar)){
			$index = strpos($data, 'answer-content-'.$ar[1], $index);
		}
		$index = strpos($data, 'accuse="aContent"', $index);
		$index = strpos($data, '>', $index);
		$index2 = strpos($data, '</pre>', $index);
		$re = substr($data, $index + 1, $index2 - $index - 1); 
		$re = iconv("gbk","utf-8", $re);
		
		$re = str_replace('<pre>',"", $re); 
		$re = str_replace('</pre>',"", $re); 
		$re = str_replace('<br />',"\n", $re); 
		$re = str_replace('<br>',"\n", $re); 
		$re = str_replace('<br _extended="true">',"\n", $re); 
		$re = str_replace('<p _extended="true">',"", $re); 
		$re = str_replace('</p>',"\n", $re); 
		$re = str_replace('&nbsp;'," ", $re);  
		
		return $re; 
	}
    //baidu经验
    public function getjingyan($link) {
        
		$webHelperObj = new webHelper();
		$data = $webHelperObj->get($link);
		
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~';
        
        $index  = strpos($data, '<div class="content-listblock-text">');
        $index2 = strpos($data, '</div>', $index);
        $len = strlen('<div class="content-listblock-text">');
        $re = substr($data, $index + $len, $index2 - $index - $len);
        
        
		$re = $this->getcontent($re);
        
        return $re;
        
    }
	
	//通过链接获取 搜搜百科 内容
	public function getsosobaike($link){
		if(strlen($link) == 0)
			return '你想说什么呀';
 		$curl = curl_init();  
		curl_setopt($curl, CURLOPT_URL,  $link); 
	 	curl_setopt($curl, CURLOPT_HEADER, 0); 
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 4); 
		$data = curl_exec($curl);  
		curl_close($curl);  
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~';
		$index = 0; 
		//$index = strpos($data, '<div class="abstract_content">', $index);
		//if($index === false)
		//	$index = strpos($data, '<p class="abstract">');
		$re = '';	
		while(strstr($data, '<dd>')){
			$index = strpos($data, '<dd>');
			$index2 = strpos($data, '</dd>', $index); 
			$re .= substr($data, $index+4, $index2 - $index - 4); 
			$data = substr($data, $index2);
		}
		if (strlen(trim($re)) == 0){
			$index = strpos($data, '<p>');
			$index2 = strpos($data, '</p>', $index); 
			$re .= substr($data, $index+3, $index2 - $index - 3); 
		}
		 
		$re = str_replace('<pre>',"", $re); 
		$re = str_replace('[1]',"", $re); 
		$re = str_replace('</pre>',"", $re); 
		$re = str_replace('<br />',"\n", $re); 
		$re = str_replace('<br>',"\n", $re); 
		$re = str_replace('<br _extended="true">',"\n", $re); 
		$re = str_replace('<p _extended="true">',"", $re); 
		$re = str_replace('<p>',"", $re); 
		$re = str_replace('</p>',"\n", $re); 
		$re = str_replace('&nbsp;'," ", $re);   
		$re = str_replace('&amp;',"&", $re);  
		
		while(strstr($re, '<')){
			$index = strpos($re, '<');
			$index2 = strpos($re, '>', $index); 
			$re = substr($re, 0, $index).substr($re, $index2+1);
		}
		
		return trim($re); 
	}
	
	//获取搜搜问问答案摘要，正常有两个答案，否则就一个
	public function getwenwen($keyword, $fromuser, $flag){ 
 		$curl = curl_init();  
		$link = 'http://wenwen.soso.com/s/?w='.urlencode($keyword).'&search=%E6%90%9C%E7%B4%A2%E7%AD%94%E6%A1%88';
		curl_setopt($curl, CURLOPT_URL, $link); 
	 	curl_setopt($curl, CURLOPT_HEADER, 0);   
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1"); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 4); 
		$data = curl_exec($curl);  
		curl_close($curl);
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~'; 
	 
		$index = strpos($data, '<ol class="result_list">');
		if($index == false)
			return "抱歉啊 我也不知道...\n要不你问问别的或者换种表达方式好吗？"; 
			
		$index2 = strpos($data, 'ol>');
		$data = substr($data, $index, $index2 - $index);
		
        preg_match_all('|<p>(.*)</p>|U', $data, $ar);  
		$c = count($ar[1]); 
		 
		//是否有百科，得到$n
		$index = strpos($data, '<li>'); 
		$index2 = strpos($data, '</li>', $index + 4); 
		$tmp = substr($data, $index, $index2 - $index);
		if(strstr($tmp, 'http://baike.soso.com/')){
			$n = 0;
		}else{
			$n = $this->getrand($c-1);
		}
		
		$answers = ''; //回复变量
		
		//答案1
		$re = $ar[1][$n]; 
		$index = 0; 		
		for($i = 0; $i <= $n; $i++){
			$index = strpos($data, '<li>', $index + 4);  
		} 
		$index2 = strpos($data, '</li>', $index + 4); 
		$tmp = substr($data, $index, $index2 - $index); 
		$index = strpos($tmp, 'href=');
		$index2 = strpos($tmp, '"', $index + 8); 
		$link = substr($tmp, $index+6, $index2 - $index - 6); 		
		if(!strstr($link, 'baike.soso.com')){ 
			$link = 'http://wenwen.soso.com'.$link;   
		}  
		if(strstr($re, '...')){
			d_setzhidaostr($fromuser, $link, 0);
			$re .= "\n【获取详细回复a】";
		}		
		$re = str_replace('<span>答：</span>','', $re);
		$re = str_replace('<em>','', $re);
		$re = str_replace('</em>','', $re);
		$re = str_replace('&lt;','<', $re);
		$re = str_replace('&gt;','>', $re);
		$re = str_replace('<p>',"\n", $re);
		$re = str_replace('</p>',"\n", $re);
		$re = str_replace('&amp;gt;',">", $re); 
		$re = str_replace('&amp;lt;',"<", $re);  
		$re = str_replace('<br>',"\n", $re);
		$re = str_replace("&nbsp;"," ", $re);
		
        
		//不是提问
		if($flag == 0)
			return $re;
			
		$index = strpos($tmp, '解决时间&nbsp;');
		if($index == false)
			$index = strpos($tmp, '更新时间&nbsp;'); 
		if($index !== false){ 
			$index2 = strpos($tmp, '<', $index); 
			$solve = substr($tmp, $index + strlen('解决时间&nbsp;'), $index2 - $index - strlen('解决时间&nbsp;')); 
			if(strlen($solve) > 0) 	
				$re = '回答时间：'.$solve."\n".$re;	
		}
		
		$answers = $re;
		
		//答案2
		$n = ($n+1)%$c;
		$re = $ar[1][$n]; 
		$index = 0; 		
		for($i = 0; $i <= $n; $i++){
			$index = strpos($data, '<li>', $index + 4);  
		} 
		$index2 = strpos($data, '</li>', $index + 4); 
		$tmp = substr($data, $index, $index2 - $index); 
		$index = strpos($tmp, 'href=');
		$index2 = strpos($tmp, '"', $index + 8); 
		$link = substr($tmp, $index+6, $index2 - $index - 6); 		
		if(!strstr($link, 'baike.soso.com')){ 
			$link = 'http://wenwen.soso.com'.$link;   
		}  
		if(strstr($re, '...')){
			d_setzhidaostr($fromuser, $link, 1);
			$re .= "\n【获取详细回复b】";
		}		
		$re = str_replace('<span>答：</span>','', $re);
		$re = str_replace('<em>','', $re);
		$re = str_replace('</em>','', $re);
		$re = str_replace('&lt;','<', $re);
		$re = str_replace('&gt;','>', $re);
		$re = str_replace('<p>',"\n", $re); 
		$re = str_replace('&amp;gt;',">", $re); 
		$re = str_replace('&amp;lt;',"<", $re); 
		$re = str_replace('<br>',"\n", $re);
		$re = str_replace("&nbsp;"," ", $re);
		
		$index = strpos($tmp, '解决时间&nbsp;');
		if($index == false)
			$index = strpos($tmp, '更新时间&nbsp;'); 
		if($index !== false){ 
			$index2 = strpos($tmp, '<', $index); 
			$solve = substr($tmp, $index + strlen('解决时间&nbsp;'), $index2 - $index - strlen('解决时间&nbsp;')); 
			if(strlen($solve) > 0) 	
				$re = '回答时间：'.$solve."\n".$re;	
		}
		
		$answers .= "\n\n-----------------\n\n";
		$answers .= $re;
		
        $answers = $this->getcontent($answers);
        
		return $answers."\n\n☆重发一遍或换种表达方式，可能会有让你更满意的答案☆"; 
	}   
	
	//问问，通过链接，获取内容
	public function getwenwen2($link){   
		if(strstr($link, 'baike.soso.com'))
			return $this->getsosobaike($link);
			 
		if(strlen($link) == 0)
			return '你想说什么呀';
 		$curl = curl_init();  
		curl_setopt($curl, CURLOPT_URL,  $link); 
	 	curl_setopt($curl, CURLOPT_HEADER, 0); 
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 4); 
		$data = curl_exec($curl);  
		curl_close($curl);  
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~';
		$index = 0; 
		$re_last = '';
		while(strstr($data, 'satisfaction_answer')){
			$index = strpos($data, '<div class="answer_con">', $index);
			if($index === false)
				break;
			$index2 = strpos($data, '</div>', $index); 
			$re = substr($data, $index + strlen('<div class="answer_con">'), $index2 - $index - strlen('<div class="answer_con">')); 
			
			if(strstr($re, '<pre>')){
				$index_1 = strpos($re, '<pre>');
				$index_2 = strpos($re, '</pre>', $index_1); 
				$re = substr($re, $index_1 + 5, $index_2 - $index_1 - 5);
			} 
			$re = str_replace('<br />',"\n", $re); 
			$re = str_replace('<br/>',"\n", $re); 
			$re = str_replace('<br>',"\n", $re); 
			$re = str_replace('<br _extended="true">',"\n", $re); 
			$re = str_replace('<p _extended="true">',"", $re);
			$re = str_replace('<p>',"", $re);  
			$re = str_replace('</p>',"\n", $re); 
			$re = str_replace('&nbsp;'," ", $re);   
			$re = str_replace('楼主',"", $re);    
			$re = str_replace('<strong _extended="true">',"", $re);    
			$re = str_replace('</strong>',"", $re);  
			 
			$re_last .= $re."\n";
			$data = substr($data, $index2);
			$index = 0;  
		}
		if (strlen(trim($re_last)) == 0)
		{
			$index = strpos($data, '<div class="answer_con">', $index);
			if($index === false)
				break;
			$index2 = strpos($data, '</div>', $index); 
			$re = substr($data, $index + strlen('<div class="answer_con">'), $index2 - $index - strlen('<div class="answer_con">')); 
			
			if(strstr($re, '<pre>')){
				$index_1 = strpos($re, '<pre>');
				$index_2 = strpos($re, '</pre>', $index_1); 
				$re = substr($re, $index_1 + 5, $index_2 - $index_1 - 5);
			}
			$re = str_replace('<br />',"\n", $re); 
			$re = str_replace('<br/>',"\n", $re); 
			$re = str_replace('<br>',"\n", $re); 
			$re = str_replace('<br _extended="true">',"\n", $re); 
			$re = str_replace('<p _extended="true">',"", $re);
			$re = str_replace('<p>',"", $re);  
			$re = str_replace('</p>',"\n", $re); 
			$re = str_replace('&nbsp;'," ", $re);
			$re = str_replace('&gt;'," ", $re);
			$re = str_replace('楼主',"", $re);    
			$re = str_replace('<strong _extended="true">',"", $re);    
			$re = str_replace('</strong>',"", $re);  
            
            
			$re_last .= $re."\n";
			$data = substr($data, $index2);
			$index = 0;  
		}
        
        $re_last = $this->getcontent($re_last);
		 
		return trim($re_last); 
	}  
	
	//通过ask120链接获取内容
	public function getask($link){ 
			 
		if(strlen($link) == 0)
			return '你想说什么呀';
 		$curl = curl_init();  
		curl_setopt($curl, CURLOPT_URL,  $link); 
	 	curl_setopt($curl, CURLOPT_HEADER, 0); 
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 4); 
		$data = curl_exec($curl);  
		curl_close($curl);  
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~'; 
		$re_last = '';
		while(strstr($data, "<div class='crazy'>")){
			$index = strpos($data, "<div class='crazy'>");
			if($index === false)
				break;
			$index2 = strpos($data, '</div>', $index); 
			$re = substr($data, $index, $index2 - $index); 
			$data = substr($data, $index2);
			
			 
			$re = str_replace('<br />',"\n", $re); 
			$re = str_replace('<br/>',"\n", $re); 
			$re = str_replace('<br>',"\n", $re); 
			$re = str_replace('<br _extended="true">',"\n", $re); 
			$re = str_replace('<p _extended="true">',"", $re);
			$re = str_replace('<p>',"", $re);  
			$re = str_replace('</p>',"\n", $re); 
			$re = str_replace('&nbsp;'," ", $re);   
			$re = str_replace('楼主',"", $re);    
			$re = str_replace('<strong _extended="true">',"", $re);    
			$re = str_replace('</strong>',"", $re);  
			 
			while(strstr($re, '<')){
				$index = strpos($re, '<');
				$index2 = strpos($re, '>', $index); 
				$re = substr($re, 0, $index).substr($re, $index2+1);
			}
			$re_last .= $re."\n";
			$index = 0;  
		}
		 
		return trim($re_last); 
	}
	
	//搜索 搜搜百科
	public function getbaike($keyword, $fromuser){ 
	
		$link = "http://baike.soso.com/Search.e?sp=S".urlencode($keyword)."&sp=F";
		
 		$curl = curl_init();  
		curl_setopt($curl, CURLOPT_URL,  $link); 
	 	curl_setopt($curl, CURLOPT_HEADER, 0); 
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 4); 
		$data = curl_exec($curl);  
		curl_close($curl);  
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~'; 
		$index = strpos($data, '<div class="newscont wh_550">');
		if($keyword == '爱情')
			$index = strpos($data, '<div class="newscont wh_550">', $index + 2);
		$index2 = strpos($data, '<div>', $index);
		$tmp = substr($data, $index, $index2 - $index);
		$index = strpos($tmp, 'href="');
		$index2 = strpos($tmp, '"', $index+10);
		$link = "http://baike.soso.com".substr($tmp, $index+6, $index2 - $index - 6);   
		  
		while(strstr($tmp, '<')){ 
			$index = strpos($tmp, '<');
			$index2 = strpos($tmp, '>', $index);
			$tmp = substr($tmp, 0, $index).substr($tmp, $index2+1);
		}
		  
		if(strstr($tmp, '...')){
			d_setzhidaostr($fromuser, $link, 0);
			$tmp .= "\n【获取详细回复a】";
		} 
		
		return $tmp;
		
	}  
	
	public function poi($location_X, $location_Y){
		$re = '以"找"字开头，后面跟上关键词，搜索周边你要去的地方'."如输入：\n".
			  '找饭店'."\n".
			  '找KTV'."\n".
			  '找电影院'."\n".
			  '找ATM'."\n".
			  '找银行'."\n"; 		
		return $re;
	}
	
	function getPOI($location, $keywords, $size){	
		if(strlen($location) == 0)
			return  "找周边地点，请先发送位置~\n界面右下角有个加号，点击加号就会出现几个图标，有个是位置的图标点下然后点发送就ok了"; 
		$link = 'http://api.map.baidu.com/place/v2/search?&query='.urlencode($keywords)
		.'&filter=sort_name:distance&location='.$location.'&radius=10000&output=json&ak=2350616e387ffff91ec19b1a1643c0b2&scope=2'
		.'&page_size=3&page_num='.$size;
		 
		$curl = curl_init();  
		curl_setopt($curl, CURLOPT_URL, $link); 
	 	curl_setopt($curl, CURLOPT_HEADER, 0);  
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 4); 
		$data = curl_exec($curl);  
		curl_close($curl);  
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~';
		$Message = json_decode($data, true); 
		$re = '';
		if($Message['message'] == 'ok'){   
			for($i = 0; $i < count($Message['results']); $i++){
				$re .= $Message['results'][$i]['name'];
				$re .= "\n";   
				$re .= '地址：'.$Message['results'][$i]['address'];
				$re .= "\n";   
				if(array_key_exists('telephone', $Message['results'][$i])){ 
					$re .= '电话: '.$Message['results'][$i]['telephone'];
					$re .= "\n"; 
				}
				if(array_key_exists('distance', $Message['results'][$i]['detail_info'])){ 
					$re .= '距离: '.$Message['results'][$i]['detail_info']['distance'].'米';
					$re .= "\n";  
				}
				if(array_key_exists('price', $Message['results'][$i]['detail_info'])){ 
					$re .= '价格: '.$Message['results'][$i]['detail_info']['price'].'元';
					$re .= "\n";  
				}
				if(array_key_exists('tag', $Message['results'][$i]['detail_info'])){ 
					$re .= '标签: '.$Message['results'][$i]['detail_info']['tag'];
					$re .= "\n";  
				}
				
				$search_array = array("overall_rating" => '总体评分', 
									"taste_rating" => '口味评分',
									"service_rating" => '服务评分',
									"environment_rating" => '环境评分',
									"hygiene_rating" => '卫生评分',
									"shop_hours" => '营业时间',
									"technology_rating" => '技术评分'
									);

				foreach($search_array AS $key => $info) {
					if(array_key_exists($key, $Message['result']['detail_info']))
						$re .=$info.'：'.$Message['result']['detail_info'][$key]."\n"; 
				} 
			//	$re .= "\n"; 
			//	$re .= '<a href="http://baiwanlu.com/de.php?uid='
			//	.$Message['results'][$i]['uid'].'&p0='.$location.'">详情点击</a>';
				$re .= "\n"; 
				$re .= "\n"; 
			} 
			if(strlen($re) > 0)
				$re .= '【获取更多回复n】';
		}
		if(strlen($re) == 0)
			return '找不到你要的地方呢 找找别的吧';
		return $re;
	}
	
	function seed() 
	{	
		list($msec, $sec) = explode(' ', microtime());
		return (float) $sec; 
	}
	
	function getrand($m){
		$numbers = range (0,$m);  
		shuffle ($numbers);  
		$result = $numbers[mt_rand(0, $m)];  
		return (abs(($result * time()) % ($m + 1)) + mt_rand(0, 100))% ($m + 1);
	}
	//去除尖括号
    function getcontent($tmp) {
        while(strstr($tmp, '<')){
			$index = strpos($tmp, '<');
			$index2 = strpos($tmp, '>', $index);
            if($index2 === false) {
                break;
            }
			$tmp = substr($tmp, 0, $index).substr($tmp, $index2+1);
		}
        return $tmp;
    }
//	echo baidu_getzhidao('怎样才能不自卑');
}
?>