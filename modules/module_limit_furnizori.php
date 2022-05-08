<div class="container" style="width: 810px; padding: 0px; margin-left: 5px;">
<style>
body { background: #fff; }
</style>
<script>
function confirm(){
	window.top.$('body').find('#dialog_add').dialog('close');
}
function actualizare(subdomeniu,id_user,cont){
	window.top.$('body').find('#dialog_add').dialog('close');
	window.top.document.location.href = '/'+subdomeniu+'/cont-actualizare/'+id_user+'/?cont='+cont;
}
</script>
<div class="span-20">
<?php
	echo '
<div class="span-20 box-limit ui-widget-content" style="font-weight: normal;">
	<div class="span-19 text-limit-header">Numarul maxim de furnizori a fost atins</div>
	<div class="span-19 text-limit">Pentru contul <span class="capitalize">'.$rt['denumire'].'</span> nu puteti adauga mai mult de <strong>'.ro($rt['nr_furnizori']).' furnizori</strong>. Pentru a adauga mai multi furnizori puteti sa alegeti unul dintre urmatoarele conturi:</div>
	<div class="span-20" style="margin: 20px 0;">
	';
$i = 1;
if ($i == 1){
	$sql = $db->query('select * from tip_cont where id_tip="'.$i.'"');
	$row = mysql_fetch_array($sql);
	echo '
	<div class="span-5 last ui-state-active">
		<div class="span5 b-box">
			<div class="b-header capitalize-b" style="text-align: center;">'.$row['denumire'].'</div>
			<div class="b-header-s">'.$row['pret'].' Lei pe luna</div>
		</div>
		<div class="span5 b-box">
			<div class="b-important">'.$row['nr_facturi'].'</div>
			<div class="b-text">Facturi pe luna</div>
		</div>
		<div class="span5 b-box"><span class="b-mediu">'.$row['nr_furnizori'].'</span><span class="b-text">Furnizor</span></div>
			<div class="span5 b-box-s">
			<div class="b-null"></div>
		</div>
		<div class="span5 b-box-s">
			<div class="b-null" style="padding-bottom: 11px;"></div>
		</div>
		';
	if ($row['id_tip'] < $rt['id_tip']) echo '
		<div class="span5 b-button">
			<button class="fg-button-s white ui-corner-all button-mod span3" onclick="actualizare(\''.$subdomeniu.'\',\''.$_GET['id_user'].'\',\''.$row['denumire'].'\')"><span class="button-text">Alege cont</span></button>
		</div>
	';
	else echo '
		<div class="span5 b-nobutton">
			<div class="b-text">Contul tau</div>
		</div>
	';
	echo '</div>';
	$i++;
}
while ($i <= 4){
	$sql = $db->query('select * from tip_cont where id_tip="'.$i.'"');
	$row = mysql_fetch_array($sql);
	echo '
	<div class="span-5 last ui-state-active b-container">
		<div class="span5 b-box">
			<div class="b-header capitalize-b" style="text-align: center;">'.$row['denumire'].'</div>
			<div class="b-header-s">'.$row['pret'].' Lei pe luna</div>
		</div>
		<div class="span5 b-box">
			<div class="b-important">'.$row['nr_facturi'].'</div>
			<div class="b-text">Facturi pe luna</div>
		</div>
		<div class="span5 b-box"><span class="b-mediu">'.$row['nr_furnizori'].'</span><span class="b-text">Furnizori</span></div>
		<div class="span5 b-box-s">
			<div class="b-header-s">Modele de facturi</div>
		</div>
		<div class="span5 b-box-s">
			<div class="b-header-s">Facturi personalizate</div>
		</div>
	';
	if ($row['id_tip'] < $rt['id_tip']) echo '
		<div class="span5 b-button">
			<button class="fg-button-s white ui-corner-all button-mod span3" onclick="actualizare(\''.$subdomeniu.'\',\''.$_GET['id_user'].'\',\''.$row['denumire'].'\')"><span class="button-text">Alege cont</span></button>
		</div>
	';
	if ($row['id_tip'] == $rt['id_tip']) echo '
		<div class="span5 b-nobutton">
			<div class="b-text">Contul tau</div>
		</div>
	';
	if ($row['id_tip'] > $rt['id_tip']) echo '
		<div class="span5 b-button">
			<button class="fg-button-s orange ui-corner-all button-mod span3" onclick="actualizare(\''.$subdomeniu.'\',\''.$_GET['id_user'].'\',\''.$row['denumire'].'\')"><span class="button-text">Alege cont</span></button>
		</div>
	';
	echo '</div>';
	$i++;
}
	echo '
	</div>
	<div class="span-19" style="text-align: right; padding-left: 20px;"><a href="javascript: confirm()" class="back">Inapoi</a></div>
</div>';
?>	
</div>
<!-- End container -->
</div>