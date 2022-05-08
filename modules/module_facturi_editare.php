<div class="container">
<div class="span-22 last" style="padding-left: 20px;">
<script>
$(document).ready(function(){
	$('#adress').jBreadCrumb();
	$('.pret, .cantitate, #cota_tva').keyfilter(/[\d\,\-\.]/);
	$('.produs').textareaEmitere();
	$('#tabel tr:not(.tr-head) td:not(.toggle-row) .rand').focus(
		function(){	if (!$(this).parents('td').hasClass('.ui-state-disabled')) $(this).parents('tr').addClass('ui-state-hover'); }
	).blur(
		function(){	$(this).parents('tr').removeClass('ui-state-hover'); }
	);
	$('[tips]').qtip({
		position:{
			corner:{
				target: 'leftMiddle',
				tooltip: 'rightMiddle'
			}
		},
		style:{
			name: 'light',
			tip:{
				corner:'rightMiddle',
				size:{ x:10, y:10 }
			},
			border:{ width: 2, radius: 4 }
		}
	});
	$('#client').autocomplete('/includes/functii.php',{
		loader:'../imagini/ajax-loader.gif',
		minChars: 1,
		formatItem: function(row, i, max,value) {
			var mat = value.split(';');
			return '<div style="text-transform: uppercase;">'+mat[1]+'</div><div style="text-transform: uppercase;">'+mat[2]+'</div>';
		},
		formatResult: function (data,value) {
			var mat = value.split(';');
			return mat[1];
		},
		extraParams:{'op':'autocomplete_client'}
	})
	.result(function(event, data, formatted){
		var rez = formatted.split(';');
		$("#c-cif").attr('idc',rez[0]);
		$("#denumire-result").html(rez[1]);
		$.ajax({type:'GET',dataType:"json",url:'/includes/functii.php?op=query_client&id_firma='+rez[0],success:function(raspuns){
			$("#content-client").removeClass('ui-helper-hidden');
			$("#c-cif").html(rez[2]);
			$("#c-adresa").html(raspuns.adresa);
			$("#c-tva").html(raspuns.tva);
			$("#c-reg-com").html(raspuns.reg_com);
			$("#c-banca").html(raspuns.banca);
			$("#c-iban").html(raspuns.iban);
			$("#client").attr('fault','1').blur();
		}
		});
		autocomplete_delegat();
	});
	$('#furnizor').autocomplete('/includes/functii.php',{
		loader:'../imagini/ajax-loader.gif',
		loaderx:'../imagini/bullet-down.png',
		minChars: 3,
		formatItem: function(row, i, max,value) {
			var mat = value.split(';');
			return '<div style="text-transform: uppercase;">'+mat[1]+'</div><div style="text-transform: uppercase;">'+mat[2]+'</div>';
		},
		formatResult: function (data,value) {
			var mat = value.split(';');
			return mat[1];
		},
		extraParams:{'op':'autocomplete_furnizor'}
	})
	.result(function(event, data, formatted){
		var rez = formatted.split(';');
		$("#f-cif").attr('idf',rez[0])
		$("#denumire-result").html(rez[1]);
		var query = '&id_user=<?php echo $_GET['idf']; ?>&id_draft='+$('#id_factura').attr('id_draft')+'&id_factura='+$('#id_factura').html()+'&id_firma='+rez[0];
		if ($('#furnizor').attr('acces') == '1') query += '&acces=1&serie='+$("#f-serie").html()+'&numar='+$("#f-numar").html();
		$.ajax({type:'GET',dataType:"json",url:'/includes/functii.php?op=query_furnizor_editare'+query,success:function(raspuns){
			$("#content-furnizor").removeClass('ui-helper-hidden');
			$("#f-cif").html(rez[2]);
			$("#f-adresa").html(raspuns.adresa);
			$("#f-tva").html(raspuns.tva);
			$("#f-reg-com").html(raspuns.reg_com);
			$("#f-banca").html(raspuns.banca);
			$("#f-iban").html(raspuns.iban);
			$("#err-serie").addClass('ui-helper-hidden');
			$("#f-serie").html(raspuns.serie);
			$("#f-numar").html(raspuns.numar);
			$('#f-tva').attr('id_tva',raspuns.tva);
	if (raspuns.tva == '1'){
		$('#f-tva').html('Platitor de TVA');
		if ($('.td-tva').is(':hidden')){
			$('.line-adv:first').removeClass('ui-helper-hidden');
			$('.td-valoare').removeClass('ui-corner-tr');
			$('.td-tva, .tva, .ttl-tva').removeClass('ui-helper-hidden');
			$('.row').eq(0).addClass('span-7').removeClass('span-8');
			$('.row').eq(1).addClass('span-2').removeClass('span-3');
			$('.row').eq(2).addClass('span-2').removeClass('span-3');
			$('.ttl-general-2').attr('colspan','2');
			$('.ttl-subtotal').addClass('span-5').removeClass('span-8');
			$('#ttl-general').addClass('span-15').removeClass('span-18');
			$.each($('#tabel tr td .rand'),function(){
				if ($(this).attr('jsn') == 'produs') $(this).addClass('span7').removeClass('span8');
				if ($(this).attr('jsn') == 'um') $(this).addClass('span2').removeClass('span3');
				if ($(this).attr('jsn') == 'q') $(this).addClass('span2').removeClass('span3');
			});
			notify_bars(10,'Coloana TVA a fost adaugata in factura.');
		}
	}
	else{
		$('#f-tva').html('Neplatitor de TVA');
		if ($('.td-tva').is(':visible')){
			$('.line-adv:first').addClass('ui-helper-hidden');
			$('.td-valoare').addClass('ui-corner-tr');
			$('.td-tva, .tva, .ttl-tva').addClass('ui-helper-hidden');
			$('.row').eq(0).addClass('span-8').removeClass('span-7');
			$('.row').eq(1).addClass('span-3').removeClass('span-2');
			$('.row').eq(2).addClass('span-3').removeClass('span-2');
			$('.ttl-general-2').attr('colspan','1');
			$('.ttl-subtotal').addClass('span-8').removeClass('span-5');
			$('#ttl-general').addClass('span-18').removeClass('span-15');
			$.each($('#tabel tr td .rand'),function(){
				if ($(this).attr('jsn') == 'produs') $(this).addClass('span8').removeClass('span7');
				if ($(this).attr('jsn') == 'um') $(this).addClass('span3').removeClass('span2');
				if ($(this).attr('jsn') == 'q') $(this).addClass('span3').removeClass('span2');
			});
			notify_bar(10,'Coloana TVA a fost eliminata din factura.');
		}
	}
			calculeaza();
			$('#furnizor').attr('fault','1').attr('acces','1').blur();
		}
		});
		autocomplete_reprez();
	});

	$('#client').keyup(function(){
		if ($(this).attr('fault') != '0') $(this).attr('fault','0');
	});
	$('#furnizor').keyup(function(){
		$('.ui-jmenucontain').addClass('ui-helper-hidden');
		if ($(this).attr('fault') != '0') $(this).attr('fault','0');
	});
	$('#delegat').keydown(function(){
		if ($(this).attr('acces') != '0'){
			$(this).attr('acces','0');
			$(this).attr('id_delegat','');
		}
	});
	$('#reprez').keydown(function(){
		if ($(this).attr('acces') != '0'){
			$(this).attr('acces','0');
			$(this).attr('id_reprez','');
		}
	});
	$('#tabel tr td .produs').keydown(function(){
		if ($(this).attr('acces') != '0'){
			$(this).attr('acces','0');
			$(this).attr('id_produs','');
		}
		$(this).textareaEmitere();
	});
	
	$('#detalii-furnizor').toggle(
		function(){
			$(this).text('Ascunde detalii');
			$("#continut-furnizor").removeClass('ui-helper-hidden');
		},
		function(){
			$(this).text('Detalii furnizor');
			$("#continut-furnizor").addClass('ui-helper-hidden');
		}
	);
	$('#detalii-client').toggle(
		function(){
			$(this).text('Ascunde detalii');
			$("#content-client-hide").removeClass('ui-helper-hidden');
		},
		function(){
			$(this).text('Detalii client');
			$("#content-client-hide").addClass('ui-helper-hidden');
		}
	);
	$('#data-factura, #data_scadenta').datepicker({
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
		minDate: '-1y',
		maxDate: '+1y',
		firstDay: 1
	});
	$('#config').click(function(){
		if ($("#box-adv").hasClass('.ui-helper-hidden')){
			$(this).text('Ascunde configurare avansata');
			$("#box-adv").removeClass('ui-helper-hidden');
			$('body').animate({ scrollTop: 250 }, 1000);
		}
		else{
			$(this).text('Configurare avansata factura');
			$("#box-adv").addClass('ui-helper-hidden');
		}
	});
	$('#observatii').focus(
		function(){ $(this).css('border', 'solid 1px #f3c809'); }
	).blur(
		function(){ $(this).css('border', 'solid 1px #aaa'); }
	).textareaDefault();

	var reguli = jQuery.validationAide.getDefaultValidationRules();
	reguli.add('validator-client', '', function(v, obj){
		if ($(obj).attr('fault') == '0') return false;
		return true;
	});
	reguli.add('validator-furnizor', '', function(v, obj){
		if ($(obj).attr('fault') == '0') return false;
		return true;
	});
	$("#formular").validationAideEnable(
		reguli,
		{ fieldErrorCssClass: 'failed', showSummary: false },
		null,
		chk
	);
	autocomplete_produs();
	autocomplete_unitate();
	autocomplete_delegat();
	autocomplete_reprez();
});
function defaults(){
	$('.pret, .cantitate').keyfilter(/[\d\,\-\.]/);
	$('#tabel tr:not(.tr-head) td:not(.toggle-row) .rand').focus(
		function(){	if (!$(this).parents('td').hasClass('.ui-state-disabled')) $(this).parents('tr').addClass('ui-state-hover'); }
	).blur(
		function(){	$(this).parents('tr').removeClass('ui-state-hover'); }
	);
	$('.after-s, .afters').focus(
		function(){ $(this).css('border', 'solid 1px #f3c809'); }
	).blur(
		function(){ $(this).css('border', 'solid 1px #ccc'); }
	);
	$('#tabel tr td .produs').keydown(function(){
		if ($(this).attr('acces') != '0'){
			$(this).attr('acces','0');
			$(this).attr('id_produs','');
		}
		$(this).textareaEmitere();
	});
	autocomplete_produs();
	autocomplete_unitate();
}
function add_client(){
<?php
$src = '/'.$subdomeniu.'/popup_add_clienti/'.$_GET['idf'].'/';
echo 'var src = "'.$src.'";';
?>
	hide_now('bar');
	var query = '?factura=1';
	if ($('#client').length == 1 && $.trim($('#client').val()) != 'client') query += '&denumire='+$('#client').val();
	$("#dialog_add").dialog('destroy'); 
	$("#dialog_add").remove();
	$("body").append('<div id="dialog_add" title="<div class=\'span-20\' style=\'text-align: center;\'>Adaugare client</div>"><iframe src="'+src+query+'" width="900" height="520" frameborder="0" border="0"></iframe>');
	$("#dialog_add").show(); 
	$("#dialog_add").dialog({
			height: 600,
			width: 950,
			modal:true,
			resizable: false,
			overlay:{
					"background-color": "#333",
					"opacity": "0.75",
					"-moz-opacity": "0.75"
			}
	});
}
function add_furnizor(){
<?php
$src = '/'.$subdomeniu.'/module_verifica_furnizori/'.$_GET['idf'].'/';
echo 'var src = "'.$src.'";';
?>
	hide_now('bar');
	var query = '?factura=1';
	if ($('#furnizor').length == 1 && $.trim($('#furnizor').val()) != 'furnizor') query += '&denumire='+$('#furnizor').val();
	$("#dialog_add").dialog('destroy'); 
	$("#dialog_add").remove();
	$("body").append('<div id="dialog_add" title="<div class=\'span-20\' style=\'text-align: center;\'>Adaugare furnizor</div>"><iframe src="'+src+query+'" width="900" height="650" frameborder="0" border="0"></iframe>');
	$("#dialog_add").show();
	$("#dialog_add").dialog({
			height: 720,
			width: 950,
			modal:true,
			resizable: false,
			overlay:{
					"background-color": "#333",
					"opacity": "0.75",
					"-moz-opacity": "0.75"
			}
	});
}
function modify_client(id){
<?php
$src = '/'.$subdomeniu.'/popup_mod_clienti/'.$_GET['idf'].'/';
echo 'var src = "'.$src.'";';
?>
	var query = '?factura=1';
	if (id) query += '&id_firma='+id;
	else query += '&id_firma='+$('#c-cif').attr('idc');
	
	$("#dialog_modify").dialog('destroy');
	$("#dialog_modify").remove();
	$("body").append('<div id="dialog_modify" title="<div class=\'span-20\' style=\'text-align: center;\'>Modificare informatii client</div>" style="text-align:left"><iframe src="'+src+query+'" width="900" height="520" frameborder="0" border="0"></iframe>');
	$("#dialog_modify").show();
	$("#dialog_modify").dialog({
			height: 600,
			width: 950,
			modal:true,
			resizable: false,
			overlay:{ 
					"background-color": "#333",
					"opacity": "0.75",
					"-moz-opacity": "0.75"
			}
	});
}
function modify_furnizor(id){
<?php
$src = '/'.$subdomeniu.'/popup_mod_furnizori/'.$_GET['idf'].'/';
echo 'var src = "'.$src.'";';
?>
	var query = '?factura=1';
	if (id) query += '&id_firma='+id;
	else query += '&id_firma='+$('#f-cif').attr('idf');
	
	$("#dialog_modify").dialog('destroy'); 
	$("#dialog_modify").remove();
	$("body").append('<div id="dialog_modify" title="<div class=\'span-20\' style=\'text-align: center;\'>Modificare informatii furnizor</div>" style="text-align:left"><iframe src="'+src+query+'" width="900" height="520" frameborder="0" border="0"></iframe>');
	$("#dialog_modify").show();
	$("#dialog_modify").dialog({
			height: 600,
			width: 950,
			modal:true,
			resizable: false,
			overlay:{
					"background-color": "#333",
					"opacity": "0.75",
					"-moz-opacity": "0.75"
			}
	});
}
function query_id_client(){
	if ($.trim($('#client').val()) && $('#client').attr('fault') == '0'){
		var query = '&denumire='+$('#client').val();
		$.ajax({type:'GET',dataType:"json",url:'/includes/functii.php?op=query_id_client'+query,success:function(raspuns){
				if (raspuns.id_firma == 0){
					if (!$("#content-client").hasClass('.ui-helper-hidden')){
						$("#content-client").addClass('ui-helper-hidden');
						$("#c-cif").html('');
						$("#c-cif").attr('idc','');
						$("#c-adresa").html('');
						$("#c-reg-com").html('');
						$("#c-banca").html('');
						$("#c-iban").html('');
					}
				}
				else query_client(raspuns.id_firma);
			}
		});
	}
}
function query_client(id){
	$.ajax({type:'GET',dataType:"json",url:'/includes/functii.php?op=query_client&id_firma='+id,success:function(raspuns){
			$("#content-client").removeClass('ui-helper-hidden');
			$("#client").val(raspuns.denumire);
			$("#c-cif").html(raspuns.cif);
			$("#c-cif").attr('idc',raspuns.id_firma);
			$("#c-adresa").html(raspuns.adresa);
			$("#c-reg-com").html(raspuns.reg_com);
			$("#c-banca").html(raspuns.banca);
			$("#c-iban").html(raspuns.iban);
			$("#client").attr('fault','1').blur();
		}
	});
}
function query_id_furnizor(){
	if ($.trim($('#furnizor').val()) && $('#furnizor').attr('fault') == '0'){
		var query = '&denumire='+$('#furnizor').val()+'&id_user=<?php echo $_GET['idf'];?>&serie='+$("#f-serie").html()+'&numar='+$("#f-numar").html();
		$.ajax({type:'GET',dataType:"json",url:'/includes/functii.php?op=query_id_furnizor'+query,success:function(raspuns){
				if (raspuns.id_firma == 0){
					if (!$("#content-furnizor").hasClass('.ui-helper-hidden')){
						$("#content-furnizor").addClass('ui-helper-hidden');
						$("#f-cif").html('');
						$("#f-cif").attr('idf','');
						$("#f-adresa").html('');
						$("#f-reg-com").html('');
						$("#f-banca").html('');
						$("#f-iban").html('');
						$("#err-serie").removeClass('ui-helper-hidden');
						$("#f-serie").html('');
						$("#f-numar").html('');
						$("#f-draft").html('');
					}
				}
				else query_furnizor(raspuns.id_firma);
			}
		});
	}
}
function query_furnizor(id){
	if ($('#furnizor').hasClass('failed')) $('#furnizor').removeClass('failed');
	var query = '&id_user=<?php echo $_GET['idf']; ?>&id_draft='+$('#id_factura').attr('id_draft')+'&id_factura='+$('#id_factura').html()+'&id_firma='+id;
	if ($('#furnizor').attr('acces') == '1') query += '&acces=1&serie='+$("#f-serie").html()+'&numar='+$("#f-numar").html();
	$.ajax({type:'GET',dataType:"json",url:'/includes/functii.php?op=query_furnizor_editare'+query,success:function(raspuns){
			$("#content-furnizor").removeClass('ui-helper-hidden');
			$("#furnizor").val(raspuns.denumire);
			$("#f-cif").html(raspuns.cif);
			$("#f-cif").attr('idf',raspuns.id_firma);
			$("#f-adresa").html(raspuns.adresa);
			$("#f-reg-com").html(raspuns.reg_com);
			$("#f-banca").html(raspuns.banca);
			$("#f-iban").html(raspuns.iban);
			$("#err-serie").addClass('ui-helper-hidden');
			$("#f-serie").html(raspuns.serie);
			$("#f-numar").html(raspuns.numar);
			$('#f-tva').attr('id_tva',raspuns.tva);
	if (raspuns.tva == '1'){
		$('#f-tva').html('Platitor de TVA');
		if ($('.td-tva').is(':hidden')){
			$('.line-adv:first').removeClass('ui-helper-hidden');
			$('.td-valoare').removeClass('ui-corner-tr');
			$('.td-tva, .tva, .ttl-tva').removeClass('ui-helper-hidden');
			$('.row').eq(0).addClass('span-7').removeClass('span-8');
			$('.row').eq(1).addClass('span-2').removeClass('span-3');
			$('.row').eq(2).addClass('span-2').removeClass('span-3');
			$('.ttl-general-2').attr('colspan','2');
			$('.ttl-subtotal').addClass('span-5').removeClass('span-8');
			$('#ttl-general').addClass('span-15').removeClass('span-18');
			$.each($('#tabel tr td .rand'),function(){
				if ($(this).attr('jsn') == 'produs') $(this).addClass('span7').removeClass('span8');
				if ($(this).attr('jsn') == 'um') $(this).addClass('span2').removeClass('span3');
				if ($(this).attr('jsn') == 'q') $(this).addClass('span2').removeClass('span3');
			});
			notify_bars(10,'Coloana TVA a fost adaugata in factura.');
		}
	}
	else{
		$('#f-tva').html('Neplatitor de TVA');
		if ($('.td-tva').is(':visible')){
			$('.line-adv:first').addClass('ui-helper-hidden');
			$('.td-valoare').addClass('ui-corner-tr');
			$('.td-tva, .tva, .ttl-tva').addClass('ui-helper-hidden');
			$('.row').eq(0).addClass('span-8').removeClass('span-7');
			$('.row').eq(1).addClass('span-3').removeClass('span-2');
			$('.row').eq(2).addClass('span-3').removeClass('span-2');
			$('.ttl-general-2').attr('colspan','1');
			$('.ttl-subtotal').addClass('span-8').removeClass('span-5');
			$('#ttl-general').addClass('span-18').removeClass('span-15');
			$.each($('#tabel tr td .rand'),function(){
				if ($(this).attr('jsn') == 'produs') $(this).addClass('span8').removeClass('span7');
				if ($(this).attr('jsn') == 'um') $(this).addClass('span3').removeClass('span2');
				if ($(this).attr('jsn') == 'q') $(this).addClass('span3').removeClass('span2');
			});
			notify_bar(10,'Coloana TVA a fost eliminata din factura.');
		}
	}
			calculeaza();
			if (raspuns.draft) $("#f-draft").html('<a href="javascript:">Factura <span class="uppercase">'+raspuns.serie+'</span> '+raspuns.draft+' este salvata ca draft</a>');
			else $("#f-draft").html('');
			$("#furnizor").attr('fault','1').attr('acces','1').blur();
			autocomplete_reprez();
		}
	});
}
function check_input(id){
	if ($("#"+id).attr('checked')){ $("."+id).removeClass('ui-helper-hidden'); $("."+id+':first').focus(); }
	if (!$("#"+id).attr('checked')) $("."+id).addClass('ui-helper-hidden');
}
function get_tva(){
	var maxrow = $('#tabel tr').length; var i = 1;
	if ($("#adv1").attr('checked') && $(".adv1").val()){
		$('#val-tva').text($('.adv1').val());
		for (i=1; i<=maxrow; i++){
			calc_linie(i);
		}
	}
	else{
		$('#val-tva').text('24');
		for (i=1; i<=maxrow; i++){
			calc_linie(i);
		}
	}
}
function get_valuta(){
	if ($("#adv2").attr('checked')) $("#ttl-general").html('Total General '+$("#valuta").val());
	else $("#ttl-general").html('Total General');
}
function init_cantitate(id){
	if (!$("#q"+id).val()) $("#q"+id).val('1');	
	else return false;
}
function add_row(){
	var maxrow = $('#tabel tr').length;
	var content;
	content +=
	'<tr id="'+maxrow+'">'+
		'<td class="toggle-row"><span id="icon'+maxrow+'" class="ui-icon ui-icon-circle-minus toggle-margin" onclick="remove_row('+maxrow+')" tips=\'<div id="toggle-tips'+maxrow+'" class="span3 tips-x">Sterge linie</div>\'></span></td>';
	if ($('#f-tva').attr('id_tva') == '0') content +=
		'<td><textarea class="rand span8 afters produs" id="produs'+maxrow+'" id_produs="" jsn="produs"></textarea></td>'+
		'<td><input type="text" class="rand span3 after-s" id="um'+maxrow+'" jsn="um"></td>'+
		'<td><input type="text" class="rand span3 after-s cantitate" id="q'+maxrow+'" jsn="q" style="text-align: right;" onfocus="init_cantitate('+maxrow+')" onkeyup="calc_linie('+maxrow+')" autocomplete="off"></td>';
	else content +=
		'<td><textarea class="rand span7 afters produs" id="produs'+maxrow+'" id_produs="" jsn="produs"></textarea></td>'+
		'<td><input type="text" class="rand span2 after-s" id="um'+maxrow+'" jsn="um"></td>'+
		'<td><input type="text" class="rand span2 after-s cantitate" id="q'+maxrow+'" jsn="q" style="text-align: right;" onfocus="init_cantitate('+maxrow+')" onkeyup="calc_linie('+maxrow+')" autocomplete="off"></td>';
	content +=	
		'<td><input type="text" class="rand span3 after-s pret" id="pret'+maxrow+'" jsn="pret" style="text-align: right;" onkeyup="calc_linie('+maxrow+')" autocomplete="off"></td>'+
		'<td><div id="val'+maxrow+'" class="val" alt="n9p3c2S" style="text-align: right; font-size: 1.2em; padding-right: 3px;">0</div></td>';
	if ($('#f-tva').attr('id_tva') == '0') content +=
		'<td><div id="tva'+maxrow+'" class="tva ui-helper-hidden" alt="n9p3c2S" style="text-align: right; font-size: 1.2em; padding-right: 3px;">0</div></td>';
	else content +=
		'<td><div id="tva'+maxrow+'" class="tva" alt="n9p3c2S" style="text-align: right; font-size: 1.2em; padding-right: 3px;">0</div></td>';
	content += '</tr>';
	$(content).appendTo('#tabel');
	$('#icon'+maxrow+'[tips]').qtip({
		position:{
			corner:{
				target: 'leftMiddle',
				tooltip: 'rightMiddle'
			}
		},
		style:{
			name: 'light',
			tip:{
				corner:'rightMiddle',
				size:{ x:10, y:10 }
			},
			border:{ width: 2, radius: 4 }
		}
	});
	defaults();
	$('#produs'+maxrow).click(function(){
		$(this).trigger('focus');
	}).trigger('click');
}
function remove_row(id){
	if ($('#icon'+id).length == 1){
		$('#'+id).children(':not(.toggle-row)').addClass('ui-state-disabled');
		if ($('#'+ id +' td:not(.toggle-row) input').hasClass('failed')) $('#'+ id +' td:not(.toggle-row) input').removeClass('validator-required');
		$('#toggle-tips'+id).html('Refacere linie');
		calc_linie(id);
		$('#icon'+id).removeClass('ui-icon-circle-minus').addClass('ui-icon-arrowreturnthick-1-w').attr('id','undo'+id);
		return;
	}
	if ($('#undo'+id).length == 1){
		$("#"+id).children(':not(.toggle-row)').removeClass('ui-state-disabled');
		$('#toggle-tips'+id).html('Sterge linie');
		calc_linie(id);
		$("#undo"+id).addClass('ui-icon-circle-minus').removeClass('ui-icon-arrowreturnthick-1-w').attr('id','icon'+id);
		return;
	}
}
function show_rows(){
	$.each($('#tabel tr:not(.tr-head)'),function(i,obj){
		$(obj).removeClass('ui-helper-hidden');
	});
	$('#linii-hide').html('');
}
function calc_linie(id){
	if ($('#pret'+id).val() && $('#pret'+id).parent(':not(.ui-state-disabled)').html() && $('#q'+id).val() && $('#q'+id).parent(':not(.ui-state-disabled)').html()){
		var x = $("#q"+id).val().replace(',','.');
		var y = $("#pret"+id).val().replace(',','.');
		var z = $("#val-tva").text();
		var tva0 = new String(x*y*z/100);
		if (tva0 != 'NaN'){
			var tva = $.fn.autoNumeric.Format('tva'+id,tva0);
			$("#tva"+id).text(tva);
		}
		else $("#tva"+id).text('0');
		var val0 = new String(x*y);
		if (val0 != 'NaN'){
			var val = $.fn.autoNumeric.Format('tva'+id,val0);
			$("#val"+id).text(val);
		}
		else $("#val"+id).text('0');
	}
	else{
		$("#tva"+id).text('0');
		$("#val"+id).text('0');
	}
	calculeaza();
}
function calculeaza(){
	var ttl_tva = 0;
	var ttl_valoare = 0;
	var ttl_general = 0;
	if ($('#f-tva').attr('id_tva') != '0'){
		$.each($('tr td:not(.ui-state-disabled)').find('.tva'),function(){
			ttl_tva += new Number($(this).text().replace('.','').replace('.','').replace(',','.'));
		});
	}
	total_tva = $.fn.autoNumeric.Format('total-tva',ttl_tva);
	$("#total-tva").text(total_tva);
	$.each($('tr td:not(.ui-state-disabled)').find('.val'),function(){
		ttl_valoare += new Number($(this).text().replace('.','').replace('.','').replace(',','.'));
	});
	total_valoare = $.fn.autoNumeric.Format('total-valoare',ttl_valoare);
	$("#total-valoare").text(total_valoare);
	ttl_general = ttl_tva + ttl_valoare;
	total_general = $.fn.autoNumeric.Format('total-general',ttl_general);
	$("#total-general").text(total_general);
}
function autocomplete_produs(){
	$('#tabel tr td textarea').autocomplete('/includes/functii.php',{
		autoFill: false,
		highlight: false,
		selectFirst: true,
		formatItem: function(row, i, max, value) {
			var mat = value.split(';');
			return mat[1];
		},
		formatResult: function (data,value) {
			var mat = value.split(';');
			return mat[1];
		},
		extraParams:{'op':'autocomplete_produs'}
	}).result(function(event, data, formatted){
		var rez = formatted.split(';');
		$(this).val(rez[1]).attr('id_produs',rez[0]).attr('acces','1').textareaEmitere().blur();
		var nr = $(this).attr('id').replace('produs','');
		$('#um'+nr).val(rez[2]);
		$('#q'+nr).focus().select();
	});
}
function autocomplete_unitate(){
	$('#tabel tr td input:eq(0)').autocomplete('/includes/functii.php',{
		autoFill: false,
		highlight: false,
		selectFirst: true,
		formatItem: function(row, i, max,value) {
			var mat = value.split(';');
			return mat[1];
		},
		formatResult: function (data,value) {
			var mat = value.split(';');
			return mat[1];
		},
		extraParams:{'op':'autocomplete_unitate'}
	}).result(function(event, data, formatted){
		var rez = formatted.split(';');
	});
}
function autocomplete_reprez(){
	if ($('#f-cif').attr('idf') != ''){
		$("#reprez").autocomplete('/includes/functii.php',{
			loader:'../imagini/ajax-loader.gif',
			autoFill: false,
			highlight: false,
			matchSubset: false,
			//dataType: 'json',
			formatItem: function(row, i, max, value) {
				var mat = value.split(';');
				return mat[1];
			},
			formatResult: function (data,value) {
				var mat = value.split(';');
				return mat[1];
			},
			extraParams:{'op':'autocomplete_reprez', 'id_furnizor':$('#f-cif').attr('idf')}
		})
		.result(function(event, data, formatted){
			var rez = formatted.split(';');
			$(this).val(rez[1]).attr('id_reprez',rez[0]).attr('acces','1');
			$('#act_reprez').val(rez[2]);
		});
	}
}
function autocomplete_delegat(){
	if ($("#c-cif").attr('idc') != ''){
		$("#delegat").autocomplete('/includes/functii.php',{
			loader:'../imagini/ajax-loader.gif',
			autoFill: false,
			highlight: false,
			matchSubset: false,
			formatItem: function(row, i, max, value) {
				var mat = value.split(';');
				return mat[1];
			},
			formatResult: function (data,value) {
				var mat = value.split(';');
				return mat[1];
			},
			extraParams:{'op':'autocomplete_delegat', 'id_client':$('#c-cif').attr('idc')}
		})
		.result(function(event, data, formatted){
			var rez = formatted.split(';');
			$(this).val(rez[1]).attr('id_delegat',rez[0]).attr('acces','1');
			$('#act_identitate').val(rez[2]);
		});
	}
}
function emitere(){
	var row = $('#tabel tr:not(.tr-head)').length; var maxrow = $('#tabel tr:not(.tr-head)').length;
	if (maxrow == 1){
		if ($('#tabel tr:not(.tr-head) td').hasClass('ui-state-disabled')){
			$('#'+maxrow).children(':not(.toggle-row)').removeClass('ui-state-disabled');
			$('#undo'+maxrow).addClass('ui-icon-circle-minus').removeClass('ui-icon-arrowreturnthick-1-w').attr('id', 'icon'+maxrow);
			calc_linie(maxrow);
			notify_bar(20,'Pentru a emite o factura trebuie sa completati cel putin o linie!');
		}
		$('#tabel tr:not(.tr-head) td .rand').addClass('validator-required');
	}
	else{
		$.each($('#tabel tr:not(.tr-head)'),function(i,obj){
			if ($(obj).find('td:not(.toggle-row)').hasClass('ui-state-disabled')) row--;
			else{				
				if ($.trim($(obj).find('textarea').val()) == '' && $.trim($(obj).find('input:not(.cantitate)').eq(0).val()) == '' && $.trim($(obj).find('input:not(.cantitate)').eq(1).val()) == '') row--;
			}
		});
		if (row <= 0){
			notify_bar(20,'Pentru a emite o factura trebuie sa completati cel putin o linie!');
			$('#tabel tr:not(.tr-head):first td .rand').addClass('validator-required');
		}
		if (row >= 1){
			var numar_row;
			$('#linii-hide').html('');
			if ((maxrow-row) != 0){
				(maxrow-row) == 1 ? numar_row = 'rand nul</strong> a fost ascuns. ' : numar_row = 'randuri nule</strong> au fost ascunse. ';
				$('<p><strong>'+(maxrow-row)+' '+numar_row+'<a href="javascript:show_rows()">Apasa aici pentru vizualizare</a></p>').appendTo("#linii-hide");
				$.each($('#tabel tr:not(.tr-head)'),function(i,obj){
					if ($(obj).find('td:not(.toggle-row)').hasClass('ui-state-disabled')) $(obj).addClass('ui-helper-hidden');
					else{
						if ($.trim($(obj).find('textarea').val()) == '' && $.trim($(obj).find('input:not(.cantitate)').eq(0).val()) == '' && $.trim($(obj).find('input:not(.cantitate)').eq(1).val()) == '') $(obj).addClass('ui-helper-hidden');
					}
				});
			}
			$('#tabel tr:not(.tr-head, .ui-helper-hidden) td:not(.ui-state-disabled) .rand').addClass('validator-required');
		}
	}
}
function chk(s){
	if (!s){
		notify_bar(10,'Eroare la completarea datelor!');
		return false;
	}	
	else{
		$(document).find('.after').css({border: 'solid 1px #ccc'});
		<?php if (isset($_GET['id_draft'])) { ?> save_factura(); <?php } ?>
		<?php if (isset($_GET['id_factura'])) { ?> editare_factura(); <?php } ?>
		return false;
	}
}
function editare_factura(){
	var rest_plata;
	var incasare = new Number($('#incasare').text());
	var ttl = new Number($('#total-general').text().replace('.','').replace('.','').replace(',','.'));
	if (ttl >= $('#incasare').text()){
		if (incasare != 0){
			rest_plata = (ttl - incasare);
			$('#rest-plata').text(rest_plata);
			rest_plata = $.fn.autoNumeric.Format('rest-plata',rest_plata);
			save_factura(rest_plata);
		}
		else save_factura('0');
	}
	else{
		$("#dialog_confirm").dialog('destroy'); 
		$("#dialog_confirm").remove();
		var content = '';
		content +=
			'<div id="dialog_confirm" title="<div class=\'span-12\' style=\'font-size: .9em; text-align: center;\'>Editare factura</div>" style="text-align:left;">'+
			'<div class="span-11 ui-state-error-text form-row" style="font-weight: bold;">Atentie!</div>'+
			'<div class="span-12 form-row">Totalul facturii <span class="uppercase-b" style="font-size: .9em;">'+$('#f-serie').text()+' '+$('#f-numar').text()+'</span> este mai mic decat sumele incasate pana acum.</div>'+
			'<div class="span-11 form-row">Toate incasarile vor fi sterse. Continuati?</div>'+
			'</div>';
		$("body").append(content);
		$("#dialog_confirm").show();
		$("#dialog_confirm").dialog({ 
			height: 'auto',
			width: 500,
			modal:true, 
			resizable: false, 
			overlay:{ 
					"background-color": "#333", 
					"opacity": "0.75", 
					"-moz-opacity": "0.75" 
			},
			buttons:{
				"Salvare modificari":function(){
					$("#dialog_confirm").dialog('close');
					save_factura('0');
				},
				"Nu salva modificari":function(){
					$("#dialog_confirm").dialog('close');
				}
			}
		});
	}
}
function save(){
	var maxrow = $('#tabel tr:not(.tr-head, .ui-helper-hidden)').length;
	var jsn = '{"linii": ['; var jsni='';
	var query = "&idf="+<?php echo $_GET['idf']; ?>+"&id_furnizor="+$("#f-cif").attr('idf')+"&id_client="+$("#c-cif").attr('idc');
	query += "&serie="+$("#f-serie").text()+"&numar="+$("#f-numar").text()+"&data_factura="+$("#data-factura").val();
	
	$.each($('.check-adv:checked'),function(i,obj){
		var id = $(obj).attr('id');
		$.each($('.'+id).not('#observatii'),function(i,obj){
			if ($(obj).val()){
				if ($(obj).attr('id_delegat')) query += '&id_delegat='+$(obj).attr('id_delegat');
				if ($(obj).attr('id_reprez')) query += '&id_reprez='+$(obj).attr('id_reprez');
				query += "&"+$(obj).attr('id')+"="+$(obj).val();
			}
		});
		if ($('#observatii').val()) query += '&observatii='+$('#observatii').val().replace(new RegExp( "\\n", "g" ),' ');
	});
	if (!$('.check-adv').eq(0).attr('checked')){
		if ($('#f-tva').attr('id_tva') != '0') query += '&cota_tva=24';
		else query += '&cota_tva=0';
	}
	if (!$('.check-adv').eq(1).attr('checked')) query += '&valuta=Lei';
	
	query += "&total_tva="+$("#total-tva").text()+"&total_valoare="+$("#total-valoare").text()+"&total_general="+$("#total-general").text();
	query += "&numar_linii="+maxrow;
	$.each($('#tabel tr:not(.tr-head, .ui-helper-hidden)'),function(i,obj){
		jsni += '{';
		var linie = '';
		$.each($(obj).find('td:not(.ui-state-disabled) .rand'),function(j,v){
				if ($(v).attr('id_produs')) linie += '"id_produs":"'+$(v).attr('id_produs')+'",';
				if ($(this).hasClass('.validator-required')) linie += '"'+$(v).attr('jsn')+'":"'+$(v).val()+'",';
		});
		$.each($(obj).find('td:not(.ui-state-disabled) div:not(.textareaEmitere)'),function(j,v){
			linie += '"'+$(v).attr('class')+'":"'+$(v).text()+'",';
		});
		linie = linie.substring(0,(linie.length-1));
		jsni += linie+'},';
	});
	jsn += jsni.substring(0,jsni.length-1);
	jsn += ']}';
	return query+'&jsn='+jsn;
}
function save_factura(ttl){
	if ($('#c-cif').attr('idc') && $('#f-cif').attr('idf')){
		var x = save();
		var query = '';
	<?php if (isset($_GET['id_draft'])) { ?>
		query += '&id_factura=<?php echo $_GET['id_draft']; ?>';
		$.ajax({type:'POST',dataType:'json',url:'/includes/functii.php',data:'op=update_factura'+query+x,success:function(raspuns){
			document.location.href = '../../../facturi-vizualizare/'+raspuns.idf+'/'+raspuns.id_furnizor+'/'+raspuns.factura+'/?add=1';
		},beforeSend:function(){
			$('.box-loading').removeClass('ui-helper-hidden');
		}
		});
	<?php } ?>
	<?php if (isset($_GET['id_factura'])) { ?>
		query += '&id_factura=<?php echo $_GET['id_factura']; ?>&editare='+ttl;
		$.ajax({type:'POST',dataType:'json',url:'/includes/functii.php',data:'op=update_factura'+query+x,success:function(raspuns){
			document.location.href = '../../../facturi-vizualizare/'+raspuns.idf+'/'+raspuns.id_furnizor+'/'+raspuns.factura+'/?add=1';
		},beforeSend:function(){
			$('.box-loading').removeClass('ui-helper-hidden');
		}
		});
	<?php } ?>
	}
	else{
		if ($('#c-cif').attr('idc') == ''){
			$('#client').addClass('failed');
			notify_bar(10,'Eroare la completarea datelor! Pentru a emite factura adaugati clientul <span class="uppercase">'+$('#client').val()+'</span>');
		}
		if ($('#f-cif').attr('idf') == ''){
			$('#furnizor').addClass('failed');
			notify_bar(10,'Eroare la completarea datelor! Pentru a emite factura adaugati furnizorul <span class="uppercase">'+$('#furnizor').val()+'</span>');
		}
	}
}

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
</script>
<div class="breadCrumbHolder module">
	<div id="adress" class="breadCrumb module span-20 ui-corner-all" style="border-width: 0;">
		<div class="span-10"><ul>
