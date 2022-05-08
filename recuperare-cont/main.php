<script>
$(document).ready(function(){
	$('#email').focus();
	$('#form').validationAideEnable(
		null,
		{ fieldErrorCssClass: 'failed', showSummary: false },
		null,
		chk
	);
});
function chk(s){
	if (!s){
		notify_bar(10,'Eroare la completarea datelor!');
		return false;
	}
	else{
		$(document).find('.after').css('border','solid 1px #aaa');
		email_cont();
		return false;
	}	
}
function confirm(){
	var valoare = '';
	$.each($('#form').find('input:not("#ascuns")'),function(i,obj){
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
function email_cont(){
	if (!$('#ascuns').val()){
		var query = '';
		$.each($('#form').find('input:not("#ascuns")'),function(i,obj){
			query += '&'+$(this).attr('id')+'='+$(this).val();
		});
		$.ajax({type:'GET',url:'/includes/functii123.php?op=email_cont'+query,success:function(raspuns){
				if (raspuns == 1){
					notify_bars(20,'Un email cu informatiile asociate contului tau a fost trimis la adresa <span class="activ">'+$('#email').val()+'</span>');
					$('#email').val(''); $('#user').val('');
				}
				if (raspuns == 0) notify_bar(20,'Eroare! Emailul nu este inregistrat pentru niciun cont existent.');
				if (raspuns == -1) notify_bar(20,'Eroare! Emailul nu a putut fi trimis.');
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
	<div class="span-20 form-header ui-corner-top">Informatii contul tau</div>
	<div class="span-20 form-content">
	
	<div class="span-16 box-margin-m">
		<div class="before span-2">Email</div>
		<div class="span-11">
			<input class="after span-11 validator-required validator-email" type="text" id="email" tips="<div id='toggle-tips' class='span-7 tips-e'><strong>Informatie necesara</strong><br>Dupa introducerea adresei de email, iti vom trimite informatiile asociate contului tau</div>" autocomplete="off">
		</div>
	</div>
	<div class="span-16 box-margin-m">
		<div class="before span-2">Utilizator</div>
		<div class="span-11">
			<input class="after span-11 validator-required" type="text" id="user" tips="<div id='toggle-tips' class='span-7 tips-e'><strong>Informatie necesara</strong><br>Completeaza utilizatorul asociat contului tau</div>" autocomplete="off">
		</div>
	</div>
	<div class="span-16 box-margin-m">
		<div class="span10 back-box"><a href="javascript: confirm()" class="back" style="font-size: 1.2em;">Inapoi</a></div>
		<div class="span-3"><button class="fg-button orange ui-corner-all" type="submit"><span class="button-text">Trimite</span></button></div>
		<div class="span-1 box-loading ui-helper-hidden" style="margin-top: 2px;"></div>
	</div>
	
	</div>
</form>
</div>

<div class="span-23 box-main box-helper" style="margin: -10px 0 30px 0;">
	<div class="span-11 last">
		<div class="span-10 text-helper">Ai uitat utilizatorul asociat contului tau?</div>
		<div class="span-10 text-helper-small" style="margin-bottom: 10px;">
			<p>Poti oricand sa ne contactezi <a class="link" href="/contact/">accesand pagina de contact</a> sau prin email la adresa contact@facturi123.ro.</p>
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

<div class="ascuns"><input type="text" id="ascuns"></div>
</div>
</div>