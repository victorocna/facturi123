<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include ('../config.php');
include ('resize.php');
$buffer = '';

if ($_POST['op'] == 'upload'){
	if (!file_exists('../../xml/'.$_POST['id_user'].'/sigla/')) mkdir('../../xml/'.$_POST['id_user'].'/sigla');
	$upload_dir = '../../xml/'.$_POST['id_user'].'/sigla/';
	$upload_file = $upload_dir.basename($_FILES['userfile']['name']);

	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $upload_file)){
		echo '1';
		$resizeObj = new resize('../../xml/'.$_POST['id_user'].'/sigla/'.$_FILES['userfile']['name']);
		$resizeObj -> resizeImage(120, 80, 'crop');
		$resizeObj -> saveImage('../../xml/'.$_POST['id_user'].'/sigla/'.$_FILES['userfile']['name'], 100);
		$buffer = 1;
	}
	else echo '0';
}

if ($_POST['op'] == 'delete'){
	if (file_exists('../../xml/'.$_POST['id_user'].'/sigla/'.$_POST['file'])){
		unlink('../../xml/'.$_POST['id_user'].'/sigla/'.$_POST['file']);
		$buffer = 1;
	}
	else $buffer = 0;
	echo $buffer;
}
?>