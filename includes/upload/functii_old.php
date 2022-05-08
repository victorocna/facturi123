<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include ('../config.php');
include ('resize.php');
$buffer = '';

if ($_POST['op'] == 'save_sigla'){
	if (!file_exists('../../xml/'.$_POST['id_user'].'/sigla/')) mkdir('../../xml/'.$_POST['id_user'].'/sigla');
	$upload_dir = '../../xml/'.$_POST['id_user'].'/sigla/';
	$upload_file = $upload_dir.basename($_FILES['userfile']['name']);

	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $upload_file)){
		$resizeObj = new resize('../../xml/'.$_POST['id_user'].'/sigla/'.$_FILES['userfile']['name']);
		$resizeObj -> resizeImage(120, 80, 'crop');
		$resizeObj -> saveImage('../../xml/'.$_POST['id_user'].'/sigla/'.$_FILES['userfile']['name'], 100);
		
		if (isset($_POST['id_firma'])) $db->query('update firme set sigla="'.$_FILES['userfile']['name'].'" where id_firma="'.$_POST['id_firma'].'" and tip_firma="0"');
		$buffer = 1;
	}
	else $buffer = 0;
	echo $buffer;
}

if ($_POST['op'] == 'delete_sigla'){
	if (file_exists('../../xml/'.$_POST['id_user'].'/sigla/'.$_POST['sigla'])){
		unlink('../../xml/'.$_POST['id_user'].'/sigla/'.$_POST['sigla']);
		
		if (isset($_POST['id_firma'])) $db->query('update firme set sigla="" where id_firma="'.$_POST['id_firma'].'" and tip_firma="0"');
		$buffer = 1;
	}
	else $buffer = 0;
	echo $buffer;
}
?>