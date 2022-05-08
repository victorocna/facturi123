<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include ('../config.php');
$buffer = '';

if ($_POST['op'] == 'save_sigla_add'){
	recursiveDelete('../../useri/'.$_POST['subdomeniu'].'/temp/');
//vars
	$sigla_name = $_FILES['userfile']['name'];
	$sigla_temp = $_FILES['userfile']['tmp_name'];
	$sigla_rel = '../../useri/'.$_POST['subdomeniu'].'/temp/'.basename($sigla_name);
	$sigla_abs = '/useri/'.$_POST['subdomeniu'].'/temp/'.basename($sigla_name);
	$sigla_size = filesize($sigla_temp);
//end vars

if ($sigla_size <= 102400){ //100KB
	if (move_uploaded_file($sigla_temp, $sigla_rel)){
		$resize = construct($sigla_rel);
		if ($resize == 1) $buffer = '<img src="/includes/upload/resize.php/'.$sigla_name.'?width=130&amp;height=90&amp;image='.$sigla_abs.'">';
		if ($resize == 2) $buffer = '<img src="/includes/upload/resize.php/'.$sigla_name.'?width=160&amp;height=110&amp;image='.$sigla_abs.'">';
	}
	else $buffer = 0;
}
	else $buffer = 0;
	echo $buffer;
}

if ($_POST['op'] == 'save_sigla_mod'){
//vars
	$sigla_name = $_FILES['userfile']['name'];
	$sigla_temp = $_FILES['userfile']['tmp_name'];
	$sigla_rel = '../../useri/'.$_POST['subdomeniu'].'/'.$_POST['id_firma'].'/sigla/'.basename($sigla_name);
	$sigla_abs = '/useri/'.$_POST['subdomeniu'].'/'.$_POST['id_firma'].'/sigla/'.basename($sigla_name);
	$sigla_size = filesize($sigla_temp);
//end vars
	
if ($sigla_size <= 102400){ //100KB
	recursiveDelete('/useri/'.$_POST['subdomeniu'].'/'.$_POST['id_firma'].'/sigla/');
	if (move_uploaded_file($sigla_temp, $sigla_rel)){
		if (isset($_POST['id_firma'])) $db->query('update firme set sigla="'.$sigla_name.'" where id_firma="'.$_POST['id_firma'].'" and tip_firma="0"');
		$resize = construct($sigla_rel);
		if ($resize == 1) $buffer = '<img src="/includes/upload/resize.php/'.$sigla_name.'?width=130&amp;height=90&amp;image='.$sigla_abs.'">';
		if ($resize == 2) $buffer = '<img src="/includes/upload/resize.php/'.$sigla_name.'?width=160&amp;height=110&amp;image='.$sigla_abs.'">';
	}
	else $buffer = 0;
}
	else $buffer = 0;
	echo $buffer;
}

if ($_POST['op'] == 'delete_sigla_add'){
	if (file_exists('../../useri/'.$_POST['subdomeniu'].'/temp/'.$_POST['sigla'])){
		unlink('../../useri/'.$_POST['subdomeniu'].'/temp/'.$_POST['sigla']);
		$buffer = 1;
	}
	else $buffer = 0;
	echo $buffer;
}

if ($_POST['op'] == 'delete_sigla_mod'){
	if (file_exists('../../useri/'.$_POST['subdomeniu'].'/'.$_POST['id_firma'].'/sigla/'.$_POST['sigla'])){
		unlink('../../useri/'.$_POST['subdomeniu'].'/'.$_POST['id_firma'].'/sigla/'.$_POST['sigla']);
		
		if (isset($_POST['id_firma'])) $db->query('update firme set sigla="" where id_firma="'.$_POST['id_firma'].'" and tip_firma="0"');
		$buffer = 1;
	}
	else $buffer = 0;
	echo $buffer;
}

?>