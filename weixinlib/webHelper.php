<?php 
class webHelper 
{
	var $useragent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";
	public function get($link, $t = 4) {
		$curl = curl_init();  
		curl_setopt($curl, CURLOPT_URL, $link);
	 	curl_setopt($curl, CURLOPT_HEADER, 0);  
		curl_setopt($curl, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_TIMEOUT, $t); 
		$data = curl_exec($curl);  
		curl_close($curl);
		return $data;
	}
	public function post() {
		return $database_conn;
	}
}
?>