<?php include 'top.php'; ?>
<div class="container" style="width: 790px; padding: 0px; margin: 5px 0 0 5px;">
<style>body { background: #fff; }</style>
<script>
$(document).ready(function(){
	$('.afters-s, .afters').focus(
		function(){ $(this).css('border', 'solid 1px #f3c809'); }
	).blur(
		function(){ $(this).css('border', 'solid 1px #ccc'); }
	);
	$('textarea[tips]').qtip({
		position:{
			corner:{
				target: 'rightMiddle',
				tooltip: 'leftMiddle'
			}
		},
		style:{
			width:{
				min: 70
			},
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
	$('#denumire').textareaEmitere().focus();
	
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
function verifica_produs(){
	if ($("#denumire").val()){
		var query = '&denumire='+$.trim($("#denumire").val());
		$.ajax({type:'GET',dataType:'json',url:'/includes/functii.php?op=verifica_produs'+query,success:function(raspuns){
				if (raspuns.fault == '0'){
					$('#verifica-produs').html('');
					$('#denumire').attr('fault', '0').removeClass('image-no failed').addClass('image-yes');
					$('#toggle-tips').html('<strong>Informatie necesara</strong><br>Introduceti denumirea produsului sau a serviciului');
				}
				if (raspuns.fault == '1'){
					$('#verifica-produs').html('Produsul exista deja');
					$('#denumire').attr('fault', '1').removeClass('image-yes').addClass('image-no');
					$('#toggle-tips').html('<div style="margin-bottom: 5px;"><strong>Atentie!</strong><br>Produsul '+$('#denumire').val()+' a fost adaugat pe data de <strong>'+raspuns.data_add+'</strong></div><span>Vreti sa modificati produsul? <a href="#" onmouseover="modify('+raspuns.id+')" id="modify" style="color: #00f !important;">Modifica</a></span>');
					$('input').blur();
					$('#denumire').focus();
				}
			}
		});
	}
}
function modify(id){
	$('#modify').bind('mousedown',function(){
		window.top.modify(id);
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
		save_produs();
		return false;
	}	
}
function save_produs(){
	var query = "&id_user="+<?php echo $_GET['id_user']; ?>;
	for (i=0;i<$(".rand").length;i++){
		query += "&"+$(".rand").eq(i).attr("id")+"="+$.trim($(".rand").eq(i).val());
	}
	$.ajax({type:'GET',url:'/includes/functii.php?op=save_produs'+query,success:function(raspuns){
		if (raspuns){
			window.top.$('body').find('#dialog_add').dialog('close');
			window.top.init_query((window.top.$('body').find('#pagination > span').html()-1),'pagination');
			window.top.notify_bars(10,'Produsul <span class="activ">'+$('#denumire').val()+'</span> a fost salvat');
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
				"Salvare produs":function(){
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
<form id="form" onsubmit="return false;" style="margin: 10px 20px;">
<div class="span-20 form-header ui-corner-top">Informatii produs</div>
<div class="span-20 form-content" style="margin-bottom: 15px;">

<div class="span-20 form-row">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td><div class="before span-4" style="padding-top: 0px;">Denumire produs</div></td>
			<td><div class="box-after span-14">
				<textarea class="rand span-10 afters-s validator-required validator-corect" id="denumire" onblur="verifica_produs()" autocomplete="off" tips="<div id='toggle-tips' class='span-6 tips-e'><strong>Informatie necesara</strong><br>Introduceti denumirea produsului sau a serviciului</div>"><?php echo $_GET['denumire']; ?></textarea>
			</div></td>
		</tr>
	</table>
</div>
<div class="span-20" style="margin-bottom: 10px;">
	<div class="before span-4">Unitate de masura</div>
	<div class="box-after span-14">
		<input class="rand after span-10 validator-required" type="text" id="unitate" autocomplete="off" tips="<div class='tips-c' style='font-weight: bold;'>Informatie necesara</div>">
	</div>
</div>
</div>

<div class="span-20" style="margin-bottom: 10px;">
	<div class="span12" style="text-align: right;"><a href="javascript: confirm()" class="back">Inapoi</a></div>
	<div class="span-3" style="text-align: right;"><button class="fg-button orange ui-corner-all" type="submit" style="padding: .5em 1.4em;"><span class="button-text">Salveaza</span></button></div>
	<div class="span-1 box-loading ui-helper-hidden"></div>
</div>	
</form>
</div>
<!-- End container-m div -->
</div>