<?php
echo '<li><a href="/'.$subdomeniu.'/facturi/'.$_GET['idf'].'/">Facturi</a></li>';
	if (isset($_GET['id_draft'])) echo '<li class="ui-state-active">Emitere factura salvata ca draft</li>';
	if (isset($_GET['id_factura'])) echo '<li class="ui-state-active">Editare factura</li>';
?>
		</ul></div>
	</div>
</div>
<?php
if (isset($_GET['id_draft'])) $sql = $db->query('select * from facturi where id_draft="'.$_GET['id_draft'].'"');
if (isset($_GET['id_factura'])) $sql = $db->query('select * from facturi where id_factura="'.$_GET['id_factura'].'"');
$row = mysql_fetch_array($sql);

$sc = $db->query('select * from firme where id_user="'.$_GET['idf'].'" and id_firma="'.$row['id_client'].'" and tip_firma="1"');
$rc = mysql_fetch_array($sc);
	echo '
<div class="span-23">
<form id="formular" onsubmit="return false;">
<div class="span-11">
	<div class="box ui-active ui-helper-clearfix" id="content-result" style="min-height: 110px; color: #000; font-weight: normal;">
		<div class="span-11 last text-header">Informatii client</div>
		<input value="'.$rc['denumire'].'" class="after span-10 validator-required validator-client after-margin" id="client" style="text-transform: uppercase;" onblur="query_id_client()" fault="1">
		<div class="span-9 add"><a href="javascript:add_client()" style="color: #00f; text-decoration: underline;">Adauga client</a></div>
		<div id="content-client">
			<div class="span-10 client-val">
				<div class="span-1 min-left">CIF/CUI:</div>
				<div class="span-6 uppercase-l" id="c-cif" idc="'.$rc['id_firma'].'">'.$rc['cif'].'</div>
			</div>
			<div class="span-10 client-val">
				<div class="span-1 min-left">Adresa:</div>
				<div class="span-8 capitalize-l" id="c-adresa">'.$rc['adresa'].'</div>
			</div>
			<div class="span-8 client-val">
				<span style="font-weight: bold;" id="c-tva"></span>
			</div>
			<div class="span-10"><div class="span-3 hlink" id="detalii-client" style="margin-top: 5px;">Detalii client</div></div>
			<div class="ui-helper-hidden" id="content-client-hide">
				<div class="span-10 client-val">
					<div class="min-left-m">Reg Com:</div>
					<div class="span-8 uppercase-b" id="c-reg-com">'.$rc['reg_com'].'</div>
				</div>
				<div class="span-10 client-val">
					<div class="min-left-m">Banca:</div>
					<div class="span-8 capitalize-b" id="c-banca">'.$rc['banca'].'</div>
				</div>
				<div class="span-10 client-val">
					<div class="min-left-m">IBAN:</div>
					<div class="span-8 uppercase-b" id="c-iban">'.$rc['iban'].'</div>
				</div>
				<div class="span-10 hlink" style="margin-top: 10px;"><a href="javascript:modify_client()" style="color: #00f; text-decoration: underline;">Modifica informatii client</a></div>
			</div>
		</div>
	</div>
