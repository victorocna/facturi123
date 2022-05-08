<script>
$(document).ready(function(){
	var reguli = jQuery.validationAide.getDefaultValidationRules();
	reguli.add('validator-corect', '', function(v, obj){
		if ($(obj).val().length < 5) return false;
		return true;
	});
	$('#form').validationAideEnable(
		reguli,
		{ fieldErrorCssClass: 'failed', showSummary: false },
		null,
		chk
	);
	$('#subdomeniu').keyfilter(/[\d\w\-]/).focus();
});
function confirm(){
	var valoare = '';
	$.each($('.box-main form:visible').find('input'),function(i,obj){
		if ($(obj).val()) valoare = $(obj).val();
	});
	if (valoare){
		var title = "<div class='span-11' style='font-size: .9em; text-align: center;'>Alege alt cont</div>";
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
function verifica_subdomeniu(){
	if ($.trim($("#subdomeniu").val())){
		$.ajax({type:'GET',url:'/includes/functii.php?op=verifica_subdomeniu&subdomeniu='+$("#subdomeniu").val(),success:function(raspuns){
			if (raspuns == 0){
				$('#subdomeniu').attr('fault', '1').removeClass('img-yes').addClass('img-not');
				$('input').blur();
				$("#subdomeniu").focus();
				notify_bar(10,'Contul <span class="activ">'+$("#subdomeniu").val()+'</span> exista deja!');
			}
			else $('#subdomeniu').attr('fault', '0').removeClass('img-not failed').addClass('img-yes');
		}
		});
	}
}
function verifica_email(){
	if ($.trim($("#email").val())){
		$.ajax({type:'GET',dataType:'json',url:'/includes/functii.php?op=verifica_email&email='+$("#email").val(),success:function(raspuns){
			if (raspuns.fault == 1){
				$('#email').attr('fault', '1').removeClass('img-yes').addClass('img-not');
				$('input').blur();
				$("#email").focus();
				notify_bar(10,'Emailul <span class="activ">'+$("#email").val()+'</span> exista deja!');
			}
			else $('#email').attr('fault', '0').removeClass('img-not failed').addClass('img-yes');
		}
		});
	}
}
function chk(s){
	if (!s){
		notify_bar(10,'Eroare la completarea datelor!');
		return false;
	}
	else{
		$(document).find('.after').css('border','solid 1px #aaa');
		save_user();
		return false;
	}	
}
function save_user(){
	if (!$('#ascuns').val()){
<?php
$query = "&id_tip=".$_GET['id_tip']."&stare=".$_GET['stare'];
echo 'var query = "'.$query.'";';
?>
		$.each($('#form').find('input:not("#ascuns")'),function(i,obj){
			query += '&'+$(this).attr('id')+'='+$(this).val();
		});
		$.ajax({type:'GET',url:'/includes/functii123.php?op=save_user'+query,success:function(raspuns){
				if (raspuns) document.location.href = raspuns;
			}
		});
	}
}
</script>
<div class="container">
<div class="span-24 last box-main" style="margin-top: -10px;">
<form id="form" onsubmit="return false;">
	<div class="span-20 form-header ui-corner-top">Informatii cont nou</div>
	<div class="span-20 form-content">
	
		<div class="span-23 box-margin-m">
			<div class="before span3">Contul tau</div>
			<div class="span-11">
				<input class="after span123 validator-required" type="text" id="subdomeniu" style="text-transform: lowercase;" tips="<div id='toggle-tips' class='span-7 tips-e'><strong>Informatie necesara</strong><br>Contul tau reprezinta adresa ta de conectare la programul de facturi.<br><span style='font-size: .9em;'><span class='activ'>Exemplu: www.facturi123.ro/<strong>contul-tau</strong></span></span></div>" autocomplete="off" onblur="verifica_subdomeniu()">
			</div>
		</div>
		<div class="span-23 box-margin-m">
			<div class="before span3">Email</div>
			<div class="span-11">
				<input class="after span123 validator-required validator-email" type="text" id="email" tips="<div id='toggle-tips' class='span-7 tips-e'><strong>Informatie necesara</strong><br>Adresa de email permite trimiterea online a facturilor emise catre clienti.</div>" autocomplete="off" onblur="verifica_email()">
			</div>
		</div>
		<div class="span-23 box-margin-m">
			<div class="before span3">Utilizator</div>
			<div class="span-11">
				<input class="after span123 validator-required" type="text" id="user" tips="<div id='toggle-tips' class='span-7 tips-e'><strong>Informatie necesara</strong><br>Dupa accesarea contului tau trebuie folosit utilizatorul si parola pentru autentificare.</div>" autocomplete="off">
			</div>
		</div>
		<div class="span-23 box-margin-m">
			<div class="before span3">Parola</div>
			<div class="span-11">
				<input class="after span123 validator-required validator-corect" type="password" id="parola" tips="<div id='toggle-tips' class='span-7 tips-e'><strong>Informatie necesara</strong><br><span class='activ' style='font-size: .9em;'>Parola trebuie sa contina minim <strong>5</strong> caractere.</span></div>" autocomplete="off">
			</div>
		</div>
		<div class="span-16 box-margin-m">
			<div class="span9 back-box"><a href="javascript: confirm()" class="back" style="font-size: 1.2em;">Alege alt cont</a></div>
			<div class="span-4" style="margin-left:10px;"><button class="fg-button orange ui-corner-all" type="submit" style="padding: .45em 1.5em; font-size: 1.2em;"><span class="button-text">Creare cont</span></button></div>
			<div class="span-1 box-loading ui-helper-hidden" style="margin-top: 2px;"></div>
		</div>
	</div>
</form>

<div class="span-23 box-helper" style="margin-top: 50px;">
	<div class="span-11 last">
		<div class="span-10 text-helper">Cand pot emite prima factura?</div>
		<div class="span-10 text-helper-small" style="margin-bottom: 10px;">
			<p>Poti emite prima factura imediat dupa crearea noului cont.</p>
			<p>Poti folosi contul gratuit nelimitat, fara alte obligatii sau contracte.</p>
		</div>
		<div class="span-10 text-helper">Pot sa aleg un alt tip de cont?</div>
		<div class="span-10 text-helper-small">Poti alege oricand un alt tip de cont dupa autentificarea in program prin accesarea paginii <strong>Tipuri de conturi</strong> si selectarea noului cont. Poti sa maresti sau sa micsorezi numarul maxim de facturi lunare si de furnizori in functie de necesitatile tale.</div>
	</div>
	<div class="span-11">
		<div class="span-10 text-helper">Ce trebuie sa instalez pentru a folosi Facturi123?</div>
		<div class="span-10 text-helper-small" style="margin-bottom: 10px;">Singurul program de care ai nevoie este Adobe Reader. Daca nu il ai deja instalat, il poti descarca <a class="link" href=" http://get.adobe.com/reader/" target="tab">de aici</a><br>Nu trebuie sa instalezi sau sa descarci altceva!</div>
		<div class="span-10 text-helper">Ce browsere web pot folosi?</div>
		<div class="span-10 text-helper-small">Poti folosi <strong>orice browser web</strong> pentru a-ti accesa contul si a putea emite facturi. In plus, fiind un program online, poti emite facturi de oriunde ai acces la internet.</div>
	</div>
</div>
</div>
<div class="ascuns"><input type="text" id="ascuns"></div>
</div>