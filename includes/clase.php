<?php
class db {
	var $link;
	var $baza;
	var $result;
	var $host;
	var $user;
	var $pass;
	var $bz;
	var $sql;
	
	function db($host,$user,$pass,$base){
		$this->host=$host;
		$this->user=$user;
		$this->pass=$pass;
		$this->baza=$base;
		$this->link=mysql_connect($this->host,$this->user,$this->pass);
		$this->bz=mysql_select_db($this->baza,$this->link);
	}
	function query($query) {
		$this->sql=$query;
		 $this->result=mysql_query($this->sql,$this->link);
		 return $this->result;
	}
	function show_tables() {
		$this->result=mysql_list_tables($this->baza,$this->link);
		return $this->result;
	}
	function kill() {
		mysql_close($this->link);
	}
}

class crypt{ 
	var $scramble1;
	var $scramble2;
	var $adj;
	var $mod;

	function crypt(){
		$this->scramble1 = ' -0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$this->scramble2 = 'fjAEokIOzU2q13h5w794p6s8BgPdFVm DTcSZerlGKuCyJxHiQLt-RMaNvWYnb0X';
		if (strlen($this->scramble1) <> strlen($this->scramble2)){
			trigger_error('** SCRAMBLE1 is not same length as SCRAMBLE2 **', E_USER_ERROR);
		}
		$this->adj = 1.75;
		$this->mod = 3;
	}
	
	function encrypt ($key, $source, $sourcelen=0){
		$fudgefactor = $this->_convertKey($key);
		if (empty($source))	return;
		while (strlen($source) < $sourcelen){
			$source .= ' ';
		}
		$target  = NULL;
		$factor2 = 0;
		for ($i = 0; $i < strlen($source); $i++){
			$char1 = substr($source, $i, 1);
			$num1 = strpos($this->scramble1, $char1);
			if ($num1 === false) return;
			$adj = $this->_applyFudgeFactor($fudgefactor);
			$factor1 = $factor2 + $adj;
			$num2    = round($factor1) + $num1;
			$num2    = $this->_checkRange($num2);
			$factor2 = $factor1 + $num2;
			$char2 = substr($this->scramble2, $num2, 1);
			$target .= $char2;
		}
		return $target;
	}

	function _convertKey($key){
		if (empty($key)) return;
		$array[] = strlen($key);
		$tot = 0;
		for ($i = 0; $i < strlen($key); $i++){
			$char = substr($key, $i, 1);
			$num = strpos($this->scramble1, $char);
			if ($num === false)	return;
			$array[] = $num;
			$tot = $tot + $num;
		}
		$array[] = $tot;
		return $array;
	}
	
	function _applyFudgeFactor(&$fudgefactor){
		$fudge = array_shift($fudgefactor);
		$fudge = $fudge + $this->adj;
		$fudgefactor[] = $fudge;
		if (!empty($this->mod)){
			if ($fudge % $this->mod == 0){
				$fudge = $fudge * -1;
			}
		}
		return $fudge;
	}

	function _checkRange($num){
		$num = round($num);
		$limit = strlen($this->scramble1);
		while ($num >= $limit){
			$num = $num - $limit;
		}
		while ($num < 0){
			$num = $num + $limit;
		}
		return $num;
	}

	function decrypt($key, $source){
		$fudgefactor = $this->_convertKey($key);
		if (empty($source))	return;
		$target = NULL;
		$factor2 = 0;
		for ($i = 0; $i < strlen($source); $i++){
			$char2 = substr($source, $i, 1);
			$num2  = strpos($this->scramble2, $char2);
			if ($num2 === false) return;
			$adj = $this->_applyFudgeFactor($fudgefactor);
			$factor1 = $factor2 + $adj;
			$num1    = $num2 - round($factor1);
			$num1    = $this->_checkRange($num1);
			$factor2 = $factor1 + $num2;
			$char1 = substr($this->scramble1, $num1, 1);
			$target .= $char1;
		}
		return rtrim($target);
	}
}
?>