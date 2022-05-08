<div id="sablon-normal">
<div class="span-22 last" style="margin: 20px 0 30px 0;">
<div class="span-8 last">
<?php
	echo '
	<div class="line-s span-8" style="font-weight: bold;">
		<div class="line-left" style="font-size: 1.1em;">Furnizor</div>
		<div class="span-6 last uppercase-r" style="font-size: 1.3em;">'.$rf['denumire'].'</div>
	</div>
	<div class="line-s span-8">
		<div class="line-left-xl">CIF / CUI</div>
		<div class="uppercase">'.$rf['cif'].'</div>
	</div>';
	echo '
	<div class="line-s span-8">
		<div class="line-left-xl">Adresa</div>';
		if ($rf['adresa']) echo '<div class="span-6 last capitalize-r">'.$rf['adresa'].'</div>';
		else echo '<div class="span-6 last capitalize-r">-</div>';
	echo '
	</div>
	<div class="line-s span-8">
		<div class="line-left-xl">Reg Com</div>';
		if ($rf['reg_com']) echo '<div class="span6 last uppercase-r">'.$rf['reg_com'].'</div>';
		else echo '<div class="span6 last uppercase-r">-</div>';
	echo '
	</div>
	<div class="line-s span-8">
		<div class="line-left-xl">Banca</div>';
		if ($rf['banca']) echo '<div class="span-6 last capitalize-r">'.$rf['banca'].'</div>';
		else echo '<div class="span-6 last capitalize-r">-</div>';
	echo '
	</div>
	<div class="line-s span-8">
		<div class="line-left-xl">IBAN</div>';
		if ($rf['iban']) echo '<div class="span-6 last uppercase-r">'.$rf['iban'].'</div>';
		else echo '<div class="span-6 last uppercase-r">-</div>';
	echo '</div>';
?>	
</div>
<div class="span-4 last" style="margin: 10px 30px 0 0; border: 1px solid #79b7e7; padding: 10px 21px 5px 21px;">
<?php
	echo '<div class="line-s span-6" style="font-weight: bold;"><div class="line-left-s" style="font-size: 1.1em;">Serie</div><div class="uppercase" style="padding-top: 1px;">'.str_replace('-',' ',$_GET['factura']).'</div></div>';
	echo '<div class="line-s span-6" style="font-weight: bold;"><div class="line-left-s" style="font-size: 1.1em; padding-right: 2px;">Data</div><div style="text-align: left; padding-top: 2px;">'.convert_data($factura['facturi']['data_factura']).'</div></div>';
	echo '<div class="line-s span-6" style="font-weight: bold;"><div class="line-left" style="font-size: 1.1em;">Cota TVA</div><div style=" text-align: left; font-size: 1.1em;">'.$cota_tva.' %</div></div>';
	echo '<div class="line-s span-6" style="font-weight: bold;"><div class="line-left-s" style="font-size: 1.1em;">Valuta</div><div style=" text-align: left; font-size: 1.1em;">'.$valuta.'</div></div>';
?>
</div>
<div class="span-8 last">
<?php
	echo '
	<div class="line-s span-8" style="font-weight: bold;">
		<div class="line-left-l" style="font-size: 1.1em;">Client</div>
		<div class="span-6 last uppercase-r" style="font-size: 1.3em;">'.$rc['denumire'].'</div>
	</div>
	<div class="line-s span-8">
		<div class="line-left-l">CIF / CUI</div>
		<div class="uppercase">'.$rc['cif'].'</div>
	</div>
	<div class="line-s span-8">
		<div class="line-left-l">Adresa</div>';
		if ($rc['adresa']) echo '<div class="span-6 last capitalize-r">'.$rc['adresa'].'</div>';
		else echo '<div class="span-6 last capitalize-r">-</div>';
	echo '
	</div>
	<div class="line-s span-8">
		<div class="line-left-l">Reg Com</div>';
		if ($rc['reg_com']) echo '<div class="span6 last uppercase-r">'.$rc['reg_com'].'</div>';
		else echo '<div class="span6 last uppercase-r">-</div>';
	echo '
	</div>
	<div class="line-s span-8">
		<div class="line-left-l">Banca</div>';
		if ($rc['banca']) echo '<div class="span-6 last capitalize-r">'.$rc['banca'].'</div>';
		else echo '<div class="span-6 last capitalize-r">-</div>';
	echo '
	</div>
	<div class="line-s span-8">
		<div class="line-left-l">IBAN</div>';
		if ($rc['iban']) echo '<div class="span-6 last uppercase-r">'.$rc['iban'].'</div>';
		else echo '<div class="span-6 last uppercase-r">-</div>';
	echo '</div>';
