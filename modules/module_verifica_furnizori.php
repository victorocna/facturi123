<?php
include 'top.php';
$sf = $db->query('select count(*) as nr from firme where id_user="'.$_GET['id_user'].'" and tip_firma="0"');
$rf = mysql_fetch_array($sf);
$st = $db->query('select * from useri,tip_cont where useri.id_user="'.$_GET['id_user'].'" and useri.id_tip=tip_cont.id_tip');
$rt = mysql_fetch_array($st);
if ($rf['nr'] < $rt['nr_furnizori']) include 'popup_add_furnizori.php';
else include 'module_limit_furnizori.php';
?>