<div class="container" style="min-height: 500px;">
<div class="span-22 last" style="padding-left: 30px;">
<script>
$(document).ready(function(){
});
</script>
<?php
	echo '
<script>
$(document).ready(function(){
	$("#furnizor").zonepicker({
		presetRanges: [
	';
$i = 0;
$sql = $db->query('select count(facturi.id_furnizor) as nr,facturi.id_furnizor,denumire from facturi,firme where firme.id_firma=facturi.id_furnizor and firme.id_user="'.$_SESSION['id_user'].'" and firme.tip_firma="0" group by facturi.id_furnizor order by nr desc limit 4');
while ($row = mysql_fetch_array($sql)){
	$row['nr'] == 1 ? $result = '<strong>1</strong> factura' : $result = ro($row['nr']).' facturi';
	echo '{"text":"<div class=\'text-imp\' style=\'color: #2e6e9e;\'>'.$row['denumire'].'</div><div class=\'picker-reset\'>'.$result.'</div>","ida":"'.$row['id_furnizor'].'"},';
	$i++;
}
if ($i < 4){
	$limit = (4-$i);
	$sql2 = $db->query('select id_firma,denumire from firme where id_user="'.$_SESSION['id_user'].'" and tip_firma="0" and id_firma not in (select facturi.id_furnizor from facturi,firme where firme.id_user="'.$_SESSION['id_user'].'" and id_draft="0" group by facturi.id_furnizor) order by denumire asc limit '.$limit);
	if (mysql_num_rows($sql2)){
		while ($rows = mysql_fetch_array($sql2)){
			echo '{"text":"<div class=\'text-imp\' style=\'color: #2e6e9e;\'>'.$rows['denumire'].'</div>","ida":"'.$rows['id_firma'].'"},';
		}
	}
}
$sqln = $db->query('select count(*) as ttl from firme where id_user="'.$_SESSION['id_user'].'" and tip_firma="0"');
$rown = mysql_fetch_array($sqln);

if ($rown['ttl'] > 4){
	echo '
		{separator:true}
		],
		presets:
			{dateRange:"Cauta furnizor"},
		doneButtonText:"Accepta",
	';
}
if ($rown['ttl'] <= 4){
	echo '
		{separator:false}
		],
	';
}
	echo '
		posX: $("#furnizor").offset().left,
		posY: $("#furnizor").offset().top+26
	});
});
</script>
	';

$sqlf = $db->query('select count(facturi.id_furnizor) as nr,facturi.id_furnizor,firme.denumire,firme.id_firma from facturi,firme where firme.id_firma=facturi.id_furnizor and firme.id_user="'.$_SESSION['id_user'].'" and firme.tip_firma="0" group by facturi.id_furnizor order by nr desc limit 1');
$rowf = mysql_fetch_array($sqlf);
?>
<div class="span-22 ui-widget-header ui-corner-top">
	<div class="header">Dashboard</div>
</div>
<?php
$sql = $db->query('select count(*) as ttl from facturi,firme where firme.id_user="'.$_SESSION['id_user'].'" and firme.id_firma=facturi.id_furnizor and firme.tip_firma="0" and facturi.id_furnizor="'.$rowf['id_firma'].'"');
$row = mysql_fetch_array($sql);
if ($row['ttl'] == 0) echo '
<div class="interior">
	<div class="span-22 box-top ui-widget-content" style="margin-bottom: 0;">
		<div class="span-20 box-functii ui-state-disabled">
			<div class="span-1 before-n">Furnizor</div>
			<div class="span-7">
				<input class="after-select span-6" style="font-size: 1em; text-transform: uppercase;" id="furnizor" readonly="readonly">
			</div>
		</div>
	</div>
</div>
<div class="span-22" id="container-pages" style="min-height: 475px;">
	<div class="span-19 err">
		<span style="margin-right: 5px;">Nu aveti nicio factura emisa. </span>
		<a href="javascript:add()">Adauga o factura acum</a>
	</div>
</div>
	';
