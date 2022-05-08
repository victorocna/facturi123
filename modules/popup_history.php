<?php
include 'top.php';
$xml = '../useri/'.$_SESSION['subdomeniu'].'/'.$_GET['id_furnizor'].'/xml/'.$_GET['factura'].'.xml';
$factura = xml2array($xml);
$sc = $db->query('select * from firme where id_firma="'.$factura['facturi']['client_attr']['id'].'" and tip_firma="1"');
$rc = mysql_fetch_array($sc);
$ttl_general = $factura['facturi']['total_general'];
$valuta = $factura['facturi']['adv']['valuta'];
?>
<div class="container" style="padding-top: 0; width: 350px; padding: 0px; margin-left: 40px;">
<style>
body { background: #fff; width: 350px; margin-top: 10px; }
</style>
<script>
$(document).ready(function(){
	$('.content-history').mouseover(function(){
		$(this).find('.linc').removeClass('ui-helper-hidden');
	})
	.mouseout(function(){
		$(this).find('.linc').addClass('ui-helper-hidden');
	});
});
function details(id){
	$('#link-details-'+id).attr('href','javascript:hide_details('+id+')').text('Ascunde detalii');
	$('#details-'+id).removeClass('ui-helper-hidden');
}
function hide_details(id){
	$('#link-details-'+id).attr('href','javascript:details('+id+')').text('Detalii');
	$('#details-'+id).addClass('ui-helper-hidden');
}
function print_chitanta(id_chitanta,serie,numar){
	window.top.$('body').find('#dialog_history').dialog('close');
	window.top.print_chitanta(id_chitanta,serie,numar);
}
</script>
<div class="span-14" style="font-size: 1.2em;">
<div class="span-14" id="detalii" style="margin-bottom: 10px;">
	<div class="span-14 last info-history ui-corner-top ui-state-hover">
		<div class="span-11" style="padding: 3px 10px;">Detalii factura</div>
		<div class="span-2 last link-history ui-helper-hidden elink" id="link-detalii">Mai mult</div>
	</div>
<?php
$sql = $db->query('select * from facturi where id_factura="'.$_GET['id_factura'].'" and id_draft="0"');
$row = mysql_fetch_array($sql);
$i=1;

echo '
	<div class="span-14 content-history detalii-first"><div style="padding: 0 10px;">
		<div class="span-14 last" style="margin-bottom: 2px;"><span class="activ-b">'.convert_sql_date($row['data_add']).'</span> (<label class="factura-time" style="font-weight: normal;">'.str_replace('-','/',$row['data_add']).'</label>)</div>
		<div class="span-14 last" style="margin-bottom: 2px;"><div class="span-11 last">Factura <span class="uppercase">'.$row['serie'].' '.$row['numar'].'</span> a fost emisa</div>
		<div class="span-3 linc ui-helper-hidden" style="text-align: right;"><a href="javascript:details('.$i.')" id="link-details-'.$i.'">Detalii</a></div></div>
		<div class="span-14 ui-helper-hidden box-detalii" id="details-'.$i.'">
			<div class="span-14 last">Client: <span style="text-transform: uppercase;">'.$rc['denumire'].'</span></div>
			<div class="span-14 last">Total general: '.$ttl_general.' '.$valuta.'</div>
		</div>
	</div></div>';
?>
</div>
<script>
$(document).ready(function(){
	$('.factura-time').cuteTime({refresh: 60*1000});
});
</script>

<div class="span-14" id="email" style="margin-bottom: 10px;">
	<div class="span-14 last info-history ui-corner-top ui-state-hover">
		<div class="span-11" style="padding: 3px 10px;">Emailuri trimise</div>
		<div class="span-2 last link-history ui-helper-hidden elink" id="link-email">Mai mult</div>
	</div>
	
<?php
$sql = $db->query('select * from email where id_factura="'.$_GET['id_factura'].'" order by data_add desc limit 1');
if (mysql_num_rows($sql) != 0){
	$i++;
	$row = mysql_fetch_array($sql);
	$exclude_email = $row ['id_email'];

	echo '
	<div class="span-14 content-history email-first"><div style="padding: 0 10px;">
		<div class="span-14 last" style=" margin-bottom: 2px;"><span class="activ-b">'.convert_sql_date($row['data_add']).'</span> (<label class="email-time" style="font-weight: normal;">'.str_replace('-','/',$row['data_add']).'</label>)</div>
		<div class="span-14 last"><div class="span-11 last" style="margin-bottom: 2px;">Un email a fost trimis la adresa <span class="activ">'.$row['catre'].'</span></div>
		<div class="span-3 linc ui-helper-hidden" style="text-align: right;"><a href="javascript:details('.$i.')" id="link-details-'.$i.'">Detalii</a></div></div>
		<div class="span-14 ui-helper-hidden box-detalii" id="details-'.$i.'">
	';
	if ($row['email'] != '') echo '<div class="span-14 last">Expeditor: '.$row['email'].'</div>';
	if ($row['cc'] != '') echo '<div class="span-14 last">CC: '.$row['cc'].'</div>';
	if ($row['bcc'] != '') echo '<div class="span-14 last">BCC: '.$row['bcc'].'</div>';
	if ($row['subiect'] != '') echo '<div class="span-14 last">Subiect: '.$row['subiect'].'</div>';
	echo '<div class="span-13 last ui-corner-all box-email">'.$row['mesaj'].'</div>';
	if ($row['atasament'] == 1) echo '<div class="span-14 last">A fost atasata factura in format PDF</div>';
	echo '
		</div>
	</div></div>
	';
}
else echo '<div class="span-14 last" style="padding: 0 10px; margin: 3px 0;">Nu exista niciun email trimis</div>';

$sql = $db->query('select * from email where id_factura="'.$_GET['id_factura'].'" and id_email != "'.$exclude_email.'" order by data_add desc limit 20');
if (mysql_num_rows($sql) != 0){
	$i++;
	while ($row = mysql_fetch_array($sql)){
		echo 
	'<div class="span-14 content-history email-hide ui-helper-hidden"><div style="padding: 0 10px;">
		<div class="span-14 last" style=" margin-bottom: 2px;"><span class="activ-b">'.convert_sql_date($row['data_add']).'</span> (<label class="email-time" style="font-weight: normal;">'.str_replace('-','/',$row['data_add']).'</label>)</div>
		<div class="span-14 last"><div class="span-11 last" style="margin-bottom: 2px;">Un email a fost trimis la adresa <span class="activ">'.$row['catre'].'</span></div>
		<div class="span-3 linc ui-helper-hidden" style="text-align: right;"><a href="javascript:details('.$i.')" id="link-details-'.$i.'">Detalii</a></div>
	</div>
	<div class="span-14 ui-helper-hidden box-detalii" id="details-'.$i.'">
	';
	if ($row['email'] != '') echo '<div class="span-14 last">Expeditor: '.$row['email'].'</div>';
	if ($row['cc'] != '') echo '<div class="span-14 last">CC: '.$row['cc'].'</div>';
	if ($row['bcc'] != '') echo '<div class="span-14 last">BCC: '.$row['bcc'].'</div>';
	if ($row['subiect'] != '') echo '<div class="span-14 last">Subiect: '.$row['subiect'].'</div>';
	echo '<div class="span-13 last ui-corner-all box-email">'.$row['mesaj'].'</div>';
	if ($row['atasament'] == 1) echo '<div class="span-14 last">A fost atasata factura in format PDF</div>';
	echo '
		</div>
	</div></div>';
	$i++;
	}
}
?>
</div>
<script>
$(document).ready(function(){
	$('#link-email').toggle(
		function(){
			$(this).text('Mai putin');
			$('.email-hide').removeClass('ui-helper-hidden');
		},
		function(){
			$(this).text('Mai mult');
			$('.email-hide').addClass('ui-helper-hidden');
		}
	);
	$('#email').mouseover(function(){
		if ($('.email-hide').length > 0) $('#link-email').removeClass('ui-helper-hidden');
	})
	.mouseout(function(){
		if ($('.email-hide').length > 0) $('#link-email').addClass('ui-helper-hidden');
	});
	$('.email-time').cuteTime({refresh: 60*1000});
	$.each($('.box-email'),function(){
		$(this).html($(this).text());
	});
});
</script>
<div class="span-14" id="incasari">
	<div class="span-14 last info-history ui-corner-top ui-state-hover">
		<div class="span-11" style="padding: 3px 10px;">Incasari</div>
		<div class="span-2 last link-history ui-helper-hidden elink" id="link-incasari">Mai mult</div>
	</div>
	
<?php
$sql = $db->query('select * from incasare where id_factura="'.$_GET['id_factura'].'" order by data_add desc limit 1');
if (mysql_num_rows($sql) != 0){
	$i++;
	$row = mysql_fetch_array($sql);
	$exclude_incasare = $row['id_incasare'];

	echo '
	<div class="span-14 content-history incasari-first"><div style="padding: 0 10px;">
		<div class="span-14 last" style="margin-bottom: 2px;"><span class="activ-b">'.convert_sql_date($row['data_add']).'</span> (<label class="incasare-time" style="font-weight: normal;">'.str_replace('-','/',$row['data_add']).'</label>)</div>
		<div class="span-14 last"><div class="span-11 last" style="margin-bottom: 2px;">S-a incasat suma de '.$row['suma'].' '.$valuta.'. Rest de plata '.$row['rest_plata'].' '.$valuta.'</div>
		<div class="span-3 linc ui-helper-hidden" style="text-align: right;"><a href="javascript:details('.$i.')" id="link-details-'.$i.'">Detalii</a></div>
	</div>
	<div class="span-14 ui-helper-hidden box-detalii" id="details-'.$i.'">
		<div class="span-14 last">Data incasarii: '.convert_data(date('d-m-Y',strtotime($row['data_incasare']))).'</div>
	';
		if ($row['document'] != ''){
			if ($row['id_chitanta'] != ''){
				$sqls = $db->query('select * from chitante where id_chitanta="'.$row['id_chitanta'].'"');
				$rows = mysql_fetch_array($sqls);
				echo '
				<div class="span-14">Document: '.$row['document'].'</div>
				<div class="span-14 ui-priority-secondary">
					<a href="javascript:print_chitanta(\''.$rows['id_chitanta'].'\',\''.$rows['serie_ch'].'\',\''.$rows['numar_ch'].'\')">Tipareste chitanta acum</a>
				</div>
				';
			}
			else echo '<div class="span-14 last">Document: '.$row['document'].'</div>';
		}
		else echo '<div class="span-14 last">Nu exista alt document</div>';
	echo '
		</div>
	</div></div>
	';	
}
else echo '<div class="span-14 last" style="padding: 0 10px; margin: 3px 0;">Nu exista nicio incasare</div>';

$sql = $db->query('select * from incasare where id_factura="'.$_GET['id_factura'].'" and id_incasare != "'.$exclude_incasare.'" order by data_add desc limit 20');
if (mysql_num_rows($sql) != 0){
	$i++;
	while ($row = mysql_fetch_array($sql)){
		echo '
	<div class="span-14 content-history incasari-hide ui-helper-hidden"><div style="padding: 0 10px;">
		<div class="span-14 last" style="margin-bottom: 2px;"><span class="activ-b">'.convert_sql_date($row['data_add']).'</span> (<label class="incasare-time" style="font-weight: normal;">'.str_replace('-','/',$row['data_add']).'</label>)</div>
		<div class="span-14 last"><div class="span-11 last" style="margin-bottom: 2px;">S-a incasat suma de '.$row['suma'].' '.$valuta.'. Rest de plata '.$row['rest_plata'].' '.$valuta.'</div>
		<div class="span-3 linc ui-helper-hidden" style="text-align: right;"><a href="javascript:details('.$i.')" id="link-details-'.$i.'">Detalii</a></div>
	</div>
	<div class="span-14 ui-helper-hidden box-detalii" id="details-'.$i.'">
		<div class="span-14 last">Data incasarii: '.convert_data(date('d-m-Y',strtotime($row['data_incasare']))).'</div>
	';
		if ($row['document'] != ''){
			if ($row['id_chitanta'] != ''){
				$sqls = $db->query('select * from chitante where id_chitanta="'.$row['id_chitanta'].'"');
				$rows = mysql_fetch_array($sqls);
				echo '
				<div class="span-14">Document: '.$row['document'].'</div>
				<div class="span-14 ui-priority-secondary">
					<a href="javascript:print_chitanta(\''.$rows['id_chitanta'].'\',\''.$rows['serie_ch'].'\',\''.$rows['numar_ch'].'\')">Tipareste chitanta acum</a>
				</div>
				';
			}
			else echo '<div class="span-14 last">Document: '.$row['document'].'</div>';
		}
		else echo '<div class="span-14 last">Nu exista alt document</div>';
	echo '
		</div>
	</div></div>
	';
	$i++;
	}
}	
?>
<script>
$(document).ready(function(){
	$('#link-incasari').toggle(
		function(){
			$(this).text('Mai putin');
			$('.incasari-hide').removeClass('ui-helper-hidden');
		},
		function(){
			$(this).text('Mai mult');
			$('.incasari-hide').addClass('ui-helper-hidden');
		}
	);
	$('#incasari').mouseover(function(){
		if ($('.incasari-hide').length > 0) $('#link-incasari').removeClass('ui-helper-hidden');
	})
	.mouseout(function(){
		if ($('.incasari-hide').length > 0) $('#link-incasari').addClass('ui-helper-hidden');
	});
	$('.incasare-time').cuteTime({refresh: 60*1000});
});
</script>	
</div>
</div>