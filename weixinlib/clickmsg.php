<?php 

require_once 'repeat.php';

class webchat_clickmsg
{
	public function dotext($keyword, $fromUsername)
	{
		//database
		$ctype = 'general';
		$contentStr = '真是太感谢您了~';
		$moretext = '';
		if($keyword == 'iCoding_ONE'){
			$contentStr = "每天21时-22时左右发送\n"
						."以下随机挑选以往的一条\n\n"
						.d_getonestr();
			$ctype = 'one';
		}
		else if($keyword == 'iCoding_ENGLISH'){
			$contentStr =  data_getEnglish();
			$ctype = 'english';
		}
		else if($keyword == 'iCoding_JOKE'){
			$contentStr = data_getjoke($fromUsername).getending();
			$ctype = 'joke'; 
		}
		else if($keyword == 'iCoding_WHY'){
			$contentStr = "【科普知识】\n".data_getkepu().getending();
			$ctype = 'kepu';
		}
		else if($keyword == 'iCoding_JZW')
		{  
			$contentStr = "【脑筋急转弯】\n".data_getjzw().getending();
			$ctype = 'jzw';
		}
		else if($keyword == 'iCoding_NEWS')
		{  
			$webchat_sinaObject = new webchat_sina(); 
			$contentStr = $webchat_sinaObject->hotnews();	
			$ctype = 'hotnews';
		}
		else if($keyword == 'iCoding_MUSIC')
		{  
			$webchat_lyricObj = new webchat_lyric();
			$contentStr = $webchat_lyricObj->bysong('随机', 0);
			if(!strstr($contentStr, '找不到歌曲')){
				d_setvalues_3($fromUsername, 'song', trim($match[1]), 'singer', '', 'songPage', 2);
				$ctype = 'music';
			}
		}
		 
					
		$namesArray = array( '傻逼','生殖器官','交配', '约炮');
		$contentStr = trim(str_replace($namesArray, '**', $contentStr));  
		if(strlen($contentStr) == 0){			

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
			$contentStr = $default_msg[rand(0, count($default_msg)-1)];
			$contentStr .= $motion[rand(0, count($motion)-1)];
			$ctype = 'default';
		} 
		
		return array($contentStr, $ctype);
	}
} 

?>