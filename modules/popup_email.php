<?php 
include 'top.php';
$xml = '../useri/'.$subdomeniu.'/'.$_GET['id_furnizor'].'/xml/'.$_GET['factura'].'.xml';
$factura = xml2array($xml);
$sql = $db->query('select * from useri where id_user="'.$_GET['idf'].'"');
$row = mysql_fetch_array($sql);
$sc = $db->query('select * from firme where id_firma="'.$factura['facturi']['client_attr']['id'].'" and tip_firma="1"');
$rc = mysql_fetch_array($sc);
?>
<div class="container" style="padding-top: 0; width: 350px; padding: 0px; margin-left: 70px;">
<style>
body { background: #fff; width: 350px; margin-top: 10px; }
</style>
<script>
$(document).ready(function(){
	$('#add-email').toggle(
		function(){
			$(this).text('Ascundeti expeditor');
			$('#container-email').removeClass('ui-helper-hidden');
			$('#email').focus().addClass('validator-required validator-email');
		},
		function(){
			$(this).text('Adaugati expeditor');
			$('#container-email').addClass('ui-helper-hidden');
			$('#email').removeClass('validator-required validator-email');
		}
	);
	$('#add-cc').toggle(
		function(){
			$(this).text('Ascundeti CC');
			$('#container-cc').removeClass('ui-helper-hidden');
			$('#cc').focus().addClass('validator-required validator-email');
		},
		function(){
			$(this).text('Adaugati CC');
			$('#container-cc').addClass('ui-helper-hidden');
			$('#cc').removeClass('validator-required validator-email');
		}
	);
	$('#add-bcc').toggle(
		function(){
			$(this).text('Ascundeti BCC');
			$('#container-bcc').removeClass('ui-helper-hidden');
			$('#bcc').focus().addClass('validator-required validator-email');
		},
		function(){
			$(this).text('Adaugati BCC');
			$('#container-bcc').addClass('ui-helper-hidden');
			$('#bcc').removeClass('validator-required validator-email');
		}
	);
	$("#form").validationAideEnable(
		null,
		{ fieldErrorCssClass: 'failed', summaryFieldMessageFormat: '##FIELD##', showSummary:false },
		null,
		chk
	);
	$('.after').eq(0).focus();
	$('.afters-email').focus(
		function(){ $(this).css('border', 'solid 1px #f3c809'); }
	).blur(
		function(){ $(this).css('border', 'solid 1px #aaa'); }
	).textareaDefault();
});

function countable(i,obj){
	var maxLength = $(obj).attr('maxlength');
	var diff = maxLength - i;
	$('.countable').text(diff);
	// diff = 0 => text eroare
	if (diff == 0) $('.countable-box').addClass('ui-state-error-text');
	else $('.countable-box').removeClass('ui-state-error-text');
	// diff = 1 => singular
	if (diff == 1) $('.countable-text').text('caracter ramas');
	else $('.countable-text').text('caractere ramase');
}

function save_atasament(){
	if ($('#atasament').is(':checked')){
<?php
$query = "&subdomeniu=".$subdomeniu."&id_furnizor=".$_GET['id_furnizor']."&factura=".$_GET['factura'];
$url = "/modules/popup_factura_".$_GET['tip_factura']."_".$_GET['tva'].".php";
echo '
	var query = "'.$query.'";
	var url = "'.$url.'";
';
?>
		$.ajax({
			type:'GET',
			url:url+'?op=save_atasament'+query,
			beforeSend:function(){
				$('.box-loading-s').removeClass('ui-helper-hidden');
			},complete:function(){
				$('.box-loading-s').addClass('ui-helper-hidden');
			}
		});
	}
}
function chk(s){
	if (!s) return
	else{
		$(document).find('.after').css('border', 'solid 1px #ccc');
		save_email();
		return false;
	}	
}
function save_email(){
<?php
$jsn = '{"subdomeniu":"'.$subdomeniu.'","id_furnizor":"'.$_GET['id_furnizor'].'","factura":"'.$_GET['factura'].'"}';
$query = "&id_factura=".$factura['facturi_attr']['id']."&tip_factura=".$_GET['tip_factura'];
echo "
	var query = '".$query."';
	var jsn = '".$jsn."';
";
?>
	$.each($('input:text').parent(),function(i,obj){
		if (!$(obj).hasClass('.ui-helper-hidden')){
			query += '&'+$(obj).find('input').attr('id')+'='+$(obj).find('input').val();
		}
	});
	if ($('#email').parent().hasClass('.ui-helper-hidden')){
		if ($('#email').val()) query += '&email='+$('#email').val();
		else query += '&email=<?php echo $row['email']; ?>';
	}
	
	if ($('#atasament').attr('checked') == false) query += '&atasament=0';
	else query += '&atasament=1';
	
	query += '&mesaj='+$('#textarea').val().replace(new RegExp( "\\n", "g" ),'<br>')+'&jsn='+jsn;
	
	$.ajax({type:'GET',url:'/includes/functii.php?op=save_email'+query,success:function(raspuns){
		if (raspuns == 1){
			var numar = $('#catre').val().split('@'); var text = '';
			if (numar.length == 2) text += 'Emailul a fost trimis la adresa <span class="activ">'+$('#catre').val()+'</span>';
			else text += 'Emailul a fost trimis la adresele <span class="activ">'+$('#catre').val()+'</span>';
			window.top.$('body').find('#dialog_email').dialog('close');
			window.top.notify_bars(10,text);
		}
		else window.top.notify_bar(10,'Eroare! Emailul nu a putut fi trimis.');
	},beforeSend:function(){
		$('.box-loading').removeClass('ui-helper-hidden');
	},complete:function(){
		$('.box-loading').addClass('ui-helper-hidden');
	}
	});
}
function confirm(){
	var valoare = '';
	$.each($('#form').find('input:not("#subiect,#email"),textarea'),function(i,obj){
		if ($(obj).val()) valoare = $(obj).val();
	});
	if (valoare){
		var title = "<div class='span-11' style='font-size: .9em; text-align: center;'>Iesire</div>";
		$("#dialog_confirm").dialog('destroy'); 
		$("#dialog_confirm").remove(); 
		$("body").append('<div id="dialog_confirm" title="'+title+'" style="text-align:left"><h4>Confirmati parasirea acestei pagini?</h4><p style="margin: 15px 0 10px 0;">Daca parasiti aceasta pagina datele introduse vor fi pierdute.</p>');
		$("#dialog_confirm").show(); 
		$("#dialog_confirm").dialog({ 
			height: 'auto',
			width: 470,
			modal:true, 
			resizable: false, 
			overlay:{ 
					"background-color": "#333", 
					"opacity": "0.75", 
					"-moz-opacity": "0.75" 
			},
			buttons:{
				"Inapoi in pagina":function(){
					$('#dialog_confirm').dialog('close');
				},
				"Parasire pagina":function(){
					window.top.$('body').find('#dialog_email').dialog('close');
				}
			}
		});
	}
	else window.top.$('body').find('#dialog_email').dialog('close');
}
</script>
<div class="span-12">
<form id="form" onsubmit="return false;" style="margin: 20px; margin-top: 0;">
<div class="span-12 box ui-active ui-helper-clearfix">
	<div class="span-12" style="margin-bottom: 5px;">
		<div class="span-2 last before" style="text-align: right; padding-right: 10px;">Catre</div>
		<input class="after span-9 validator-required validator-email" type="text" id="catre" autocomplete="off">
	</div>
	<div class="span-9" style="margin-bottom: 10px; margin-left: 90px;">
		<div class="span-3 elink" id="add-email">Adaugati expeditor</div>
		<div class="span-3 last elink" id="add-cc" style="margin: 0 0px 0 10px;">Adaugati CC</div>
		<div class="span-3 last elink" id="add-bcc">Adaugati BCC</div>
	</div>
	<div class="span-12 ui-helper-hidden" id="container-email" style="margin-bottom: 5px;">
		<div class="span-2 last before" style="text-align: right; padding-right: 10px;">Expeditor</div>
		<input class="after span-9" type="text" id="email" autocomplete="off" value="<?php echo $row['email']; ?>">
	</div>
	<div class="span-12 ui-helper-hidden" id="container-cc" style="margin-bottom: 5px;">
		<div class="span-2 last before" style="text-align: right; padding-right: 10px;">CC</div>
		<input class="after span-9" type="text" id="cc" autocomplete="off">
	</div>
	<div class="span-12 ui-helper-hidden" id="container-bcc" style="margin-bottom: 5px;">
		<div class="span-2 last before" style="text-align: right; padding-right: 10px;">BCC</div>
		<input class="after span-9" type="text" id="bcc" autocomplete="off">
	</div>
	<div class="span-12" style="margin-bottom: 5px;">
		<div class="span-2 last before" style="text-align: right; padding-right: 10px;">Subiect</div>
		<input class="after span-9" type="text" id="subiect" autocomplete="off" value="Factura <?php echo strtoupper(str_replace('-',' ',$_GET['factura'])); ?>">
	</div>
	
	<div class="span-9" style="padding: 5px 0 5px 90px;">
		<input type="checkbox" id="atasament" onchange="save_atasament()">
		<div class="span-5 text-adv f-link" style="font-size: 1.1em; height: 24px; font-weight: bold;">Atasati factura in format PDF</div>
		<div class="span-1 box-loading-s ui-helper-hidden" style="margin-top: -5px;"></div>
	</div>
	
	<div class="span-9" style="margin-bottom: 10px; margin-left: 90px; font-size: .9em; color: #666;">Clientul <span class="uppercase-b"><?php echo $rc['denumire']; ?></span> va primi automat un link al facturii de unde o poate tipari sau salva in format PDF.</div>
</div>
	
	<div class="span-13">
		<textarea class="span-13 afters-email" id="textarea" maxlength="400" onkeyup="countable($(this).val().length,$(this))"></textarea>
	</div>
	<div class="span-13 form-row" style="margin-left: 5px;">
		<div class="span-4 countable-box grey">
			<span class="countable">400</span>
			<span class="countable-text">caractere ramase</span>
		</div>
	</div>
	
	<div class="span-15 last">
		<div class="span-10" style="text-align: right;"><a href="javascript:confirm()" class="back">Nu trimite</a></div>
		<div class="span-3" style="text-align: right;"><button class="fg-button orange ui-corner-all" type="submit"><span class="button-text">Trimite</span></button></div>
		<div class="span-1 box-loading ui-helper-hidden"></div>
	</div>
	
</form>
</div>