</div>
	';

	echo '
<script>
$(document).ready(function(){
	$("#furnizor").zonepicker({
		presetRanges: [
	';
$i = 0;
$sqla = $db->query('select count(facturi.id_furnizor) as nr,facturi.id_furnizor,denumire from facturi,firme where firme.id_firma=facturi.id_furnizor and firme.id_user="'.$_SESSION['id_user'].'" and firme.tip_firma="0" and id_draft="0" group by facturi.id_furnizor order by nr desc limit 4');
while ($rowa = mysql_fetch_array($sqla)){
	$rowa['nr'] == 1 ? $result = '<strong>1</strong> factura emisa' : $result = ro($rowa['nr']).' facturi emise';
	echo '{"text":"<div class=\'text-imp\' style=\'color: #2e6e9e;\'>'.$rowa['denumire'].'</div><div class=\'picker-reset\'>'.$result.'</div>","ida":"'.$rowa['id_furnizor'].'"},';
	$i++;
}
if ($i < 4){
	$limit = (4-$i);
	$sql2 = $db->query('select id_firma,denumire from firme where id_user="'.$_SESSION['id_user'].'" and tip_firma="0" and id_firma not in (select facturi.id_furnizor from facturi,firme where firme.id_user="'.$_SESSION['id_user'].'" and id_draft="0" group by facturi.id_furnizor) order by denumire asc limit '.$limit);
	if (mysql_num_rows($sql2)){
		while ($rows = mysql_fetch_array($sql2)){
			echo '{"text":"<div class=\'text-imp\' style=\'color: #2e6e9e;\'>'.$rows['denumire'].'</div>","ida":"'.$rows['id_firma'].'"},';
		}
	}
}
$sqln = $db->query('select count(*) as ttl from firme where id_user="'.$_SESSION['id_user'].'" and tip_firma="0"');
$rown = mysql_fetch_array($sqln);
if ($rown['ttl'] > 4){
	echo '
		{separator:true}
		],
		presets:
			{dateRange:"Cauta furnizor"},
		doneButtonText:"Accepta",
	';
}
if ($rown['ttl'] <= 4){
	echo '
		],
	';
}
	echo '
		posX: $("#furnizor").offset().left,
		posY: $("#furnizor").offset().top+32
	});
});
</script>
	';

