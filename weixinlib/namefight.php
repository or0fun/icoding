<?php

class webchat_namefight
{
	public function fight($n1, $n2){ 
		if(strlen($n1) == 0 || strlen($n2) == 0){
			return '姓名大作战，看看谁能打赢呢，例如输入：李小四vs张小三';
		} 
		if($n1 == $n2)
			return '自己打自己，你神经啊...换个名字看看，亲~~';
			
		$name = array($n1, $n2);
		$md5 = array('','');
		$hp = array(0, 0);
		$attack = array(0, 0);
		$defense = array(0, 0);
		$speed = array(0, 0);
		$skill = array(0, 0);
		$fortune = array(0, 0);
		$re = '';
		
		for($_i = 0; $_i < 2; $_i++){
			$md5[$_i] = md5($name[$_i]); 
			$hp[$_i] = $this->getvalue($md5[$_i], 1, 1); 
			$attack[$_i] = $this->getvalue($md5[$_i], rand(10, 100), 100); 
			$defense[$_i] = $this->getvalue($md5[$_i], rand(10, 100), 100); 
			$speed[$_i] = $this->getvalue($md5[$_i], rand(10, 100), 100); 
			$skill[$_i] = $this->getvalue($md5[$_i], rand(10, 100), 100); 
			$fortune[$_i] = $this->getvalue($md5[$_i], rand(10, 100), 100);  
						
			$name[$_i] = '『'.$name[$_i].'』';
			
			$re .= $name[$_i]
			."\n"
			.' HP:'.$hp[$_i]
			.' 攻:'.$attack[$_i]
			.' 防:'.$defense[$_i]
			."\n"
			.' 速:'.$speed[$_i]
			.' 技:'.$skill[$_i]
			.' 运:'.$fortune[$_i]
			."\n";
		} 
		$re .= "【缘分指数：".$this->getvalue($md5[0].$md5[1], rand(10, 100), 100)."】\n";
		
		$i = 0;
		$v_0 = 0;
		$v_1 = 0;
		$att = -1;
		$tricks = $this->gettricks();
		$tricks_c = count($tricks);
		$status = $this->getstatus();
		$status_c = count($status);
		$fight_count = 0;
		while($hp[0] > 0 && $hp[1] > 0){
			if($fight_count == 10)
				$re .= "\n/::D真是打得难分难解啊\n在一起在一起!!!/:B-)\n";
			if($fight_count == 16)
				$re .= "\n 逆天了，这要打到神马时候\n不在一起都对不起天上那月亮!!!/:@>/:<@\n";
				
			if($i >= 32){
				$i = $i % 32;				
			} 
			$v_0 = ord(substr($md5[0], $i, 1));
			$v_1 = ord(substr($md5[1], $i, 1));
			$bigger = 1;
			$smaller = 0;
			if($v_1 == $v_0){ 
				$i+=3;
				continue;
			}
			else if($v_1 > $v_0){
				$bigger = 1;
				$smaller = 0; 
			}else{
				$bigger = 0; 
				$smaller = 1;
			}
			
			if($att == $bigger){
				$re .= "\n".$name[$bigger].'发动连击';
			}
			$re .= "\n".$name[$bigger].sprintf($tricks[rand(0, $tricks_c -1)],$name[$smaller]);
			//根据skill和speed 是否闪开
			$spe = rand(0, $speed[$smaller]) + rand(0, $fortune[$smaller]);
			if($spe > 100){
				$re .= "\n哟吼，".$name[$smaller].'竟然闪开了'; 
			}else{
				//根据攻击技能和对方的防御判断失去多少伤害
				$def = rand(0, $attack[$bigger]) + rand(0, $skill[$bigger]) - rand(0, $defense[$smaller]);
				if($hp[$smaller] < $def)
					$def = $hp[$smaller];
				if($def < 0)
					$def = rand(0, 30);
					
				$hp[$smaller] -= $def;
				$re .= "\n".$name[$smaller].'受到'.$def.'点伤害'; 
				$re .= "\n".$name[$smaller].$status[rand(0, $status_c -1)];
				if($hp[$smaller] > 0){
					$re .= ',剩'.$hp[$smaller].'点血';
					if($hp[$smaller] < 20)
						$re .= "\n".$name[$smaller].'快撑不下去了';
				}
			}
			$re .= "\n";
			$att = $bigger;
			$fight_count++;
						
			$i+=3;
		}
		$fail_array = array('哎,真是太不经打了', '擦，这很容易不是吗，哈哈！','还敢嚣张，再来啊~~',
		'不用这样吧，真没劲~','真是太可惜了~','纳尼，险胜呢~','尼玛，惜败啊','靠，这不科学！'
		,'这简直无法想象'
		,'靠，这怎么可能'
		,'这。。搞笑呢。。'
		,'赢就赢呗，人艰不拆啊'
		,'又输了，累觉不爱'
		);
		$re .= "\n".$fail_array[rand(0, count($fail_array)-1)];
		if($hp[0] == 0)
			$re .= "\n".$name[0].'被击败啦';
		else
			$re .= "\n".$name[1].'被击败啦'; 
			
		return $re;
		
	}  
	
