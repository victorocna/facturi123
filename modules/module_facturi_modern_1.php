<div id="sablon-modern" class="ui-helper-hidden">
<div class="span-22 last" style="margin: 20px 0 40px 0;">
<div class="span-12">
<?php
	echo '<div class="line span-9" style="text-transform: uppercase; font-weight: bold;">'.$rf['denumire'].'</div>';
	echo '<div class="line-s span-9" style="text-transform: uppercase;">'.$rf['cif'].'</div>';
	if ($rf['adresa']) echo '<div class="line-s span-9" style="text-transform: capitalize;">'.$rf['adresa'].'</div>';
	if ($rf['reg_com']) echo '<div class="line-s span-9" style="text-transform: uppercase;">'.$rf['reg_com'].'</div>';
	if ($rf['banca']) echo '<div class="line-s span-9" style="text-transform: capitalize;">'.$rf['banca'].'</div>';
	if ($rf['iban']) echo '<div class="line-s span-9" style="text-transform: uppercase;">'.$rf['iban'].'</div>';
?>	
</div>
<div class="span-8 last" style="margin-left: 15px;">
<?php
	echo '<div class="line span-9" style="text-transform: uppercase; font-weight: bold;">'.$rc['denumire'].'</div>';
	echo '<div class="line-s span-9" style="text-transform: uppercase;">'.$rc['cif'].'</div>';
	if ($rc['reg_com']) echo '<div class="line-s span-9" style="text-transform: uppercase;">'.$rc['reg_com'].'</div>';
?>	
	<div class="span-8">
<?php
if ($factura['facturi']['text_client']) echo '
		<textarea id="text-client-m" class="afters-default span-8" onkeyup="countable($(this).val().length,$(this))" maxlength="160">'.$factura['facturi']['text_client'].'</textarea>
		<div class="span-4 countable-box grey">
			<span class="countable">160</span>
			<span class="countable-text">caractere ramase</span>
		</div>
<script>
$(document).ready(function(){
	$("#text-client-m").val($("#text-client-m").val().replace(new RegExp( "<br>", "g" ),"\n")).trigger("keyup");
	$(".countable").text(160-$("#text-client-m").val().length);
});
</script>
';
else echo '
		<textarea id="text-client-m" class="afters-default span-8" onkeyup="countable($(this).val().length,$(this))" maxlength="160"></textarea>
		<div class="span-4 countable-box grey">
			<span class="countable">160</span>
			<span class="countable-text">caractere ramase</span>
		</div>
';
?>
		<div class="span4 text-save" style="text-align: right;"><a href="javascript:update_text_client()">Salveaza mesaj</a></div>
		<div class="span4 text-save-null ui-helper-hidden" style="text-align: right;"><a href="javascript:delete_text_client()">Renunta la mesaj</a></div>
	</div>
</div>
</div>

<div class="span-23" id="primary" style="margin-bottom: 60px;">
<?php
echo '<div class="span-3 last imp-top">Serie si numar
	<div id="data-emitere" class="imp-big" style="text-transform: uppercase;">'.str_replace('-',' ',$_GET['factura']).'</div>
	</div>';
if ($factura['facturi']['adv']['data_scadenta']) echo '<div class="line span-9 last" style="border-top: solid 1px #ccc; padding: 20px 0 0 15px;"></div>';
else echo '<div class="line span-12 last" style="border-top: solid 1px #ccc; padding: 20px 0 0 20px;"></div>';
echo '
	<div class="span-3 last imp">Data emiterii
	<div id="data-emitere" class="imp-big">'.convert_data($factura['facturi']['data_factura']).'</div>
	</div>
	<div class="span-3 last imp" style="background: #e17009; color: #fff; border: solid 1px #e17009;">Total de plata
	<div class="imp-big" id="ttl-general">'.$factura['facturi']['total_general'].' '.$valuta.'</div>
	</div>
	';
if ($factura['facturi']['adv']['data_scadenta']) echo '<div class="span-3 last imp">Data scadentei<div id="data-scadenta" class="imp-big">'.convert_data($factura['facturi']['adv']['data_scadenta']).'</div></div>';
?>
</div>