$sf = $db->query('select * from firme where id_user="'.$_GET['idf'].'" and id_firma="'.$row['id_furnizor'].'" and tip_firma="0"');
$rf = mysql_fetch_array($sf);
	echo '
<div class="span-11 last">
	<div class="box ui-active ui-helper-clearfix" style="min-height: 110px; color: #000; font-weight: normal;">
		<div class="span-11 last text-header">Informatii furnizor</div>
		<input value="'.$rf['denumire'].'" class="after-select span-10 validator-required validator-furnizor" id="furnizor" href="select_furnizori.php" onblur="query_id_furnizor()" fault="1" acces="0">
		<div class="span-9 add"><a href="javascript:add_furnizor()" style="color: #00f; text-decoration: underline;">Adauga furnizor</a></div>
		<div id="content-furnizor">
			<div class="span-10 furnizor-val">
				<div class="span-1 min-left">CIF/CUI:</div>
				<div class="span-8 uppercase-l" id="f-cif" idf="'.$rf['id_firma'].'">'.$rf['cif'].'</div>
			</div>
			<div class="span-10 furnizor-val">
				<div class="span-1 min-left">Adresa:</div>
				<div class="span-8 capitalize-l" id="f-adresa">'.$rf['adresa'].'</div>
			</div>';
	if ($rf['tva'] == 1) echo '<div class="span-10 furnizor-val"><span style="font-weight: bold;" id="f-tva" id_tva="'.$rf['tva'].'">Platitor de TVA</span></div>';
	if ($rf['tva'] == 0) echo '<div class="span-10 furnizor-val"><span style="font-weight: bold;" id="f-tva" id_tva="'.$rf['tva'].'">Neplatitor de TVA</span></div>';
	echo '
			<div class="span-10"><div class="span-3 hlink" id="detalii-furnizor" style="margin-top: 5px;">Detalii furnizor</div></div>
			<div class="ui-helper-hidden" id="continut-furnizor">
				<div class="span-10 furnizor-val">
					<div class="min-left-m">Reg Com:</div>
					<div class="span-8 uppercase-b" id="f-reg-com">'.$rf['reg_com'].'</div>
				</div>
				<div class="span-10 furnizor-val">
					<div class="min-left-m">Banca:</div>
					<div class="span-8 capitalize-b" id="f-banca">'.$rf['banca'].'</div>
				</div>
				<div class="span-10 furnizor-val">
					<div class="min-left-m">IBAN:</div>
					<div class="span-8 uppercase-b" id="f-iban">'.$rf['iban'].'</div>
				</div>
				<div class="span-10 hlink" style="margin-top: 10px;"><a href="javascript:modify_furnizor()" style="color: #00f; text-decoration: underline;">Modifica informatii furnizor</a></div>
			</div>
		</div>
	</div>
