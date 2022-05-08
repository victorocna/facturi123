<script>
$(document).ready(function(){
	$('.afters-email').focus(
		function(){ $(this).css('border', 'solid 1px #f3c809'); }
	).blur(
		function(){ $(this).css('border', 'solid 1px #aaa'); }
	).textareaDefault();
	$('#email').focus();
	$('#form').validationAideEnable(
		null,
		{ fieldErrorCssClass: 'failed', showSummary: false },
		null,
		chk
	);
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
function chk(s){
	if (!s){
		notify_bar(10,'Eroare la completarea datelor!');
		return false;
	}
	else{
		$(document).find('.after').css('border','solid 1px #aaa');
		email_contact();
		return false;
	}	
}
function confirm(){
	var valoare = '';
	$.each($('#form').find('input:not("#ascuns"),textarea'),function(i,obj){
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
					document.location.href = '../';
				}
			}
		});
	}
	else document.location.href = '../';
}
function email_contact(){
	if (!$('#ascuns').val()){
		var query = '';
		$.each($('#form').find('input:not("#ascuns")'),function(i,obj){
			query += '&'+$(this).attr('id')+'='+$(this).val();
		});
		query += '&mesaj='+$('#mesaj').val().replace(new RegExp( "\\n", "g" ),'<br>');
		$.ajax({type:'GET',url:'/includes/functii123.php?op=email_contact'+query,success:function(raspuns){
				if (raspuns == 1){
					notify_bars(20,'Emailul a fost trimis. Multumim!');
					$('#email').val(''); $('#subiect').val(''); $('#mesaj').val('');
					$('#mesaj').trigger('keyup');
				}
				else notify_bar(20,'Eroare! Emailul nu a putut fi trimis.');
			},beforeSend:function(){
				$('.box-loading-s').removeClass('ui-helper-hidden');
			},complete:function(){
				$('.box-loading-s').addClass('ui-helper-hidden');
			}
		});
	}
}
</script>

<div class="container">
<div class="span-24" style="min-height: 400px;">

<div class="span-14 last box-main">
<form id="form" onsubmit="return false;">
	<div class="span-21 form-header ui-corner-top">Contacteaza-ne!</div>
	<div class="span-21 form-content">
	
	<div class="span-16 box-margin-m">
		<div class="before span-2">Email</div>
		<div class="span-13">
			<input class="after span-13 validator-required validator-email" type="text" id="email" autocomplete="off">
		</div>
	</div>
	<div class="span-16 box-margin-m">
		<div class="before span-2">Subiect</div>
		<div class="span-13">
			<input class="after span-13 validator-required" type="text" id="subiect" autocomplete="off">
		</div>
	</div>
	<div class="span-16 box-margin-m">
		<div class="before span-2">Mesaj</div>
		<div class="span-13">
			<textarea class="afters-email span-13 validator-required" type="text" id="mesaj" maxlength="400" onkeyup="countable($(this).val().length,$(this))" autocomplete="off"></textarea>
		</div>
	</div>
	<div class="span-13 box-grey">
		<div class="span-4 countable-box grey">
			<span class="countable">400</span>
			<span class="countable-text">caractere ramase</span>
		</div>
	</div>
	<div class="span-16 box-margin-m">
		<div class="span12 back-box" style="margin-right: 10px;">
			<a href="javascript: confirm()" class="back" style="font-size: 1.2em;">Inapoi</a>
		</div>
		<div class="span-3"><button class="fg-button orange ui-corner-all" type="submit"><span class="button-text">Trimite</span></button></div>
		<div class="span-1 box-loading ui-helper-hidden" style="margin-top: 2px;"></div>
	</div>
	
	</div>
</form>
</div>

<div class="span-23 box-main box-helper" style="margin-top: -20px;">
	<div class="span-11">
		<div class="span-10 text-helper">Incearca gratuit Facturi123!</div>
		<div class="span-10 text-helper-small" style="margin-bottom: 10px; font-size: 1.2em;">
			<p>Nu este nevoie sa instalezi sau sa actualizezi programul.</p>
			<p>Poti folosi contul gratuit nelimitat, fara alte obligatii.</p>
			<p>Emiti facturi in mai putin de 1 minut!</p>
		</div>
	</div>
	<div class="span-11">
		<div class="span-10 text-helper">SC Creative Minds Software SRL</div>
		<div class="span-10 text-helper-small" style="margin-bottom: 10px; font-size: 1.2em;">
			<div class="span2">CIF/CUI:</div><div class="span-8">24032840</div>
			<div class="span2">Adresa:</div><div class="span-8">Str Recoltei Nr 20, Chitila, Jud Ilfov</div>
			<div class="span2">Email:</div><div class="span-8">contact@facturi123.ro</div>
		</div>
	</div>
</div>

<div class="ascuns"><input type="text" id="ascuns"></div>
</div>
</div>