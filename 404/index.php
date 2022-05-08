<?php
session_start();
$_GET['title'] = 'Eroare';
if (isset($_SESSION['subdomeniu'])){
	include 'top.php';
	include '404.php';
	include 'bottom.php';
}
if (!isset($_SESSION['subdomeniu'])){
	include '../top.php';
	include '../menu.php';
	include 'main.php';
	include '../bottom.php';
}
?>