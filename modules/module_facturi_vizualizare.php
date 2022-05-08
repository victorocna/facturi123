<?php
$xml = '../useri/'.$subdomeniu.'/'.$_GET['id_furnizor'].'/xml/'.$_GET['factura'].'.xml';
$factura = xml2array($xml);
$cota_tva = $factura['facturi']['adv']['cota_tva'];
$valuta = $factura['facturi']['adv']['valuta'];
$id_furnizor = $factura['facturi']['furnizor_attr']['id'];

$sc = $db->query('select * from firme where id_firma="'.$factura['facturi']['client_attr']['id'].'" and tip_firma="1"');
$rc = mysql_fetch_array($sc);
$sf = $db->query('select * from firme where id_firma="'.$factura['facturi']['furnizor_attr']['id'].'" and tip_firma="0"');
$rf = mysql_fetch_array($sf);
?>
<div class="container">
<div class="span-22 last" style="padding-left: 20px;">
<script>
$(document).ready(function(){
	$('#adress').jBreadCrumb();
	$('.after, .after-s, .after-adv, .after-xs, .afters-default').focus(
		function(){ $(this).css('border', 'solid 1px #f3c809'); }
	).blur(
		function(){ $(this).css('border', 'solid 1px #ccc'); }
	);
	$('.afters-default').qtip({
		content: '<div style="text-align: center;">Aici puteti scrie mentiuni, alte observatii sau un mesaj pentru client</div>',
		position:{
			corner:{
				target: 'leftMiddle',
				tooltip: 'rightMiddle'
			}
		},
		style:{
			width: 180,
			name: 'light',
			tip:{
				corner:'rightMiddle',
				size:{ x:12, y:10 }
			},
			border:{ width: 3, radius: 4 }
		},
		hide:{
			when: 'blur'
		},
		show:{
			when: 'focus'
		}
	}).textareaDefault();
<?php
if (isset($_GET['print_factura'])) echo 'print_factura();';
if (isset($_GET['email'])) echo 'email();';
if (isset($_GET['incasare'])) echo 'incasare();';
if (isset($_GET['history'])) echo 'history();';
?>
});