	function getvalue($md5, $t, $r){
		$re = 0;
		$c = strlen($md5);
		for($i = 0; $i < $c; $i++){
			$re += ord(substr($md5, $i, 1)) * $t; 
		} 
		if($r == 1){
			$re = $re / 10;
		}else{
			$re = $re % $r;
		}
		return intval($re);
	}
	//失败的状态
	function getstatus(){
		$words_array = array('泪流满面', '头晕目眩','泣不成声','大叫 臣妾做不到啊！',
		'跪了','想妈妈了','大喊这不科学','疼得叫出声来','头破血流','哇哇直叫','强忍着疼痛'
		);
		
		return $words_array;
	}
	//招式
	function gettricks(){
		$words_array = array('一招失传已久的如来神掌向%s扑面而来', 
		'竟然对%s使出了降龙十八掌',
		'耍出太极十三式让%s措手不及',
		'竟然偷偷向%s飞出狠毒的冰魄银针',
		'扑向%s一阵乱咬',
		'把%s按在地上一顿暴打',
		'的六脉神剑让%s狼狈不堪',
		'扔出香蕉皮要把%s绊倒',
		'piapia对%s使出打狗棒法',
		'罗汉拳、伏虎拳通通向%s袭来',
		'对%s使出的竟然是九阴白骨爪',
		'对%s使出屁股向后平沙落雁式',
		'出其不意对%s使出猴子偷桃',
		'对%s使出了。。纳尼，玉女心经..',
		'竟然会葵花宝典，%s惊呆了',
		'居然会迷踪拳必杀技，%s吓尿了',
		'迷迷糊糊，这是要对%s使出睡罗汉啊',
		'的九阴真经真是博大精深，%s都傻了',
		'踩了一下%s的脚趾头...',
		'啪啪打了%s两巴掌。。',
		'终于对%s使出了这叫不出名字的大招',
		'对%s使出正宗的小李飞刀',
		'向%s的背后打了一枪',
		'对%s耍出一套咏春',
		'向%s膝盖射了一箭',
		'居然向%s扔出一篮子臭鸡蛋',
		'对%s使出了早已失传的独孤九剑',
		'居然对%s扔出了血滴子',
		'对%s使出落英神剑掌',
		'对%s使出旋风扫叶腿',
		'对%s使出黯然销魂掌',
		'对%s使出玉女素心剑法',
		'对%s使出排云掌',
		'对%s使出全真剑法',
		'对%s喷出一个大大的臭屁',
		'一口盐汽水喷向%s',
		'对%s一阵乱吼，靠，狮吼功啊',
		'自创的亲亲亲嘴功，亲得%s直发抖',
		'朝%s一个板砖拍下去',
		'像饿狼一样将%s扑倒，额...不带这么玩的',
		'对%s扔出一箩筐诺基亚。。。',
		'向%s扔出一桶地沟油。。。',
		'向%s使出夺命剪刀脚',
		'居然向%s使出 芙蓉姐姐百媚笑，不敢直视',
		'一招葵花点穴手，%s定住了',
		'排山倒海,%s飞出十多公里..',
		'泰山压顶, %s被压得喘不过气了',
		'竟然会奥特曼的十字死光，%s大喊我不是妖怪！',
		'居然使出抓奶龙抓手，%s大骂 淫魔！',
		'居然使出降龙十八摸，%s破口大骂',
		'追魂夺命七十八式打得%s落荒而逃',
		'拉出了一泡粑粑，熏得%s泪流满面',
		'神经病发作，一阵乱咬，%s叹到 何弃疗？！'
		);
		return $words_array;
	}
}
//$webchat_namefightObject = new webchat_namefight();
//echo $webchat_namefightObject->fight('aa', 'bb');
?>