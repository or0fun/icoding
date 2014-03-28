<?php 
	header("Content-type:text/html;charset=utf-8");
	$login = false;
	$n = 0;
	if($_POST) {
		$address = addslashes($_POST["address"]);
		//$hostname_conn = "mysql1403.ixwebhosting.com:3306";
		//$database_conn = "C360953_fangjun";
		//$username_conn = "C360953_fangjun";
		//$password_conn = "Fangjun65320";
        
        $hostname_conn = "115.29.17.79";
        $database_conn = "icoding";
        $username_conn = "root";
        $password_conn = "fangjun";
        

		$table_comm = "shortaddr";
		$conn = mysql_connect($hostname_conn,$username_conn,$password_conn);
		
		if ($conn) {  
			mysql_select_db($database_conn, $conn);
			$sql = "select id from $table_comm where addr='$address'"; 
			if($result = mysql_query($sql, $conn)) {  
				if($row = mysql_fetch_array($result)) { 
					$n = $row['id'];
				}
			}
			if($n == 0){
				$sql = "insert into $table_comm (addr) values('$address')"; 
				if($result = mysql_query($sql, $conn)) {  
					$n = mysql_insert_id();
				}
			}
		}  
		if($n > 0 ){
			$strarray = "RQu32qwEZ8Gsl9h6oynkD0VFXaWixf4gKULTMzcPBtSAp51HCjI7dmNeJvOrYb";
			$c = strlen($strarray);
			$mapArray = array();
			for($i=0;$i<$c;$i++){
				$mapArray[$i] = $strarray[$i];
			}  
			$re = '';
			while($n >= 1){
				$re = $mapArray[(int)((int)$n)%62].$re;
				$n = (int)((int)$n / 62); 
			}
			while(strlen($re) < 3){
				$re = 'R'.$re;
			}
			if(strlen($re) > 0){
				$re = "http://115.29.17.79//t/?p=".$re;
				echo  "<script>$('#warningText').text('".$re."');$('.alert').show();</script>";  
			}
		}
	}
	
?>