function print_factura(){
	var query = 'popup_factura_';
	if ($('#sablon-normal').is(':visible')) query += 'normal';
	else query += 'modern';
<?php
$query = "/".$_GET['idf']."/".$_GET['id_furnizor']."/".$_GET['factura']."/?subdomeniu=".$subdomeniu;
$title = "<div class='span-23' style='text-align: center;'>Tiparire factura <span class='uppercase'>".str_replace('-',' ',$_GET['factura'])."</span></div>";
$popup = '_'.$rf['tva'];
echo '
	var src = "/'.$subdomeniu.'/";
	var title = "'.$title.'";
	query += "'.$popup.$query.'";
';
?>
	$("#dialog_print").dialog('destroy'); 
	$("#dialog_print").remove();
	$("body").append('<div id="dialog_print" title="'+title+'" style="text-align:left"><iframe src="'+src+query+'" width="930" height="620" frameborder="0" border="0"></iframe></div>');
	$("#dialog_print").show(); 
	$("#dialog_print").dialog({
			height: 700,
			width: 980,
			closeOnEscape: true,
			modal:true, 
			resizable: false, 
			overlay:{ 
					"background-color": "#333", 
					"opacity": "0.75", 
					"-moz-opacity": "0.75" 
			}
	});
}
function print_chitanta(id_chitanta,serie,numar){
<?php
$query = "/".$subdomeniu."/popup_chitanta/".$_GET['idf']."/".$_GET['id_furnizor']."/".$_GET['factura']."/";
echo 'var query = "'.$query.'";';
?>
	query += '?id_chitanta='+id_chitanta;
	var title = "<div class='span-23' style='text-align: center;'>Tiparire chitanta <span class='uppercase'>"+serie+" "+numar+"</span></div>";
	$("#dialog_cht").dialog('destroy'); 
	$("#dialog_cht").remove();
	$("body").append('<div id="dialog_cht" title="'+title+'" style="text-align:left"><iframe src="'+query+'" width="930" height="470" frameborder="0" border="0"></iframe></div>');
	$("#dialog_cht").show();
	$("#dialog_cht").dialog({
			height: 550,
			width: 980,
			closeOnEscape: true,
			modal:true,
			resizable: false,
			overlay:{
					"background-color": "#333",
					"opacity": "0.75",
					"-moz-opacity": "0.75"
			}
	});
}
function incasare(){
<?php
$src = "/".$subdomeniu."/popup_incasare/".$_GET['idf']."/".$_GET['id_furnizor']."/".$_GET['factura']."/";
$title = "<div class='span-18' style='text-align: center;'>Incasare factura <span class='uppercase'>".str_replace('-',' ',$_GET['factura'])."</span></div>";
echo '
	var src = "'.$src.'";
	var title = "'.$title.'";
';
?>
	if (!$('.icon-incasare').hasClass('.priority-secondary')){
		$("#dialog_incasare").dialog('destroy'); 
		$("#dialog_incasare").remove();
		$("body").append('<div id="dialog_incasare" title="'+title+'" style="text-align:left"><iframe src="'+src+'" width="730" height="500" frameborder="0" border="0"></iframe></div>');
		$("#dialog_incasare").show(); 
		$("#dialog_incasare").dialog({
				height: 600,
				width: 800,
				modal:true, 
				resizable: false, 
				overlay:{ 
						"background-color": "#333", 
						"opacity": "0.75", 
						"-moz-opacity": "0.75" 
				}
		});
	}
}
function email(){
	if ($('#sablon-normal').is(':visible')) tip_factura = 'normal';
	else tip_factura = 'modern';
<?php
$src = "/".$subdomeniu."/popup_email/".$_GET['idf']."/".$_GET['id_furnizor']."/".$_GET['factura']."/?tva=".$rf['tva'];
$title = "<div class='span-18' style='text-align: center;'>Trimitere factura <span class='uppercase'>".str_replace('-',' ',$_GET['factura'])."</span> prin email</div>";
echo '
	var src = "'.$src.'";
	var title = "'.$title.'";
';
?>
	src += '&tip_factura='+tip_factura;
	$("#dialog_email").dialog('destroy'); 
	$("#dialog_email").remove();
	$("body").append('<div id="dialog_email" title="'+title+'" style="text-align:left"><iframe src="'+src+'" width="750" height="520" frameborder="0" border="0"></iframe></div>');
	$("#dialog_email").show();
	$("#dialog_email").dialog({
			height: 600,
			width: 800,
			modal:true,
			resizable: false,
			overlay:{
					"background-color": "#333",
					"opacity": "0.75",
					"-moz-opacity": "0.75"
			}
	});
}
function history(){
<?php
$src = "/".$subdomeniu."/popup_history/".$_GET['idf']."/".$_GET['id_furnizor']."/".$_GET['factura']."/?id_factura=".$factura['facturi_attr']['id'];
$title = "<div class='span-18' style='text-align: center;'>Istoric factura <span class='uppercase'>".str_replace('-',' ',$_GET['factura'])."</span></div>";
echo '
	var src = "'.$src.'";
	var title = "'.$title.'";
';
?>
	$("#dialog_history").dialog('destroy');
	$("#dialog_history").remove();
	$("body").append('<div id="dialog_history" title="'+title+'" style="text-align:left"><iframe src="'+src+'" width="730" height="470" frameborder="0" border="0"></iframe></div>');
	$("#dialog_history").show();
	$("#dialog_history").dialog({
			height: 600,
			width: 800,
			modal:true,
			resizable: false,
			overlay:{
					"background-color": "#333",
					"opacity": "0.75",
					"-moz-opacity": "0.75"
			},
			buttons:{
				"Iesire":function(){
					$(this).dialog('close');
				}
			}
	});
}

