<?php 
//$webchat_lyricObj = new webchat_lyric();
//echo $webchat_lyricObj->bysongsinger('周杰伦', '菊花台', 0);
//echo $webchat_lyricObj->bysong('白色恋人', 1);
//echo $webchat_lyricObj->randomsong();
//echo $webchat_lyricObj->getlrc('越长大越孤单','牛奶咖啡');
//echo $webchat_lyricObj->getsinger('十年');
class webchat_lyric
{
	//geci song/singer
	function getlrc($song, $singer){
		$song = trim($song);
		$singer = trim($singer); 
	//	$re = $this->getsolrc2($song, $singer); 
		$re = $this->getbdlrc2($song, $singer); 
		return $re;
	}   
	//geci song/singer
	function getsinger($song){
		$song = trim($song); 
	//	$re = $this->getsolrc($song);
		$re = $this->getbdlrc($song);
		$re = "【可指定歌手，如输入：\n歌词 倔强 五月天】\n☆--------------------☆\n".$re; 

		return $re;
	}   
	function getvoice()
	{
		return "http://translate.google.com/translate_tts?q=%E5%81%9A%E5%80%8B%E5%8B%87%E6%95%A2%E4%B8%AD%E5%9C%8B%E4%BA%BA&tl=zh-CN";
	}
	//随机歌曲
	function randomsong(){
		$url = 'http://music.qq.com/musicbox/shop/v3/data/hit/hit_all.js'; 
		  
 		$curl = curl_init();  
		curl_setopt($curl, CURLOPT_URL, $url); 
	 	curl_setopt($curl, CURLOPT_HEADER, 0); 
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1"); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 4); 
		$data = curl_exec($curl);  
		curl_close($curl);  
		if($data === FALSE){
			return "Oops!这网速也忒慢啦~请再输入一遍~"; 
		}	
		$data = iconv("gbk","utf-8",$data);
		
		$c = substr_count($data, 'url:"');
		$n = rand(0, $c - 1);
		$index_url = 0;
		while($n >= 0){
			$index_url = strpos($data, 'url:"', $index_url + 5);
			$n = $n - 1;
		}
		$index2 = strpos($data, '"', $index_url + 5);
		$link = substr($data, $index_url + 5, $index2 - $index_url -5); 
		
		$index = strpos($link, ':0');
		$index2 = strpos($link, '.', $index);
		$url = substr($link, 0, $index);
		$id = substr($link, $index + 3, $index2 - $index -3); 
		while(strlen($id) > 0 && strlen($id) < 7)
			$id = '0'.$id;
		
		$link = $url.'/3'.$id.'.mp3'; 
		
		$index = strpos($data, 'songName:"', $index_url); 		
		$index2 = strpos($data, '"', $index+10); 
		$song_name = substr($data, $index+10, $index2 - $index - 10);  
		
		$index = strpos($data, 'albumName:"', $index_url); 		
		$index2 = strpos($data, '"', $index+11); 
		$album_name = substr($data, $index+11, $index2 - $index - 11);  
		
		$index = strpos($data, 'singerName:"', $index_url); 		
		$index2 = strpos($data, '"', $index+12); 
		$singer_name = substr($data, $index+12, $index2 - $index - 12);  
		
		$re = $song_name.'*'.$link.'*'.$singer_name.' ['.$album_name.']*'.$link;
		return $re;
	}
	
