<div class="container">
<div class="span-22 last" style="padding-left: 30px;">
<script>
$(document).ready(function(){
	$('[tips]').qtip({
		position:{
			corner:{
				target: 'rightMiddle',
				tooltip: 'leftMiddle'
			}
		},
		style:{
			width: 136,
			name: 'light',
			tip: 'leftMiddle',
			border: {
				width: 2,
				radius: 4
			}
		}
	});
});
function defaults(){
	$('.orange, .white').hover(
		function(){	$(this).find('.button-text').addClass('underline'); },
		function(){	$(this).find('.button-text').removeClass('underline'); }
	);
	$('.list-container').hover(
		function(){
			$(this).find('.linc').removeClass('ui-helper-hidden');
			$(this).addClass('ui-state-default ui-corner-all');
		},
		function(){
			$(this).find('.linc').addClass('ui-helper-hidden');
			$(this).removeClass('ui-state-default ui-corner-all');
		}
	);
}
function details(id){
	if ($('#detalii'+id).length == 1 && !$('#emitere'+id).find('.button-text').hasClass('underline') && !$('#mod'+id).find('.button-text').hasClass('underline')){
		$('#details-'+id).removeClass('ui-helper-hidden');
		$('#detalii'+id).attr('id','hide-detalii'+id);
		return;
	}
	if ($('#hide-detalii'+id).length == 1 && !$('#emitere'+id).find('.button-text').hasClass('underline') && !$('#mod'+id).find('.button-text').hasClass('underline')){
		$('#details-'+id).addClass('ui-helper-hidden');
		$('#hide-detalii'+id).attr('id','detalii'+id);
		return;
	}
}
function add(denumire){
<?php
$src = '/'.$subdomeniu.'/module_verifica_furnizori/'.$_GET['idf'].'/';
echo 'var src = "'.$src.'";';
?>
	var query = '?furnizor=1';
	if (denumire) query += '&denumire='+denumire;
	$("#dialog_add").dialog('destroy'); 
	$("#dialog_add").remove();
	$("body").append('<div id="dialog_add" title="<div class=\'span-22\' style=\'text-align: center;\'>Adaugare furnizor</div>"><iframe src="'+src+query+'" width="900" height="650" frameborder="0" border="0"></iframe>');
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
function modify(id){
<?php
$src = '/'.$subdomeniu.'/popup_mod_furnizori/'.$_GET['idf'].'/';
echo 'var src = "'.$src.'";';
?>
	var query = '?furnizor=1&id_firma='+id;
	$("#dialog_modify").dialog('destroy'); 
	$("#dialog_modify").remove();
	$("body").append('<div id="dialog_modify" title="<div class=\'span-22\' style=\'text-align: center;\'>Modificare informatii furnizor</div>" style="text-align:left"><iframe src="'+src+query+'" width="900" height="520" frameborder="0" border="0"></iframe>');
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
function query_furnizor(id){
	$.ajax({type:'GET',dataType:"json",url:'/includes/functii.php?op=query_furnizor&id_firma='+id,success:function(raspuns){
			$('#container-pages').find("#denumire"+id).html(raspuns.denumire);
			$('#container-pages').find("#cif"+id).html(raspuns.cif);
			$('#container-pages').find("#adresa"+id).html(raspuns.adresa);
			$('#container-pages').find("#banca"+id).html(raspuns.banca);
			$('#container-pages').find("#iban"+id).html(raspuns.iban);
			$('#container-pages').find("#reg_com"+id).html(raspuns.reg_com);
			if (raspuns.tva == 1) $('#container-pages').find("#tva"+id).html('Platitor de TVA');
			if (raspuns.tva == 0) $('#container-pages').find("#tva"+id).html('Neplatitor de TVA');
		}
	});
	if ($('#detalii'+id).length == 1) details(id);
}
function init_pagination(ttl,curr){
	if (ttl > 8){
		$("#pagination").pagination(ttl, {
			items_per_page: 8,
			num_edge_entries: 1,
			num_display_entries: 8,
			ellipse_text: "...",
			prev_text: "",
			next_text: "",
			current_page: curr,
			callback: init_query
		});
	}
}
function init_query(i,obj){
	if (i == -1) i = 0;
	var limit = (i*8);
	order = 'denumire asc';
	if ($('#search').val().length <= 2){
		if ($('#search').attr('search') == '1'){
			$('#pagination').removeClass('ui-state-disabled');
			$('#search').attr('search','0');
			if (($('#pagination > .current').html()-1) == -1) limit = 0;
			else limit = (($('#pagination > .current').html()-1)*8);
		}
		query_furnizori(order,limit);
	}
	if ($('#search').val().length > 2) search_furnizori(order);
}
function query_furnizori(order,limit){
	if ($('.box-functii').hasClass('.ui-state-disabled')){
		$('.box-functii').removeClass('ui-state-disabled');
		$('#search').attr('readonly','');
	}
	var query = '&order='+order+'&limit='+limit;
	$.ajax({type:'GET',dataType:'json',url:'/includes/functii.php?op=query_furnizori'+query,success:function(raspuns){
			$('#container-pages').html('');
			$.each(raspuns.furnizori,function(i,obj){
				var content;
				if (i%2 == 0) content += '<div class="span-22 list-container even ui-corner-all" id="detalii'+obj.id+'" onclick="details('+obj.id+')">';
				else content += '<div class="span-22 list-container ui-corner-all" id="detalii'+obj.id+'" onclick="details('+obj.id+')">';
				content +=
	'<div class="span-22"><div id="denumire'+obj.id+'" class="span-15 list-header" style="text-transform: uppercase; cursor: pointer !important;">'+obj.denumire+'</div>'+
	'<div class="span-3 ui-helper-hidden linc"><button id="emitere'+obj.id+'" class="fg-button-s orange ui-corner-all button-mod span31" onclick="emitere('+obj.id+')"><span class="button-text">Emite factura</span></button></div>'+
	'<div class="span-2 ui-helper-hidden linc"><button id="mod'+obj.id+'" class="fg-button-s white ui-corner-all button-mod span-2" onclick="modify('+obj.id+')"><span class="button-text">Modifica</span></button></div></div>'+
	'<div class="span-20 list-content">'+
		'<div id="cif'+obj.id+'" class="span-9 last uppercase" style="cursor: pointer !important;">'+obj.cif+'</div>'+
		'<div class="span-13 ui-helper-hidden box-details" id="details-'+obj.id+'">'+
			'<div class="span-13 box-margin"><div class="span2 line-left">Adresa: </div><div id="adresa'+obj.id+'" class="span-10 capitalize-b">'+obj.adresa+'</div></div>'+
			'<div class="span-13 box-margin"><div class="span2 line-left">Reg Com: </div><div id="reg_com'+obj.id+'" class="span-8 uppercase-b">'+obj.reg_com+'</div></div>'+
			'<div class="span-13 box-margin"><div class="span2 line-left">Banca: </div><div id="banca'+obj.id+'" class="span-10 capitalize-b">'+obj.banca+'</div></div>'+
			'<div class="span-13 box-margin"><div class="span2 line-left">IBAN: </div><div id="iban'+obj.id+'" class="span-10 uppercase-b">'+obj.iban+'</div></div>'+
			'<div class="span-13 box-margin"><div class="span3 line-left">Data adaugarii: </div><div id="data_add'+obj.id+'" class="span-8 normal-b">'+obj.data_add+'</div></div>';
				if (obj.tva == 1) tva = 'Platitor de TVA';
				else tva = 'Neplatitor de TVA';
				content +=
			'<div class="span-13"><div id="tva'+obj.id+'" class="span-8 normal-b">'+tva+'</div></div>'+
		'</div>'+
	'</div>'+
'</div>';
				$(content).appendTo('#container-pages');
			});
			if (raspuns.ttl % 8 == 1) init_pagination(raspuns.ttl,($('#pagination > .current').html()-1));
			raspuns.ttl == 1 ? $('#ttl').html('<span style="margin-right: 1px;">Total </span> <strong>1</strong> furnizor') : $('#ttl').html('<span style="margin-right: 1px;">Total </span>'+ro(raspuns.ttl)+' furnizori');
			defaults();
		}
	});
}
function search_furnizori(order){
	var query = '&denumire='+$('#search').val()+'&order='+order;
	$.ajax({type:'GET',dataType:'json',url:'/includes/functii.php?op=search_furnizori'+query,success:function(raspuns){
			$('#container-pages').html('');
			$('#pagination').addClass('ui-state-disabled');
			$.each(raspuns,function(i,obj){
				if (obj.id){
					var content;
					if (i%2 == 0) content += '<div class="span-22 list-container even ui-corner-all" id="detalii'+obj.id+'" onclick="details('+obj.id+')">';
					else content += '<div class="span-22 list-container ui-corner-all" id="detalii'+obj.id+'" onclick="details('+obj.id+')">';
					content +=
'<div class="span-22"><div id="denumire'+obj.id+'" class="span-15 list-header" style="text-transform: uppercase; cursor: pointer !important;">'+obj.denumire+'</div>'+
	'<div class="span-3 ui-helper-hidden linc"><button id="emitere'+obj.id+'" class="fg-button-s orange ui-corner-all button-mod span31" onclick="emitere('+obj.id+')"><span class="button-text">Emite factura</span></button></div>'+
	'<div class="span-2 ui-helper-hidden linc"><button id="mod'+obj.id+'" class="fg-button-s white ui-corner-all button-mod span-2" onclick="modify('+obj.id+')"><span class="button-text">Modifica</span></button></div></div>'+
	'<div class="span-20 list-content">'+
		'<div id="cif'+obj.id+'" class="span-9 last uppercase" style="cursor: pointer !important;">'+obj.cif+'</div>'+
		'<div class="span-13 ui-helper-hidden box-details" id="details-'+obj.id+'">'+
			'<div class="span-13 box-margin"><div class="span2 line-left">Adresa: </div><div id="adresa'+obj.id+'" class="span-10 capitalize-b">'+obj.adresa+'</div></div>'+
			'<div class="span-13 box-margin"><div class="span2 line-left">Reg Com: </div><div id="reg_com'+obj.id+'" class="span-8 uppercase-b">'+obj.reg_com+'</div></div>'+
			'<div class="span-13 box-margin"><div class="span2 line-left">Banca: </div><div id="banca'+obj.id+'" class="span-10 capitalize-b">'+obj.banca+'</div></div>'+
			'<div class="span-13 box-margin"><div class="span2 line-left">IBAN: </div><div id="iban'+obj.id+'" class="span-10 uppercase-b">'+obj.iban+'</div></div>'+
			'<div class="span-13 box-margin"><div class="span3 line-left">Data adaugarii: </div><div id="data_add'+obj.id+'" class="span-8 normal-b">'+obj.data_add+'</div></div>';
				if (obj.tva == 1) tva = 'Platitor de TVA';
				else tva = 'Neplatitor de TVA';
				content +=
			'<div class="span-13"><div id="tva'+obj.id+'" class="span-8 normal-b">'+tva+'</div></div>'+
		'</div>'+
	'</div>'+
'</div>';
					$(content).appendTo('#container-pages');
				}
				else $('<div class="span-19 err-s">Nu a fost gasit niciun furnizor cu denumirea <span class="uppercase-b">'+$('#search').val()+'</span>. <a href="javascript:add(\''+$('#search').val()+'\')" style="margin-left: 5px;">Adauga furnizorul <span class="uppercase-b">'+$('#search').val()+'</span> acum</a></div>').appendTo('#container-pages');
			});
			$('.list-container').length == 1 ? $('#ttl').html('<strong>1</strong> furnizor gasit') : $('#ttl').html('<strong>'+$('.list-container').length+'</strong> furnizori gasiti');
			$('#search').attr('search','1');
			defaults();
		}
	});
}
<?php
echo '
function emitere(id){
	document.location.href = "/'.$subdomeniu.'/facturi-emitere/'.$_GET['idf'].'/?furnizor="+id;
}
';
?>
</script>
<div class="span-22 ui-widget-header ui-corner-top">
	<div class="header">Furnizori</div>
</div>
<?php
$sql = $db->query('select count(*) as ttl from firme where id_user="'.$_GET['idf'].'" and tip_firma="0"');
$row = mysql_fetch_array($sql);
if ($row['ttl'] == 0) echo '
<div class="interior">
	<div class="span-22 box-top ui-widget-content">
		<div class="span-20 box-functii ui-state-disabled">
			<div class="span-1 before-n">Cautare</div>
			<div class="span-7">
				<input class="after-s span-6" style="font-size: 1em; text-transform: uppercase;" type="text" id="search" search="0" readonly="readonly" onkeyup="init_query(($(\'#pagination > .current\').html()-1),\'pagination\')" autocomplete="off">
			</div>
		</div>
		<div class="box-add img-add" tips="<div class=\'tips-add\'>Adauga furnizor</div>" onclick="add()"></div>
	</div>
</div>
<div class="span-22" id="container-pages" style="min-height: 500px;">
	<div class="span-19 err">
		<span style="margin-right: 5px;">Momentan nu ai niciun furnizor adaugat. </span>
		<a href="javascript:add()">Adauga un furnizor acum</a>
	</div>
</div>
	';
else{
	echo '
<script>
$(document).ready(function(){
	$("#search").focus();
	$(".list-container").hover(
		function(){
			$(this).find(".linc").removeClass("ui-helper-hidden");
			$(this).addClass("ui-state-default ui-corner-all");
		},
		function(){
			$(this).find(".linc").addClass("ui-helper-hidden");
			$(this).removeClass("ui-state-default ui-corner-all");
		}
	);
});
</script>
<div class="interior">
	<div class="span-22 box-top ui-widget-content">
		<div class="span-20 box-functii">
			<div class="span-1 before-n">Cautare</div>
			<div class="span-7">
				<input class="after-s span-6" style="font-size: 1em; text-transform: uppercase;" type="text" id="search" onkeyup="init_query(($(\'#pagination > .current\').html()-1),\'pagination\')" search="0" autocomplete="off">
			</div>
		</div>
		<div class="box-add img-add" tips="<div class=\'tips-add\'>Adauga furnizor</div>" onclick="add()"></div>
	</div>
</div>
<div class="span-22" id="container-pages" style="min-height: 500px;">
	';

$i=0;
$sqls = $db->query('select * from firme where id_user="'.$_GET['idf'].'" and tip_firma="0" order by denumire asc limit 8');
while ($rows = mysql_fetch_array($sqls)){
	if ($rows['tva'] == 1) $tva = 'Platitor de TVA';
	else $tva = 'Neplatitor de TVA';
	if ($i%2 == 0) echo '<div class="span-22 list-container even" id="detalii'.$rows['id_firma'].'" onclick="details('.$rows['id_firma'].')">';
	else echo '<div class="span-22 list-container" id="detalii'.$rows['id_firma'].'" onclick="details('.$rows['id_firma'].')">';
	echo
'
	<div class="span-22">
		<div id="denumire'.$rows['id_firma'].'" class="span-15 list-header" style="text-transform: uppercase; cursor: pointer !important;">'.$rows['denumire'].'</div>
		<div class="span-3 ui-helper-hidden linc"><button id="emitere'.$rows['id_firma'].'" class="fg-button-s orange ui-corner-all button-mod span31" onclick="emitere('.$rows['id_firma'].')"><span class="button-text">Emite factura</span></button></div>
		<div class="span-2 ui-helper-hidden linc"><button id="mod'.$rows['id_firma'].'" class="fg-button-s white ui-corner-all button-mod span-2" onclick="modify('.$rows['id_firma'].')"><span class="button-text">Modifica</span></button></div>
	</div>
	<div class="span-20 list-content">
		<div id="cif'.$rows['id_firma'].'" class="span-9 last uppercase" style="cursor: pointer !important;">'.$rows['cif'].'</div>
		<div class="span-13 ui-helper-hidden box-details" id="details-'.$rows['id_firma'].'">
			<div class="span-13 box-margin">
				<div class="span2 line-left">Adresa: </div>
				<div id="adresa'.$rows['id_firma'].'" class="span-10 capitalize-b">'.$rows['adresa'].'</div>
			</div>
			<div class="span-13 box-margin">
				<div class="span2 line-left">Reg Com: </div>
				<div id="reg_com'.$rows['id_firma'].'" class="span-8 uppercase-b">'.$rows['reg_com'].'</div>
			</div>
			<div class="span-13 box-margin">
				<div class="span2 line-left">Banca: </div>
				<div id="banca'.$rows['id_firma'].'" class="span-10 capitalize-b">'.$rows['banca'].'</div>
			</div>
			<div class="span-13 box-margin">
				<div class="span2 line-left">IBAN: </div>
				<div id="iban'.$rows['id_firma'].'" class="span-10 uppercase-b">'.$rows['iban'].'</div>
			</div>
			<div class="span-13 box-margin">
				<div class="span3 last line-left">Data adaugarii: </div>
				<div id="data_add'.$rows['id_firma'].'" class="span-8 normal-b">'.convert_data(date('d-m-Y',strtotime($rows['data_add']))).'</div>
			</div>
			<div class="span-13">
				<div id="tva'.$rows['id_firma'].'" class="span-8 normal-b">'.$tva.'</div>
			</div>
		</div>
	</div>
</div>';
	$i++;
}
echo '</div>';
}
echo '
<div class="interior">
	<div class="span-21 box-bottom ui-widget-content" style="margin-top: 20px;">
		<div id="pagination" class="span-13 pagination"></div>
		<div id="ttl" class="span-5 ttl" style="float: right;">
';
$row['ttl'] == 1 ? $result = '<strong>1</strong> furnizor' : $result = ro($row['ttl']).' furnizori';
echo '
		<span style="margin-right: 1px;">Total</span> '.$result.'
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
init_pagination('.$row['ttl'].',0);
});
</script>
';
?>
</div>
<!-- End container-m -->
</div>