function change(){
	if ($('#sablon').val() == '1'){
		$('#sablon-modern').addClass('ui-helper-hidden');
		$('#sablon-normal').removeClass('ui-helper-hidden');
		$('#text-client-n').trigger('keyup');
	}
	else{
		$('#sablon-normal').addClass('ui-helper-hidden');
		$('#sablon-modern').removeClass('ui-helper-hidden');
		$('#text-client-m').trigger('keyup');
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
	//2 textarea
	if ($(obj).attr('id') == 'text-client-n') $('#text-client-m').val($(obj).val());
	if ($(obj).attr('id') == 'text-client-m') $('#text-client-n').val($(obj).val());
	// text salvat
<?php if ($factura['facturi']['text_client']) { ?>
	if (i == 0){
		$('.text-save').addClass('ui-helper-hidden');
		$('.text-save-null').removeClass('ui-helper-hidden');
	}
	else if ($('.text-save').hasClass('.ui-helper-hidden')){
		$('.text-save-null').addClass('ui-helper-hidden');
		$('.text-save').removeClass('ui-helper-hidden');
	}
<?php } ?>
}

function update_text_client(){
	if ($('.afters-default').val()){
<?php
$query = "&id_furnizor=".$_GET['id_furnizor']."&id_factura=".$factura['facturi_attr']['id']."&factura=".$_GET['factura'];
echo 'var query = "'.$query.'";';
?>
		var text_client = $('.afters-default').val().replace(new RegExp( "\\n", "g" ),'<br>');
		query += '&text_client='+text_client;
		$.ajax({type:'GET',url:'/includes/functii.php?op=update_text_client'+query,success:function(raspuns){
			if (raspuns) notify_bars(10,raspuns);
		}
		});
	}
	else notify_bar(5,'Scrieti un mesaj de 160 de caractere inainte de a salva!');
}
function delete_text_client(){
<?php
$query = "&id_furnizor=".$_GET['id_furnizor']."&id_factura=".$factura['facturi_attr']['id']."&factura=".$_GET['factura'];
echo 'var query = "'.$query.'";';
?>
	$.ajax({type:'GET',url:'/includes/functii.php?op=delete_text_client'+query,success:function(raspuns){
		$('#text-save-null').addClass('ui-helper-hidden');
		$('#text-save').removeClass('ui-helper-hidden');
		notify_bar(10,raspuns);
	}
	});
}
</script>
<?php
if (isset($factura['facturi']['adv']['data_scadenta']) && strtotime($factura['facturi']['adv']['data_scadenta']) < strtotime(date('d-m-Y')) && (!isset($factura['facturi']['rest_plata']) || $factura['facturi']['rest_plata'] != 0)){
	echo '
<div class="breadCrumbHolder module span-8">
	<div id="adress" class="breadCrumb module span-8 ui-corner-all" style="border-width: 0;">
		<div class="span-11"><ul>
			<li><a href="/'.$subdomeniu.'/facturi/'.$_GET['idf'].'/">Facturi</a></li>
	';
	if (isset($_GET['add'])) echo '
			<li><a href="/'.$subdomeniu.'/facturi-emitere/'.$_GET['idf'].'/">Emitere factura</a></li>
	';
	echo '
			<li class="ui-state-active">Vizualizare factura</li>
		</ul></div>
	</div>
</div>
<div class="span-5 box-restant">
	<div class="icon-restant"></div>
	<div class="span4 text-restant ui-state-error-text">Factura restanta</div>
</div>
';
}
else{
		echo '
<div class="breadCrumbHolder module span-13">
	<div id="adress" class="breadCrumb module span-13 ui-corner-all" style="border-width: 0;">
		<div class="span-11"><ul>
			<li><a href="/'.$subdomeniu.'/facturi/'.$_GET['idf'].'/">Facturi</a></li>
	';
	if (isset($_GET['add'])) echo '
			<li><a href="/'.$subdomeniu.'/facturi-emitere/'.$_GET['idf'].'/">Emitere factura</a></li>
	';
	echo '
			<li class="ui-state-active">Vizualizare factura</li>
		</ul></div>
	</div>
</div>
';
}
$sql = $db->query('select * from useri where id_user="'.$_GET['idf'].'"');
$row = mysql_fetch_array($sql);
if ($row['id_tip'] != 1) {
	echo '
<div class="span-8 last" style="margin: 0px 0 10px 15px;">
	<div class="span-3 last ui-state-hover" id="sablon-text">Model factura</div>
	<div class="span-5 last">
		<select class="span-4 after-adv" style="font-size: 1.1em;" id="sablon" onchange="change()">
			<option value="1">Normal</option>
			<option value="2">Modern</option>
		</select>
	</div>
</div>
	';
}

@include 'module_facturi_normal_'.$rf['tva'].'.php';
if ($row['id_tip'] != 1)
@include 'module_facturi_modern_'.$rf['tva'].'.php';

?>
</div>
<?php
if ($rf['tva'] != '')
include 'menu_facturi_finalizare.php';
?>
</div>