<div class="span-23 last box-table" style="margin-bottom: 30px;">
	<table border="0" id="tabel">
		<tr style="text-align: center; font-size: 1.2em;" class="tr-head ui-widget-header">
			<td class="rows-l span-9 ui-corner-tl" colspan="2">Denumire produs</td>
			<td class="rows-l span-2">UM</td>
			<td class="rows-l span-2">Cantitate</td>
			<td class="rows-l span-25" id="pret-h">Pret unitar</td>
			<td class="rows-l span-25">Valoare</td>
			<td class="rows-l span-25 ui-corner-tr">TVA <span class="taxa-tva"><?php echo $cota_tva; ?>%</span></td>
		</tr>
<?php
if (isset($factura['facturi']['linii']['linie_attr'])){
	$sp = $db->query('select * from produse where id_produs="'.$factura['facturi']['linii']['linie']['denumire_attr']['id'].'"');
	$rp = mysql_fetch_array($sp);
	echo '
		<tr class="tr-even" style="font-size: 1.2em;">
			<td class="rind-c" style="width: 20px;">1.</td>
			<td class="span-9 rind">'.$rp['denumire'].'</td>
			<td class="span-2 rind">'.$rp['unitate'].'</td>
			<td class="span-2 rind-r">'.$factura['facturi']['linii']['linie']['cantitate'].'</td>
			<td class="span-25 rind-r">'.$factura['facturi']['linii']['linie']['pret'].'</td>
			<td class="span-25 rind-r">'.$factura['facturi']['linii']['linie']['valoare'].'</td>
			<td class="span-25 rind-r">'.$factura['facturi']['linii']['linie']['tva'].'</td>
		</tr>
	';
}
else{
	for ($i=0; $i<(count($factura['facturi']['linii']['linie'])-1); $i++){
		$sp = $db->query('select * from produse where id_produs="'.$factura['facturi']['linii']['linie'][$i]['denumire_attr']['id'].'"');
		$rp = mysql_fetch_array($sp);
		if ($i%2 == 0) echo '<tr class="tr-even" style="font-size: 1.2em;">';
		if ($i%2 == 1) echo '<tr class="tr-odd" style="font-size: 1.2em;">';
		echo '
			<td class="rind-c" style="width: 20px;">'.($i+1).'.</td>
			<td class="span-9 rind">'.$rp['denumire'].'</td>
			<td class="span-2 rind">'.$rp['unitate'].'</td>
			<td class="span-2 rind-r">'.$factura['facturi']['linii']['linie'][$i]['cantitate'].'</td>
			<td class="span-25 rind-r">'.$factura['facturi']['linii']['linie'][$i]['pret'].'</td>
			<td class="span-25 rind-r">'.$factura['facturi']['linii']['linie'][$i]['valoare'].'</td>
			<td class="span-25 rind-r">'.$factura['facturi']['linii']['linie'][$i]['tva'].'</td>
		</tr>';
	}
}
?>		
	</table>
	<table border="0" id="tabel2" style="margin-top: 10px;">
		<tr style="font-weight: bold; font-size: 1.3em; text-align: right;">
			<td class="rows span16">Subtotal</td>
<?php echo '<td class="rows span-25 init-subtotal">'.$factura['facturi']['total_valoare'].'</td>'; ?>
<?php echo '<td class="rows span-25" style="padding-left: 10px;">'.$factura['facturi']['total_tva'].'</td>'; ?>					
		</tr>
		<tr style="font-weight: bold; font-size: 1.3em; text-align: right;">
<?php echo '<td class="rows span16">Total General '.$valuta.'</td>'; ?>
<?php echo '<td colspan="2" class="rows">'.$factura['facturi']['total_general'].'</td>'; ?>
		</tr>
	</table>
