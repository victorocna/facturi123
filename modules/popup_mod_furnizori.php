<?php include 'top.php'; ?>
<div class="container" style="width: 810px; padding: 0px; margin-left: 5px;">
<style>body { background: #fff; }</style>
<script>
$(document).ready(function(){
	$('#denumire').focus();
	$('#iban').mask(
		'aa99 aaaa **** **** **** ****',
		{completed: function(){
			verifica_iban();
		}}
	);
	var reguli = jQuery.validationAide.getDefaultValidationRules();
	reguli.add('validator-corect', '', function(v, obj){
		if ($(obj).attr('fault') != 0) return false;
		return true;
	});
	$('#form').validationAideEnable(
		reguli,
		{ fieldErrorCssClass: 'failed', showSummary: false },
		null,
		chk
	);

	var upload = new AjaxUpload('button-sigla', {
		action: '/includes/upload/functii.php',
		data : {
			'op' : 'save_sigla_mod',
			'subdomeniu' : '<?php echo $subdomeniu; ?>',
			'id_firma' : '<?php echo $_GET['id_firma']; ?>'
		},
		onSubmit : function(file,ext){
			if (ext && /^(jpg|jpeg|png)$/.test(ext)){
				$('#button-sigla').html('<span class="button-text">Se incarca...</span>');
			}
			else{
				window.top.notify_bar(10,'Eroare! <span style="font-size: 15px; padding-left: 3px;">Alegeti o imagine ca sigla <span class="activ">(un fisier cu extensie jpg, jpeg, png)</span></span>');
				return false;
			}
		},
		onComplete : function(file,raspuns){
			if (raspuns.substring(0,1) != '0'){
				$('#sigla').val(file);
				$('#button-sigla').html('<span class="button-text">Alege sigla</span>');
				$('.box-cancel, .box-image, .box-img-text').removeClass('ui-helper-hidden');
				$('.box-image').html('');
				$(raspuns).appendTo('.box-image');
			}
			if (raspuns.substring(0,1) == '0'){
				$('#button-sigla').html('<span class="button-text">Alege sigla</span>');
				window.top.notify_bar(10,'Eroare! <span style="font-size: 15px; padding-left: 3px;">Alegeti o imagine cu o dimensiune mai mica <span class="activ">(mai putin de 100KB)</span></span>');
			}
		}
	});
});
function delete_sigla_mod(){
	if ($('#sigla').val()){
		var query = '&subdomeniu=<?php echo $subdomeniu; ?>&id_firma=<?php echo $_GET['id_firma']; ?>&sigla='+$('#sigla').val();
		$.ajax({type:'POST',url:'/includes/upload/functii.php',data:'op=delete_sigla_mod'+query,success:function(raspuns){
				if (raspuns == 1){
					$('#sigla').val('');
					$('.box-image').html('');
					$('.box-cancel, .box-image, .box-img-text').addClass('ui-helper-hidden');
					window.top.notify_bar(10,'Sigla a fost stearsa!');
				}
			}
		});
	}
}
function verifica_denumire(){
	if ($("#denumire").val()){
		var query = '&denumire='+$.trim($("#denumire").val())+'&tip_firma=0&id_firma='+<?php echo $_GET['id_firma']; ?>;
		$.ajax({type:'GET',dataType:'json',url:'/includes/functii.php?op=verifica_denumire'+query,success:function(raspuns){
				if (raspuns.fault == '0'){
					$('#verifica-denumire').html('');
					$('#denumire').attr('fault', '0').removeClass('img-no failed').addClass('img-yes');
					$('#toggle-tips').html('<strong>Informatie necesara</strong><br>Se completeaza denumirea firmei inclusiv forma juridica.<br><span style="font-size: .9em;"><strong>Exemplu:</strong> SC Firma SRL</span>');
				}
				if (raspuns.fault == '1'){
					$('#verifica-denumire').html('Furnizorul exista deja');
					$('#denumire').attr('fault', '1').removeClass('img-yes').addClass('img-no');
					$('#toggle-tips').html('<div style="margin-bottom: 5px;"><strong>Atentie!</strong><br>Furnizorul <span class="uppercase">'+$('#denumire').val()+'</span> a fost adaugat pe data de <strong>'+raspuns.data_add+'</strong></div><span>Vreti sa modificati datele furnizorului? <a href="#" onmouseover="modify('+raspuns.id+')" id="modify" style="color: #00f !important;">Modifica</a></span>');
					$('#cif').blur();
					$('#denumire').focus();
				}
			}
		});
	}
}
function verifica_cif(){
	if ($("#cif").val()){
		var tva = ''; var cif = '';
		if ($("#cif").val().slice(0,2).toLowerCase() == 'ro'){
			cif = $('#cif').val().slice(2);
			tva = '<strong>Platitor de TVA</strong>';
			$("#cif").attr('tva', 1);
		}
		else{
			cif = $('#cif').val();
			tva = '<strong>Neplatitor de TVA</strong>';
			$("#cif").attr('tva', 0);
		}
		$.ajax({type:'GET',url:'/includes/functii.php?op=verifica_cif&cif='+$.trim(cif),success:function(raspuns){
			if (raspuns == 1){
				$('#verifica-cif').html('CIF/CUI corect. '+tva);
				$("#cif").attr('fault', '0').removeClass('img-no').removeClass('failed').addClass('img-yes');
			}
			else{
				$('#verifica-cif').html('CIF/CUI incorect. '+tva);
				$("#cif").attr('fault', '1').removeClass('img-yes').addClass('img-no');
			}
		}
		});
	}
}
function verifica_iban(){
	if ($.trim($("#iban").val())){
		$.ajax({type:'GET',url:'/includes/functii.php?op=verifica_iban&iban='+$.trim($("#iban").val()),success:function(raspuns){
			if (raspuns == 1){
				$('#verifica-iban').html('IBAN corect');
				$('#iban').attr('fault', '0').removeClass('img-no').addClass('img-yes');
			}
			else{
				$('#verifica-iban').html('IBAN incorect');
				$('#iban').attr('fault', '1').removeClass('img-yes').addClass('img-no');
			}
		}
		});
	}
	else{
		$('#verifica-iban').html('');
		$('#iban').attr('fault', '0').removeClass('img-no').removeClass('img-yes');
	}
}
function modify(id){
	$('#modify').bind('mousedown',function(){
		<?php if (isset($_GET['furnizor'])) { ?>
		window.top.modify(id);
		<?php } ?>
		<?php if (isset($_GET['factura'])) { ?>
		window.top.modify_furnizor(id);
		<?php } ?>
	});
}
function chk(s){
	if (!s){
		window.top.notify_bar(10,'Eroare la completarea datelor!');
		if ($("#dialog_confirm").length == 1) $("#dialog_confirm").dialog('close');
		return false;
	}
	else{
		$(document).find('.after').css('border', 'solid 1px #ccc');
		save_firma();
		return false;
	}	
}
function save_firma(){
	var query = "&id_user="+<?php echo $_GET['id_user']; ?>+"&id_firma="+<?php echo $_GET['id_firma']; ?>;
	for (i=0;i<$("input").length;i++){
		query += "&"+$("input").eq(i).attr("id")+"="+$.trim($("input").eq(i).val());
	}
	query += "&tva="+$("#cif").attr('tva');
	$.ajax({type:'GET',url:'/includes/functii.php?op=update_firma'+query+'&furnizor=1',success:function(raspuns){
		if (raspuns){
			window.top.$('body').find('#dialog_modify').dialog('close');
			window.top.notify_bars(6,'Datele furnizorului <span class="uppercase activ">'+$('#denumire').val()+'</span> au fost modificate');
			window.top.query_furnizor(raspuns);
		}
		else window.top.notify_bar(10,'Eroare la completarea datelor!');
	},beforeSend:function(){
		$('.box-loading').removeClass('ui-helper-hidden');
	},complete:function(){
		$('.box-loading').addClass('ui-helper-hidden');
	}
	});
}
function confirm(){
	$("#dialog_confirm").dialog('destroy');
	$("#dialog_confirm").remove();
	var title = "<div class='span-11' style='font-size: .9em; text-align: center;'>Iesire</div>";
	var content =
	'<div id="dialog_confirm" title="'+title+'" style="text-align:left;margin: 10px 0;">'+
		'<h4 style="margin-bottom: 10px;">Confirmati iesirea?</h4>'+
		'<p>Daca parasiti acest formular datele introduse vor fi pierdute.</p>'+
	'</div>';
	$("body").append(content);
	$("#dialog_confirm").show();
	$("#dialog_confirm").dialog({
		height: 'auto',
		width: 460,
		modal:true,
		resizable: false,
		overlay:{
				"background-color": "#333",
				"opacity": "0.75",
				"-moz-opacity": "0.75"
		},
		buttons:{
			"Modificare furnizor":function(){
				$('.fg-button').trigger('submit');
				return false;
				$("#dialog_confirm").dialog('close');
			},
			"Iesire fara modificare":function(){
				$("#dialog_confirm").dialog('close');
				window.top.$('body').find('#dialog_modify').dialog('close');
			}
		}
	});
}
</script>
<?php
	$sql = $db->query('select * from firme where id_user="'.$_GET['id_user'].'" and id_firma="'.$_GET['id_firma'].'"');
	while ($row = mysql_fetch_array($sql)){
		echo '
<div class="span-20">
<form id="form" onsubmit="return false;" style="margin: 20px; margin-top: 0;">
<div class="span-20 form-header ui-corner-top">Informatii firma</div>
<div class="span-20 form-content" style="margin-bottom: 20px;">

<div class="span-20 form-row">
	<div class="before span-3">Denumire</div>
	<div class="box-after span-16 last">
		<input value="'.$row['denumire'].'" class="after span-11 validator-required validator-corect" style="text-transform: uppercase;" type="text" id="denumire" tips="<div id=\'toggle-tips\' class=\'span-6 tips-e\'><strong>Informatie necesara</strong><br>Se completeaza denumirea firmei inclusiv forma juridica.<br><span style=\'font-size: .9em;\'><strong>Exemplu:</strong> SC Firma SRL</span></div>" onblur="verifica_denumire()" autocomplete="off">
		<span id="verifica-denumire" class="input-helper" style="left: -160px; font-weight: bold;"></span>
	</div>
</div>
<div class="span-20 form-row">
	<div class="before span-3">CIF / CUI</div>
	<div class="box-after span-16 last">
		<input value="'.$row['cif'].'" class="after span-11 validator-required validator-corect" style="text-transform: uppercase;" type="text" id="cif" onblur="verifica_cif()" tips="<div class=\'span-6 tips-e\'><strong>Informatie necesara</strong><br>Se completeaza codul <strong>RO</strong> daca firma este platitoare de TVA.</div>" tva="1" fault="0" autocomplete="off">
		<span id="verifica-cif" class="input-helper" style="left: -220px;"></span>
	</div>
</div>
<div class="span-20 form-row">	
	<div class="before span-3">Adresa</div>
	<div class="box-after span-16 last">
		<input value="'.$row['adresa'].'" class="after span-11" type="text" id="adresa" style="text-transform: capitalize;" tips="<div class=\'tips-n\'><strong>Informatie optionala</strong><br>Se poate completa sediul social sau adresa unui punct de lucru</div>" autocomplete="off">
	</div>
</div>
<div class="span-20 form-row">
	<div class="before span-3">Reg Com</div>
	<div class="box-after span-16 last">
		<input value="'.$row['reg_com'].'" class="after span-11" type="text" id="reg_com" style="text-transform: uppercase;" tips="<div class=\'tips-c\' style=\'font-weight: bold;\'>Informatie optionala</div>" autocomplete="off">
	</div>
</div>
		';
$su = $db->query('select * from useri where id_user="'.$_GET['id_user'].'"');
$ru = mysql_fetch_array($su);
if ($ru['id_tip'] != '1'){
	if ($row['sigla']){
		echo '
<div class="span-20 form-last">
	<div class="before span-3">Sigla</div>
	<div class="box-after span-8">
		<input value="'.$row['sigla'].'" class="after span-8" type="text" id="sigla" autocomplete="off" readonly="readonly">
	</div>
	<div class="box-after span-3 last box-file">
		<button class="fg-button-s white ui-corner-all" id="button-sigla" style="margin-top: 8px; width: 110px;"><span class="button-text">Alege sigla</span></button>
	</div>
	<div class="span-4 box-img">
		<div class="span-4 box-img-text ui-state-hover">Vizualizare sigla</div>
		<div class="span-4 box-image ui-widget-content">
	';
	$sigla_rel = '/useri/'.$subdomeniu.'/'.$row['id_firma'].'/sigla/'.$row['sigla'];
	$resize = construct($sigla_rel);
	if ($resize == 1) echo '<img src="/includes/upload/resize.php/'.$row['sigla'].'?width=130&amp;height=190&amp;image=/useri/'.$subdomeniu.'/'.$_GET['id_firma'].'/sigla/'.$row['sigla'].'">';
	if ($resize == 2) echo '<img src="/includes/upload/resize.php/'.$row['sigla'].'?width=160&amp;height=110&amp;image=/useri/'.$subdomeniu.'/'.$_GET['id_firma'].'/sigla/'.$row['sigla'].'">';
	echo '
		</div>
	</div>
</div>
<div class="span-14">
	<div class="box-after span-4 last box-cancel">
		<a href="javascript: delete_sigla_mod()" class="back" style="color: #666; font-size: .9em;">Renunta la sigla</a>
	</div>
</div>
	';
	}
	else echo '
<div class="span-20 form-last">
	<div class="before span-3">Sigla</div>
	<div class="box-after span-8">
		<input class="after span-8" type="text" id="sigla" autocomplete="off" readonly="readonly">
	</div>
	<div class="box-after span-3 last box-file">
		<button class="fg-button-s white ui-corner-all" id="button-sigla" style="margin-top: 8px; width: 110px;"><span class="button-text">Alege sigla</span></button>
	</div>
	<div class="span-4 box-img">
		<div class="span-4 box-img-text ui-state-hover ui-helper-hidden">Vizualizare sigla</div>
		<div class="span-4 box-image ui-widget-content ui-helper-hidden"></div>
	</div>
</div>
<div class="span-14">
	<div class="box-after span-4 last box-cancel ui-helper-hidden">
		<a href="javascript: delete_sigla_mod()" class="back" style="color: #666; font-size: .9em;">Renunta la sigla</a>
	</div>
</div>
	';
}
		echo '
</div>

<div class="span-20 form-header ui-corner-top">Informatii financiare</div>
<div class="span-20 form-content" style="margin-bottom: 10px;">

<div class="span-20 form-row">
	<div class="before span-3">Banca</div>
	<div class="box-after span-16 last">
		<input value="'.$row['banca'].'" class="after span-11" type="text" id="banca" style="text-transform: capitalize;" tips="<div class=\'tips-n\'><strong>Informatie optionala</strong><br>Se poate completa denumirea bancii si sucursala sau agentia</div>" autocomplete="off">
	</div>
</div>
<div class="span-20 form-last">	
	<div class="before span-3">IBAN</div>
	<div class="box-after span-16 last">
		<input value="'.$row['iban'].'" class="after span-11 validator-corect" style="text-transform: uppercase;" type="text" id="iban" tips="<div class=\'tips-c\' style=\'font-weight: bold;\'>Informatie optionala</div>" fault="0" autocomplete="off">
		<span id="verifica-iban" class="input-helper"></span>
	</div>
</div>
</div>
		';
	}
?>
<div class="span-17" style="margin-bottom: 10px;">
	<div class="span12" style="text-align: right;"><a href="javascript: confirm()" class="back">Inapoi</a></div>
	<div class="span-3" style="text-align: right;"><button class="fg-button orange ui-corner-all" type="submit" style="padding: .5em 1.4em;"><span class="button-text">Modifica</span></button></div>
	<div class="span-1 box-loading ui-helper-hidden"></div>
</div>
</form>
</div>
<!-- End container -->
</div>