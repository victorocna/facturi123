<!--[if IE]>
<script src="../includes/js/corner.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
	$('.ui-corner-right').corner('5px right Hover');
});
</script>
<![endif]-->
<script>
$(document).ready(function(){
	$(".button-default").click(function(){
		if ($(".box-signup").hasClass('ui-helper-hidden')){
			$(document).find('.col-focus').removeClass('col-focus ui-corner-all').addClass('col');
			$(document).find('.cell-hover-first').removeClass('cell-hover-first ui-corner-top').addClass('cell-first');
			$(document).find('.cell-buton-hover').removeClass('cell-buton-hover ui-corner-bottom');
			$("#col"+$(this).attr('id_tip')).addClass('col-focus ui-corner-all').removeClass('col');
			$("#first"+$(this).attr('id_tip')).addClass('cell-hover-first ui-corner-top').removeClass('cell-first');
			$("#bottom"+$(this).attr('id_tip')).addClass('cell-buton-hover ui-corner-bottom');
			$("#subdomeniu").attr('id_tip',$(this).attr('id_tip'));
			$(".col").fadeOut(500,signup);
		}
	});
	function signup(){
		$(".ui-helper-hidden").removeClass('ui-helper-hidden').show('slide',500,function(){
			$('.box-signup').trigger('mouseover');
			$("#subdomeniu").select().focus();
		});
		$(".button .ui-state-default, .button .ui-state-hover").attr('disabled',true);
	}
	$("#form").validationAideEnable(
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
		$(document).find('.after').css('border','solid 1px #ccc');
		save_user();
		return false;
	}	
}
function back(){
	$(".box-signup").hide('slide',500,showcol);
	function showcol(){
		$(".box-signup").addClass('ui-helper-hidden');
		$(".col").fadeIn(500);
	}
}
function verifica_subdomeniu(){
	if ($.trim($("#subdomeniu").val())){
		$.ajax({type:'GET',url:'../includes/functii.php?op=verifica_subdomeniu&subdomeniu='+$("#subdomeniu").val(),success:function(raspuns){
			if (raspuns == 1){
				$('#subdomeniu').attr('fault', '1').removeClass('img-yes').addClass('img-no');
				$('input').blur();
				$("#subdomeniu").focus();
				notify_bar(10,'Contul <span class="activ">http://'+$("#subdomeniu").val()+'.facturi.ro</span> exista deja!');
			}
			else $('#subdomeniu').attr('fault', '0').removeClass('img-no failed').addClass('img-yes');
		}
		});
	}
}
function verifica_email(){
	if ($.trim($("#email").val())){
		$.ajax({type:'GET',dataType:'json',url:'../includes/functii.php?op=verifica_email&email='+$("#email").val(),success:function(raspuns){
			if (raspuns.fault == 1){
				$('#email').attr('fault', '1').removeClass('img-yes').addClass('img-no');
				$('input').blur();
				$("#email").focus();
				notify_bar(10,'Emailul <span class="activ">'+$("#email").val()+'</span> exista deja!');
			}
			else $('#email').attr('fault', '0').removeClass('img-no failed').addClass('img-yes');
		}
		});
	}
}
function save_user(){
	if (!$("#ascuns").val()){
		var query = '';
		$.each($('input').not("#ascuns"),function(i,obj){
			query += '&'+$(this).attr('id')+'='+$(this).val();
		});
		query += '&id_tip='+$("#subdomeniu").attr('id_tip');
		$.ajax({type:'GET',url:'../includes/functii.php?op=save_user'+query,success:function(raspuns){
				if (raspuns){
	$("#dialog_confirm").dialog('destroy'); 
	$("#dialog_confirm").remove(); 
	$("body").append('<div id="dialog_confirm" title="<span class=\'font-size: .9em;\'>Cont nou</span>" style="text-align:left"><h4>Contul a fost creat!</h4><br><p>Text text</p>');
	$("#dialog_confirm").show(); 
	$("#dialog_confirm").dialog({ 
		height: 'auto',
		width: 400,
		modal:true, 
		resizable: false, 
		overlay:{ 
				"background-color": "#333", 
				"opacity": "0.75", 
				"-moz-opacity": "0.75" 
		},
		buttons:{
			"Continua":function(){
				document.location.href = '../?user='+$('#user').val();
			}
		}
	});
				}
			},beforeSend:function(){
				$('.box-loading').removeClass('ascuns');
			},complete:function(){
				$('.box-loading').addClass('ascuns');
			}
		});
	}	
}
</script>
<div class="container" style="margin-top: 50px;">
<div class="span-24" style="padding: 10px 20px;">
<div class="span-6 last" id="col0">
	<div class="ui-state-default cell-left-null" style="margin-top: 13px;"></div>
	<div class="ui-state-default cell-left">Numar de facturi pe luna</div>
	<div class="ui-state-default cell-left">Numar de furnizori</div>
	<div class="ui-state-default cell-left">Numar de clienti</div>
	<div class="ui-state-default cell-left">Modele de facturi multiple</div>
	<div class="ui-state-default cell-left">Facturi in format PDF</div>
	<div class="ui-state-default cell-left">Facturi salvate ca draft</div>
	<div class="ui-state-default cell-left">Facturi storno</div>
	<div class="ui-state-default cell-left">Trimitere facturi prin email</div>
	<div class="ui-state-default cell-left">Modul incasare facturi</div>
	<div class="ui-state-default cell-left">Modul istoric facturi</div>
	<div class="ui-state-default cell-left-null" style="padding: 28px 20px 18px 20px;"></div>
</div>
<div class="span-4 last col-focus ui-corner-all" id="col1">
<?php
$sql = $db->query('select * from tip_cont where id_tip="1"');
$row = mysql_fetch_array($sql);
	echo '
	<div class="cell cell-hover-first cell-odd ui-corner-top" id="first1"><div class="cell-free">Free</div></div>
	<div class="cell cell-even">'.$row['nr_facturi'].'</div>
	<div class="cell cell-odd">'.$row['nr_furnizori'].'</div>
	<div class="cell cell-even">'.$row['nr_clienti'].'</div>
	';
?>
	<div class="cell cell-odd"><div class="cell-empty"></div></div>
	<div class="cell cell-odd"><div class="img-ok"></div></div>
	<div class="cell cell-odd"><div class="img-ok"></div></div>
	<div class="cell cell-odd"><div class="img-ok"></div></div>
	<div class="cell cell-odd"><div class="img-ok"></div></div>
	<div class="cell cell-odd"><div class="img-ok"></div></div>
	<div class="cell cell-odd"><div class="img-ok"></div></div>
	<div class="cell cell-even cell-buton-hover ui-corner-bottom" id="bottom1">
		<button class="fg-button-load ui-state-default ui-corner-all button-default" id="signup1" id_tip="1"><span class="button-text" style="font-size: .9em;">Creare cont</span></button>
	</div>
</div>
<div class="span-4 last col" id="col2">
<?php
$sql = $db->query('select * from tip_cont where id_tip="2"');
$row = mysql_fetch_array($sql);
	echo '
	<div class="cell cell-first cell-odd" id="first2">Basic<div style="font-size: .8em">'.$row['pret'].' Lei pe luna</div></div>
	<div class="cell cell-even">'.$row['nr_facturi'].'</div>
	<div class="cell cell-odd">'.$row['nr_furnizori'].'</div>
	<div class="cell cell-even">'.$row['nr_clienti'].'</div>
	';
?>
	<div class="cell cell-odd"><div class="img-ok"></div></div>
	<div class="cell cell-even"><div class="img-ok"></div></div>
	<div class="cell cell-odd"><div class="img-ok"></div></div>
	<div class="cell cell-even"><div class="img-ok"></div></div>
	<div class="cell cell-odd"><div class="img-ok"></div></div>
	<div class="cell cell-even"><div class="img-ok"></div></div>
	<div class="cell cell-odd"><div class="img-ok"></div></div>
	<div class="cell cell-even" id="bottom2">
		<button class="fg-button-load ui-state-default ui-corner-all button-default" id="signup2" id_tip="2"><span class="button-text" style="font-size: .9em;">Creare cont</span></button>
	</div>
</div>
<div class="span-4 last col" id="col3">
<?php
$sql = $db->query('select * from tip_cont where id_tip="3"');
$row = mysql_fetch_array($sql);
	echo '
	<div class="cell cell-first cell-odd" id="first3">Best<div style="font-size: .8em">'.$row['pret'].' Lei pe luna</div></div>
	<div class="cell cell-even">'.$row['nr_facturi'].'</div>
	<div class="cell cell-odd">'.$row['nr_furnizori'].'</div>
	<div class="cell cell-even">'.$row['nr_clienti'].'</div>
	';
?>
	<div class="cell cell-odd"><div class="img-ok"></div></div>
	<div class="cell cell-even"><div class="img-ok"></div></div>
	<div class="cell cell-odd"><div class="img-ok"></div></div>
	<div class="cell cell-even"><div class="img-ok"></div></div>
	<div class="cell cell-odd"><div class="img-ok"></div></div>
	<div class="cell cell-even"><div class="img-ok"></div></div>
	<div class="cell cell-odd"><div class="img-ok"></div></div>
	<div class="cell cell-even" id="bottom3">
		<button class="fg-button-load ui-state-default ui-corner-all button-default" id="signup3" id_tip="3"><span class="button-text" style="font-size: .9em;">Creare cont</span></button>
	</div>
</div>
<div class="span-4 last col" id="col4">
<?php
$sql = $db->query('select * from tip_cont where id_tip="4"');
$row = mysql_fetch_array($sql);
	echo '
	<div class="cell cell-first cell-odd" id="first4">Supreme<div style="font-size: .8em">'.$row['pret'].' Lei pe luna</div></div>
	<div class="cell cell-even">'.$row['nr_facturi'].'</div>
	<div class="cell cell-odd">'.$row['nr_furnizori'].'</div>
	<div class="cell cell-even">'.$row['nr_clienti'].'</div>
	';
?>
	<div class="cell cell-odd"><div class="img-ok"></div></div>
	<div class="cell cell-even"><div class="img-ok"></div></div>
	<div class="cell cell-odd"><div class="img-ok"></div></div>
	<div class="cell cell-even"><div class="img-ok"></div></div>
	<div class="cell cell-odd"><div class="img-ok"></div></div>
	<div class="cell cell-even"><div class="img-ok"></div></div>
	<div class="cell cell-odd"><div class="img-ok"></div></div>
	<div class="cell cell-even" id="bottom4">
		<button class="fg-button-load ui-state-default ui-corner-all button-default" id="signup4" id_tip="4"><span class="button-text" style="font-size: .9em;">Creare cont</span></button>
	</div>
</div>
<div class="span-13 last box-signup ui-state-active ui-corner-right ui-helper-hidden">
<div class="span-15 last" style="margin-bottom: 40px;">
	<div class="span-12 info-signup"><div style="padding-left: 20px;">Cont nou</div></div>
</div>
<form id="form" style="padding: 0 20px;" onsubmit="return false;">
	<div class="span-15 form-row">
		<div class="before span3">Contul tau</div>
		<div class="span-12">
			<input class="after span-9 validator-required" type="text" id="subdomeniu" tips="<div class='tips-c' style='font-weight: bold;'>Informatie necesara</div>" id_tip="" autocomplete="off" onblur="verifica_subdomeniu()">
			<span class="input-helper-m">.facturi.ro</span>
		</div>
	</div>
	<div class="span-14 form-last">
		<div class="before span3">Email</div>
		<div class="span-11">
			<input class="after span-9 validator-required validator-email" type="text" id="email" tips="<div class='tips-c' style='font-weight: bold;'>Informatie necesara</div>" autocomplete="off" onblur="verifica_email()">
		</div>
	</div>
	<div class="span-14 form-row">
		<div class="before span3">Utilizator</div>
		<div class="span-11">
			<input class="after span-9 validator-required" type="text" id="user" tips="<div class='tips-c' style='font-weight: bold;'>Informatie necesara</div>" autocomplete="off">
		</div>
	</div>
	<div class="span-14 form-last">
		<div class="before span3">Parola</div>
		<div class="span-11">
			<input class="after span-9 validator-required" type="password" id="parola" tips="<div class='tips-c' style='font-weight: bold;'>Informatie necesara</div>" autocomplete="off">
		</div>
	</div>
	<div class="span-14">
		<div class="span-8"><a href="javascript: back()" class="back" style="color: #666;">Inapoi</a></div>
		<div class="span-3" style="margin-left: 28px;"><button class="fg-button fg-orange ui-corner-all" type="submit"><span class="button-text">Continua</span></button></div>
		<div class="span-1 box-loading ascuns"></div>
	</div>	
</form>	
</div>
<div class="ascuns"><input type="text" id="ascuns"></div>
</div>
<!-- End container -->