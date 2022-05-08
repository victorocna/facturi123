<?php
$_GET['title'] = 'Contul tau';
include 'top.php';
if (isset($_SESSION['id_user']) && $_SESSION['id_user'] == $_GET['idf'] && $_SESSION['subdomeniu'] == $subdomeniu) {
	include 'header.php';
	include 'menu.php';
	include 'module_cont.php';
	include 'bottom.php';
}
if (!isset($_SESSION['id_user']) || $_SESSION['id_user'] != $_GET['idf'] || $_SESSION['subdomeniu'] != $subdomeniu) {
	unset($_SESSION['id_user']);
	unset($_SESSION['user']);
	unset($_SESSION['subdomeniu']);
	include 'module_logout.php';
}
?>