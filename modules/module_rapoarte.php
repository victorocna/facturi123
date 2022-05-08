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
	<div class="header">Rapoarte</div>
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
			<div class="span3 before-n">Cautare client</div>
			<div class="span-7 search-facturi">
				<input class="after-s span-6" style="font-size: 1em; text-transform: uppercase;" type="text" id="search" readonly="readonly" onkeyup="init_query(($(\'#pagination > .current\').html()-1),\'pagination\')" search="0" autocomplete="off">
			</div>
		</div>
		<div class="box-add img-add" tips="<div class=\'tips-l\'>Adauga factura</div>" onclick="add()"></div>
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
	<div class="span-22 box-top ui-widget-content" style="margin-bottom: 0;">
		<div class="span-20 box-functii">
			<div class="span-1 before-n">Furnizor</div>
			<div class="span-7">
				<input value="'.$rowf['denumire'].'" class="after-select span-6" style="font-size: 1em; text-transform: uppercase;" id="furnizor" href="furnizori_select.php" iid="'.$rowf['id_firma'].'">
			</div>
			<div class="span3 before-n">Cautare client</div>
			<div class="span-7 search-facturi">
				<input class="after-s span-6" style="font-size: 1em; text-transform: uppercase;" type="text" id="search" onkeyup="init_query(($(\'#pagination > .current\').html()-1),\'pagination\')" search="0" autocomplete="off">
			</div>
		</div>
		<div class="box-add img-add" tips="<div class=\'tips-l\'>Adauga factura</div>" onclick="add()"></div>
	</div>
</div>
<div class="span-22" id="container-pages" style="min-height: 475px;">
	';
}
?>
</div>
<!-- End container -->
</div>