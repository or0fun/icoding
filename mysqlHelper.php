<?php 
class mysqlHelper 
{
	var $hostname_conn = "mysql1403.ixwebhosting.com:3306";
	var $database_conn = "C360953_fangjun";
	var $username_conn = "C360953_fangjun";
	var $password_conn = "Fangjun65320";
	
	//ִ�У��ɹ�����true��ʧ�ܷ���false
	public function execute($sql, $memo = true) {
		$conn = mysql_connect($this->hostname_conn,$this->username_conn,$this->password_conn);
		if ($conn) {  
			mysql_query("set names 'utf8'"); 
			mysql_select_db($this->database_conn, $conn);
			if(mysql_query($sql,$conn)) {
				mysql_close($conn);
				return true;
			}
		}
		mysql_close($conn);
		return false;
	}
	
	//���ң����ڷ���true�������ڷ���false
	public function query($sql) {
		$conn = mysql_connect($this->hostname_conn,$this->username_conn,$this->password_conn);
		if ($conn) {  
			mysql_query("set names 'utf8'"); 
			mysql_select_db($this->database_conn, $conn);
			if($result = mysql_query($sql,$conn)) {
				if ($row = mysql_fetch_array($result)) {
					mysql_close($conn);
					return true;
				}
			}
		}
		mysql_close($conn);
		return false;
	}
	
	//���ң������򷵻ص�һ�������ֵ�õ�keyֵ�������ڷ��� ���ַ���
	public function queryValue($sql, $key, $memo = true) {
		$conn = mysql_connect($this->hostname_conn,$this->username_conn,$this->password_conn);
		if ($conn) {  
			mysql_query("set names 'utf8'"); 
			mysql_select_db($this->database_conn, $conn);
			if($result = mysql_query($sql,$conn)) {
				if ($row = mysql_fetch_array($result)) {
					$re = $row[$key];
					mysql_close($conn);
					return $re;
				}
			}
		}
		mysql_close($conn);
		return "";
	}	
	
	//���ң������򷵻ؽ�����飬�����ڷ��� ���ַ���
	public function queryValueArray($sql) {
		$conn = mysql_connect($this->hostname_conn,$this->username_conn,$this->password_conn);
		if ($conn){  
			mysql_query("set names 'utf8'"); 
			mysql_select_db($this->database_conn, $conn);
			if($result = mysql_query($sql,$conn)){
				$i = 0;
				while ($row = mysql_fetch_array($result)) {
					$rows[$i++] = $row;
				}
				if ($i > 0) {
					mysql_close($conn);
					return $rows;
				}
			}
		}
		mysql_close($conn);
		return "";
	}
}
?>