	//music song
	function bysong($song, $page){	
	
		if(strstr($song, '随机')){ 			
			return $this->randomsong();
		}
		
		$url = 'http://shopcgi.qqmusic.qq.com/fcgi-bin/shopsearch.fcg?value='
		.urlencode(iconv("utf-8","gbk",$song)).'&type=qry_song&out=json&page_record_num=1&page_no='.$page;
		 
		// 初始化一个 cURL 对象  
 		$curl = curl_init(); 
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, $url);
		// 设置header 
	 	curl_setopt($curl, CURLOPT_HEADER, 0);
		// 设置User-Agent
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 设置cURL 参数，时间超时
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		// 运行cURL，请求网页		
		$data = curl_exec($curl); 
		// 关闭URL请求	
		curl_close($curl);  
		if($data === FALSE){
			return "Oops!这网速也忒慢啦~请再输入一遍~"; 
		}	
		$data = iconv("gbk","utf-8",$data);
		echo $data;
		$index = strpos($data, 'song_id:"'); 
		if($index == false)
			return '找不到歌曲'.$song."了~\n请确认歌名是否正确。歌名和歌手直接用*号隔开，如输入：\n点歌 十年*陈奕迅";
		$index2 = strpos($data, '"', $index+11); 
		$id = substr($data, $index+9, $index2 - $index - 9);  
		while(strlen($id) > 0 && strlen($id) < 7)
			$id = '0'.$id;
			
		$index = strpos($data, 'location:"'); 		
		$index2 = strpos($data, '"', $index+10); 
		$location = substr($data, $index+10, $index2 - $index - 10);		
		
		if(strlen($location) == 1)
			$link = 'http://stream1'.$location.'.qqmusic.qq.com/3'.$id.'.mp3';
		else
			$link = 'http://stream'.$location.'.qqmusic.qq.com/3'.$id.'.mp3';

		$index = strpos($data, 'song_name:"'); 		
		$index2 = strpos($data, '"', $index+12); 
		$song_name = substr($data, $index+11, $index2 - $index - 11);  
		
		$index = strpos($data, 'album_name:"'); 		
		$index2 = strpos($data, '"', $index+12); 
		$album_name = substr($data, $index+12, $index2 - $index - 12);  
		
		$index = strpos($data, 'singer_name:"'); 		
		$index2 = strpos($data, '"', $index+13); 
		$singer_name = substr($data, $index+13, $index2 - $index - 13); 
		
	//	if(strstr($singer_name, '(')){
	//		$index = strpos($singer_name, '(');
	//		$singer_name = substr($singer_name, 0, $index);
	//	}
	//	$lrclink = 'http://baiwanlu.com/m.php?n='.$song.'&s='.$singer_name;
		