else{
	echo '
<div class="interior">
	<div class="span-22 box-top ui-widget-content" style="margin-bottom: 0; border-bottom: none !important;">
		<div class="span-20 box-functii">
			<div class="span-1 before-n">Furnizor</div>
			<div class="span-7">
				<input value="'.$rowf['denumire'].'" class="after-select span-6" style="font-size: 1em; text-transform: uppercase;" id="furnizor" href="furnizori_select.php" iid="'.$rowf['id_firma'].'">
			</div>
		</div>
	</div>
</div>
<div class="span-22" id="container-pages" style="min-height: 475px;">
	';
}
?>
<?php
$sd = $db->query('select * from facturi where data_factura between "'.date('Y-m-01').'" and "'.date('Y-m-d').'" and id_draft!="0"');
$sf = $db->query('select * from facturi where data_factura between "'.date('Y-m-01').'" and "'.date('Y-m-d').'" and id_draft="0"');
while ($rf = mysql_fetch_array($sf)){
	if ($rf['valuta'] == '' || $rf['valuta'] == 'Lei'){
		$lei0 = str_replace('.','',$rf['total_general']);
		$lei1 = str_replace(',','.',$lei0);
		$lei += $lei1;
	}
	if ($rf['valuta'] == 'Euro'){
		$eur0 = str_replace('.','',$rf['total_general']);
		$eur1 = str_replace(',','.',$eur0);
		$eur += $eur1;
	}
	if ($rf['valuta'] == 'USD'){
		$usd0 = str_replace('.','',$rf['total_general']);
		$usd1 = str_replace(',','.',$usd0);
		$usd += $usd1;
	}
	$total = '<div class="span-6 text-home">'.number_format($lei,2,',','.').' Lei</div>';
	if ($eur != 0) $total .= '<span class="text-home-sf" style="margin-right: 20px;">'.number_format($eur,2,',','.').' Euro</span>';
	if ($usd != 0) $total .= '<span class="text-home-s">'.number_format($usd,2,',','.').' USD</span>';
}
echo '
<div class="span-22 box-top ui-state-active" style="margin-bottom: 30px; font-weight: normal;">
	<div class="span-10 box-home">
		<div class="span-6 text-caps">Facturi luna '.convert_month(date('Y-m-d')).'</div>
		<div class="span-6 text-home">'.mysql_num_rows($sf).' facturi emise</div>
		<div class="span-6 text-home-s">'.mysql_num_rows($sd).' facturi draft</div>
	</div>
	<div class="span-6 box-hmiddle" style="min-height: 70px;">
		<div class="span-6 text-caps">Total luna '.convert_month(date('Y-m-d')).'</div>'.
		$total
	.'
	</div>
</div>
';
?>
<div class="span-22" style="margin-bottom: 20px;">
	<div class="span-22 last"><div class="info-login ui-state-hover form-row">Facturi emise recent</div></div>