</div>
<hr class="span-22">
<div class="span-23">
<div class="span-22 last">
	<div class="span-18 text-header" style="margin: 0 0 10px 0;">Editare factura</div>
	<div class="span-18" style="margin-bottom: 5px;">
		<div class="span-1" style="font-size: 1.1em; font-weight: bold; padding: 2px 0;">Serie:</div>
		<div id="text-fact" class="span-12 last" style="font-size: 1.2em; font-weight: bold; margin: 2px 0 0 10px;">
			<span id="err-serie" class="ui-helper-hidden" style="font-size: .9em; margin-top: 1px;">Pentru seria facturii selecteaza un furnizor</span>
			<span id="f-serie" style="text-transform: uppercase;">'.$row['serie'].'</span>
			<span id="f-numar" style="padding: 2px 0 2px 4px;">'.$row['numar'].'</span>
		</div>
	</div>
	<div class="span-18" style="margin-bottom: 5px;">
		<div class="span-1 data-factura">Data:</div>
		<div class="span-10 left">
			<input type="text" value="'.date('d-m-Y',strtotime($row['data_factura'])).'" id="data-factura" class="after-s span-6 img-data validator-date-ddmmyyyy" style="margin-left: 10px;">
		</div>
	</div>
</div>
	';
if (!isset($row['data_scadenta']) && !isset($row['id_delegat']) && !isset($row['id_reprez']) && !isset($row['observatii'])) echo '
	<div class="span-6 klink ui-helper-clearfix" id="config" style="margin-bottom: 15px;">Configurare avansata factura</div>
	<div id="box-adv" class="box ui-active span-16 ui-helper-hidden" style="color: #000; font-weight: normal;">
	';
