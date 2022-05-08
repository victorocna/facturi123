<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include ('../includes/config.php');
$i = 0;
if (isset($_GET['vl'])){
	$sql = $db->query('select * from firme where id_user="'.$_SESSION['id_user'].'" and tip_firma="0" and denumire like "'.$_GET['vl'].'%" order by denumire asc');
	if (mysql_num_rows($sql) > 0){
		echo '<ul>';
		while ($row = mysql_fetch_array($sql)){
			if ($i%2 == 0) echo '<div class="span-6 even">';
			else echo '<div class="span-6">';
			echo '<li class="f-search ui-corner-all" style="padding: 3px 6px; font-size: 1.05em; width: 17em;" ida="'.$row['id_firma'].'" onclick="action($(this).attr(\'ida\'),\'0\',$(this))"><a class="f-link" href="#">'.$row['denumire'].'</a></li></div>';
			$i++;
		}
		echo '</ul>';
	}
	else{
		$sql2 = $db->query('select * from firme having locate(" '.$_GET['vl'].'",denumire) > 0 and id_user="'.$_SESSION['id_user'].'" and tip_firma="0" order by denumire asc');
		echo '<ul>';
		while ($row = mysql_fetch_array($sql2)){
			if ($i%2 == 0) echo '<div class="span-6 even">';
			else echo '<div class="span-6">';
			echo '<li class="f-search ui-corner-all" style="padding: 3px 6px; font-size: 1.05em; width: 17em;" ida="'.$row['id_firma'].'" onclick="action($(this).attr(\'ida\'),\'0\',$(this))"><a class="f-link" href="#">'.$row['denumire'].'</a></li></div>';
			$i++;
		}
		echo '</ul>';
	}
}
else{
	$sql = $db->query('select * from firme where id_user="'.$_SESSION['id_user'].'" and tip_firma="0" order by denumire asc limit 20');
	if (mysql_num_rows($sql) > 0){
		echo '<ul>';
		while ($row = mysql_fetch_array($sql)){
			if ($i%2 == 0) echo '<div class="span-6 even">';
			else echo '<div class="span-6">';
			echo '<li class="f-search ui-corner-all" style="padding: 3px 6px; font-size: 1.05em; width: 17em;" ida="'.$row['id_firma'].'" onmouseover="$(this).addClass(\'ui-state-hover\')" onmouseout="$(this).removeClass(\'ui-state-hover\')" onclick="action($(this).attr(\'ida\'),\'0\',$(this))"><a href="#" class="f-link">'.$row['denumire'].'</a></li></div>';
			$i++;
		}
		echo '</ul>';
	}
}
?>