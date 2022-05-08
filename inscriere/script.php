<script>
$(document).ready(function(){
	$('#meniu').tabs();
	$('#meniu').tabs('option','disabled', [1, 2]);
	$('#iban').mask(
		'aa99 aaaa **** **** **** ****',
		{completed: function(){
			verifica_iban();
		}}
	);
	$('#text-adv').toggle(
		function(){
			$(this).text('Ascunde configurare instiintare de plata');
			$("#box-adv").removeClass('ui-helper-hidden');
		},
		function(){
			$(this).text('Configurare instiintare de plata');
			$("#box-adv").addClass('ui-helper-hidden');
		}
	);
	$('#data_ini').datepicker({
		changeMonth: true,
		changeYear: true,
		showAnim: 'fadeIn',
		duration: 200,
		showOn: 'focus',
		dayNamesMin: ['Du','Lu','Ma','Mi','Jo','Vi','Sa'],
		prevText: 'Luna precedenta',
		nextText: 'Luna urmatoare',
		monthNamesShort: ['Ian','Feb','Mar','Apr','Mai','Iun','Iul','Aug','Sep','Oct','Nov','Dec'],
		dateFormat: 'dd-mm-yy',
		yearRange: '-1y:+1y',
		minDate: '0',
		maxDate: '+1y',
		firstDay: 1
	});
	$('#data_scadenta').datepicker({
		changeMonth: true,
		changeYear: true,
		showAnim: 'fadeIn',
		duration: 200,
		showOn: 'focus',
		dayNamesMin: ['Du','Lu','Ma','Mi','Jo','Vi','Sa'],
		prevText: 'Luna precedenta',
		nextText: 'Luna urmatoare',
		monthNamesShort: ['Ian','Feb','Mar','Apr','Mai','Iun','Iul','Aug','Sep','Oct','Nov','Dec'],
		dateFormat: 'dd-mm-yy',
		yearRange: '-1y:+1y',
		minDate: '+5d',
		maxDate: '+1y',
		firstDay: 1
	});
	$('input[tip]').qtip({
		content: '<div class="tips-c" style="font-weight: bold;">Informatie optionala</div>',
		position:{
			corner:{
				target: 'rightMiddle',
				tooltip: 'leftMiddle'
			}
		},
		style:{
			width: 180,
			name: 'light',
			tip: 'leftMiddle',
			border: {
				width: 1,
				radius: 4
			}
		},
		hide:{
			when: 'blur',
			delay: 100
		},
		show:{
			when: 'focus'
		}
	});
	var rules = jQuery.validationAide.getDefaultValidationRules();
	rules.add('validator-corect', '', function(v, obj){
		if ($(obj).attr('fault') != 0) return false;
		return true;
	});
	rules.add('validator-parola', '', function(v, obj){
		if ($(obj).val().length < 5) return false;
		return true;
	});
	$('#form1').validationAideEnable(
		rules,
		{ fieldErrorCssClass: 'failed', showSummary: false },
		null,
		check1
	);
	$('#form2').validationAideEnable(
		rules,
		{ fieldErrorCssClass: 'failed', showSummary: false },
		null,
		check2
	);
	$('#form3').validationAideEnable(
		rules,
		{ fieldErrorCssClass: 'failed', showSummary: false },
		null,
		check3
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

function pas1(s){
	if (s) $('#meniu').tabs('enable',0);
	$(document).find('.after').blur();
	$('.pas1').trigger('click');
	$.each($('.box-main form:visible').find('input'),function(i,obj){
		if ($(obj).val() == ''){
			$(obj).focus();
			return false;
		}
	});
}
function pas2(s){
	if (s) $('#meniu').tabs('enable',1);
	$(document).find('.after').blur();
	$('.pas2').trigger('click');
	$.each($('.box-main form:visible').find('input'),function(i,obj){
		if ($(obj).val() == ''){
			$(obj).focus();
			return false;
		}
	});
}
function pas3(s){
	if (s) $('#meniu').tabs('enable',2);
	$(document).find('.after').blur();
	$('.pas3').trigger('click');
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
				$("#cif").attr('fault', '0').removeClass('img-not').removeClass('failed').addClass('img-yes');
			}
			else{
				$('#verifica-cif').html('CIF/CUI incorect. '+tva);
				$("#cif").attr('fault', '1').removeClass('img-yes').addClass('img-not');
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
				$('#iban').attr('fault', '0').removeClass('img-not').addClass('img-yes');
			}
			else{
				$('#verifica-iban').html('IBAN incorect');
				$('#iban').attr('fault', '1').removeClass('img-yes').addClass('img-not');
			}
		}
		});
	}
	else{
		$('#verifica-iban').html('');
		$('#iban').attr('fault', '0').removeClass('img-not').removeClass('img-yes');
	}
}

function check1(s){
	if (!s){
		notify_bar(10,'Eroare la completarea datelor!');
		return false;
	}
	else{
		$(document).find('.after').css('border','solid 1px #aaa');
		pas2(true);
		return false;
	}	
}
function check2(s){
	if (!s){
		notify_bar(10,'Eroare la completarea datelor!');
		return false;
	}
	else{
		$(document).find('.after').css('border','solid 1px #aaa');
		pas3(true);
		return false;
	}	
}
function check3(s){
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
	//user
		var user = '{';
		$.each($('#form1').find('input'),function(i,obj){
			user += '"'+$(this).attr('id')+'":"'+$(this).val()+'",';
		});
		user = user.substring(0,user.length-1);
		user += '}';
	//platitor
		var platitor = '{';
		$.each($('#form2').find('input'),function(i,obj){
			platitor += '"'+$(this).attr('id')+'":"'+$(this).val()+'",';
		});
		platitor += '"tva":"'+$("#cif").attr('tva')+'"';
		platitor += '}';
	//plata
		var plata = '{';
		plata += '"perioada":"'+$.trim($('#perioada :selected').text().substring(0,7))+'",'+
			'"pret_fin":"'+$('#text-money > span:first').text()+'",'+
			'"data_ini":"'+$('#data_ini').val()+'",'+
			'"data_scadenta":"'+$('#data_scadenta').val()+'"}';
		query += '&subdomeniu='+$('#subdomeniu').val()+'&user='+user+'&platitor='+platitor+'&plata='+plata;
		//alert (query);
		$.ajax({type:'GET',url:'/includes/functii123.php?op=save_user'+query,success:function(raspuns){
				if (raspuns) document.location.href = raspuns;
			},beforeSend:function(){
				$('.img-loader').removeClass('ui-helper-hidden');
			},complete:function(){
				$('.img-loader').addClass('ui-helper-hidden');
			}
		});
	}
}
</script>