<?php
$i = 0;
$sqls = $db->query('select facturi.*, firme.denumire from facturi,firme where facturi.id_client=firme.id_firma order by facturi.data_add desc limit 3');
while ($rows = mysql_fetch_array($sqls)){
	if (isset($rows['valuta'])) $valuta = '<span class="valuta">'.$rows['valuta'].'</span>';
	else $valuta = '<span class="valuta">Lei</span>';
	if ($i%2 == 0) echo '<div class="span-22 list-container even" id_draft="'.$rows['id_draft'].'" factura="'.str_replace(' ','_',$rows['serie']).'_'.$rows['numar'].'">';
	else echo '<div class="span-22 list-container" id_draft="'.$rows['id_draft'].'" factura="'.str_replace(' ','_',$rows['serie']).'_'.$rows['numar'].'">';
	
if ($rows['id_draft'] == 0){
	if ($rows['stare_incasare'] == 0) echo '
		<div class="span-4 facturi-serie">
			<div id="'.$rows['id_factura'].'" class="list-options ui-state-default ui-corner-all"><div class="ui-icon ui-icon-triangle-1-s"></div></div>
			<div class="serie span-3 uppercase">'.$rows['serie'].' '.$rows['numar'].'</div>
		</div>
		<div class="span-8 facturi-client uppercase">'.$rows['denumire'].'</div>
		<div class="span-3 facturi-data">'.convert_data(date('d-m-Y',strtotime($rows['data_factura']))).'</div>
		<div class="span-3 facturi-ttl">'.$rows['total_general'].' '.$valuta.'</div>
		<div class="span-2 sts0 ui-corner-all ui-state-error">Neincasat</div>
	</div>
	<div class="span-5 ui-helper-hidden box-options0 ui-state-highlight ui-corner-bottom ui-corner-tr" id="box-options'.$rows['id_factura'].'">
		<div class="span5 text-options ui-corner-all even"><a href="facturi_finalizare.php?idf='.$_SESSION['id_user'].'&factura='.str_replace(' ','_',$rows['serie']).'_'.$rows['numar'].'&print_factura=1">Tiparire factura</a></div>
		<div class="span5 text-options ui-corner-all"><a href="facturi_finalizare.php?idf='.$_SESSION['id_user'].'&factura='.str_replace(' ','_',$rows['serie']).'_'.$rows['numar'].'&email=1">Trimitere email</a></div>
		<div class="span5 text-options ui-corner-all even"><a href="facturi_finalizare.php?idf='.$_SESSION['id_user'].'&factura='.str_replace(' ','_',$rows['serie']).'_'.$rows['numar'].'&incasare=1">Incasare factura</a></div>
		<div class="span5 text-options ui-corner-all"><a href="facturi_finalizare.php?idf='.$_SESSION['id_user'].'&factura='.str_replace(' ','_',$rows['serie']).'_'.$rows['numar'].'&history=1">Istoric factura</a></div>
		<div class="span5 text-options ui-corner-all even"><a href="facturi_editare.php?idf='.$_SESSION['id_user'].'&id_factura='.$rows['id_factura'].'">Editare factura</a></div>
	</div>
	';
	if ($rows['stare_incasare'] == 1) echo '
		<div class="span-4 facturi-serie">
			<div id="'.$rows['id_factura'].'" class="list-options ui-state-default ui-corner-all"><div class="ui-icon ui-icon-triangle-1-s"></div></div>
			<div class="serie span-3 uppercase">'.$rows['serie'].' '.$rows['numar'].'</div>
		</div>
		<div class="span-8 facturi-client uppercase">'.$rows['denumire'].'</div>
		<div class="span-3 facturi-data">'.convert_data(date('d-m-Y',strtotime($rows['data_factura']))).'</div>
		<div class="span-3 facturi-ttl">'.$rows['total_general'].' '.$valuta.'</div>
		<div class="span-2 sts1 ui-corner-all ui-state-hover">Incasat partial</div>
	</div>
	<div class="span-5 ui-helper-hidden box-options1 ui-state-highlight ui-corner-bottom ui-corner-tr" id="box-options'.$rows['id_factura'].'">
		<div class="span5 text-options ui-corner-all even"><a href="facturi_finalizare.php?idf='.$_SESSION['id_user'].'&factura='.str_replace(' ','_',$rows['serie']).'_'.$rows['numar'].'&print_factura=1">Tiparire factura</a></div>
		<div class="span5 text-options ui-corner-all"><a href="facturi_finalizare.php?idf='.$_SESSION['id_user'].'&factura='.str_replace(' ','_',$rows['serie']).'_'.$rows['numar'].'&email=1">Trimitere email</a></div>
		<div class="span5 text-options ui-corner-all even"><a href="facturi_finalizare.php?idf='.$_SESSION['id_user'].'&factura='.str_replace(' ','_',$rows['serie']).'_'.$rows['numar'].'&incasare=1">Incasare factura</a></div>
		<div class="span5 text-options ui-corner-all"><a href="facturi_finalizare.php?idf='.$_SESSION['id_user'].'&factura='.str_replace(' ','_',$rows['serie']).'_'.$rows['numar'].'&history=1">Istoric factura</a></div>
	</div>
	';
	if ($rows['stare_incasare'] == 2) echo '
		<div class="span-4 facturi-serie">
			<div id="'.$rows['id_factura'].'" class="list-options ui-state-default ui-corner-all"><div class="ui-icon ui-icon-triangle-1-s"></div></div>
			<div class="serie span-3 uppercase">'.$rows['serie'].' '.$rows['numar'].'</div>
		</div>
		<div class="span-8 facturi-client uppercase">'.$rows['denumire'].'</div>
		<div class="span-3 facturi-data">'.convert_data(date('d-m-Y',strtotime($rows['data_factura']))).'</div>
		<div class="span-3 facturi-ttl">'.$rows['total_general'].' '.$valuta.'</div>
		<div class="span-2 sts2 ui-corner-all">Incasat</div>
	</div>
	<div class="span-5 ui-helper-hidden box-options2 ui-state-highlight ui-corner-bottom ui-corner-tr" id="box-options'.$rows['id_factura'].'">
		<div class="span5 text-options ui-corner-all even"><a href="facturi_finalizare.php?idf='.$_SESSION['id_user'].'&factura='.str_replace(' ','_',$rows['serie']).'_'.$rows['numar'].'&print_factura=1">Tiparire factura</a></div>
		<div class="span5 text-options ui-corner-all"><a href="facturi_finalizare.php?idf='.$_SESSION['id_user'].'&factura='.str_replace(' ','_',$rows['serie']).'_'.$rows['numar'].'&email=1">Trimitere email</a></div>
		<div class="span5 text-options ui-corner-all even"><a href="facturi_finalizare.php?idf='.$_SESSION['id_user'].'&factura='.str_replace(' ','_',$rows['serie']).'_'.$rows['numar'].'&history=1">Istoric factura</a></div>
	</div>
	';
}
else echo '
		<div class="span-4 facturi-serie">
			<div id="'.$rows['id_factura'].'" class="list-options ui-state-default ui-corner-all"><div class="ui-icon ui-icon-triangle-1-s"></div></div>
			<div class="serie span-3 uppercase">'.$rows['serie'].' '.$rows['numar'].'</div>
		</div>
		<div class="span-8 facturi-client uppercase">'.$rows['denumire'].'</div>
		<div class="span-3 facturi-data">'.convert_data(date('d-m-Y',strtotime($rows['data_factura']))).'</div>
		<div class="span-3 facturi-ttl">'.$rows['total_general'].' '.$valuta.'</div>
		<div class="interior"><div class="span-2 sts3 ui-widget-content ui-corner-all">Draft</div></div>
	</div>
	<div class="span-5 ui-helper-hidden box-options ui-state-highlight ui-corner-bottom ui-corner-tr" id="box-options'.$rows['id_factura'].'">
		<div class="span5 text-options ui-corner-all even"><a href="facturi_editare.php?idf='.$_SESSION['id_user'].'&id_draft='.$rows['id_draft'].'">Emitere draft</a></div>
	</div>
';
	$i++;
}
?>
</div>
<div class="span-22">
	<div class="span-22 last"><div class="info-login ui-state-hover form-row">Facturi restante</div></div>
