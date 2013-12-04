<?php
//$webchat_expressObject = new webchat_express(); 
//echo $webchat_expressObject->kuaidi100('V082912137', '圆通');
class webchat_express{
	var $expresses = array (
	  'aae' => 'AAE快递',
	  'anjie' => '安捷快递',
	  'anxinda' => '安信达快递',
	  'aramex' => 'Aramex国际快递',
	  'balunzhi' => '巴伦支',
	  'baotongda' => '宝通达',
	  'benteng' => '成都奔腾国际快递',
	  'cces' => 'CCES快递',
	  'changtong' => '长通物流',
	  'chengguang' => '程光快递',
	  'chengji' => '城际快递',
	  'chengshi100' => '城市100',
	  'chuanxi' => '传喜快递',
	  'chuanzhi' => '传志快递',
	  'chukouyi' => '出口易物流',
	  'citylink' => 'CityLinkExpress',
	  'coe' => '东方快递',
	  'cszx' => '城市之星',
	  'datian' => '大田物流',
	  'dayang' => '大洋物流快递',
	  'debang' => '德邦物流',
	  'dechuang' => '德创物流',
	  'dhl' => 'DHL快递',
	  'diantong' => '店通快递',
	  'dida' => '递达快递',
	  'dingdong' => '叮咚快递',
	  'disifang' => '递四方速递',
	  'dpex' => 'DPEX快递',
	  'dsu' => 'D速快递',
	  'ees' => '百福东方物流',
	  'ems' => 'EMS快递',
	  'fanyu' => '凡宇快递',
	  'fardar' => 'Fardar',
	  'fedex' => '国际Fedex',
	  'fedexcn' => 'Fedex国内',
	  'feibang' => '飞邦物流',
	  'feibao' => '飞豹快递',
	  'feihang' => '原飞航物流',
	  'feihu' => '飞狐快递',
	  'feite' => '飞特物流',
	  'feiyuan' => '飞远物流',
	  'fengda' => '丰达快递',
	  'fkd' => '飞康达快递',
	  'gdyz' => '广东邮政物流',
	  'gnxb' => '邮政国内小包',
	  'gongsuda' => '共速达物流|快递',
	  'guotong' => '国通快递',
	  'haihong' => '山东海红快递',
	  'haimeng' => '海盟速递',
	  'haosheng' => '昊盛物流',
	  'hebeijianhua' => '河北建华快递',
	  'henglu' => '恒路物流',
	  'huacheng' => '华诚物流',
	  'huahan' => '华翰物流',
	  'huaqi' => '华企快递',
	  'huaxialong' => '华夏龙物流',
	  'huayu' => '天地华宇物流',
	  'huiqiang' => '汇强快递',
	  'huitong' => '汇通快递',
	  'hwhq' => '海外环球快递',
	  'jiaji' => '佳吉快运',
	  'jiayi' => '佳怡物流',
	  'jiayunmei' => '加运美快递',
	  'jinda' => '金大物流',
	  'jingguang' => '京广快递',
	  'jinyue' => '晋越快递',
	  'jixianda' => '急先达物流',
	  'jldt' => '嘉里大通物流',
	  'kangli' => '康力物流',
	  'kcs' => '顺鑫(KCS)快递',
	  'kuaijie' => '快捷快递',
	  'kuanrong' => '宽容物流',
	  'kuayue' => '跨越快递',
	  'lejiedi' => '乐捷递快递',
	  'lianhaotong' => '联昊通快递',
	  'lijisong' => '成都立即送快递',
	  'longbang' => '龙邦快递',
	  'minbang' => '民邦快递',
	  'mingliang' => '明亮物流',
	  'minsheng' => '闽盛快递',
	  'nell' => '尼尔快递',
	  'nengda' => '港中能达快递',
	  'ocs' => 'OCS快递',
	  'pinganda' => '平安达',
	  'pingyou' => '中国邮政平邮',
	  'pinsu' => '品速心达快递',
	  'quanchen' => '全晨快递',
	  'quanfeng' => '全峰快递',
	  'quanjitong' => '全际通快递',
	  'quanritong' => '全日通快递',
	  'quanyi' => '全一快递',
	  'rpx' => 'RPX保时达',
	  'rufeng' => '如风达快递',
	  'saiaodi' => '赛澳递',
	  'santai' => '三态速递',
	  'scs' => '伟邦(SCS)快递',
	  'shengan' => '圣安物流',
	  'shengfeng' => '盛丰物流',
	  'shenghui' => '盛辉物流',
	  'shentong' => '申通快递（可能存在延迟）',
	  'shunfeng' => '顺丰快递',
	  'suijia' => '穗佳物流',
	  'sure' => '速尔快递',
	  'tiantian' => '天天快递',
	  'tnt' => 'TNT快递',
	  'tongcheng' => '通成物流',
	  'tonghe' => '通和天下物流',
	  'ups' => 'UPS快递',
	  'usps' => 'USPS快递',
	  'wanbo' => '万博快递',
	  'wanjia' => '万家物流',
	  'weitepai' => '微特派',
	  'xianglong' => '祥龙运通快递',
	  'xinbang' => '新邦物流',
	  'xinfeng' => '信丰快递',
	  'xiyoute' => '希优特快递',
	  'yad' => '源安达快递',
	  'yafeng' => '亚风快递',
	  'yibang' => '一邦快递',
	  'yinjie' => '银捷快递',
	  'yinsu' => '音素快运',
	  'yishunhang' => '亿顺航快递',
	  'yousu' => '优速快递',
	  'ytfh' => '北京一统飞鸿快递',
	  'yuancheng' => '远成物流',
	  'yuantong' => '圆通快递',
	  'yuanzhi' => '元智捷诚',
	  'yuefeng' => '越丰快递',
	  'yumeijie' => '誉美捷快递',
	  'yunda' => '韵达快递',
	  'yuntong' => '运通中港快递',
	  'yuxin' => '宇鑫物流',
	  'ywfex' => '源伟丰',
	  'zhaijisong' => '宅急送快递',
	  'zhengzhoujianhua' => '郑州建华快递',
	  'zhima' => '芝麻开门快递',
	  'zhongtian' => '济南中天万运',
	  'zhongtong' => '中通快递',
	  'zhongxinda' => '忠信达快递',
	  'zhongyou' => '中邮物流',
	);
	public function getdata($keyword, $type){
		//申通快递单号格式为12位纯数字，大多以368、468、558、888开头
		if(preg_match("/^368[0-9]{9}$/", trim($keyword)) ||
			preg_match("/^468[0-9]{9}$/", trim($keyword)) ||
			preg_match("/^558[0-9]{9}$/", trim($keyword)) ||
			preg_match("/^668[0-9]{9}$/", trim($keyword)) ||
			preg_match("/^888[0-9]{9}$/", trim($keyword))
		){
			return $this->kuaidi100(trim($keyword),'shentong');
		}
		//圆通单号格式有3种，一种是纯10位数字，一种是W开头加10位数字，这2种都是普通的格式，还有一种是V开头加10为数字，这种是VIP客服的，
		if(preg_match("/^[0-9]{10}$/", trim($keyword)) ||
			preg_match("/^W[0-9]{10}$/", trim($keyword)) ||
			preg_match("/^V[0-9]{10}$/", trim($keyword)) 
		){
			return $this->kuaidi100(trim($keyword), 'yuantong');
		}
		//顺丰
		if(preg_match("/^[0-9]{12}$/", trim($keyword))){
			return $this->getresult(trim($keyword), '402');
		}
		//宅急送
		if(preg_match("/^[0-9]{10}$/", trim($keyword))){
			return $this->getresult(trim($keyword), '8');
		}
		//EMS
		if(preg_match("/^[a-zA-Z]{2}[0-9]{9}[a-zA-Z]{2}$/", trim($keyword))){
			return $this->getresult(trim($keyword), '101');
		}   
	}
	function getresult($no, $type){
		$link = "http://kd.demo.ifangka.com/model/ajax.php?type=".$type."&no=".$no;
		$referer = 'http://kd.demo.ifangka.com/';
		
		$ch = curl_init();   
		curl_setopt($ch, CURLOPT_URL, $link);   
		curl_setopt($ch, CURLOPT_HEADER, 0);   
		curl_setopt($ch, CURLOPT_REFERER, $referer);   
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31"); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 4);  
		$content = curl_exec($ch);   
		curl_close($ch);   
		if($content == false)
			return "Oops!这网速也忒慢啦~请再输入一遍~"; 
		
		$Message = json_decode($content, true); 
		$re = '';
		if($Message['content']){ 
			foreach($Message['content'] as $list){
				foreach($list as $key){
					foreach($key as $value){
						$re .= $value; 
						$re .= "\n";
					}
				}
			}
			$re = str_replace('&nbsp;&nbsp;&nbsp;',' ', $re);
			if (strpos($re,'签收情况') == 1 && $type == '101') {
				$re = "SORRY,无此运单跟踪记录!请确认单号输入正确。\n".
				"邮政EMS单号由13位数字组成，一般开头和结尾2位是字母；中间的9位是数字(部分EMS单号50开头)";
			}
			return $re;
		}else{
			return "此单号无记录，请核对快递公司名称和运单号码是否正确!\n也可能刚寄出还未录入.\n";
			//return 'SORRY,无此运单跟踪记录!请确认单号输入正确。';
		}
	} 
	//kuaidi100
	//huitongkuaidi
	//shentong
	//yuantong
	//tiantian
	//zhongtong
	//shunfeng
	//zhaijisong
	//rufengda   凡客
	//lianbangkuaidi  联邦快递
	//suer   速尔
	//ups
	//quanfengkuaidi
	//yunda
	public function help(){
		$re = "目前支持的快递查询有\n";
		$kuaidiArray = array('优速','顺丰','申通','汇通','凡客','速尔','联邦','中通','天天','圆通','UPS','EMS','全峰','韵达','宅急送','国通');
		foreach($kuaidiArray as $k){
			$re .= $k;
			$re .= "\n";
		}
		$re .= "请在相应快递后面加上单号，如输入：\n顺丰 028376220863";
		return $re;
	}
	