?>
</div>
</div>
<div class="span-9 last" style="margin: 0 0 50px 535px;">
	<div class="span-8">
<?php
if ($factura['facturi']['text_client']) echo '
		<textarea id="text-client-n" class="afters-default span-8" onkeyup="countable($(this).val().length,$(this))" maxlength="160">'.$factura['facturi']['text_client'].'</textarea>
		<div class="span-4 countable-box grey">
			<span class="countable">160</span>
			<span class="countable-text">caractere ramase</span>
		</div>
<script>
$(document).ready(function(){
	$("#text-client-n").val($("#text-client-n").val().replace(new RegExp( "<br>", "g" ),"\n")).trigger("keyup");
	$(".countable").text(160-$("#text-client-n").val().length);
});
</script>
';
else echo '
		<textarea id="text-client-n" class="afters-default span-8" onkeyup="countable($(this).val().length,$(this))" maxlength="160"></textarea>
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
<div class="span-22 last" style="margin-left: 10px;">
	<table border="1" style="border-collapse: collapse; margin-bottom: 30px; border: solid 1px #b3b3b3;" id="tbl">
		<tr style="text-align: center; font-size: 1.2em;" class="ui-widget-header">
			<td class="rows-l span-1">Nr</td>
			<td class="rows-l span-8">Denumire produs</td>
			<td class="rows-l span-25">UM</td>
			<td class="rows-l span-2">Cantitate</td>
			<td class="rows-l span-2" id="pret-h">Pret unitar</td>
			<td class="rows-l span-25">Valoare</td>
			<td class="rows-l span-25">Valoare TVA</td>
		</tr>
		<tr class="grey" style="text-align: center; font-size: 1em; padding: 0;">	
			<td class="rows-s span-1"><span class="rows-ie">0</span></td>
			<td class="rows-s span-8">1</td>
			<td class="rows-s span-25">2</td>
			<td class="rows-s span-2">3</td>
			<td class="rows-s span-2">4</td>
			<td class="rows-s span-25">5(3x4)</td>
			<td class="rows-s span-25">6</td>
		</tr>
<?php
if (isset($factura['facturi']['linii']['linie_attr'])){
	$sp = $db->query('select * from produse where id_produs="'.$factura['facturi']['linii']['linie']['denumire_attr']['id'].'"');
	$rp = mysql_fetch_array($sp);
	echo '
		<tr class="tr-last tr-even" style="font-size: 1.2em;">
			<td class="rnd-c" style="width: 20px;">1</td>
			<td class="span-8 rnd">'.$rp['denumire'].'</td>
			<td class="span-25 rnd">'.$rp['unitate'].'</td>
			<td class="span-2 rnd-r">'.$factura['facturi']['linii']['linie']['cantitate'].'</td>
			<td class="span-2 rnd-r">'.$factura['facturi']['linii']['linie']['pret'].'</td>
			<td class="span-25 rnd-r">'.$factura['facturi']['linii']['linie']['valoare'].'</td>
			<td class="span-25 rnd-r">'.$factura['facturi']['linii']['linie']['tva'].'</td>
		</tr>
	';
}
else{
	for ($i=0; $i<(count($factura['facturi']['linii']['linie'])-2); $i++){
		$sp = $db->query('select * from produse where id_produs="'.$factura['facturi']['linii']['linie'][$i]['denumire_attr']['id'].'"');
		$rp = mysql_fetch_array($sp);
		if ($i%2 == 0) echo '<tr class="tr-even" style="font-size: 1.2em;">';
		if ($i%2 == 1) echo '<tr class="tr-odd" style="font-size: 1.2em;">';
		echo '
			<td class="rnd-c" style="width: 20px; text-align: center;">'.($i+1).'</td>
			<td class="span-8 rnd">'.$rp['denumire'].'</td>
			<td class="span-25 rnd">'.$rp['unitate'].'</td>
			<td class="span-2 rnd-r">'.$factura['facturi']['linii']['linie'][$i]['cantitate'].'</td>
			<td class="span-2 rnd-r">'.$factura['facturi']['linii']['linie'][$i]['pret'].'</td>
			<td class="span-25 rnd-r">'.$factura['facturi']['linii']['linie'][$i]['valoare'].'</td>
			<td class="span-25 rnd-r">'.$factura['facturi']['linii']['linie'][$i]['tva'].'</td>
		</tr>
		';
	}
	for ($i=(count($factura['facturi']['linii']['linie'])-2); $i<(count($factura['facturi']['linii']['linie'])-1); $i++){
		$sp = $db->query('select * from produse where id_produs="'.$factura['facturi']['linii']['linie'][$i]['denumire_attr']['id'].'"');
		$rp = mysql_fetch_array($sp);
		if ($i%2 == 0) echo '<tr class="tr-last tr-even" style="font-size: 1.2em;">';
		if ($i%2 == 1) echo '<tr class="tr-last tr-odd" style="font-size: 1.2em;">';
		echo '
			<td class="rnd-c" style="width: 20px; text-align: center;">'.($i+1).'</td>
			<td class="span-8 rnd">'.$rp['denumire'].'</td>
			<td class="span-25 rnd">'.$rp['unitate'].'</td>
			<td class="span-2 rnd-r">'.$factura['facturi']['linii']['linie'][$i]['cantitate'].'</td>
			<td class="span-2 rnd-r">'.$factura['facturi']['linii']['linie'][$i]['pret'].'</td>
			<td class="span-25 rnd-r">'.$factura['facturi']['linii']['linie'][$i]['valoare'].'</td>
			<td class="span-25 rnd-r">'.$factura['facturi']['linii']['linie'][$i]['tva'].'</td>
		</tr>
		';
	}
}
?>
		<tr style="font-size: 1.3em;">
			<td colspan="3" rowspan="2" style="font-size: .7em; padding: 10px;">