<?php
$j = 0;
$sqln = $db->query('select facturi.*, firme.denumire from facturi,firme where facturi.id_client=firme.id_firma and id_draft="0" and stare_incasare="0" order by facturi.data_add asc limit 3');
while ($rown = mysql_fetch_array($sqln)){
	if (isset($rows['valuta'])) $valuta = '<span class="valuta">'.$rown['valuta'].'</span>';
	else $valuta = '<span class="valuta">Lei</span>';
	if ($j%2 == 0) echo '<div class="span-22 list-container even" id_draft="'.$rown['id_draft'].'" factura="'.str_replace(' ','_',$rown['serie']).'_'.$rows['numar'].'">';
	else echo '<div class="span-22 list-container" id_draft="'.$rown['id_draft'].'" factura="'.str_replace(' ','_',$rown['serie']).'_'.$rown['numar'].'">';
	
	echo '
		<div class="span-4 facturi-serie">
			<div id="'.$rows['id_factura'].'" class="list-options ui-state-default ui-corner-all"><div class="ui-icon ui-icon-triangle-1-s"></div></div>
			<div class="serie span-3 uppercase">'.$rown['serie'].' '.$rown['numar'].'</div>
		</div>
		<div class="span-8 facturi-client uppercase">'.$rown['denumire'].'</div>
		<div class="span-3 facturi-data">'.convert_data(date('d-m-Y',strtotime($rown['data_factura']))).'</div>
		<div class="span-3 facturi-ttl">'.$rown['total_general'].' '.$valuta.'</div>
		<div class="span-2 sts0 ui-corner-all ui-state-error">Neincasat</div>
	</div>
	<div class="span-5 ui-helper-hidden box-options0 ui-state-highlight ui-corner-bottom ui-corner-tr" id="box-options'.$rows['id_factura'].'">
		<div class="span5 text-options ui-corner-all even"><a href="facturi_finalizare.php?idf='.$_SESSION['id_user'].'&factura='.str_replace(' ','_',$rown['serie']).'_'.$rown['numar'].'&print_factura=1">Tiparire factura</a></div>
		<div class="span5 text-options ui-corner-all"><a href="facturi_finalizare.php?idf='.$_SESSION['id_user'].'&factura='.str_replace(' ','_',$rown['serie']).'_'.$rown['numar'].'&email=1">Trimitere email</a></div>
		<div class="span5 text-options ui-corner-all even"><a href="facturi_finalizare.php?idf='.$_SESSION['id_user'].'&factura='.str_replace(' ','_',$rown['serie']).'_'.$rown['numar'].'&incasare=1">Incasare factura</a></div>
		<div class="span5 text-options ui-corner-all"><a href="facturi_finalizare.php?idf='.$_SESSION['id_user'].'&factura='.str_replace(' ','_',$rown['serie']).'_'.$rown['numar'].'&history=1">Istoric factura</a></div>
		<div class="span5 text-options ui-corner-all even"><a href="facturi_editare.php?idf='.$_SESSION['id_user'].'&id_factura='.$rown['id_factura'].'">Editare factura</a></div>
	</div>
	';
	$j++;
}
?>
</div>
</div>
<div class="span-7">
</div>
</div>
<!-- End container -->
</div>