	public function kuaidi100($keyword, $type){
		$re = $this->ickd($keyword, $type);
		//if(strlen($re) > 0){
			return $re;
		//}
		
		$keyword = trim($keyword);
		if(strstr($type, '顺丰'))
			return $this->getresult($keyword, '402');
		if(strstr(strtolower($type), 'ems'))
			return $this->getresult($keyword, '101');
		if(strstr($type, '宅急送'))
			return $this->getresult($keyword, '8');
		if(strstr($type, '优速'))
			return $this->getresult($keyword, '25');
		if(strstr($type, '韵达'))
			return $this->getresult($keyword, '6');
			
		$typename = $type;
		$type = str_replace('快递',' ', $type);
		$type = strtolower(str_replace('查询',' ', $type)); 
		
		$kuaidiArray = array('优速','国通','顺丰','申通','汇通','凡客','速尔','联邦','中通','天天','圆通','UPS','EMS','全峰','韵达','宅急送');
		$kuaidiIDArray = array('youshuwuliu','guotongkuaidi','shunfeng','shentong','huitong','rufengda','suer','lianbangkuaidi','zhongtong','tiantian','yuantong','ups','ems','quanfengkuaidi','yunda','zhaijisong');
		
		$astroID = array_search($type, $kuaidiArray);
		if( $astroID === FALSE ){
			return $this->help();
		}  
		
		$type = $kuaidiIDArray[$astroID]; 
 		$url = "http://baidu.kuaidi100.com/query?type=".$type."&postid=".$keyword."&id=4&valicode=%E9%AA%8C%E8%AF%81%E7%A0%81";
		$referer = 'http://baidu.kuaidi100.com/all2.html?com='.$type;
		
		$ch = curl_init();   
		curl_setopt($ch, CURLOPT_URL, $url);   
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31"); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 4);  
		$content = curl_exec($ch);   
		curl_close($ch);   
		if($content == false)
			return "Oops!这网速也忒慢啦~请再输入一遍~"; 
		$Message = json_decode($content, true); 
		$re = '';
		if($Message['message'] == 'ok'){ 
			foreach($Message['data'] as $list){ 
				$re .= $list['time']; 
				$re .= "\n";  
				$re .= $list['context']; 
				$re .= "\n";  
			}
			$re = str_replace('&nbsp;&nbsp;&nbsp;',' ', $re);
			return "【".$typename."单号".$keyword."】\n".$re;
		}else{
			return "单号".$keyword."无记录，请核对快递公司名称和运单号码是否正确!\n也可能刚寄出还未录入.\n" 
			.'[查询结果来自 '.$typename.' 官网]';
		} 
	}  