<?php
if ($factura['facturi']['adv']['reprez_attr']['id']){
	$sr = $db->query('select * from reprezentanti where id_reprez="'.$factura['facturi']['adv']['reprez_attr']['id'].'"');
	$rr = mysql_fetch_array($sr);
	echo '
	<div class="span-7 left" style="margin-bottom: 3px;">
		<span class="adv-min">Intocmit de </span><span class="capitalize">'.$rr['nume_reprez'].'</span>
	</div>
	';
	if ($rr['act_reprez']) echo '
	<div class="span-7 left" style="margin-bottom: 7px;">
		<span class="adv-min">Act identitate</span><span class="uppercase">'.$rr['act_reprez'].'</span>
	</div>';
}
if ($factura['facturi']['adv']['delegat_attr']['id']){
	$sd = $db->query('select * from delegati where id_delegat="'.$factura['facturi']['adv']['delegat_attr']['id'].'"');
	$rd = mysql_fetch_array($sd);
	echo '
	<div class="span-7 left" style="margin-bottom: 3px;">
		<span class="adv-min">Delegat </span><span class="capitalize">'.$rd['nume_delegat'].'</span>
	</div>
	';
	if ($rd['act_identitate']) echo '
	<div class="span-7 left" style="margin-bottom: 3px;">
		<span class="adv-min">Act identitate</span>
		<span class="uppercase">'.$rd['act_identitate'].'</span>
	</div>
	';
}
?>
			</td>
			<td colspan="2" class="span-4" style="font-weight: bold; text-align: right; padding: 13px 10px 13px 0;">Subtotal</td>
<?php
	echo '
		<td style="text-align: right; padding: 10px 5px 10px 0; font-weight: bold;">'.$factura['facturi']['total_valoare'].'</td>
		<td style="text-align: right; padding: 10px 5px 10px 0; font-weight: bold;">'.$factura['facturi']['total_tva'].'</td>
	';
?>	
		</tr>
		<tr style="font-size: 1.3em;">
<?php
	echo '
		<td colspan="2" class="rows span-4" style="font-weight: bold; text-align: right; padding: 13px 10px 13px 0;">Total General '.$valuta.'</td>
		<td colspan="2" class="rows" style="text-align: right; padding: 10px 5px 10px 0; font-weight: bold;">'.$factura['facturi']['total_general'].'</td>
	';
?>
		</tr>
<?php
	if ($factura['facturi']['adv']['data_scadenta'] || $factura['facturi']['adv']['observatii']){
		echo '<tr class="tr-even" style="font-size: 1.1em;"><td class="rind" colspan="7"><div class="span-20" style="min-height: 50px;">';
		if ($factura['facturi']['adv']['data_scadenta']) echo '
			<div class="span-20" style="margin-bottom: 5px;">
				<div class="span3 adv-med">Data scadentei </div>
				<div class="span-16 capitalize">'.convert_data($factura['facturi']['adv']['data_scadenta']).'</div>
			</div>
		';
		if ($factura['facturi']['adv']['observatii']) echo '
			<div class="span-20" style="margin-bottom: 5px;">
				<div class="span3 adv-med">Observatii </div>
				<div class="span-16">'.$factura['facturi']['adv']['observatii'].'</div>
			</div>
		';
		echo '</div></td></tr>';
	}
?>
	</table>
</div>
</div>