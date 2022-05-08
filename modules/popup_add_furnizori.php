<div class="container" style="width: 810px; padding: 0px; margin-left: 5px;">
<style>body { background: #fff; }</style>
<script>
$(document).ready(function(){
	$('#denumire').focus();
	$('#numar, #numar_ch').keyfilter(/[\d\,\-\.]/);
	$('#serie').mask('aaa? a');
	$('#serie_ch').mask('aaa? aa');
	$('#iban').mask(
		'aa99 aaaa **** **** **** ****',
		{completed: function(){
			verifica_iban();
		}}
	);
	$('#adv').toggle(
		function(){
			$(this).text('Ascunde configurare chitantier');
			$('#box-adv').removeClass('ui-helper-hidden');
			$('.init_chitanta').addClass('ui-helper-hidden');
			$('body').animate({ scrollTop: 200 }, 1000);
		},
		function(){
			$(this).text('Configurare chitantier');
			$('#box-adv').addClass('ui-helper-hidden');
		}
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
			'op' : 'save_sigla_add',
			'subdomeniu' : '<?php echo $subdomeniu; ?>'
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
function delete_sigla_add(){
	if ($('#sigla').val()){
		var query = '&subdomeniu=<?php echo $subdomeniu; ?>&sigla='+$('#sigla').val();
		$.ajax({type:'POST',url:'/includes/upload/functii.php',data:'op=delete_sigla_add'+query,success:function(raspuns){
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
		var query = '&denumire='+$.trim($("#denumire").val())+'&tip_firma=0';
		$.ajax({type:'GET',dataType:'json',url:'/includes/functii.php?op=verifica_denumire'+query,success:function(raspuns){
				if (raspuns.fault == '0'){
					$('#verifica-denumire').html('');
					$('#denumire').attr('fault', '0').removeClass('img-no failed').addClass('img-yes');
					$('#toggle-tips').html('<strong>Informatie necesara</strong><br>Se completeaza denumirea firmei inclusiv forma juridica.<br><span style="font-size: .9em;"><strong>Exemplu:</strong> SC Firma SRL</span>');
					init_factura();
				}
				if (raspuns.fault == '1'){
					$('#verifica-denumire').html('Furnizorul exista deja');
					$('#denumire').attr('fault', '1').removeClass('img-yes').addClass('img-no');
					$('#toggle-tips').html('<div style="margin-bottom: 5px;"><strong>Atentie!</strong><br>Furnizorul <span class="uppercase">'+$('#denumire').val()+'</span> a fost adaugat pe data de <strong>'+raspuns.data_add+'</strong></div><span>Vreti sa modificati datele furnizorului? <a href="#" onmouseover="modify('+raspuns.id+')" id="modify" style="color: #00f !important;">Modifica</a></span>');
					$('input').blur();
					$('#denumire').focus();
					if ($('#serie').val()) $('#serie').val('');
					if ($('#numar').val()) $('#numar').val('');
					if ($('#serie_ch').val()) $('#serie_ch').val('');
					if ($('#numar_ch').val()) $('#numar_ch').val('');
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
function verifica_serie(){
	if ($.trim($("#serie").val())){
		var query = '&id_user=<?php echo $_GET['id_user']; ?>&serie='+$.trim($("#serie").val());
		$.ajax({type:'GET',url:'/includes/functii.php?op=verifica_serie'+query,success:function(raspuns){
			if (raspuns == 1) $('#serie').attr('fault', '0').removeClass('img-no failed').addClass('img-yes');
			else $('#serie').attr('fault', '1').removeClass('img-yes failed').addClass('img-no');
		}
		});
	}
	else $('#serie').attr('fault', '0').removeClass('img-no img-yes failed');
}
function verifica_chitanta(){
	if ($.trim($("#serie_ch").val())){
		var query = '&id_user=<?php echo $_GET['id_user']; ?>&serie_ch='+$.trim($("#serie_ch").val());
		$.ajax({type:'GET',url:'/includes/functii.php?op=verifica_chitanta'+query,success:function(raspuns){
			if (raspuns == 1) $('#serie_ch').attr('fault', '0').removeClass('img-no failed').addClass('img-yes');
			else $('#serie_ch').attr('fault', '1').removeClass('img-yes failed').addClass('img-no');
		}
		});
	}
	else $('#serie').attr('fault', '0').removeClass('img-no img-yes failed');
}
function modify(id){
	$('#modify').bind('mousedown',function(){
		<?php if (isset($_GET['furnizor'])) { ?>
		window.top.modify(id);
		<?php } ?>
		<?php if (isset($_GET['factura'])) { ?>
		window.top.modify_furnizor(id);
		<?php } ?>
		window.top.$('body').find('#dialog_add').dialog('destroy');
	});
}
function init_factura(){
	if ($.trim($("#denumire").val()) != ""){
		var query = '&id_user=<?php echo $_GET['id_user']; ?>&denumire='+$.trim($("#denumire").val());
		$.ajax({type:'GET',dataType:'json',url:'/includes/functii.php?op=init_factura'+query,success:function(raspuns){
			$('#serie').val(raspuns.serie);
			$('#numar').val('010001');
			$('#serie_ch').val(raspuns.serie_ch);
			$('#numar_ch').val('010001');
			$('.init_chitanta').removeClass('ui-helper-hidden');
		}
		});
	}
}
function chk(s){
	if (!s){
		window.top.notify_bar(10,'Eroare la completarea datelor!');
		if ((!$('#serie_ch').val() || !$('#numar_ch').val()) && $("#box-adv").hasClass('ui-helper-hidden')) $('#adv').trigger('click');
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
<?php
$query = "&id_user=".$_GET['id_user']."&subdomeniu=".$subdomeniu;
echo '
	var query = "'.$query.'";
';
?>
	for (i=0;i<$("input").length;i++){
		query += "&"+$("input").eq(i).attr("id")+"="+$.trim($("input").eq(i).val());
	}
	query += "&tva="+$("#cif").attr('tva');
	query += '&furnizor=1';
	$.ajax({type:'GET',url:'/includes/functii.php?op=save_firma'+query,success:function(raspuns){
		if (raspuns){
			<?php if (isset($_GET['furnizor'])) { ?>
			window.top.$('body').find('#dialog_add').dialog('close');
			window.top.notify_bars(10,'Furnizorul <span class="uppercase activ">'+$('#denumire').val()+'</span> a fost salvat');
			window.top.init_query((window.top.$('body').find('#pagination > span').html()-1),'pagination');
			<?php } ?>
			<?php if (isset($_GET['factura'])) { ?>
			window.top.$('body').find('#dialog_add').dialog('close');
			window.top.notify_bars(10,'Furnizorul <span class="uppercase activ">'+$('#denumire').val()+'</span> a fost salvat');
			window.top.query_furnizor(raspuns);
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
				"Salvare furnizor":function(){
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
<div class="span-20 form-content" style="margin-bottom: 10px;">

<div class="span-20 form-row">
	<div class="before span-3">Denumire</div>
	<div class="box-after span-16 last">
		<input value="<?php echo $_GET['denumire']; ?>" class="after span-11 validator-required validator-corect" style="text-transform: uppercase;" type="text" id="denumire" tips="<div id='toggle-tips' class='span-6 tips-e'><strong>Informatie necesara</strong><br>Se completeaza denumirea firmei inclusiv forma juridica.<br><span style='font-size: .9em;'><strong>Exemplu:</strong> SC Firma SRL</span></div>" onblur="verifica_denumire()" autocomplete="off">
		<span id="verifica-denumire" class="input-helper" style="left: -160px; font-weight: bold;"></span>
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

<?php
$sql = $db->query('select * from useri where id_user="'.$_GET['id_user'].'"');
$row = mysql_fetch_array($sql);
if ($row['id_tip'] != '1') echo '
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
		<a href="javascript: delete_sigla_add()" class="back" style="color: #666; font-size: .9em;">Renunta la sigla</a>
	</div>
</div>
';
?>
</div>

<div class="span-20 form-header ui-corner-top">Informatii financiare</div>
<div class="span-20 form-content" style="margin-bottom: 10px;">

<div class="span-20 form-row">
	<div class="before span-3">Banca</div>
	<div class="box-after span-16">
		<input class="after span-11" type="text" id="banca" style="text-transform: capitalize;" tips="<div class='tips-n'><strong>Informatie optionala</strong><br>Se poate completa denumirea bancii si sucursala sau agentia</div>" autocomplete="off">
	</div>
</div>
<div class="span-20 form-row">	
	<div class="before span-3">IBAN</div>
	<div class="box-after span-16">
		<input class="after span-11 validator-corect" style="text-transform: uppercase;" type="text" id="iban" onblur="verifica_iban()" tips="<div class='tips-c' style='font-weight: bold;'>Informatie optionala</div>" fault="0" autocomplete="off">
		<span id="verifica-iban" class="input-helper"></span>
	</div>
</div>
</div>

<div class="span-20 form-header ui-corner-top">Informatii factura</div>
<div class="span-20 form-content">

<div class="span-20 form-row">
	<div class="before span-3">Serie factura</div>
	<div class="box-after span-16">
		<input id="serie" class="after span-11 validator-required validator-corect" style="text-transform: uppercase;" onblur="verifica_serie()" tips="<div class='span-6 tips-e'><div style='margin-bottom: 5px;'><strong>Informatie necesara</strong><br>Dupa introducerea denumirii firmei va fi stabilita o serie ce poate fi modificata.</div><span style='font-size: .9em;'><strong>Atentie!</strong> Dupa salvare, seria nu mai poate fi modificata.</span></div>" fault="0" autocomplete="off">
	</div>
</div>
<div class="span-20 form-row">
	<div class="before span-3">Numar initial</div>
	<div class="box-after span-16">
		<input id="numar" class="after span-11 validator-required" style="text-transform: uppercase;" tips="<div class='span-6 tips-e'><div style='margin-bottom: 5px;'><strong>Informatie necesara</strong><br>Numarul facturii se va aloca automat crescator pentru fiecare factura.</div><span style='font-size: .9em;'><strong>Atentie!</strong> Dupa salvare, numarul nu mai poate fi modificat.</span></div>">
	</div>
</div>
</div>

<div class="span-14 last" style="font-size: 1.2em; margin: 10px 0 0 20px;">
	<div id="adv" class="klink span-6">Configurare chitantier</div>
</div>

<div id="box-adv" class="ui-helper-hidden">
	<div class="span-20 form-header ui-corner-top" style="margin-top: 10px;">Informatii chitantier</div>
	<div class="span-20 form-content" style="margin-bottom: 10px;">
	
	<div class="span-20 form-row">
		<div class="before span-3">Serie chitanta</div>
		<div class="box-after span-16">
			<input id="serie_ch" class="after span-11 validator-required validator-corect" style="text-transform: uppercase;" onblur="verifica_chitanta()" tips="<div class='span-6 tips-e'><div style='margin-bottom: 5px;'><strong>Informatie necesara</strong><br>Dupa introducerea denumirii firmei va fi stabilita o serie ce poate fi modificata.</div><span style='font-size: .9em;'><strong>Atentie!</strong> Dupa salvare, seria nu mai poate fi modificata.</span></div>" fault="0" autocomplete="off">
		</div>
	</div>
	<div class="span-20 form-row" style="margin-bottom: 10px;">
		<div class="before span-3">Numar initial</div>
		<div class="box-after span-16">
			<input id="numar_ch" class="after span-11 validator-required" style="text-transform: uppercase;" tips="<div class='span-6 tips-e'><div style='margin-bottom: 5px;'><strong>Informatie necesara</strong><br>Numarul chitantei se va aloca automat crescator pentru fiecare chitanta.</div><span style='font-size: .9em;'><strong>Atentie!</strong> Dupa salvare, numarul nu mai poate fi modificat.</span></div>">
		</div>
	</div>
	</div>
</div>

<div class="span-17 form-last">
	<div class="span12" style="text-align: right;"><a href="javascript: confirm()" class="back">Inapoi</a></div>
	<div class="span-3" style="text-align: right;"><button class="fg-button orange ui-corner-all" type="submit" style="padding: .5em 1.4em;"><span class="button-text">Salveaza</span></button></div>
	<div class="span-1 box-loading ui-helper-hidden"></div>
</div>
<div class="span-8 init_chitanta ui-helper-hidden">
	<div class="img-k"></div>
	<div class="span-7">Chitantierul a fost initializat automat</div>
</div>
</form>
</div>
<!-- End container -->
</div>