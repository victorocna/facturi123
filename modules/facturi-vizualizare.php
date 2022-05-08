<?php
$_GET['title'] = 'Vizualizare';
include 'top.php';
if (isset($_SESSION['id_user']) && $_SESSION['id_user'] == $_GET['idf'] && $_SESSION['subdomeniu'] == $subdomeniu) {
	include 'header.php';
	$_GET['menu'] = '0';
	include 'menu.php';
	include 'module_facturi_vizualizare.php';
	include 'bottom.php';
}
if (!isset($_SESSION['id_user']) || $_SESSION['id_user'] != $_GET['idf'] || $_SESSION['subdomeniu'] != $subdomeniu) {
	unset($_SESSION['id_user']);
	unset($_SESSION['user']);
	unset($_SESSION['subdomeniu']);
	include 'module_logout.php';
}
?>