</div><hr class="horizontal">
<div class="span-22 last" style="margin-bottom: 20px;">
<div class="span-8 last" style="font-size: .8em;">
<?php
	echo '<div class="line-s span-7" style="text-transform: uppercase; font-weight: bold;">'.$rf['denumire'].'</div>';
	echo '<div class="line-s span-7" style="text-transform: uppercase;">'.$rf['cif'].'</div>';
	if ($rf['adresa']) echo '<div class="line-s span-7" style="text-transform: capitalize;">'.$rf['adresa'].'</div>';
	if ($rf['reg_com']) echo '<div class="line-s span-7" style="text-transform: uppercase;">'.$rf['reg_com'].'</div>';
	if ($rf['banca']) echo '<div class="line-s span-7" style="text-transform: capitalize;">'.$rf['banca'].'</div>';
	if ($rf['iban']) echo '<div class="line-s span-7" style="text-transform: uppercase;">'.$rf['iban'].'</div>';
?>	
</div>
<div class="span-7 last" style="font-size: .9em;" id="advanced">
	<div class="span-7 left" style="margin-bottom: 3px;">
		<span class="adv-txt">Cota TVA </span>
<?php echo '<span class="taxa-tva" style="font-size: 1.1em;">'.$cota_tva.'%</span>'; ?>
	</div>
	<div class="span-7 left" style="margin-bottom: 7px;">
		<span class="adv-txt">Valuta </span>
<?php echo '<span class="valuta" style="font-size: 1.1em;">'.$valuta.'</span>'; ?>
	</div>
<?php
if ($factura['facturi']['adv']['reprez_attr']['id']){
	$sr = $db->query('select * from reprezentanti where id_reprez="'.$factura['facturi']['adv']['reprez_attr']['id'].'"');
	$rr = mysql_fetch_array($sr);
	echo '
	<div class="span-6 left" style="margin-bottom: 3px;">
		<span class="adv-txt">Intocmit de </span><span class="capitalize">'.$rr['nume_reprez'].'</span>
	</div>
	';
	if ($rr['act_reprez']) echo '
	<div class="span-6 left" style="margin-bottom: 7px;">
		<span class="adv-txt">Act identitate</span><span class="uppercase">'.$rr['act_reprez'].'</span>
	</div>';
}
if ($factura['facturi']['adv']['delegat_attr']['id']){
	$sd = $db->query('select * from delegati where id_delegat="'.$factura['facturi']['adv']['delegat_attr']['id'].'"');
	$rd = mysql_fetch_array($sd);
	echo '
	<div class="span-6 left" style="margin-bottom: 3px;">
		<span class="adv-txt">Delegat </span><span class="capitalize">'.$rd['nume_delegat'].'</span>
	</div>';
	if ($rd['act_identitate']) echo '
	<div class="span-6 left" style="margin-bottom: 7px;">
		<span class="adv-txt">Act identitate</span><span class="uppercase">'.$rd['act_identitate'].'</span>
	</div>
	';
}
if ($factura['facturi']['adv']['observatii']){
	echo '
	<div class="span-6 left">
		<span class="adv-txt">Observatii </span>
		<span>'.$factura['facturi']['adv']['observatii'].'</span>
	</div>';
}
?>	
</div>
<div class="span-5 last" style="font-size: .8em;">
<?php
	echo '<div class="line-s span-7" style="text-transform: uppercase; font-weight: bold;">'.$rc['denumire'].'</div>';
	echo '<div class="line-s span-7" style="text-transform: uppercase;">'.$rc['cif'].'</div>';
	if ($rc['adresa']) echo '<div class="line-s span-7" style="text-transform: capitalize;">'.$rc['adresa'].'</div>';
	if ($rc['reg_com']) echo '<div class="line-s span-7" style="text-transform: uppercase;">'.$rc['reg_com'].'</div>';
	if ($rc['banca']) echo '<div class="line-s span-7" style="text-transform: capitalize;">'.$rc['banca'].'</div>';
	if ($rc['iban']) echo '<div class="line-s span-7" style="text-transform: uppercase;">'.$rc['iban'].'</div>';
?>	
</div>
</div>
</div>