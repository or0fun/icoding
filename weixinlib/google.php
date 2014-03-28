<?php 
//$webchat_googleObj = new webchat_google();
//echo $webchat_googleObj->getimages('奥巴马', '');
//echo $webchat_googleObj->getqianming('杨帆', 1);
require_once "webHelper.php";

class webchat_google
{
    
    var $imageTpl_header = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[gh_3b9f2b7cbeb1]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[news]]></MsgType>
    <ArticleCount>%d</ArticleCount>
    <Articles>";
    var $imageTpl_item = "<item>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    <PicUrl><![CDATA[%s]]></PicUrl>
    <Url><![CDATA[%s]]></Url>
    </item>";
    var $imageTpl_tail = "</Articles>
    <FuncFlag>0</FuncFlag>
    </xml>";

	//获取个性签名
	public function getqianming($keyword, $fromUsername)
	{		 
		$url = 'http://artdesign.app100679734.twsapp.com/design.php?text='.urlencode($keyword).'&font=ygylianbiqianming.ttf&img=all.jpg';
		$time = time();
		$re = sprintf($this->imageTpl_header, $fromUsername, $time, 1);
		$re .= sprintf($this->imageTpl_item, $keyword, '点击查看大图', $url,
					$url);
		$re .= $this->imageTpl_tail;
		
		return $re;
	}
    
/*	public function getbaiduimages($link, $fromUsername) {
		$webHelperObj = new webHelper();
        $data = $webHelperObj->get($link);
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~';
        
        
        $len = 8;
        $index2 = 0;
		$time = time();
		$re = sprintf($this->imageTpl_header, $fromUsername, $time, $len);
        for($i = 0; $i < $len; $i++) {
		 
            $index = strpos($data, '"thumbURL":"', $index2);
            $index2 = strpos($data, '",', $index);
            $link = substr($data, $index + 12, $index2 - $index - 12);
            if(strstr($link, ".jpg")) {     
				$re .= sprintf($this->imageTpl_item, "[点击查看大图] ", '', $link,
                           $link);
            }else{
				$index = strpos($data, '"objURL":"', $index2);
				$index2 = strpos($data, '",', $index);
				$link = substr($data, $index + 10, $index2 - $index - 10);
				if(strstr($link, ".jpg")) {     
					$re .= sprintf($this->imageTpl_item, "[点击查看大图] ", '', $link,
							   $link);
				}
			}
        }
		$re .= $this->imageTpl_tail;
		
		return $re;

	}*/
	//image 
	public function getimages($keyword, $fromUsername){
        if(strlen(trim(	$keyword) ) == 0)
			$keyword = '美女';
		//return $this->getbaiduimages('http://image.baidu.com/i?tn=baiduimage&ipn=r&ct=201326592&cl=2&lm=-1&st=-1&fm=result&fr=&sf=1&fmq=1396019935218_R&pv=&ic=0&nc=1&z=&se=1&showtab=0&fb=0&width=&height=&face=0&istype=2&ie=utf-8&word='
		//.urlencode($keyword), $fromUsername);
 		
		$link = "http://icymint.me/icoding/google.php?q=".urlencode($keyword)."&n=".$fromUsername;
		$webHelperObj = new webHelper();
        $data = $webHelperObj->get($link);echo $data;
		if($data == false)
			return 'Oops!这破网太慢啦，请再试一遍~';
		return $data;
	}
}
?>