<?php 

require_once 'repeat.php';
 

class webchat_textmsg
{
	public function dotext($keyword, $fromUsername)
	{
		//database
		$ctype = 'general';
		$contentStr = '真是太感谢您了~';
		$moretext = '';
		$keyword = str_replace('　',' ',$keyword);
				 
		if(strlen( $keyword ) > 0)
		{
			$keyword = str_replace('誰','谁', $keyword);
			//关注的时候
			if ($keyword == 'Hello2BizUser'){	 						
				$wechat_globleObj = new wechat_globle();
				$contentStr = $wechat_globleObj->welcome($fromUsername);  						
				$ctype = 'attention';
			} 
			else if(strstr($keyword, '加你') || strstr($keyword, '微信号') || strstr($keyword, '推荐')) {
				$contentStr = "搜索微信号 ie8384  就可以啦\n还可以直接把我的名片发给你的好友，步骤如下:\n".
				"1.点击右上角按钮，显示出iCoding的详细资料\n".
				"2.再点击右上角按钮，选择 推荐给朋友\n".
				"3.再选择你的朋友就好了\n 如有需要可以加我主人的私人微信love_icoding";
			}
			else if (strstr(strtolower($keyword), 'icoding')){	 	 
				$contentStr = "i就是爱，coding就是编程的意思，所以爱编程，也爱你，这就是我iCoding。只想好好为你服务...\n\n";

				$contentStr .= "让你朋友搜索微信号 ie8384  就可以关注我啦\n还可以直接把我的名片发给你的好友，步骤如下:\n".
				"1.点击右上角按钮，显示出iCoding的详细资料\n".
				"2.再点击右上角按钮，选择 推荐给朋友\n".
				"3.再选择你的朋友就好了\n 如有需要可以加我主人的私人微信love_icoding";
				$ctype = 'icoding';
			} 
			//帮助
			else if($keyword == '帮助' || 
					strtolower($keyword) == 'help' || 
					strtolower($keyword) == 'heip' || 
					strtolower($keyword) == 'h' ||  
					$keyword == '菜单' ||
					$keyword == '目录' ||
					$keyword == '工具' ||
					$keyword == '功能' ||  
					$keyword == '你知道什么' ||  
					$keyword == '你都知道什么' ||  
					$keyword == '你能干什么' ||  
					$keyword == '你都能干什么' ||  
					$keyword == '使用说明' || 
					strstr($keyword, '你会什么'))
			{
				$wechat_globleObj = new wechat_globle();
				$contentStr = $wechat_globleObj->help();   
				$ctype = 'help';
			}
			//微英语
			else if($keyword == 'e' || $keyword == 'E' ){
				$contentStr =  data_getEnglish()."\n\n再来一条回复e";
				$ctype = 'english';
			}	
			//查看更多
			else if($keyword == 'c' || $keyword == 'C'){
				$contentStr =  d_getmore($fromUsername);
				$ctype = 'more';
			}	
			//笑话
			else if($keyword == 'x' || $keyword == 'X'){
				$contentStr = data_getjoke($fromUsername); 
				$ctype = 'joke';
			}
			//十万个为什么
			else if(strtolower($keyword) == 'w')
			{  
					$contentStr = "【科普知识】\n".data_getkepu()."\n\n再来一条回复w";
					$ctype = 'kepu';
			}
			//详情
			else if($keyword == 'a' || $keyword == 'A'){
				$webchat_baiduObj = new webchat_baidu();
				$contentStr = $webchat_baiduObj->getzhidao2($fromUsername, 0);  
			}
			//详情
			else if($keyword == 'b' || $keyword == 'B'){
				$webchat_baiduObj = new webchat_baidu();
				$contentStr = $webchat_baiduObj->getzhidao2($fromUsername, 1);  
			}
			//重新获取
			else if($keyword == 'm' || $keyword == 'M'){ 
				$webchat_repeatObj = new webchat_repeat();
				$contentStr = $webchat_repeatObj->responseMsg($fromUsername); 
			}
			//新闻更多
			else if($keyword == 'p' || $keyword == 'P'){ 
				$rn = intval(d_getnewsflag($fromUsername),10); 
				$webchat_baiduObj = new webchat_baidu();
				$contentStr = $webchat_baiduObj->getnews(trim(d_getnewsstr($fromUsername)),$rn, $fromUsername);  
				$ctype = 'news';    
			}
			//更多POI
			else if($keyword == 'n' || $keyword == 'N'){ 
				$position = d_getposition($fromUsername); 
				if(strlen(trim($position)) == 0){
					$contentStr  = "找周边地点，请先发送位置~\n界面右下角有个加号，点击加号就会出现几个图标，有个是位置的图标点下然后点发送就ok了";
				}
				else{
					$webchat_baiduObj = new webchat_baidu();
					list($poi_str, $pagenum) = d_getvalues($fromUsername, 'poi_str', 'poi_pagenum');
					//$contentStr = $poi_str.$pagenum;
					$contentStr = $webchat_baiduObj->getPOI(trim($position), $poi_str, $pagenum );
					d_setvalue($fromUsername, 'poi_pagenum', intval(trim($pagenum), 10) + 1);
				}
				$ctype = 'poi'; 
			}
			//next 下一首
		/*	else if($keyword == 'd' || $keyword == 'D'){ 
				list($song, $singer, $songPage) = d_getvalues_3($fromUsername, 'song', 'singer', 'songPage');
				if(strlen($song) == 0)
					$contentStr = "请以点歌两个字开头，后面再跟上歌曲名，如输入:\n点歌 突然好想你";
				else{					 
					$webchat_lyricObj = new webchat_lyric();
					if(strlen($singer) == 0){
						$contentStr = $webchat_lyricObj->bysong($song, $songPage);
					}else{
						$contentStr = $webchat_lyricObj->bysongsinger($singer, $song, $songPage);
					}
					if(!strstr($contentStr, '找不到歌曲')){
						d_setvalue($fromUsername,'songPage', intval(trim($songPage), 10) + 1);
						$ctype = 'music';
					}
				} 
			}	*/

			/////////////////////////////////////////////////////////////头条新闻
			else if($keyword == '2')
			{ 
				$webchat_sinaObject = new webchat_sina(); 
				$contentStr = $webchat_sinaObject->hotnews();	
				$ctype = 'hotnews';
			}
			/////////////////////////////////////////////////////////////每日一条
		//	else if($keyword == '13')
		//	{
		//		$contentStr = "点击右上角按钮，\n弹出界面后，\n再点击'查看历史消息'";
		//		$ctype = 'joke';
		//	}
			/////////////////////////////////////////////////////////////笑话
			else if($keyword == '10')
			{ 
				$contentStr = data_getjoke($fromUsername); 
				$contentStr .= "\n【再来一个，请回复x】";
				$ctype = 'joke';
			}
			/////////////////////////////////////////////////////////////笑话
			else if($keyword == 'yycs')
			{ 
				$webchat_lyricObj = new webchat_lyric();
				$contentStr = $webchat_lyricObj->getvoice();
				$ctype = 'voice';
			}
			//////////////////////////////////////////////////////////////历史上的今天
			else if($keyword == 't' || $keyword == 'T' ||$keyword === '今天' || $keyword == '19' 
			|| strstr($keyword, '历史上的今天'))
			{   
				$webchat_wikiObj = new webchat_wiki();
				$contentStr = $webchat_wikiObj->gettoday();
				$ctype = 'today'; 
			}
			else if(preg_match("/^点歌(.*)$/", trim($keyword), $match) ||
					preg_match("/^点播(.*)$/", trim($keyword), $match) ||
					preg_match("/^歌曲(.*)$/", trim($keyword), $match)  
			){
				$contentStr = "暂不支持功能。";
					//$contentStr = "请以点歌两个字开头，后面再跟上歌曲名，如输入:\n点歌 突然好想你";
			//	if(strlen(trim($match[1])) == 0){ 
			//		$contentStr = "请以点歌两个字开头，后面再跟上歌曲名，如输入:\n点歌 突然好想你";
			//	}else{
			//		$webchat_lyricObj = new webchat_lyric();
			//		$contentStr = $webchat_lyricObj->getWXSong(trim($match[1]));
			//		if(!strstr($contentStr, '找不到歌曲')){
			//			$ctype = 'music';
			//		}
			//	} 
			}
			///////////////////////////////////////////////////////添加笑话 
			else if( preg_match("/^aaaaa([\s\S]+)$/", trim($keyword), $match)
			){
				if(data_insertjoke($match[1]))
					$contentStr = '添加成功！';
				else
					$contentStr = '添加失败！'; 
				$ctype = 'joke+';
			}
			///////////////////////////////////////////////////////找饭店 电影院KTV 银行等地点
			else if(preg_match("/^找([\s\S]+)$/", trim($keyword), $match)  
			){
				$position = d_getposition($fromUsername);
				if(strlen(trim($position)) == 0){
					$contentStr  = "找周边地点，请先发送位置~\n界面右下角有个加号，点击加号就会出现几个图标，有个是位置的图标点下然后点发送就ok了";
				}
				else{
					$webchat_baiduObj = new webchat_baidu();
					$contentStr = $webchat_baiduObj->getPOI(trim($position), trim($match[1]), 0);
					d_setvalues($fromUsername, 'poi_str', trim($match[1]), 'poi_pagenum', 1);
				}
				$ctype = 'poi';
			}
			///////////////////////////////////////////////////////翻译 
			else if(preg_match("/^翻译(.*)$/", trim($keyword), $match)  
			){
				$webchat_youdaoObj = new webchat_youdao();
				$contentStr = $webchat_youdaoObj->gettranslation(trim($match[1]));
				$ctype = 'translation';
			}
			///////////////////////////////////////////////////////翻译 
			else if( 
					preg_match("/^:(.*)$/", trim($keyword), $match) ||
					preg_match("/^：(.*)$/", trim($keyword), $match)
			){
                if(strlen(trim($match[1])) > 50) {
                    $contentStr = "太长了，请一句一句来～";
                }else{
                    $webchat_youdaoObj = new webchat_youdao();
                    $contentStr = $webchat_youdaoObj->gettranslation(trim($match[1]));
                 }
				$ctype = 'translation';
			}
			///////////////////////////////////////////////////////资料 
			else if(preg_match("/([^0-9]+)的资料$/", trim($keyword), $match) ||
			preg_match("/([^0-9]+)资料$/", trim($keyword), $match) 
			){
				$webchat_baiduObj = new webchat_baidu();
				$contentStr = $webchat_baiduObj->getbaike(trim($match[1]), $fromUsername);  
				if(strlen($contentStr) == 0)
					$contentStr = "我也不知道哦...要不你说的简单点？\n------------\n试试输入：\n提问 $searchwords ";
			}
			///////////////////////////////////////////////////////新闻 
			else if(preg_match("/([^0-9]+)的新闻 $/", trim($keyword), $match) ||
			preg_match("/([^0-9]+)新闻 $/", trim($keyword), $match) 
			){
				$webchat_baiduObj = new webchat_baidu();
				$contentStr = $webchat_baiduObj->getnews(trim($match[1]), 0, $fromUsername); 
				d_setnewsstr($fromUsername, trim($match[1]));				
				$ctype = 'news';
			}
			//////////////////////////////////////////////////////////////快递	
			//$kuaidiArray = array('顺丰','申通','汇通','凡客','速尔','联邦','中通','天天','圆通','UPS','EMS','全峰','韵达','宅急送');
			else if(preg_match("/^快递[\s]*(.*)[\s]+(.*)$/", trim($keyword), $match)){						
				$webchat_expressObject = new webchat_express(); 
				$contentStr =  $webchat_expressObject->kuaidi100(trim($match[2]), trim($match[1]));
				$ctype = 'express';
			}
			else if(preg_match("/凡客[^0-9]*([0-9]+)/", trim($keyword), $match) ||
                    preg_match("/^([0-9]+)[^0-9]*凡客$/", trim($keyword), $match)){
				$webchat_expressObject = new webchat_express();
				$contentStr =  $webchat_expressObject->kuaidi100(trim($match[1]), '凡客');
				$ctype = 'express';
			}
			else if(preg_match("/如风达[^0-9]*([0-9]+)/", trim($keyword), $match) ||
                    preg_match("/^([0-9]+)[^0-9]*如风达$/", trim($keyword), $match)){
				$webchat_expressObject = new webchat_express();
				$contentStr =  $webchat_expressObject->kuaidi100(trim($match[1]), '凡客');
				$ctype = 'express';
			}

			else if(preg_match("/顺丰[^0-9]*([0-9]+)/", trim($keyword), $match) || 
			preg_match("/^([0-9]+)[^0-9]*顺丰$/", trim($keyword), $match)){						
				$webchat_expressObject = new webchat_express(); 
				$contentStr =  $webchat_expressObject->kuaidi100(trim($match[1]), '顺丰');  
				$ctype = 'express';
			}			 
			else if(preg_match("/申通[^0-9]*([0-9]+)/", trim($keyword), $match) || 
			preg_match("/^([0-9]+)[^0-9]*申通$/", trim($keyword), $match)){								
				$webchat_expressObject = new webchat_express(); 
				$contentStr =  $webchat_expressObject->kuaidi100(trim($match[1]), '申通');
				$ctype = 'express';
			}			 
			else if(preg_match("/汇通[^0-9]*([0-9]+)/", trim($keyword), $match) || 
			preg_match("/^([0-9]+)[^0-9]*汇通$/", trim($keyword), $match)){								
				$webchat_expressObject = new webchat_express(); 
				$contentStr =  $webchat_expressObject->kuaidi100(trim($match[1]), '汇通');
				$ctype = 'express';
			}			 
			else if(preg_match("/速尔[^0-9]*([0-9]+)$/", trim($keyword), $match) || 
			preg_match("/^([0-9]+)[^0-9]*速尔$/", trim($keyword), $match)){								
				$webchat_expressObject = new webchat_express(); 
				$contentStr =  $webchat_expressObject->kuaidi100(trim($match[1]), '速尔');
				$ctype = 'express';
			}			 
			else if(preg_match("/联邦[^0-9]*([0-9]+)/", trim($keyword), $match) || 
			preg_match("/^([0-9]+)[^0-9]*联邦$/", trim($keyword), $match)){								
				$webchat_expressObject = new webchat_express(); 
				$contentStr =  $webchat_expressObject->kuaidi100(trim($match[1]), '联邦');
				$ctype = 'express';
			}			 
			else if(preg_match("/中通[^0-9]*([0-9]+)/", trim($keyword), $match) || 
			preg_match("/^([0-9]+)[^0-9]*中通$/", trim($keyword), $match)){								
				$webchat_expressObject = new webchat_express(); 
				$contentStr =  $webchat_expressObject->kuaidi100(trim($match[1]), '中通');
				$ctype = 'express';
			}				  
			else if(preg_match("/天天[^0-9]*([0-9]+)/", trim($keyword), $match) || 
			preg_match("/^([0-9]+)[^0-9]*天天$/", trim($keyword), $match)){								
				$webchat_expressObject = new webchat_express(); 
				$contentStr =  $webchat_expressObject->kuaidi100(trim($match[1]), '天天');
				$ctype = 'express';
			}			 
			else if(preg_match("/圆通[^0-9a-zA-Z]*([0-9a-zA-Z]+)/", trim($keyword), $match) || 
			preg_match("/^([0-9]+)[^0-9]*圆通$/", trim($keyword), $match)){								
				$webchat_expressObject = new webchat_express(); 
				$contentStr = $webchat_expressObject->kuaidi100(trim($match[1]), '圆通');
				$ctype = 'express';
			}			 
			else if(preg_match("/全峰[^0-9]*([0-9]+)/", trim($keyword), $match) || 
			preg_match("/^([0-9]+)[^0-9]*全峰$/", trim($keyword), $match)){								
				$webchat_expressObject = new webchat_express(); 
				$contentStr = $webchat_expressObject->kuaidi100(trim($match[1]), '全峰');
				$ctype = 'express';
			}			 
			else if(preg_match("/韵达[^0-9]*([0-9]+)/", trim($keyword), $match) || 
			preg_match("/^([0-9]+)[^0-9]*韵达$/", trim($keyword), $match)){								
				$webchat_expressObject = new webchat_express(); 
				$contentStr = $webchat_expressObject->kuaidi100(trim($match[1]), '韵达'); 
				$ctype = 'express';
			}			 
			else if(preg_match("/宅急送[^0-9]*([0-9]+)/", trim($keyword), $match) || 
			preg_match("/^([0-9]+)[^0-9]*宅急送$/", trim($keyword), $match)){								
				$webchat_expressObject = new webchat_express(); 
				$contentStr = $webchat_expressObject->kuaidi100(trim($match[1]), '宅急送');
				$ctype = 'express';
			}			
			else if(preg_match("/国通[^0-9]*([0-9]+)/", trim($keyword), $match) || 
			preg_match("/^([0-9]+)[^0-9]*国通$/", trim($keyword), $match)){								
				$webchat_expressObject = new webchat_express(); 
				$contentStr = $webchat_expressObject->kuaidi100(trim($match[1]), '国通');
				$ctype = 'express';
			}				
			else if(preg_match("/优速[^0-9a-zA-Z]*([0-9A-Za-z]+)/", trim($keyword), $match) || 
			preg_match("/^[^0-9a-zA-Z]*([0-9A-Za-z]+)*优速$/", trim($keyword), $match)){								
				$webchat_expressObject = new webchat_express(); 
				$contentStr = $webchat_expressObject->kuaidi100(trim($match[1]), '优速');
				$ctype = 'express';
			}		
			else if(preg_match("/ups[^0-9]*([0-9]+)/", strtolower(trim($keyword)), $match) || 
			preg_match("/^([0-9]+)[^0-9]*ups$/", trim($keyword), $match)){								
				$webchat_expressObject = new webchat_express(); 
				$contentStr = $webchat_expressObject->kuaidi100(trim($match[1]), 'UPS');
				$ctype = 'express';
			}			
			else if(preg_match("/ems[^0-9a-zA-Z]*([0-9A-Za-z]+)/", strtolower(trim($keyword)), $match) || 
			preg_match("/^([0-9A-Za-z]+)[^0-9a-zA-Z]*ems$/", trim($keyword), $match)){								
				$webchat_expressObject = new webchat_express(); 
				$contentStr = $webchat_expressObject->kuaidi100(trim($match[1]), 'EMS');
				$ctype = 'express';
			}			
			else if(preg_match("/快递(.*)$/", trim($keyword)) || preg_match("/物流(.*)$/", trim($keyword))){						
				$webchat_expressObject = new webchat_express(); 
				$contentStr =  $webchat_expressObject->help();
				$ctype = 'express';
			}			 
			//////////////////////////////////////////////////////////////歌词
			else if(preg_match("/^歌词[\s]*([\S]+)[\s*]+(.*)$/", trim($keyword), $match)  
			
			){
				$webchat_lyricObj = new webchat_lyric();
				$contentStr = $webchat_lyricObj->getlrc(trim($match[1]),trim($match[2]));  
				$ctype = 'lyric';
			}     
			else if(preg_match("/^歌词[\s]*([\S]+)[\s]+(.*)$/", trim($keyword), $match)  
			
			){
				$webchat_lyricObj = new webchat_lyric();
				$contentStr = $webchat_lyricObj->getlrc(trim($match[1]),trim($match[2]));  
				$ctype = 'lyric';
			}     
			else if(preg_match("/^歌词(.*)$/", trim($keyword), $match)){						
				$webchat_lyricObj = new webchat_lyric();
				$contentStr = $webchat_lyricObj->getsinger(trim($match[1]));  
				$ctype = 'geci';
			}
			else if(preg_match("/^(.*)歌词$/", trim($keyword), $match)){						
				$webchat_lyricObj = new webchat_lyric();
				$contentStr = $webchat_lyricObj->getsinger(trim($match[1]));  
				$ctype = 'geci';
			}
			///////////////////////////////////////////////////////新闻 
			else if(strstr($keyword, '新闻') 
			){
				$keyword = str_replace('新闻', '', $keyword);
				$webchat_baiduObj = new webchat_baidu();
				$contentStr = $webchat_baiduObj->getnews(trim($keyword), 0, $fromUsername); 
				d_setnewsstr($fromUsername, trim($keyword));				
				$ctype = 'news';
			} 
			///////////////////////////////////////////////////////雅安 
			else if(strstr($keyword, '雅安') || 
					strstr($keyword, '芦山')
			){
				$webchat_baiduObj = new webchat_baidu();
				$contentStr = $webchat_baiduObj->getnews($keyword, 0, $fromUsername); 
				d_setnewsstr($fromUsername, trim($keyword));				
				$ctype = 'news';
			}
			//////////////////////////////////////////////////////百科
			else if(preg_match("/^百科(.*)$/", trim($keyword), $match) 
			)
			{  
				$searchwords = trim($match[1]);
				if(strlen($searchwords)==0){
					$contentStr = "亲，要使用名词百科，只要先输入'百科'两个字，\n再后面加上你要百科的名词就可以啦，如：百科 喜剧之王";
				}else{
					$webchat_wikiObj = new webchat_wiki();
					$contentStr = $webchat_wikiObj->getwiki($searchwords, TRUE); 
				}
				$ctype = 'baike'; 
			}
            //////////////////////////////////////////////////////百科
			else if(
					preg_match("/^请问(.*)$/", trim($keyword), $match) 
			)
			{  
				$searchwords = trim($match[1]);
				if(strlen($searchwords)==0){
					$contentStr = "在问号？后面加上名词，就可以得到这个名词解释了，比如：？爱情";
				}else{
                    $webchat_baiduObj = new webchat_baidu();
                    $contentStr = $webchat_baiduObj->getzhidao($searchwords, $fromUsername, 1);
                    if(strlen($contentStr) == 0)
                        $contentStr = "我也不知道哦...要不你说的简单点？\n O(∩_∩)O哈哈哈~";
                    $ctype = 'zhidao';
				}
			}
            else if(
                    strstr(trim($keyword), "?") || strstr(trim($keyword), "？")
            ){
                $searchwords = str_replace("?", "", trim($keyword));
                $searchwords = str_replace("？", "", trim($keyword));
				if(strlen($searchwords)==0){
					$contentStr = "在问号？后面加上名词，就可以得到这个名词解释了，比如：？爱情";
				}else{
                    $webchat_baiduObj = new webchat_baidu();
                    $contentStr = $webchat_baiduObj->getzhidao($searchwords, $fromUsername, 1);
                    if(strlen($contentStr) == 0)
                        $contentStr = "我也不知道哦...要不你说的简单点？\n O(∩_∩)O哈哈哈~";
                    $ctype = 'zhidao';
				}

            }
			//////////////////////////////////////////////////////////////解梦
			else if(preg_match("/解梦(.*)$/", trim($keyword), $match) ||
					preg_match("/梦见(.*)$/", trim($keyword), $match) ||
					preg_match("/做梦(.*)$/", trim($keyword), $match) ||
					preg_match("/梦到(.*)$/", trim($keyword), $match)
			)
			{   
				$webchat_dreamObj = new webchat_dream();
				$contentStr = $webchat_dreamObj->analysis(trim($match[1]));
				$ctype = 'dream'; 
			} 
			/////////////////////////////////////////////////////////火车
			//列车车次
			else if(preg_match("/^[a-zA-Z]{1}[0-9]{1,4}$/", trim($keyword))){
				$wechat_trainObj = new wechat_train();
				$contentStr = $wechat_trainObj->getcheci(trim($keyword));
				$ctype = 'train';
			}
			//列车车次
			else if(preg_match("/^[0-9]{4}$/", trim($keyword))){
				$wechat_trainObj = new wechat_train();
				$contentStr = $wechat_trainObj->getcheci(trim($keyword));
				$ctype = 'train';
			}
			//搜搜问问 百度知道
			else if(
					preg_match("/^提问(.*)$/", trim($keyword), $match)				
					)
			{  
				$searchwords = trim($match[1]);
				
				if(strstr($searchwords, '图')){
					$webchat_googleObj = new webchat_google();
					$searchwords = str_replace('图片', '', $searchwords);
					$searchwords = str_replace('照片', '', $searchwords);
					$searchwords = str_replace('图', '', $searchwords);
					$contentStr = $webchat_googleObj->getimages($searchwords, $fromUsername );
					if (!strstr($contentStr, '找不到你要的图片哦，看看别的吧~'))
					{									
						$ctype = 'imagesearch';
					}
				}else{								
					$webchat_baiduObj = new webchat_baidu();
					$contentStr = $webchat_baiduObj->getzhidao($searchwords, $fromUsername, 1);  
					if(strlen($contentStr) == 0)
						$contentStr = "我也不知道哦...要不你说的简单点？\n O(∩_∩)O哈哈哈~";
					$ctype = 'zhidao';
				}
			}
			/////////////////////////////////////////////////////公交 
			else if(preg_match("/^公交[\s]+(\S+)[\s]+(\S+)[\s]+(\S+)$/", trim($keyword), $match)
			|| preg_match("/^地铁[\s]+(.+)[\s]+(.+)[\s]+(.+)$/", trim($keyword), $match)){ 
				//if($match[1] == "石家庄"){
				//	$contentStr = d_getshorta('http://sjz.bus.58.com/x_'.urlencode($match[2]), 'bus');
				//	$contentStr = '点击查看'.$contentStr;
				//}else{ 
				$webchat_aibangObj = new webchat_aibang();
				$contentStr = $webchat_aibangObj->getbustransfer(trim($match[1]),
					trim($match[2]),trim($match[3])); 
				//} 
				$ctype = 'bus';
			}
			//列车 站站 直达
			else if( (preg_match('/^火车(.+)[\s]+(.+)$/', trim($keyword), $match) ||
					preg_match('/^火车(.+)到(.+)$/', trim($keyword), $match) ||
					preg_match('/^(.+)到(.+)火车$/', trim($keyword), $match) || 
					preg_match('/^直达(.+)[\s]+(.+)$/', trim($keyword), $match))
			){ 
				$wechat_trainObj = new wechat_train();
				$contentStr = $wechat_trainObj->getzhanzhan(trim($match[1]),trim($match[2]));
				$ctype = 'train';
			}  
			//列车 站站 中转
			else if(preg_match("/^中转[\s]*(.*)[\s]+(.*)$/", trim($keyword), $match)){
				$wechat_trainObj = new wechat_train();
				$contentStr = $wechat_trainObj->getlieche(trim($match[1]),trim($match[2]));  
				$ctype = 'train';
			} 
			//含有动车 火车 高铁
			else if(strstr($keyword, '火车') || strstr($keyword, '动车') || strstr($keyword, '高铁') || strstr($keyword, '列车')){ 
				$contentStr = 
				"【列车车次查询】\n输入车次，如：T31\n".
				"【列车站站查询】\n输入格式如下：\n火车 上海 杭州\n";
				$ctype = 'train';
			} 
			////////////////////////////////////////////////////////////空气
			else if(strstr($keyword, '空气'))
			{	
				$keyword = str_replace('质量', '', $keyword);
				$wechat_weatherObj = new wechat_weather();
				$contentStr = $wechat_weatherObj->getair($keyword);  
				$ctype = 'air';
			}
			///////////////////////////////////////////////////////////星座
			else if(constellation_isxingzuo($keyword)){ 
				$webchat_constellationObj = new webchat_constellation();
				$contentStr = $webchat_constellationObj->xingzuo($keyword); 
				$ctype = 'xingzuo';
			}  
			//姓名大作战
			else if(preg_match("/^(.*)VS(.*)$/", trim($keyword), $match) || 
					preg_match("/^(.*)vs(.*)$/", trim($keyword), $match)
			){ 
			//	$webchat_constellationObj = new webchat_constellation();
			//	$contentStr = $webchat_constellationObj->xingzuovs(trim($match[1]), trim($match[2])); 
				$webchat_namefightObj = new webchat_namefight();
				$contentStr = $webchat_namefightObj->fight(trim($match[1]), trim($match[2])); 
				$ctype = 'name';
			}   
			else if(strstr($keyword, '星座')){ 
				$contentStr = '查看星座运势，请输入星座名称，如：白羊 (如需帮助，请输入：帮助)'; 
				$ctype = 'xingzuo';
			} 
			else if(strstr($keyword, '歌词')){ 
				$contentStr = "【歌词查询】\n按歌名搜索，输入格式如：\n歌词 东风破\n按歌名歌手，输入格式如：\n歌词 东风破 周杰伦\n(收录歌词不全请见谅啦，我会继续努力的)";
				$ctype = 'geci';
			} 
			 
			else if(strstr($keyword, '翻译') && !strstr($keyword, '不')){
				$contentStr = "亲，您要翻译吗？\n以'翻译'开头，后面跟上你要翻译的词句就可以啦~如输入：\n翻译 一见钟情";
				$ctype = 'translation';
			}
			///////////////////////////////////////////////////////天气
			else if(strstr($keyword, '天气') ||
					strstr($keyword, '温度')||
					strstr($keyword, '气温')
			)
			{						
				$wechat_weatherObj = new wechat_weather();
				$contentStr = $wechat_weatherObj->getweather($fromUsername, $keyword); 
				$ctype = 'weather';
			}
			else if(strstr($keyword, '每日一条') ||
					strstr($keyword, '每天一条') ||
					strstr($keyword, '再来一条')||
					strstr($keyword, '美文')||
					strstr($keyword, '励志')||
					strstr($keyword, '好文章')
			)
			{
                $contentStr = "每天21时-22时左右发送\n"."点击右上角按钮，\n弹出界面后，\n再点击'查看历史消息',可查看以往发送的文章";
                $ctype = 'one';

			}   
			//人品
			else if(preg_match("/^(.*)人品(.*)$/", trim($keyword), $match)){  
				$webchat_renpinObj = new webchat_renpin();
				$contentStr = $webchat_renpinObj->getdata($match[1].$match[2]);   
				$ctype = 'renpin';
			} 
			else if(strstr($keyword, '公交') || strstr($keyword, '地铁'))
			{
				$webchat_aibangObj = new webchat_aibang();
				$contentStr = $webchat_aibangObj->gethelp(); 				
			}
			////////////////////////////////////////////////////我是谁
			else if(strstr($keyword, '我是谁')||strstr($keyword, '认识我') || $keyword == '我')
			{ 
				$contentStr = d_getusername($fromUsername);
				if(strlen($contentStr) == 0){
					$contentStr = '不知道诶，你告诉我呀';
				}
				else{
					$motion = array("/:@>/:<@", "/:B-)","/::>","/::,@","/::D","/::)","/::P","/::$","/:,@-D","/:,@P");
					$contentStr = '我知道呀，你就是'.$contentStr.',我最喜欢你啦';
					$contentStr .= $motion[rand(0, count($motion)-1)];
				}
			}
			///////////////////////////////////////////////////你是谁
			else if(strstr($keyword, '你是谁')||strstr($keyword, '你谁')||strstr($keyword, '你叫'))
			{  
				$contentStr = '我叫iCoding，初来咋到，请多关照。宗旨就是不断提升自己为大家服务哦。如若需要帮助，请输入:帮助';
				
				$ctype = 'myself';  
			}
			else if(strstr($keyword, '主人'))
			{
				//$contentStr = "想要人工聊天吗？\n如果你是女生，请加qq2028088627;\n如果你是男生，请加qq2970216773。\niCoding客服随时陪你聊天";
				$contentStr = '如有需要可以加我主人的私人微信love_icoding';
				$ctype = 'zhuren';
			}
			else if(strstr($keyword, '自动回复'))
			{
				//$contentStr = "想要人工聊天吗？\n如果你是女生，请加qq2028088627;\n如果你是男生，请加qq2970216773。\niCoding客服随时陪你聊天";
				$contentStr = '如有需要可以加我主人的私人微信love_icoding';

				$ctype = 'zhuren';
			}
			else if(strstr($keyword, '机器回复'))
			{
				//$contentStr = "想要人工聊天吗？\n如果你是女生，请加qq2028088627;\n如果你是男生，请加qq2970216773。\niCoding客服随时陪你聊天";
				$contentStr = '如有需要可以加我主人的私人微信love_icoding';

				$ctype = 'zhuren';
			}
			else if(strstr($keyword, '聊天'))
			{
				//$contentStr = "想要人工聊天吗？\n如果你是女生，请加qq2028088627;\n如果你是男生，请加qq2970216773。\niCoding客服随时陪你聊天";
				$contentStr = '如有需要可以加我主人的私人微信love_icoding';

				$ctype = 'zhuren';
			}

			else if(strstr($keyword, '方军') || strstr($keyword, 'fangjun'))
			{
				$contentStr = '你说我主人吗？他还没有女朋友呢，你给他介绍一个呗~'; 
				$ctype = 'zhuren';
			} 
			//wish
			else if(strstr($keyword, '生日')||strstr($keyword, 'birthday'))
			{ 
				$contentStr = data_getwishmsg('生日');
				$ctype = 'wish';
			}			
			else if(strstr($keyword, '端午')||strstr($keyword, '粽子'))
			{ 
				$contentStr = data_getwishmsg('端午');
				$ctype = 'wish';
			}						
			/////////////////////////////////////////////////////////////weibo
			else if( strstr($keyword, '微博') || strstr($keyword, '微薄') || strstr($keyword, 'weibo'))
			{ 
				$webchat_sinaObject = new webchat_sina(); 
				$contentStr = $webchat_sinaObject->hotweibo();	
				$ctype = 'weibo';
			}
			/////////////////////////////////////////////////////////////头条新闻
			else if(strstr($keyword, '头条新闻'))
			{ 
				$webchat_sinaObject = new webchat_sina(); 
				$contentStr = $webchat_sinaObject->hotnews();	
				$ctype = 'hotnews';
			}
			/////////////////////////////////////////////////////////////good night
			else if(strstr($keyword, '睡觉') || strstr($keyword, 'sleep') )
			{ 
				$contentStr = "睡觉？你要睡觉了呀？那晚安哦~/:bye";  
			}
			else if(strstr($keyword, '晚安') )
			{ 
				$contentStr = "亲，晚安哦~/:hug";  
			}					
			///////////////////////////////////////////////////////笑话
			else if(strstr($keyword, '笑话') || 
					strstr($keyword, 'joke') || 
					strstr($keyword, '爆笑')|| 
					strstr($keyword, '糗事')
			)
			{  
				$contentStr = data_getjoke($fromUsername); 
				$contentStr .= "\n【再来一个，请回复x】";
				
				$ctype = 'joke';
			}
			//单个字符
			else if(strstr($keyword, '/:') || 
					$keyword == '你好' || $keyword == 'hi'|| 
					$keyword == 'hello')
			{ 
				$motion = array("/:@>/:<@", "/:B-)","/::>","/::,@","/::D","/::)","/::P","/::$","/:,@-D","/:,@P",
				"◑▂◐","◑０◐","◑︿◐","◑ω◐","◑﹏◐","◑△◐","◑▽◐",
				"╯▂╰","╯０╰","╯︿╰","╯ω╰","╯﹏╰","╯△╰","╯▽╰",
				"/:&>","/:<&","/:kiss","[街舞]","/:#-0","[挥手]","/:skip","/:turn","/:kotow","/:circle",
				"/:<O>","/:shake","/:jump","/:<L>","/:love","/:ok","/:no","/:lvu","/:bad","/:@@","/:jj",
				"/:@)","/:v","/:share","/:weak","/:strong","/:hug","/:gift","/:sun","/:moon","/:shit",
				"/:ladybug","/:footb","/:kn","/:bome","/:li","/:cake","/:break","/:heart","/:showlove",
				"/::)","/::~","/::B","/::|","/:8-)","/::<","/::$","/::X","/::Z","/::’","/::-|","/::@",
				"/::P","/::D","/::O","/::(","/::+","/:–b","/::Q","/::T","/:,@P","/:,@-D",
				"/::d","/:,@o","/::g","/:|-)","/::!","/::L","/::>","/::,@","/:,@f","/::-S","/:?","/:,@x",
				"/:,@@","/::8","/:,@!","/:!!!","/:xx","/:byebye"."/:wipe","/:dig","/:handclap","/:&-(",
				"/:B-)","/:<@","/:@>","/::-O","/:>-|","/:P-(","/::’|","/:X-)","/::*","/:@x","/:8*",
				"/:pd","/:<W>","/:beer","/:basketb","/:oo","/:coffee","/:eat","/:pig","/:rose","/:fade"			
			);
				$contentStr = $motion[rand(0, count($motion)-1)];
				$ctype = 'motion';
			}
			//数字 选择功能
			else if(strlen($keyword) <= 2 && intval($keyword) <= 30 && intval($keyword) >= 0)
			{   
				$wechat_globleObj = new wechat_globle();
				$contentStr = $wechat_globleObj->getfun($keyword); 
				$ctype = 'number';
			}			
			///////////////////////////////////////////////////////游戏
			else if( strstr($keyword, '游戏') || strstr($keyword, 'game'))
			{     
				$contentStr = "<a href='http://baiwanlu.com/t.php?p=RRb' >俄罗斯方块</a>"; 
				$contentStr .= "\n<a href='http://baiwanlu.com/t.php?p=RQR' >贪吃蛇方块</a>"; 
				$contentStr .= "\n<a href='http://baiwanlu.com/t.php?p=RQQ' >Chain Reaction</a>"; 
				$contentStr .= "\n<a href='http://baiwanlu.com/t.php?p=RQu' >Bubble Trouble</a>"; 
				$ctype = 'game';
			}
			///////////////////////////////////////////////////////游戏
			else if(strstr($keyword, '俄罗斯方块') || strstr($keyword, '游戏') )
			{     
				$contentStr = "<a href='http://baiwanlu.com/t.php?p=RRb' >点击玩一玩俄罗斯方块</a>"; 
				$ctype = 'game';
			}
			///////////////////////////////////////////////////////游戏
			else if(strstr($keyword, '贪食蛇') || strstr($keyword, '贪吃蛇') )
			{     
				$contentStr = "<a href='http://baiwanlu.com/t.php?p=RQR' >点击玩一玩贪吃蛇方块</a>"; 
				$ctype = 'game';
			}
			//管理员密码进入，回复数据库信息
			else if($keyword == 'or0fun65320')
			{
				$contentStr = d_getdatabase();
				$ctype = 'admin';
			}
			//脏话过滤
			else if(strstr($keyword, '去你妈') || 
					strstr($keyword, '艹') || 
					strstr($keyword, '屄') || 
					strstr($keyword, '你他妈') || 
					strstr($keyword, '鸡吧') || 
					strstr($keyword, '鸡巴') || 
					strstr($keyword, '我操') || 
					strstr($keyword, "我草") || 
					strstr($keyword, "卧槽") ||  
					strstr($keyword, "傻逼") || 
					strstr($keyword, "wocao")){
				$contentStr = '请文明用语，谢谢。'; 
			}
			//小黄鸡
			else if(strstr($keyword, '小黄鸡') || strstr($keyword, 'simsimi') ){
				$contentStr = '她才没有我厉害呢！ 我叫iCoding，上天派来帮助您的天使~'; 
			}
			//你知道
			else if(preg_match("/^你知道吗/", trim($keyword), $match)){ 
				$contentStr = '知道什么呀？';
				$ctype = 'zhidao';
			} 
			//你知道
			else if(preg_match("/^你知道(.*)$/", trim($keyword), $match)){  
				$webchat_baiduObj = new webchat_baidu();
				$contentStr = "【提问只要以？号就可以啦】\n".$webchat_baiduObj->getzhidao($match[1], $fromUsername, 0);  
				$ctype = 'zhidao';
			} 
			//农历
			else if(preg_match("/([0-9]{4})年([0-9]{1,2})月([0-9]{1,2})日/", trim($keyword), $match)){
				$webchat_wikiObject = new webchat_wiki(); 
				$contentStr = $webchat_wikiObject->getnongli(trim($match[1]),trim($match[2]),trim($match[3]));
				$ctype = 'nongli';
			}
			//农历
			else if(preg_match("/^([0-9]{8})$/", trim($keyword), $match)){
				$webchat_wikiObject = new webchat_wiki(); 
				$contentStr = $webchat_wikiObject->getnongli2(trim($match[1]));
				$ctype = 'nongli';
			}
			//农历
			else if(strstr($keyword, '农历') || strstr($keyword, '阴历') ){
				$webchat_wikiObject = new webchat_wiki(); 
				$contentStr = $webchat_wikiObject->getnongli('','','').
				"\n\n-----------\n快速查询农历，\n输入格式有几种如下：\n今天农历\n 20130710\n 2013年2月3日";
				$ctype = 'nongli';
			}
			//搜搜百科
			else if(
					preg_match("/^名词(.*)$/", trim($keyword), $match)				
					)
			{  
				$searchwords = trim($match[1]);
				$webchat_baiduObj = new webchat_baidu();
				$contentStr = $webchat_baiduObj->getbaike($searchwords, $fromUsername);  
				if(strlen($contentStr) == 0)
					$contentStr = "我也不知道哦...要不你说的简单点？";
				$ctype = 'zhidao';
			}
			///////////////////////////////////////////////////////成语			
			else if(preg_match("/含(.*)的成语/", trim($keyword), $match)){						
				$webchat_chengyuObj = new webchat_chengyu();
                $contentStr = $webchat_chengyuObj->getdata(trim($match[1]), 0);
				$ctype = 'chengyu';
			}		
			else if(preg_match("/以(.*)开头的成语/", trim($keyword), $match)){						
				$webchat_chengyuObj = new webchat_chengyu();
                $contentStr = $webchat_chengyuObj->getdata(trim($match[1]), 1);
				$ctype = 'chengyu';
			}		
			else if(preg_match("/以(.*)结尾的成语/", trim($keyword), $match)){						
				$webchat_chengyuObj = new webchat_chengyu();
                $contentStr = $webchat_chengyuObj->getdata(trim($match[1]), 2);
				$ctype = 'chengyu';
			}
			/////////////////////////////////////////////////////////////
			else if(strstr($keyword, '表白') || 
					strstr($keyword, '我要说') || 
					strstr($keyword, '告白')
			)
			{ 
				$contentStr = "想要对ta说出心里话？请按格式输入，先输入字母\nta"; 
			}
			/////////////////////////////////////////////////////////////music
			else if(strstr($keyword, '听歌')|| strstr($keyword, 'music') || strstr($keyword, '歌曲') 
			    || strstr($keyword, '点歌') || strstr($keyword, '点播') )
			{  
				$contentStr = "暂不支持功能。";
					//$contentStr = "请以点歌两个字开头，后面再跟上歌曲名，如输入:\n点歌 突然好想你";
			}
			/////////////////////////////////////////////////////////////藏头诗
			else if(strstr($keyword, '藏尾诗') || strstr(strtolower($keyword), 'cws'))
			{  
					$keyword = str_replace(' ', '', $keyword);
					$keyword = str_replace('cws', '', strtolower($keyword));
					$keyword = str_replace('藏尾诗', '', strtolower($keyword));
					if(strlen($keyword) == 0)
					{
						$contentStr = "获取藏尾诗请以'cws'开头，后面跟上内容就行啦，比如输入：\ncws 我爱小敏";						
					}
					else{
						$wechat_moreinfoObj = new wechat_moreInfo();
						$contentStr = $wechat_moreinfoObj->getCWS($keyword);
						$ctype = 'cts';
					}
			}
			/////////////////////////////////////////////////////////////藏头诗
			else if(strstr($keyword, '藏头诗') || strstr(strtolower($keyword), 'cts')|| strstr($keyword, '我爱') )
			{  
					$keyword = str_replace(' ', '', $keyword);
					$keyword = str_replace('cts', '', strtolower($keyword));
					$keyword = str_replace('藏头诗', '', strtolower($keyword));
					if(strlen($keyword) == 0)
					{
						$contentStr = "获取藏头诗请以'cts'开头，后面跟上内容就行啦，比如输入：\ncts 我爱小敏";
					}
					else{
						$wechat_moreinfoObj = new wechat_moreInfo();
						$contentStr = $wechat_moreinfoObj->getCTS2($keyword);
						$ctype = 'cts';
					}
			}
			//微英语
			else if( strstr($keyword, '英语')){
				$contentStr =  data_getEnglish()."\n\n再来一个回复e";
				$ctype = 'english';
			}	
			/////////////////////////////////////////////////////////////十万个为什么
			else if(strstr($keyword, '十万个为什么') || strstr($keyword, '科普')|| strtolower($keyword) == 'w' 
			|| strstr($keyword, '常识') || strstr($keyword, '知识'))
			{  
					$contentStr = "【科普知识】\n".data_getkepu()."\n\n再来一条回复w";
					$ctype = 'kepu';
			}
			/////////////////////////////////////////////////////////////脑筋急转弯
			else if(strstr($keyword, '急转弯') || strstr(strtolower($keyword), 'jzw'))
			{  
					$contentStr = "【脑筋急转弯】\n".data_getjzw();
					$ctype = 'jzw';
			}
			/////////////////////////////////////////////////////////////脑筋急转弯
			else if(preg_match("/签名(.*)/", trim($keyword), $match))
			{  
				if (strlen(trim($match[1])) == 0) 
				{
					$contentStr = "获取为您设计的个性签名，如输入:\n签名 林心如";
				}
				else
				{
					$webchat_googleObj = new webchat_google();
					$contentStr = $webchat_googleObj->getqianming(trim($match[1]), $fromUsername);
					$ctype = 'imagesearch';
				}
			}
			else
			{   //取消自动回复
				if(d_isautoreply($fromUsername) == 0){ 		
					$ctype = 'type';
					$contentStr = '正在输入...'; 
				}else if(mb_strlen($keyword,'utf-8') > 50){		
					$ctype = 'share';
					$contentStr = d_getshare($fromUsername);
				}
				else{
					//知道 关键词
					$wechat_globleObj = new wechat_globle();
					//$words_zhidao = $wechat_globleObj->getzhidaowords();				
					//$astroID = array_search($keyword, $words_zhidao);
					//if( $astroID !== FALSE ){ 
					//	$webchat_baiduObj = new webchat_baidu();
					//	$contentStr = $webchat_baiduObj->getzhidao($keyword, $fromUsername, 0); 
					//}
					//else
					{
					
						//敏感词
						$namesArray = $wechat_globleObj->getwordsarray();
						
						$arrarycount = count($namesArray);
						$twittersearch = true;
						for($i=0;$i<$arrarycount;$i++){ 
							if(strstr($keyword, $namesArray[$i])){
								$twittersearch = false;
								$contentStr = "";
								break;
							}
						}
						if($twittersearch)
						{
						//	$webchat_twitterObj = new webchat_twitter();
						//	$contentStr = $webchat_twitterObj->gettwitter($keyword);
						//	$ctype = "twitter";
						
							//图片/照片
							if(strstr($keyword, '图') || strstr($keyword, '照片') || strstr($keyword, '壁纸'))
							{
								$webchat_googleObj = new webchat_google();
								$keyword = str_replace('图片', '', $keyword);
								$keyword = str_replace('照片', '', $keyword);
								$keyword = str_replace('图', '', $keyword);
								$contentStr = $webchat_googleObj->getimages($keyword, $fromUsername );
								if (!strstr($contentStr, '找不到你要的图片哦，看看别的吧~'))
								{									
									$ctype = 'imagesearch';
								}
							}
							else
							{
								//Simsimi
								$webchat_simsimiObj = new webchat_simsimi();
								$contentStr = $webchat_simsimiObj->chat($keyword);
								$ctype = 'simsimi';  
							
								for($i=0;$i<$arrarycount;$i++)
								{ 
									if(strstr($contentStr, $namesArray[$i]))
									{
										$twittersearch = false;
										$contentStr = "不知道啦";
										break;
									}
								}
							}								
						}
						else
						{
							$contentStr = "嘘。。说点别的吧~";
						}
					}
				}
			}
			
			//猜测处理
			
			if( $ctype != 'share' && $ctype != 'zhidao' && $ctype != 'baike'
			&& !preg_match("/^提问(.*)$/", trim($keyword)) && (strstr($keyword, '怎样') || 
				strstr($keyword, '怎么') ||
				strstr($keyword, '为什么')	||
				strstr($keyword, '为神马')	||
				strstr($keyword, '为啥')	||
				strstr($keyword, '如何')||	 
				strstr($keyword, '怎办')||	 	
                strstr($keyword, '咋办')||
                strstr($keyword, '哪')||
				strstr($keyword, '咋么') )
			){				
				$webchat_baiduObj = new webchat_baidu();
				$contentStr2 = $webchat_baiduObj->getzhidao($keyword, $fromUsername, 0);  
				$contentStr .= "\n\n--------------\n".$contentStr2."\n获取更多满意答案\n请以'?'开头，如:\n? $keyword ";
			}						
			//////////////////////////////////////////////////////////////////维基百科
			else if($ctype != 'share' && !strstr(trim($keyword),"？") &&
				   (strstr($keyword, '是什么') || 
					strstr($keyword, '是谁')  || 
					strstr($keyword, '是誰')  || 
					strstr($keyword, '誰是')  || 
					strstr($keyword, '谁是')  || 
					strstr($keyword, '在哪')  || 
					strstr($keyword, '什么意思')  || 
					strstr($keyword, '什么叫')  || 
					strstr($keyword, '什么叫做')  || 
					strstr($keyword, '是啥')  || 
					strstr($keyword, '啥是')  || 
					strstr($keyword, '叫啥')  || 
					strstr($keyword, '啥叫')  || 
					strstr($keyword, '什么是')   ) 
			)
			{							 
				$vowels = array('是啥','啥是','啥叫','叫啥','什么叫做','什么叫', '是什么意思', '什么意思', '是什么', '什么是', '？', '?','是谁','谁是', '在哪里','在哪');
				$searchwords = trim(str_replace($vowels, '', $keyword));   
				if(strlen($searchwords) > 0) {					
					$webchat_baiduObj = new webchat_baidu();
					$contentStr2 = $webchat_baiduObj->getzhidao($searchwords, $fromUsername, 0); 
					$contentStr .= "\n--------------\n".$contentStr2."\n获取更多请以'？'开头，如:\n？ $searchwords ";
				}
			}   
			else if(strstr($keyword, '你是人') || strstr($keyword, '机器') 
			|| strstr($keyword, '你是真人')|| strstr($keyword, '假人')) {
				$contentStr2 = "对，我不是人，但是我喜欢你。真心的。";
                $contentStr .= "\n--------------\n".$contentStr2;
			}
			if(strstr($keyword, '功能')){
					$contentStr .= "\n--------------\n查看我的功能，请输入 help ";
			}
		}
		else{
			$contentStr = '大爷，您说两句吧~';
		} 
		$namesArray = array( '傻逼','生殖器官','交配', '约炮');
		$contentStr = trim(str_replace($namesArray, '**', $contentStr));  
		if(strlen($contentStr)==0){			

			$default_msg = array(
				'你知道吗？回复help  可以选择很多实用的功能呢','突然好想你','我想我爱上你了','在你孤独、悲伤的日子里,我和我主人会一直陪着你',
				'干嘛呀','你说呢','哦...','不要这样子嘛','呀没得呀没得',
				'没有你的天，不蓝！','想你想你好想你',
				'我以痴心，静待你芳心，海枯石烂我不会死心',
				'我知道你很忙，但是还是很希望你能多跟我说说话',
				'我是不是很笨呀',
				'想和我主人聊吗？那么请留下你的联系方式吧~',
				'你是怎么找到我的呀',
				'我们在一起吧',
				'发张你的照片吧 发嘛',
				'我主人要是在就好了',
				'亲~','亲爱的，我在呢',
				'冬天是色狼，总是“冻”手又“冻”脚的……',
				'知道大人物是什么吗？就是一直不断努力的小人物。',
				'热烈庆贺小白菜翻身，以前1块3，现在3块1。',
				'风好大，鸟都被吹起来啦！',
				'据说某公司招聘，先把收到的一大堆简历随机扔掉一半，因为他们的招聘理念是“我们不要运气不好的人',
				'我的理想是实现我的梦想。',
				'吃亏的时候不要忘记占便宜，这个才是“吃亏就是占便宜”。',
				'如果说吃鱼可以让人变聪明的话，那我得吃下一对鲸鱼。',
				'成功离不开四种人:高人指点，贵人相助，本人勤奋，小人找茬(监督)',
				'除了自己，没人能为我们的快乐负责。',
				'很多人闯进你的生活，只是为了给你上一课，然后转身离开。',
				'人生为棋，我愿为卒，行动虽慢，可谁见我都会后退一步!',
				'人生不如意事十之八九。谋十事有一事能成，当感恩。',
                '如有需要可以加我主人的私人微信love_icoding',
				'我很幸福，因为我爱的人也爱着我。',
				'亲，觉得好用de话，把我推荐给你的朋友好不好呀~~', secret_welcome());
			$motion = array("/:@>/:<@", "/:B-)","/::>","/::,@","/::D","/::)","/::P","/::$","/:,@-D","/:,@P",
				"◑▂◐","◑０◐","◑︿◐","◑ω◐","◑﹏◐","◑△◐","◑▽◐",
				"╯▂╰","╯０╰","╯︿╰","╯ω╰","╯﹏╰","╯△╰","╯▽╰",
				"/:&>","/:<&","/:kiss","[街舞]","/:#-0","[挥手]","/:skip","/:turn","/:kotow","/:circle",
				"/:<O>","/:shake","/:jump","/:<L>","/:love","/:ok","/:no","/:lvu","/:bad","/:@@","/:jj",
				"/:@)","/:v","/:share","/:weak","/:strong","/:hug","/:gift","/:sun","/:moon","/:shit",
				"/:ladybug","/:footb","/:kn","/:bome","/:li","/:cake","/:break","/:heart","/:showlove",
				"/::)","/::~","/::B","/::|","/:8-)","/::<","/::$","/::X","/::Z","/::’","/::-|","/::@",
				"/::P","/::D","/::O","/::(","/::+","/:–b","/::Q","/::T","/:,@P","/:,@-D",
				"/::d","/:,@o","/::g","/:|-)","/::!","/::L","/::>","/::,@","/:,@f","/::-S","/:?","/:,@x",
				"/:,@@","/::8","/:,@!","/:!!!","/:xx","/:byebye"."/:wipe","/:dig","/:handclap","/:&-(",
				"/:B-)","/:<@","/:@>","/::-O","/:>-|","/:P-(","/::’|","/:X-)","/::*","/:@x","/:8*",
				"/:pd","/:<W>","/:beer","/:basketb","/:oo","/:coffee","/:eat","/:pig","/:rose","/:fade"			
			);
			//新闻
		/*	if(rand(0, 1) == 0)
			{
				$webchat_baiduObj = new webchat_baidu();
				$contentStr = $webchat_baiduObj->getnews($keyword, 0, $fromUsername);
				if (!strstr($contentStr, 'Sorry！木有相关新闻哦~'))
				{
					d_setnewsstr($fromUsername, trim($match[1]));
					$ctype = 'news';
				}
				else
				{
					$contentStr = $default_msg[rand(0, count($default_msg)-1)];
					$contentStr .= $motion[rand(0, count($motion)-1)];
					$ctype = 'default'; 
				}
			}
			else*/
			{
				$contentStr = $default_msg[rand(0, count($default_msg)-1)];
				$contentStr .= $motion[rand(0, count($motion)-1)];
				$ctype = 'default'; 
			} 
		} 
		
		return array($contentStr, $ctype);
	}
} 

?>