	public function ickd($keyword, $company){
		$founded = false;
		foreach($this->expresses as $key=>$value)
		{
			if(strstr($value, $company))
			{
				$founded = $key;
			}
		}
		if($founded) 
		{
			$id = '102556';
			$secret = "acfbca22c76491bc2444868f2f50388d";
			$com = $founded;//快递公司
			$nu = $keyword;//快递单号
			$type = 'json';
			$encode = 'utf8';
			$gateway = sprintf('http://api.ickd.cn/?id=%s&secret=%s&com=%s&nu=%s&type=%s&encode=%s&ord=desc',
			$id, $secret, $com, $nu, $type, $encode);
			$ch = curl_init($gateway);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch,CURLOPT_HEADER,false);
			$resp = curl_exec($ch);
			$errmsg = curl_error($ch);
			if($errmsg){
				exit($errmsg);
			}
			curl_close($ch);
			$Message = json_decode($resp, true); 
			$re = "【".$company."单号".$keyword."】\n";			
			if($Message['errCode'] == '0'){ 
				foreach($Message['data'] as $list){ 
					$re .= $list['time']; 
					$re .= "\n";  
					$re .= $list['context']; 
					$re .= "\n";  
				}
				return $re;
			}else if($Message['errCode'] == '1'){
				return "单号".$keyword."记录不存在，请核对快递公司名称和运单号码是否正确!\n也可能刚寄出还未录入.\n"
				.'[查询结果来自 '.$company.' 官网]';
			}else if($Message['errCode'] == '7'){
				return "快递公司".$company." 不存在。请核对是否输入错误。";
			}else if($Message['errCode'] == '6'){
				return "单号".$keyword."输入不正确，请核对快递公司名称和运单号码是否正确!\n"
				.'[查询结果来自 '.$company.' 官网]';
            }else{
				return "单号".$keyword."不存在，请核对快递公司名称和运单号码是否正确!\n也可能刚寄出还未录入.\n"
				.'[查询结果来自 '.$company.' 官网]';
            }
		}else{			
			return "";
		}
		
	}

	
}

?>