else echo '
	<div class="span-6 klink ui-helper-clearfix" id="config" style="margin-bottom: 15px;">Ascunde configurare avansata</div>
	<div id="box-adv" class="box ui-active span-16" style="color: #000; font-weight: normal;">
	';
	if (isset($row['cota_tva'])){
		if ($rf['tva'] == 1) echo '
		<div class="span-15 line-adv">
			<input type="checkbox" class="check-adv" id="adv1" onclick="check_input(\'adv1\'); get_tva()" checked="checked">
			<div class="text-adv span2 last">Cota TVA</div>
			<div class="f-left"><input value="'.$row['cota_tva'].'" type="text" class="after-adv span-3 adv1 img-procent" id="cota_tva" onkeyup="get_tva()"></div>
		</div>
		';
		if ($rf['tva'] == 0) echo '
		<div class="span-15 line-adv ui-helper-hidden">
			<input type="checkbox" class="check-adv" id="adv1" onclick="check_input(\'adv1\'); get_tva()">
			<div class="text-adv span2 last">Cota TVA</div>
			<div class="f-left"><input value="24" type="text" class="after-adv span-3 adv1 img-procent" id="cota_tva" onkeyup="get_tva()"></div>
		</div>
		';
	}
	else echo '
		<div class="span-15 line-adv">
			<input type="checkbox" class="check-adv" id="adv1" onclick="check_input(\'adv1\'); get_tva()">
			<div class="text-adv span2 last">Cota TVA</div>
			<div class="f-left"><input type="text" class="after-adv span-3 adv1 ui-helper-hidden img-procent" id="cota_tva" onkeyup="get_tva()"></div>
		</div>
	';
	if (isset($row['valuta']) && $row['valuta'] == 'Lei') echo '
		<div class="span-15 line-adv">
			<input type="checkbox" class="check-adv" id="adv2" onclick="check_input(\'adv2\'); get_valuta()" checked="checked">
			<div class="text-adv span2 last">Valuta</div>
			<div class="f-left"><select class="after-adv span-3 adv2" style="width:100px !important;" id="valuta" onchange="get_valuta()">
				<option selected value="Lei">Lei</option>
				<option value="Euro">Euro</option>
				<option value="USD">USD</option>
			</select></div>
		</div>
	';
	if (isset($row['valuta']) && $row['valuta'] == 'Euro') echo '
		<div class="span-15 line-adv">
			<input type="checkbox" class="check-adv" id="adv2" onclick="check_input(\'adv2\'); get_valuta()" checked="checked">
			<div class="text-adv span2 last">Valuta</div>
			<div class="f-left"><select class="after-adv span-3 adv2" style="width:100px !important;" id="valuta" onchange="get_valuta()">
				<option value="Lei">Lei</option>
				<option selected value="Euro">Euro</option>
				<option value="USD">USD</option>
			</select></div>
		</div>
	';
	if (isset($row['valuta']) && $row['valuta'] == 'USD') echo '
		<div class="span-15 line-adv">
			<input type="checkbox" class="check-adv" id="adv2" onclick="check_input(\'adv2\'); get_valuta()" checked="checked">
			<div class="text-adv span2 last">Valuta</div>
			<div class="f-left"><select class="after-adv span-3 adv2" style="width:100px !important;" id="valuta" onchange="get_valuta()">
				<option value="Lei">Lei</option>
				<option value="Euro">Euro</option>
				<option selected value="USD">USD</option>
			</select></div>
		</div>
	';
	if (!isset($row['valuta'])) echo '
		<div class="span-15 line-adv">
			<input type="checkbox" class="check-adv" id="adv2" onclick="check_input(\'adv2\'); get_valuta()">
			<div class="text-adv span2 last">Valuta</div>
			<div class="f-left"><select class="after-adv span-3 adv2 ui-helper-hidden" style="width:100px !important;" id="valuta" onchange="get_valuta()">
				<option selected value="Lei">Lei</option>
				<option value="Euro">Euro</option>
				<option value="USD">USD</option>
			</select></div>
		</div>
	';
	if (isset($row['data_scadenta'])) echo '
		<div class="span-15 line-adv">
			<input type="checkbox" class="check-adv" id="adv3" onclick="check_input(\'adv3\')" checked="checked">
			<div class="text-adv">Data scadentei</div>
			<div class="f-left"><input type="text" class="after-adv span-4 img-data adv3" id="data_scadenta" value="'.date('d-m-Y',strtotime($row['data_scadenta'])).'"></div>
		</div>
	';
	else echo '
		<div class="span-15 line-adv">
			<input type="checkbox" class="check-adv" id="adv3" onclick="check_input(\'adv3\')">
			<div class="text-adv">Data scadentei</div>
			<div class="f-left"><input type="text" class="after-adv span-4 img-data adv3 ui-helper-hidden" id="data_scadenta" value="'.date('d-m-Y').'"></div>
		</div>
	';
	if (isset($row['id_delegat'])){
		if (isset($_GET['id_draft'])) $sql_delegat = $db->query('select delegati.* from facturi,delegati where facturi.id_draft="'.$_GET['id_draft'].'" and facturi.id_delegat = delegati.id_delegat');
		if (isset($_GET['id_factura'])) $sql_delegat = $db->query('select delegati.* from facturi,delegati where facturi.id_factura="'.$_GET['id_factura'].'" and facturi.id_delegat = delegati.id_delegat and delegati.id_draft="0"');
		$row_delegat = mysql_fetch_array($sql_delegat);
		echo '
		<div class="span-16 line-adv">
			<div class="span-8 last">
				<input type="checkbox" class="check-adv" id="adv4" onclick="check_input(\'adv4\')" checked="checked">
				<div class="text-adv span-2">Delegat</div>
				<div class="f-left"><input value="'.$row_delegat['nume_delegat'].'" type="text" class="after-adv span-5 adv4" style="text-transform: capitalize;" id="delegat" id_delegat="'.$row_delegat['id_delegat'].'" acces="1"></div>
			</div>
			<div class="span-8 last">
				<div class="text-adv adv4">Act identitate</div>
				<div class="f-left"><input value="'.$row_delegat['act_identitate'].'" type="text" class="after-adv span-5 adv4" style="text-transform: uppercase;" id="act_identitate"></div>
			</div>
		</div>
	';
	}
	else echo '
		<div class="span-16 line-adv">
			<div class="span-8 last">
				<input type="checkbox" class="check-adv" id="adv4" onclick="check_input(\'adv4\')">
				<div class="text-adv span-2">Delegat</div>
				<div class="f-left"><input type="text" class="after-adv span-5 adv4 ui-helper-hidden" style="text-transform: capitalize;" id="delegat" id_delegat="" acces="0"></div>
			</div>
			<div class="span-8 last">
				<div class="text-adv adv4 ui-helper-hidden">Act identitate</div>
				<div class="f-left"><input type="text" class="after-adv span-5 adv4 ui-helper-hidden" style="text-transform: uppercase;" id="act_identitate"></div>
			</div>
		</div>
	';
	if (isset($row['id_reprez'])){
		if (isset($_GET['id_draft'])) $sql_reprez = $db->query('select reprezentanti.* from facturi,reprezentanti where facturi.id_draft="'.$_GET['id_draft'].'" and facturi.id_reprez = reprezentanti.id_reprez');
		if (isset($_GET['id_factura'])) $sql_reprez = $db->query('select reprezentanti.* from facturi,reprezentanti where facturi.id_factura="'.$_GET['id_factura'].'" and facturi.id_reprez = reprezentanti.id_reprez and reprezentanti.id_draft="0"');
		$row_reprez = mysql_fetch_array($sql_reprez);
		echo '
		<div class="span-16 line-adv">
			<div class="span-8 last">
				<input type="checkbox" class="check-adv" id="adv5" onclick="check_input(\'adv5\')" checked="checked">
				<span class="text-adv span-2">Intocmit de</span>
				<div class="f-left"><input value="'.$row_reprez['nume_reprez'].'" type="text" class="after-adv span-5 adv5" style="text-transform: capitalize;" id="reprez" id_reprez="'.$row_reprez['id_reprez'].'" acces="1"></div>
			</div>
			<div class="span-8 last">
				<div class="text-adv adv5">Act identitate</div>
				<div class="f-left"><input value="'.$row_reprez['act_reprez'].'" type="text" class="after-adv span-5 adv5" style="text-transform: uppercase;" id="act_reprez"></div>
			</div>
		</div>
	';
	}
	else echo '
		<div class="span-16 line-adv">
			<div class="span-8 last">
				<input type="checkbox" class="check-adv" id="adv5" onclick="check_input(\'adv5\')">
				<span class="text-adv span-2">Intocmit de</span>
				<div class="f-left"><input type="text" class="after-adv span-5 adv5 ui-helper-hidden" style="text-transform: capitalize;" id="reprez" id_reprez="" acces="0"></div>
			</div>
			<div class="span-8 last">
				<div class="text-adv adv5 ui-helper-hidden">Act identitate</div>
				<div class="f-left"><input type="text" class="after-adv span-5 adv5 ui-helper-hidden" style="text-transform: uppercase;" id="act_reprez"></div>
			</div>
		</div>
	';
	if (isset($row['observatii'])){
		echo '
		<script>
		$(document).ready(function(){
			$("#observatii").trigger("keyup");
		});
		</script>
		';
		echo '
		<div class="span-15" style="margin-bottom: 10px;">
			<input type="checkbox" class="check-adv" id="adv6" onclick="check_input(\'adv6\')" checked="checked">
			<div class="text-adv span-2">Observatii</div>
			<div class="f-left">
				<textarea class="afters-default span-8 adv6" style="height: 55px; overflow: hidden;" id="observatii" maxlength="160" onkeyup="countable($(this).val().length,$(this))">'.$row['observatii'].'</textarea>
			</div>
			<div class="prepend-2 span-3 adv6 ui-helper-hidden countable-box grey" style="font-size: .9em; margin-left: 20px;" id="mesaj-text">
				<span class="countable">160</span>
				<span class="countable-text">caractere ramase</span>
			</div>
		</div>
	';
	}
	else echo '
		<div class="span-15" style="margin-bottom: 10px;">
			<input type="checkbox" class="check-adv" id="adv6" onclick="check_input(\'adv6\')">
			<div class="text-adv span-2">Observatii</div>
			<div class="f-left">
				<textarea class="afters-default span-8 adv6 ui-helper-hidden" style="height: 55px; overflow: hidden;" id="observatii" maxlength="160" onkeyup="countable($(this).val().length,$(this))">'.$row['observatii'].'</textarea>
			</div>
			<div class="prepend-2 span-3 adv6 ui-helper-hidden countable-box grey" style="font-size: .9em; margin-left: 20px;" id="mesaj-text">
				<span class="countable">160</span>
				<span class="countable-text">caractere ramase</span>
			</div>
		</div>
	';
	echo '
	</div>
	<div id="linii-hide" class="span-10" style="float: right; margin: 0 20px 5px 0; text-align: right;"></div>
	<div class="span-23 last box-table">
		<table border="0" id="tabel">
	';
	if ($rf['tva'] == 1){
	echo '
		<tr style="text-align: center; font-size: 1.2em;" class="tr-head ui-widget-header">
			<td class="span-1" style="background: #fff;"></td>
			<td class="row span-7 ui-corner-tl">Denumire produs</td>
			<td class="row span-2">UM</td>
			<td class="row span-2">Cantitate</td>
			<td class="row span-3">Pret unitar</td>
			<td class="row span-3">Valoare</td>
	';
	if (isset($row['cota_tva'])) echo '<td class="row span-3 ui-corner-tr td-tva">TVA <span id="val-tva">'.$row['cota_tva'].'</span>%</td></tr>';
	else echo '<td class="row span-3 ui-corner-tr td-tva">TVA <span id="val-tva">24</span>%</td></tr>';
	}
	if ($rf['tva'] == 0){
	echo '
		<tr style="text-align: center; font-size: 1.2em;" class="tr-head ui-widget-header">
			<td class="span-1" style="background: #fff;"></td>
			<td class="row span-8 ui-corner-tl">Denumire produs</td>
			<td class="row span-3">UM</td>
			<td class="row span-3">Cantitate</td>
			<td class="row span-3">Pret unitar</td>
			<td class="row span-3 ui-corner-tr">Valoare</td>
	';
	if (isset($row['cota_tva'])) echo '<td class="row span-3 ui-corner-tr td-tva ui-helper-hidden">TVA <span id="val-tva">24</span>%</td></tr>';
	else echo '<td class="row span-3 ui-corner-tr td-tva ui-helper-hidden">TVA <span id="val-tva">24</span>%</td></tr>';
	}
	
	if (isset($_GET['id_draft'])) $sql_linii = $db->query('select * from produse,linii where linii.id_draft="'.$_GET['id_draft'].'" and linii.id_produs=produse.id_produs');
	if (isset($_GET['id_factura'])) $sql_linii = $db->query('select * from produse,linii where linii.id_factura="'.$_GET['id_factura'].'" and linii.id_produs=produse.id_produs and linii.id_draft="0"');
	$i = 1;
	while ($row_linii = mysql_fetch_array($sql_linii)){
		if ($rf['tva'] == 1) echo '
			<tr id="'.$i.'">
				<td class="toggle-row"><span id="icon'.$i.'" class="ui-icon ui-icon-circle-minus toggle-margin" onclick="remove_row('.$i.')" tips=\'<div id="toggle-tips1" class="span3 tips-x">Sterge linie</div>\'></span></td>
				<td><textarea class="rand span7 afters produs" id="produs'.$i.'" jsn="produs" id_produs="'.$row_linii['id_produs'].'" acces="1">'.$row_linii['denumire'].'</textarea></td>
				<td><input value="'.$row_linii['unitate'].'" type="text" class="rand span2 after-s" id="um'.$i.'" um="unitate" jsn="um"></td>
				<td><input value="'.$row_linii['q'].'" type="text" class="rand span2 after-s cantitate" id="q'.$i.'" jsn="q" style="text-align: right;" onfocus="init_cantitate('.$i.')" onkeyup="calc_linie('.$i.')" autocomplete="off"></td>
				<td><input value="'.$row_linii['pret'].'" type="text" class="rand span3 after-s pret" id="pret'.$i.'" jsn="pret" style="text-align: right;" onkeyup="calc_linie('.$i.')" autocomplete="off"></td>
				<td><div class="val" alt="n9p3c2S" id="val'.$i.'" style="text-align: right; font-size: 1.2em; padding-right: 3px;">'.$row_linii['val'].'</div></td>
				<td><div class="tva" alt="n9p3c2S" id="tva'.$i.'" style="text-align: right; font-size: 1.2em; padding-right: 3px;">'.$row_linii['tva'].'</div></td>
			</tr>
		';
		if ($rf['tva'] == 0) echo '
			<tr id="'.$i.'">
				<td class="toggle-row"><span id="icon'.$i.'" class="ui-icon ui-icon-circle-minus toggle-margin" onclick="remove_row('.$i.')" tips=\'<div id="toggle-tips1" class="span3 tips-x">Sterge linie</div>\'></span></td>
				<td><textarea class="rand span8 afters produs" id="produs'.$i.'" jsn="produs" id_produs="'.$row_linii['id_produs'].'" acces="1">'.$row_linii['denumire'].'</textarea></td>
				<td><input value="'.$row_linii['unitate'].'" type="text" class="rand span3 after-s" id="um'.$i.'" um="unitate" jsn="um"></td>
				<td><input value="'.$row_linii['q'].'" type="text" class="rand span3 after-s cantitate" id="q'.$i.'" jsn="q" style="text-align: right;" onfocus="init_cantitate('.$i.')" onkeyup="calc_linie('.$i.')" autocomplete="off"></td>
				<td><input value="'.$row_linii['pret'].'" type="text" class="rand span3 after-s pret" id="pret'.$i.'" jsn="pret" style="text-align: right;" onkeyup="calc_linie('.$i.')" autocomplete="off"></td>
				<td><div class="val" alt="n9p3c2S" id="val'.$i.'" style="text-align: right; font-size: 1.2em; padding-right: 3px;">'.$row_linii['val'].'</div></td>
				<td><div class="tva ui-helper-hidden" alt="n9p3c2S" id="tva'.$i.'" style="text-align: right; font-size: 1.2em; padding-right: 3px;">'.$row_linii['tva'].'</div></td>
			</tr>
		';
		$i++;
	}
	if ($rf['tva'] == 1) echo '
		</table>
		<table border="0" id="tabel2">
			<tr style="font-weight: bold; font-size: 1.3em; text-align: right;">
				<td class="row span-9" style="text-align: left; padding-right: 40px;">
					<div class="box-button-plus">
						<button class="fg-button-plus orange ui-corner-all" onclick="add_row(); return false;" tips=\'<div style="text-align: center; font-weight: bold; font-size: 1.1em;">Adauga linie</div>\'><span style="font-size: 1.2em;">+</span></button>
					</div>
				</td>
				<td class="row span-5 ttl-subtotal">Subtotal</td>
				<td class="span-3 ttl-valoare"><div id="total-valoare" alt="n9p3c2S">'.$row['total_valoare'].'</div></td>
				<td class="span-3 ttl-tva"><div id="total-tva" alt="n9p3c2S">'.$row['total_tva'].'</div></td>
			</tr>
			<tr style="font-weight: bold; font-size: 1.3em; text-align: right;">
				<td colspan="2" class="row span-15" id="ttl-general">Total General</td>
				<td colspan="2" class="ttl-general-2"><div id="total-general" alt="n9p3c2S">'.$row['total_general'].'</div></td>
			</tr>
		</table>
		<div class="span-13"></div>
	</div>
	<div class="span-22 last" style="margin: 10px 0 10px 30px;">
	';
	if ($rf['tva'] == 0) echo '
		</table>
		<table border="0" id="tabel2">
			<tr style="font-weight: bold; font-size: 1.3em; text-align: right;">
				<td class="row span-9" style="text-align: left; padding-right: 40px;">
					<div class="box-button-plus">
						<button class="fg-button-plus orange ui-corner-all" onclick="add_row(); return false;" tips=\'<div style="text-align: center; font-weight: bold; font-size: 1.1em;">Adauga linie</div>\'><span style="font-size: 1.2em;">+</span></button>
					</div>
				</td>
				<td class="row span-8 ttl-subtotal">Subtotal</td>
				<td class="span-3 ttl-valoare"><div id="total-valoare" alt="n9p3c2S">'.$row['total_valoare'].'</div></td>
				<td class="span-3 ttl-tva ui-helper-hidden"><div id="total-tva" alt="n9p3c2S">'.$row['total_tva'].'</div></td>
			</tr>
			<tr style="font-weight: bold; font-size: 1.3em; text-align: right;">
				<td colspan="2" class="row span-15" id="ttl-general">Total General</td>
				<td colspan="1" class="ttl-general-2"><div id="total-general" alt="n9p3c2S">'.$row['total_general'].'</div></td>
			</tr>
		</table>
		<div class="span-13"></div>
	</div>
	<div class="span-22 last" style="margin: 10px 0 10px 30px;">
	';
	
		if (isset($_GET['id_draft'])) echo '
		<div class="span-21 last" style="text-align: right;"><button class="fg-button orange ui-corner-all" onclick="emitere();" type="submit"><span class="button-text">Emite factura</span></button></div>
		<div class="ui-helper-hidden" id="id_factura" id_draft="'.$_GET['id_draft'].'">'.$_GET['id_draft'].'</div>
		';
		if (isset($_GET['id_factura'])){
			$si = $db->query('select * from incasare where id_factura="'.$_GET['id_factura'].'" order by data_add desc');
			if (mysql_num_rows($si) > 0){
				while ($ri = mysql_fetch_array($si)){
					$inc0 = str_replace('.','',$ri['suma']);
					$inc1 = str_replace(',','.',$inc0);
					$incasare += $inc1;
				}
			}
			else $incasare = 0;
			echo '
			<div class="span-21 last" style="text-align: right;"><button class="fg-button orange ui-corner-all" onclick="emitere()" type="submit" style="padding: .45em 1.5em;"><span class="button-text">Salvare modificari</span></button></div>
			<div class="span-1 box-loading ui-helper-hidden"></div>
			<div class="ui-helper-hidden" id="incasare">'.$incasare.'</div>
			<div class="ui-helper-hidden" id="rest-plata" alt="n9p3c2S"></div>
			<div class="ui-helper-hidden" id="id_factura" id_draft="0">'.$_GET['id_factura'].'</div>
			';
		}
	echo '
	</div>	
</div>
</div>
</form>
';
?>
</div>
<?php include 'menu_facturi_editare.php'; ?>
</div>