		$re = $song_name.'*'.$link.'*'.$singer_name.' ['.$album_name.']*'.$link;
		
		
		return $re;
	}
	
	//music song singer
	function bysongsinger($song, $singer, $page){	
		$url = 'http://shopcgi.qqmusic.qq.com/fcgi-bin/shopsearch.fcg?value='
		.urlencode(iconv("utf-8","gbk",$song)).'&artist='.urlencode(iconv("utf-8","gbk",$singer))
		.'&type=qry_song&out=json&page_record_num=1&page_no='.$page;
		 
		// 初始化一个 cURL 对象  
 		$curl = curl_init(); 
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, $url);
		// 设置header 
	 	curl_setopt($curl, CURLOPT_HEADER, 0);
		// 设置User-Agent
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 设置cURL 参数，时间超时
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		// 运行cURL，请求网页		
		$data = curl_exec($curl); 
		// 关闭URL请求	
		curl_close($curl);  
		if($data === FALSE){
			return "Oops!这网速也忒慢啦~请再输入一遍~"; 
		}	
		$data = iconv("gbk","utf-8",$data);
		$index = strpos($data, 'song_id:"'); 
		if($index == false)
			return '找不到歌曲'.$song."了~\n请确认歌名是否正确。歌名和歌手直接用*号隔开，如输入：\n点歌 十年*陈奕迅";
		$index2 = strpos($data, '"', $index+11); 
		$id = substr($data, $index+9, $index2 - $index - 9);  
		while(strlen($id) > 0 && strlen($id) < 7)
			$id = '0'.$id;
			
		$index = strpos($data, 'location:"'); 		
		$index2 = strpos($data, '"', $index+10); 
		$location = substr($data, $index+10, $index2 - $index - 10);
		
		if(strlen($location) == 1)
			$link = 'http://stream1'.$location.'.qqmusic.qq.com/3'.$id.'.mp3';
		else
			$link = 'http://stream'.$location.'.qqmusic.qq.com/3'.$id.'.mp3';
			
		
		$index = strpos($data, 'song_name:"'); 		
		$index2 = strpos($data, '"', $index+11); 
		$song_name = substr($data, $index+11, $index2 - $index - 11);  
		
		$index = strpos($data, 'album_name:"'); 		
		$index2 = strpos($data, '"', $index+12); 
		$album_name = substr($data, $index+12, $index2 - $index - 12);  
		
		$index = strpos($data, 'singer_name:"'); 		
		$index2 = strpos($data, '"', $index+13); 
		$singer_name = substr($data, $index+13, $index2 - $index - 13); 
		
	//	if(strstr($singer_name, '(')){
	//		$index = strpos($singer_name, '(');
	//		$singer_name = substr($singer_name, 0, $index);
	//	}
	//	$lrclink = 'http://baiwanlu.com/m.php?n='.$song.'&s='.$singer_name;
		
		$re = $song_name.'*'.$link.'*'.$singer_name.' ['.$album_name.']*'.$link;
		
		return $re;	 
	}
	 
	
	function getbdlrc($song){
		$url = 'http://music.baidu.com/search/lrc?key='.urlencode($song);  
		// 初始化一个 cURL 对象  
 		$curl = curl_init(); 
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, $url);
		// 设置header 
	 	curl_setopt($curl, CURLOPT_HEADER, 0);
		// 设置User-Agent
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 设置cURL 参数，时间超时
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		// 运行cURL，请求网页		
		$data = curl_exec($curl); 
		// 关闭URL请求	
		curl_close($curl);  
		if($data === FALSE){
			return "Oops!这网速也忒慢啦~请再输入一遍~\n指定歌手可能获取快一点，如输入：\n歌词 温柔 五月天"; 
		}	
		$start = 0;
		while(true){
			$start = $start + 10;
			$index = strpos($data, '<div class="lrc-content">', $start);
			if($index == null)
				break;
			
			$index = strpos($data, '<em>', $index);
			$index2 = strpos($data, '</em>', $index);
			$tmp = substr($data, $index, $index2 - $index);
			$tmp = str_replace('<em>', '', $tmp);
			$tmp = str_replace('</em>', '', $tmp);
			//判断歌名是否符合
			if(!strstr(strtolower($tmp), strtolower($song)))
				continue;
			$index2 = strpos($data, '</div>', $index);
			$tmp = substr($data, $index, $index2 - $index);
			$re = '';
			while(strstr($tmp, '<')){
				$index = strpos($tmp, '<');
				$index2 = strpos($tmp, '>');
				if($index == 0){
					if(strstr(substr($tmp, $index, $index2 - $index), '<br')){
						$re .= "\n";
					}
					$tmp = substr($tmp, $index2+1);
				}else{
					$tt = trim(substr($tmp, 0, $index));
					if(strlen($tt) > 0 )
						$re .= $tt;
					if(strstr(substr($tmp, $index, $index2 - $index), '<br')){
						$re .= "\n";
					}
					$tmp = substr($tmp, $index2+1);
				}
			}
			if(strlen($tmp) > 0)
				$re .= $tmp; 
			return $re;
		}
		//如果没有歌名对应的，就取第一个
		$start = 0;		
		while(true){
			$start = $start + 10;
			$index = strpos($data, '<div class="lrc-content">', $start);
			if($index == null)
				return "暂时找不到歌曲 $song 的歌词哦,试试别的歌曲吧~ "
				."\n\n--------------\n试试提问,如输入：\n提问 歌词 $song ";
			
			$index = strpos($data, '<em>', $index);
			$index2 = strpos($data, '</em>', $index);
			$tmp = substr($data, $index, $index2 - $index);
			$tmp = str_replace('<em>', '', $tmp);
			$tmp = str_replace('</em>', '', $tmp); 
			$index2 = strpos($data, '</div>', $index);
			$tmp = substr($data, $index, $index2 - $index);
			$re = '';
			while(strstr($tmp, '<')){
				$index = strpos($tmp, '<');
				$index2 = strpos($tmp, '>');
				if($index == 0){
					if(strstr(substr($tmp, $index, $index2 - $index), '<br')){
						$re .= "\n";
					}
					$tmp = substr($tmp, $index2+1);
				}else{
					$tt = trim(substr($tmp, 0, $index));
					if(strlen($tt) > 0 )
						$re .= $tt;
					if(strstr(substr($tmp, $index, $index2 - $index), '<br')){
						$re .= "\n";
					}
					$tmp = substr($tmp, $index2+1);
				}
			}
			if(strlen($tmp) > 0)
				$re .= $tmp; 
			return $re;
		}
	}
	
	function getbdlrc2($song, $singer){
		$url = 'http://music.baidu.com/search/lrc?key='.urlencode($song).'+'.urlencode($singer);  
		// 初始化一个 cURL 对象  
 		$curl = curl_init(); 
		// 设置你需要抓取的URL 
		curl_setopt($curl, CURLOPT_URL, $url);
		// 设置header 
	 	curl_setopt($curl, CURLOPT_HEADER, 0);
		// 设置User-Agent
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 设置cURL 参数，时间超时
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
		// 运行cURL，请求网页		
		$data = curl_exec($curl); 
		// 关闭URL请求	
		curl_close($curl);  
		if($data === FALSE){
			return "Oops!这网速也忒慢啦~请再输入一遍~"; 
		}	   
		$start = 0;
		while(true){
			$start = $start + 10;
			$index = strpos($data, '<div class="lrc-content">', $start);
			if($index == false)
				break;
			
			$index = strpos($data, '<em>', $index);
			$index2 = strpos($data, '</em>', $index);
			$tmp = substr($data, $index, $index2 - $index);
			$tmp = str_replace('<em>', '', $tmp);
			$tmp = str_replace('</em>', '', $tmp);
			//判断是否符合 歌名 歌手
			if(!strstr(strtolower($tmp), strtolower($song)) && 
				!strstr(strtolower($tmp), strtolower($singer)) &&
				!strstr(strtolower($tmp), strtolower($song.' '.$singer))
				)
				continue;
			$index2 = strpos($data, '</div>', $index); 
			$tmp = substr($data, $index, $index2 - $index); 
			$re = '';
			while(strstr($tmp, '<')){
				$index = strpos($tmp, '<');
				$index2 = strpos($tmp, '>');
				if($index == 0){
					if(strstr(substr($tmp, $index, $index2 - $index), '<br')){
						$re .= "\n";
					}
					$tmp = substr($tmp, $index2+1);
				}else{
					$tt = trim(substr($tmp, 0, $index));
					if(strlen($tt) > 0 )
						$re .= $tt;
					if(strstr(substr($tmp, $index, $index2 - $index), '<br')){
						$re .= "\n";
					}
					$tmp = substr($tmp, $index2+1);
				}
			}
			if(strlen($tmp) > 0)
				$re .= $tmp; 
			return $re; 
		}
		//如果不能取到歌名 歌手，就取第一个   
		$start = 0;
		while(true){
			$start = $start + 10;
			$index = strpos($data, '<div class="lrc-content">', $start);
			if($index == false)
				return "暂时找不到歌曲 $song 的歌词哦,试试别的歌曲吧~"
				."\n\n--------------\n试试提问,如输入：\n提问 歌词 $song $singer ";
			
			$index = strpos($data, '<em>', $index);
			$index2 = strpos($data, '</em>', $index);
			$tmp = substr($data, $index, $index2 - $index);
			$tmp = str_replace('<em>', '', $tmp);
			$tmp = str_replace('</em>', '', $tmp);
			$index2 = strpos($data, '</div>', $index); 
			$tmp = substr($data, $index, $index2 - $index); 
			$re = '';
			while(strstr($tmp, '<')){
				$index = strpos($tmp, '<');
				$index2 = strpos($tmp, '>');
				if($index == 0){
					if(strstr(substr($tmp, $index, $index2 - $index), '<br')){
						$re .= "\n";
					}
					$tmp = substr($tmp, $index2+1);
				}else{
					$tt = trim(substr($tmp, 0, $index));
					if(strlen($tt) > 0 )
						$re .= $tt;
					if(strstr(substr($tmp, $index, $index2 - $index), '<br')){
						$re .= "\n";
					}
					$tmp = substr($tmp, $index2+1);
				}
			}
			if(strlen($tmp) > 0)
				$re .= $tmp; 
			return $re; 
		}
	}
	
	//soso  music
	function getsolrc($song){ 
		return $this->getsosolrc($song, '');
	}
	function getsolrc2($song, $singer){
		return $this->getsosolrc($song, $singer);
	}
	function getsosolrc($song, $singer){
	
		$url = 'http://cgi.music.soso.com/fcgi-bin/m.q?w='.urlencode(iconv("utf-8","gbk",$song)).'&source=1&t=7'; 
		if(strlen($singer) > 0){
			$url = 'http://cgi.music.soso.com/fcgi-bin/m.q?w='.urlencode(iconv("utf-8","gbk",$song))
			.'++'.urlencode(iconv("utf-8","gbk",$singer)).'&source=1&t=7'; 
		}
			
 		$curl = curl_init();  
		curl_setopt($curl, CURLOPT_URL, $url); 
	 	curl_setopt($curl, CURLOPT_HEADER, 0); 
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1"); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 4); 
		$data = curl_exec($curl);  
		curl_close($curl);  
		
		if($data === FALSE){
			return "Oops!这网速也忒慢啦~请再输入一遍~\n指定歌手可能获取快一点，如输入：\n歌词 温柔 五月天"; 
		}	
		$data = iconv("gbk","utf-8",$data);
		
		$start = 0;
		while(true){
			$start = $start + 10;
			$index = strpos($data, '<div class="songlyric_list">', $start);
			if($index == null)
				return "暂时找不到歌曲 $song 的歌词哦,试试别的歌曲吧~ "
				."\n\n--------------\n试试提问,如输入：\n提问 歌词 $song ";
			
			$index = strpos($data, '<h4 class="title_song">', $index);
			$index2 = strpos($data, '</h4>', $index);
			$tmp_song = substr($data, $index, $index2 - $index); 
			if(!strstr(strtolower($tmp_song), strtolower($song)))
				continue;
			
			$index = strpos($data, '<h4 class="singer">', $index);
			$index2 = strpos($data, '</h4>', $index);
			$tmp_singer = substr($data, $index, $index2 - $index);
			if(strlen($singer) > 0){
				if(!strstr(strtolower($tmp_singer), strtolower($singer))){
					continue; 
				}
			}

			$index = strpos($data, '<h4 class="song_ablum">', $index);
			$index2 = strpos($data, '</h4>', $index);
			$tmp_song_ablum = substr($data, $index, $index2 - $index);
			
			$tmp = $tmp_song."<br>".$tmp_singer."<br>".$tmp_song_ablum."<br>"."<br>";
			
			$index = strpos($data, '已成功复制歌词 </div>', $index);
			$index2 = strpos($data, '本站歌词来自互联网', $index); 
			$tmp .= substr($data, $index + strlen('已成功复制歌词 </div>'), $index2 - $index - strlen('已成功复制歌词 </div>'));
			$re = '';
			while(strstr($tmp, '<')){
				$index = strpos($tmp, '<');
				$index2 = strpos($tmp, '>');
				if($index == 0){
					if(strstr(substr($tmp, $index, $index2 - $index), '<br')){
						$re .= "\n";
					}
					$tmp = substr($tmp, $index2+1);
				}else{
					$tt = trim(substr($tmp, 0, $index));
					if(strlen($tt) > 0 )
						$re .= $tt;
					if(strstr(substr($tmp, $index, $index2 - $index), '<br')){
						$re .= "\n";
					}
					$tmp = substr($tmp, $index2+1);
				}
			}
			if(strlen($tmp) > 0)
				$re .= $tmp; 
			return $re;
		}
		 
	}
	
	
}
?>