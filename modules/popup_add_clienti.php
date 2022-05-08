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
});
function verifica_denumire(){
	if ($("#denumire").val()){
		var query = '&denumire='+$.trim($("#denumire").val())+'&tip_firma=1';
		$.ajax({type:'GET',dataType:'json',url:'/includes/functii.php?op=verifica_denumire'+query,success:function(raspuns){
				if (raspuns.fault == '0'){
					$('#verifica-denumire').html('');
					$('#denumire').attr('fault', '0').removeClass('img-no failed').addClass('img-yes');
					$('#toggle-tips').html('<strong>Informatie necesara</strong><br>Se completeaza denumirea firmei inclusiv forma juridica.<br><span style="font-size: .9em;"><strong>Exemplu:</strong> SC Firma SRL</span>');
				}
				if (raspuns.fault == '1'){
					$('#verifica-denumire').html('Clientul exista deja');
					$('#denumire').attr('fault', '1').removeClass('img-yes').addClass('img-no');
					$('#toggle-tips').html('<div style="margin-bottom: 5px;"><strong>Atentie!</strong><br>Clientul <span class="uppercase">'+$('#denumire').val()+'</span> a fost adaugat pe data de <strong>'+raspuns.data_add+'</strong></div><span>Vreti sa modificati datele clientului? <a href="#" onmouseover="modify('+raspuns.id+')" id="modify" style="color: #00f !important;">Modifica</a></span>');
					$('input').blur();
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
		<?php if (isset($_GET['client'])) { ?>
		window.top.modify(id);
		<?php } ?>
		<?php if (isset($_GET['factura'])) { ?>
		window.top.modify_client(id);
		<?php } ?>
		window.top.$('body').find('#dialog_add').dialog('destroy');
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
	var query = "&id_user="+<?php echo $_GET['id_user']; ?>;
	for (i=0;i<$("input").length;i++){
		query += "&"+$("input").eq(i).attr("id")+"="+$.trim($("input").eq(i).val());
	}
	query += "&tva="+$("#cif").attr('tva');
	query += '&client=1';
	$.ajax({type:'GET',url:'/includes/functii.php?op=save_firma'+query,success:function(raspuns){
		if (raspuns){
			<?php if (isset($_GET['client'])) { ?>
			window.top.$('body').find('#dialog_add').dialog('close');
			window.top.notify_bars(10,'Clientul <span class="uppercase activ">'+$('#denumire').val()+'</span> a fost salvat');
			window.top.init_query((window.top.$('body').find('#pagination > span').html()-1),'pagination');
			<?php } ?>
			<?php if (isset($_GET['factura']) && !isset($_GET['op'])) { ?>
			window.top.$('body').find('#dialog_add').dialog('close');
			window.top.notify_bars(10,'Clientul <span class="uppercase activ">'+$('#denumire').val()+'</span> a fost salvat');
			window.top.query_client(raspuns);
			<?php } ?>
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
	var valoare = '';
	$.each($('input'),function(i,obj){
		if ($(obj).val()) valoare = $(obj).val();
	});
	if (valoare){
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
				"Salvare client":function(){
					$('.fg-button').trigger('submit');
					return false;
					$("#dialog_confirm").dialog('close');
				},
				"Iesire fara salvare":function(){
					$("#dialog_confirm").dialog('close');
					window.top.$('body').find('#dialog_add').dialog('close');
				}
			}
		});
	}
	else window.top.$('body').find('#dialog_add').dialog('close');
}
</script>
<div class="span-20">
<form id="form" onsubmit="return false;" style="margin: 20px; margin-top: 0;">
<div class="span-20 form-header ui-corner-top">Informatii firma</div>
<div class="span-20 form-content" style="margin-bottom: 20px;">

<div class="span-20 form-row">
	<div class="before span-3">Denumire</div>
	<div class="box-after span-16">
		<input value="<?php echo $_GET['denumire']; ?>" class="after span-11 validator-required validator-corect" style="text-transform: uppercase;" type="text" id="denumire" tips="<div id='toggle-tips' class='span-6 tips-e'><strong>Informatie necesara</strong><br>Se completeaza denumirea firmei inclusiv forma juridica.<br><span style='font-size: .9em;'><strong>Exemplu:</strong> SC Firma SRL</span></div>" onblur="verifica_denumire()" autocomplete="off">
		<span id="verifica-denumire" class="input-helper" style="left: -150px; font-weight: bold;"></span>
	</div>
</div>
<div class="span-20 form-row">
	<div class="before span-3">CIF / CUI</div>
	<div class="box-after span-16 last">
		<input class="after span-11 validator-required validator-corect" style="text-transform: uppercase;" type="text" id="cif" onblur="verifica_cif()" tips="<div class='span-6 tips-e'><strong>Informatie necesara</strong><br>Se completeaza codul <strong>RO</strong> daca firma este platitoare de TVA.</div>" tva="1" fault="0" autocomplete="off">
		<span id="verifica-cif" class="input-helper" style="left: -230px;"></span>
	</div>
</div>
<div class="span-20 form-row">	
	<div class="before span-3">Adresa</div>
	<div class="box-after span-16">
		<input class="after span-11" type="text" id="adresa" style="text-transform: capitalize;" tips="<div class='tips-n'><strong>Informatie optionala</strong><br>Se poate completa sediul social sau adresa unui punct de lucru</div>" autocomplete="off">
	</div>
</div>
<div class="span-20 form-row">
	<div class="before span-3">Reg Com</div>
	<div class="box-after span-16">
		<input class="after span-11" type="text" id="reg_com" style="text-transform: uppercase;" tips="<div class='tips-c' style='font-weight: bold;'>Informatie optionala</div>" autocomplete="off">
	</div>
</div>
</div>

<div class="span-20 form-header ui-corner-top">Informatii financiare</div>
<div class="span-20 form-content" style="margin-bottom: 10px;">

<div class="span-20 form-row">
	<div class="before span-3">Banca</div>
	<div class="box-after span-16">
		<input class="after span-11" type="text" id="banca" style="text-transform: capitalize;" tips="<div class='tips-n'><strong>Informatie optionala</strong><br>Se poate completa denumirea bancii si sucursala sau agentia</div>" autocomplete="off">
	</div>
</div>
<div class="span-20 form-last">	
	<div class="before span-3">IBAN</div>
	<div class="box-after span-16">
		<input class="after span-11 validator-corect" style="text-transform: uppercase;" type="text" id="iban" onblur="verifica_iban()" tips="<div class='tips-c' style='font-weight: bold;'>Informatie optionala</div>" fault="0" autocomplete="off">
		<span id="verifica-iban" class="input-helper"></span>
	</div>
</div>
</div>

<div class="span-17" style="margin-bottom: 10px;">
	<div class="span12" style="text-align: right;"><a href="javascript: confirm()" class="back">Inapoi</a></div>
	<div class="span-3" style="text-align: right;"><button class="fg-button orange ui-corner-all" type="submit" style="padding: .5em 1.4em;"><span class="button-text">Salveaza</span></button></div>
	<div class="span-1 box-loading ui-helper-hidden"></div>
</div>

</form>
</div>
<!-- End container -->
</div>