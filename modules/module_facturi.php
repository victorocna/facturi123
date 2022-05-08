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
			width: 130,
			name: 'light',
			tip: 'leftMiddle',
			border: {
				width: 2,
				radius: 4
			}
		}
	});
	$('.list-options').hover(
		function(){ $(this).addClass('ui-state-highlight').removeClass('ui-state-hover'); },
		function(){ $(this).addClass('ui-state-hover').removeClass('ui-state-highlight'); }
	).click(function(){
		if ($('#box-options'+$(this).attr('id')).hasClass('.ui-helper-hidden')){
			$('#container-pages').find('.list-options').removeClass('ui-state-highlight background-w ui-corner-top').addClass('ui-corner-all');
			$('#container-pages').find('.ui-corner-tr').addClass('ui-helper-hidden');
			$(this).addClass('background-w ui-corner-top').removeClass('ui-corner-all');
			$('#box-options'+$(this).attr('id')).removeClass('ui-helper-hidden');
		}
		else{
			$(this).removeClass('background-w ui-corner-top').addClass('ui-state-highlight ui-corner-all');
			$('#box-options'+$(this).attr('id')).addClass('ui-helper-hidden');
		}
	});
	$('.text-options').hover(
		function(){ $(this).addClass('ui-state-hover'); },
		function(){ $(this).removeClass('ui-state-hover'); }
	);
	$('.serie').hover(
		function(){ $(this).css('text-decoration','underline'); },
		function(){ $(this).css('text-decoration','none'); }
	).click(function(){
		if ($(this).parents('.list-container').attr('id_draft') == 0) document.location.href = '../../facturi-vizualizare/<?php echo $_GET['idf']; ?>/'+$('#furnizor').attr('iid')+'/'+$(this).parents('.list-container').attr('factura')+'/';
		else document.location.href = '../../facturi-editare/<?php echo $_GET['idf']; ?>/D'+$(this).parents('.list-container').attr('id_draft')+'/';
	});
});
function defaults(){
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
	$('.list-options').hover(
		function(){ $(this).addClass('ui-state-highlight').removeClass('ui-state-hover'); },
		function(){ $(this).addClass('ui-state-hover').removeClass('ui-state-highlight'); }
	).click(function(){
		if ($('#box-options'+$(this).attr('id')).hasClass('.ui-helper-hidden')){
			$('#container-pages').find('.list-options').removeClass('ui-state-highlight background-w ui-corner-top').addClass('ui-corner-all');
			$('#container-pages').find('.ui-corner-tr').addClass('ui-helper-hidden');
			$(this).addClass('background-w ui-corner-top').removeClass('ui-corner-all');
			$('#box-options'+$(this).attr('id')).removeClass('ui-helper-hidden');
		}
		else{
			$(this).removeClass('background-w ui-corner-top').addClass('ui-state-highlight ui-corner-all');
			$('#box-options'+$(this).attr('id')).addClass('ui-helper-hidden');
		}
	});
	$('.text-options').hover(
		function(){ $(this).addClass('ui-state-hover'); },
		function(){ $(this).removeClass('ui-state-hover'); }
	);
	$('.serie').hover(
		function(){ $(this).css('text-decoration','underline'); },
		function(){ $(this).css('text-decoration','none'); }
	).click(function(){
		if ($(this).parents('.list-container').attr('id_draft') == 0) document.location.href = '../../facturi-vizualizare/<?php echo $_GET['idf']; ?>/'+$('#furnizor').attr('iid')+'/'+$(this).parents('.list-container').attr('factura')+'/';
		else document.location.href = '../../facturi-editare/<?php echo $_GET['idf']; ?>/D'+$(this).parents('.list-container').attr('id_draft')+'/';
	});
}
function query_furnizor(id){
	init_query('0','pagination',id);
}
function init_pagination(ttl,curr){
	if (ttl > 8){
		if ($("#pagination").hasClass('ui-helper-hidden')) $("#pagination").removeClass('ui-helper-hidden');
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
	else $("#pagination").addClass('ui-helper-hidden');
}
function init_query(i,obj,selected){
	if (i == -1) i = 0;
	var limit = (i*8);
	var order = 'facturi.numar desc';
	var id_furnizor = $('#furnizor').attr('iid');
	if ($('#search').val().length <= 2){
		if ($('#search').attr('search') == '1'){
			$('#pagination').removeClass('ui-state-disabled');
			$('#search').attr('search','0');
			if (($('#pagination > .current').html()-1) == -1) limit = 0;
			else limit = (($('#pagination > .current').html()-1)*8);
		}
		if (selected){
			query_facturi(selected,order,limit,selected);
		}
		else query_facturi(id_furnizor,order,limit);
	}
	if ($('#search').val().length > 2) search_facturi(id_furnizor,order);
}
function query_facturi(id_furnizor,order,limit,selected){
	if ($('.box-functii').hasClass('.ui-state-disabled')){
		$('.box-functii').removeClass('ui-state-disabled');
		$('#search').attr('readonly','');
	}
	var query = '&id_furnizor='+id_furnizor+'&order='+order+'&limit='+limit;
	$.ajax({type:'GET',dataType:'json',url:'/includes/functii.php?op=query_facturi'+query,success:function(raspuns){
		$('#container-pages').html('');
		if (raspuns.ttl > 0){
			$.each(raspuns.facturi,function(i,obj){
	var content;
	if (i%2 == 0) content += '<div class="span-22 list-container even" id_draft="'+obj.id_draft+'" factura="'+obj.serie.replace(' ','-')+'-'+obj.numar+'">';
	else content += '<div class="span-22 list-container" id_draft="'+obj.id_draft+'" factura="'+obj.serie.replace(' ','-')+'-'+obj.numar+'">';
	if (obj.id_draft == 0){
		if (obj.stare_incasare == 0){
			content +=
 		'<div class="span-4 facturi-serie">'+
			'<div id="'+obj.id+'" class="list-options ui-state-hover ui-corner-all"><div class="ui-icon ui-icon-triangle-1-s"></div></div>'+
			'<div class="serie span-3 uppercase">'+obj.serie+' '+obj.numar+'</div>'+
		'</div>'+
		'<div class="span-8 facturi-client uppercase">'+obj.denumire+'</div>'+
		'<div class="span-3 facturi-data">'+obj.data_factura+'</div>'+
		'<div class="span-3 facturi-ttl">'+obj.total_general+' '+obj.valuta+'</div>'+
		'<div class="span-2 sts0 ui-corner-all ui-state-error">Neincasat</div>'+
	'</div>'+
	'<div class="span-5 ui-helper-hidden box-options0 ui-state-highlight ui-corner-bottom ui-corner-tr" id="box-options'+obj.id+'">'+
		'<div class="span5 text-options ui-corner-all even"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?print_factura=1">Tiparire factura</a></div>'+
		'<div class="span5 text-options ui-corner-all"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?email=1">Trimitere email</a></div>'+
		'<div class="span5 text-options ui-corner-all even"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?incasare=1">Incasare factura</a></div>'+
		'<div class="span5 text-options ui-corner-all"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?history=1">Istoric factura</a></div>'+
		'<div class="span5 text-options ui-corner-all even"><a href="../../facturi-editare/'+obj.id_user+'/'+obj.id+'/">Editare factura</a></div>'+
	'</div>';
		}
		if (obj.stare_incasare == 1){
			content +=
 		'<div class="span-4 facturi-serie">'+
			'<div id="'+obj.id+'" class="list-options ui-state-hover ui-corner-all"><div class="ui-icon ui-icon-triangle-1-s"></div></div>'+
			'<div class="serie span-3 uppercase">'+obj.serie+' '+obj.numar+'</div>'+
		'</div>'+
		'<div class="span-8 facturi-client uppercase">'+obj.denumire+'</div>'+
		'<div class="span-3 facturi-data">'+obj.data_factura+'</div>'+
		'<div class="span-3 facturi-ttl">'+obj.total_general+' '+obj.valuta+'</div>'+
		'<div class="span-2 sts1 ui-state-hover ui-corner-all">Incasat partial</div>'+
	'</div>'+
	'<div class="span-5 ui-helper-hidden box-options0 ui-state-highlight ui-corner-bottom ui-corner-tr" id="box-options'+obj.id+'">'+
		'<div class="span5 text-options ui-corner-all even"><a href="../../facturi-vizualizare/'+obj.id_user+'&/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?print_factura=1">Tiparire factura</a></div>'+
		'<div class="span5 text-options ui-corner-all"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?email=1">Trimitere email</a></div>'+
		'<div class="span5 text-options ui-corner-all even"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?incasare=1">Incasare factura</a></div>'+
		'<div class="span5 text-options ui-corner-all"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?history=1">Istoric factura</a></div>'+
		'<div class="span5 text-options ui-corner-all even"><a href="../../facturi-editare/'+obj.id_user+'/'+obj.id+'/">Editare factura</a></div>'+
	'</div>';
		}
		if (obj.stare_incasare == 2){
			content +=
 		'<div class="span-4 facturi-serie">'+
			'<div id="'+obj.id+'" class="list-options ui-state-hover ui-corner-all"><div class="ui-icon ui-icon-triangle-1-s"></div></div>'+
			'<div class="serie span-3 uppercase">'+obj.serie+' '+obj.numar+'</div>'+
		'</div>'+
		'<div class="span-8 facturi-client uppercase">'+obj.denumire+'</div>'+
		'<div class="span-3 facturi-data">'+obj.data_factura+'</div>'+
		'<div class="span-3 facturi-ttl">'+obj.total_general+' '+obj.valuta+'</div>'+
		'<div class="span-2 sts2 ui-corner-all">Incasat</div>'+
	'</div>'+
	'<div class="span-5 ui-helper-hidden box-options1 ui-state-highlight ui-corner-bottom ui-corner-tr" id="box-options'+obj.id+'">'+
		'<div class="span5 text-options ui-corner-all even"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?print_factura=1">Tiparire factura</a></div>'+
		'<div class="span5 text-options ui-corner-all"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?email=1">Trimitere email</a></div>'+
		'<div class="span5 text-options ui-corner-all even"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?history=1">Istoric factura</a></div>'+
		'<div class="span5 text-options ui-corner-all"><a href="../../facturi-editare/'+obj.id_user+'/'+obj.id+'/">Editare factura</a></div>'+
	'</div>';
		}
		if (obj.data_scadenta != '' && (obj.stare_incasare == 0 || obj.stare_incasare == 1)){
			var data = new Date();
			var y = data.getFullYear();
			var m = new String(data.getMonth()+1); if (m.length == 1) m = '0'+m;
			var d = new String(data.getDate()); if (d.length == 1) d = '0'+d;
			var dat = y+'-'+m+'-'+d;
			if (obj.data_scadenta < dat) content += '<div class="icon-restant-mod"></div>';
		}
	}
	else{
			content +=
 		'<div class="span-4 facturi-serie">'+
			'<div id="'+obj.id+'" class="list-options ui-state-hover ui-corner-all"><div class="ui-icon ui-icon-triangle-1-s"></div></div>'+
			'<div class="serie span-3 uppercase">'+obj.serie+' '+obj.numar+'</div>'+
		'</div>'+
		'<div class="span-8 facturi-client uppercase">'+obj.denumire+'</div>'+
		'<div class="span-3 facturi-data">'+obj.data_factura+'</div>'+
		'<div class="span-3 facturi-ttl">'+obj.total_general+' '+obj.valuta+'</div>'+
		'<div class="interior"><div class="span-2 sts3 ui-corner-all ui-widget-content">Draft</div></div>'+
	'</div>'+
	'<div class="span-5 ui-helper-hidden box-options ui-state-highlight ui-corner-bottom ui-corner-tr" id="box-options'+obj.id+'">'+
		'<div class="span5 text-options ui-corner-all even"><a href="../../facturi-editare/'+obj.id_user+'/D'+obj.id_draft+'/">Emitere draft</a></div>'+
	'</div>';
	}
				$(content).appendTo('#container-pages');
			});
		}
		else{
			$('<div class="span-19 err-s">Nu a fost gasita nicio factura emisa pentru furnizorul <span class="uppercase-b">'+$('#furnizor').val()+'</span>. <a href="javascript:add_furnizor()" style="margin-left: 5px; color: #00f !important;">Emite o factura acum</a></div>').appendTo('#container-pages');
		}
			if (selected) init_pagination(raspuns.ttl,0);
			else init_pagination(raspuns.ttl,($('#pagination > .current').html()-1));
			raspuns.ttl == 1 ? $('#ttl').html('<span style="margin-right: 1px;">Total </span> <strong>1</strong> factura') : $('#ttl').html('<span style="margin-right: 1px;">Total </span>'+ro(raspuns.ttl)+' facturi');
			defaults();
		}
	});
}
function search_facturi(id_furnizor,order){
	var query = '&id_furnizor='+id_furnizor+'&denumire='+$('#search').val()+'&order='+order;
	$.ajax({type:'GET',dataType:'json',url:'/includes/functii.php?op=search_facturi'+query,success:function(raspuns){
			$('#container-pages').html('');
			$('#pagination').addClass('ui-state-disabled');
			$.each(raspuns,function(i,obj){
				if (obj.id){
	var content;
	if (i%2 == 0) content += '<div class="span-22 list-container even" id_draft="'+obj.id_draft+'" factura="'+obj.serie.replace(' ','-')+'-'+obj.numar+'">';
	else content += '<div class="span-22 list-container" id_draft="'+obj.id_draft+'" factura="'+obj.serie.replace(' ','-')+'-'+obj.numar+'">';
	if (obj.id_draft == 0){
		if (obj.stare_incasare == 0){
			content +=
 		'<div class="span-4 facturi-serie">'+
			'<div id="'+obj.id+'" class="list-options ui-state-hover ui-corner-all"><div class="ui-icon ui-icon-triangle-1-s"></div></div>'+
			'<div class="serie span-3 uppercase">'+obj.serie+' '+obj.numar+'</div>'+
		'</div>'+
		'<div class="span-8 facturi-client uppercase">'+obj.denumire+'</div>'+
		'<div class="span-3 facturi-data">'+obj.data_factura+'</div>'+
		'<div class="span-3 facturi-ttl">'+obj.total_general+' '+obj.valuta+'</div>'+
		'<div class="span-2 sts0 ui-corner-all ui-state-error">Neincasat</div>'+
	'</div>'+
	'<div class="span-5 ui-helper-hidden box-options0 ui-state-highlight ui-corner-bottom ui-corner-tr" id="box-options'+obj.id+'">'+
		'<div class="span5 text-options ui-corner-all even"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?print_factura=1">Tiparire factura</a></div>'+
		'<div class="span5 text-options ui-corner-all"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?email=1">Trimitere email</a></div>'+
		'<div class="span5 text-options ui-corner-all even"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?incasare=1">Incasare factura</a></div>'+
		'<div class="span5 text-options ui-corner-all"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?history=1">Istoric factura</a></div>'+
		'<div class="span5 text-options ui-corner-all even"><a href="../../facturi-editare/'+obj.id_user+'/'+obj.id+'/">Editare factura</a></div>'+
	'</div>';
		}
		if (obj.stare_incasare == 1){
			content +=
 		'<div class="span-4 facturi-serie">'+
			'<div id="'+obj.id+'" class="list-options ui-state-hover ui-corner-all"><div class="ui-icon ui-icon-triangle-1-s"></div></div>'+
			'<div class="serie span-3 uppercase">'+obj.serie+' '+obj.numar+'</div>'+
		'</div>'+
		'<div class="span-8 facturi-client uppercase">'+obj.denumire+'</div>'+
		'<div class="span-3 facturi-data">'+obj.data_factura+'</div>'+
		'<div class="span-3 facturi-ttl">'+obj.total_general+' '+obj.valuta+'</div>'+
		'<div class="span-2 sts1 ui-state-hover ui-corner-all">Incasat partial</div>'+
	'</div>'+
	'<div class="span-5 ui-helper-hidden box-options0 ui-state-highlight ui-corner-bottom ui-corner-tr" id="box-options'+obj.id+'">'+
		'<div class="span5 text-options ui-corner-all even"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?print_factura=1">Tiparire factura</a></div>'+
		'<div class="span5 text-options ui-corner-all"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?email=1">Trimitere email</a></div>'+
		'<div class="span5 text-options ui-corner-all even"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?incasare=1">Incasare factura</a></div>'+
		'<div class="span5 text-options ui-corner-all"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?history=1">Istoric factura</a></div>'+
		'<div class="span5 text-options ui-corner-all even"><a href="../../facturi-editare/'+obj.id_user+'/'+obj.id+'/">Editare factura</a></div>'+
	'</div>';
		}
		if (obj.stare_incasare == 2){
			content +=
 		'<div class="span-4 facturi-serie">'+
			'<div id="'+obj.id+'" class="list-options ui-state-hover ui-corner-all"><div class="ui-icon ui-icon-triangle-1-s"></div></div>'+
			'<div class="serie span-3 uppercase">'+obj.serie+' '+obj.numar+'</div>'+
		'</div>'+
		'<div class="span-8 facturi-client uppercase">'+obj.denumire+'</div>'+
		'<div class="span-3 facturi-data">'+obj.data_factura+'</div>'+
		'<div class="span-3 facturi-ttl">'+obj.total_general+' '+obj.valuta+'</div>'+
		'<div class="span-2 sts2 ui-corner-all">Incasat</div>'+
	'</div>'+
	'<div class="span-5 ui-helper-hidden box-options1 ui-state-highlight ui-corner-bottom ui-corner-tr" id="box-options'+obj.id+'">'+
		'<div class="span5 text-options ui-corner-all even"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?print_factura=1">Tiparire factura</a></div>'+
		'<div class="span5 text-options ui-corner-all"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?email=1">Trimitere email</a></div>'+
		'<div class="span5 text-options ui-corner-all even"><a href="../../facturi-vizualizare/'+obj.id_user+'/'+obj.id_furnizor+'/'+obj.serie.replace(' ','-')+'-'+obj.numar+'/?history=1">Istoric factura</a></div>'+
		'<div class="span5 text-options ui-corner-all"><a href="../../facturi-editare/'+obj.id_user+'/'+obj.id+'/">Editare factura</a></div>'+
	'</div>';
		}
		if (obj.data_scadenta != '' && (obj.stare_incasare == 0 || obj.stare_incasare == 1)){
			var data = new Date();
			var y = data.getFullYear();
			var m = new String(data.getMonth()+1); if (m.length == 1) m = '0'+m;
			var d = new String(data.getDate()); if (d.length == 1) d = '0'+d;
			var dat = y+'-'+m+'-'+d;
			if (obj.data_scadenta < dat) content += '<div class="icon-restant-mod"></div>';
		}
	}
	else{
			content +=
 		'<div class="span-4 facturi-serie">'+
			'<div id="'+obj.id+'" class="list-options ui-state-hover ui-corner-all"><div class="ui-icon ui-icon-triangle-1-s"></div></div>'+
			'<div class="serie span-3 uppercase">'+obj.serie+' '+obj.numar+'</div>'+
		'</div>'+
		'<div class="span-8 facturi-client uppercase">'+obj.denumire+'</div>'+
		'<div class="span-3 facturi-data">'+obj.data_factura+'</div>'+
		'<div class="span-3 facturi-ttl">'+obj.total_general+' '+obj.valuta+'</div>'+
		'<div class="interior"><div class="span-2 sts3 ui-corner-all ui-widget-content">Draft</div></div>'+
	'</div>'+
	'<div class="span-5 ui-helper-hidden box-options ui-state-highlight ui-corner-bottom ui-corner-tr" id="box-options'+obj.id+'">'+
		'<div class="span5 text-options ui-corner-all even"><a href="../../facturi-editare/'+obj.id_user+'/D'+obj.id_draft+'/">Emitere draft</a></div>'+
	'</div>';
	}
				$(content).appendTo('#container-pages');
				}
				else $('<div class="span-19 err-s">Nu a fost gasita nicio factura emisa pentru clientul <span class="uppercase-b">'+$('#search').val()+'</span>. <a href="javascript:add()" style="margin-left: 5px; color: #00f !important;">Emite o factura acum</a></div>').appendTo('#container-pages');
			});
			$('.list-container').length == 1 ? $('#ttl').html('<strong>1</strong> factura gasita') : $('#ttl').html('<strong>'+$('.list-container').length+'</strong> facturi gasite');
			$('#search').attr('search','1');
			defaults();
		}
	});
}
<?php
echo '
function add(){
	document.location.href = "/'.$subdomeniu.'/facturi-emitere/'.$_GET['idf'].'/";
}
function add_furnizor(){
	document.location.href = "/'.$subdomeniu.'/facturi-emitere/'.$_GET['idf'].'/?furnizor="+$("#furnizor").attr("iid");
}
';
?>
</script>

<?php
$sqlf = $db->query('select count(facturi.id_furnizor) as nr,facturi.id_furnizor,firme.denumire,firme.id_firma from facturi,firme where firme.id_firma=facturi.id_furnizor and firme.id_user="'.$_GET['idf'].'" and firme.tip_firma="0" group by facturi.id_furnizor order by nr desc limit 1');
$rowf = mysql_fetch_array($sqlf);

$sql = $db->query('select count(*) as ttl from facturi,firme where firme.id_user="'.$_GET['idf'].'" and firme.id_firma=facturi.id_furnizor and firme.tip_firma="0" and facturi.id_furnizor="'.$rowf['id_firma'].'"');
$row = mysql_fetch_array($sql);

if ($row['ttl'] != 0){
	echo '
<script>
$(document).ready(function(){
	$("#furnizor").zonepicker({
		presetRanges: [
	';
$i = 0;
$sql2 = $db->query('select count(facturi.id_furnizor) as nr,facturi.id_furnizor,denumire from facturi,firme where firme.id_firma=facturi.id_furnizor and firme.id_user="'.$_GET['idf'].'" and firme.tip_firma="0" group by facturi.id_furnizor order by nr desc limit 4');
while ($row2 = mysql_fetch_array($sql2)){
	$row2['nr'] == 1 ? $result = '<strong>1</strong> factura' : $result = ro($row2['nr']).' facturi';
	echo '{"text":"<div class=\'text-imp\' style=\'color: #2e6e9e;\'>'.$row2['denumire'].'</div><div class=\'picker-reset\'>'.$result.'</div>","ida":"'.$row2['id_furnizor'].'"},';
	$i++;
}
if ($i < 4){
	$limit = (4-$i);
	$sql3 = $db->query('select id_firma,denumire from firme where id_user="'.$_GET['idf'].'" and tip_firma="0" and id_firma not in (select facturi.id_furnizor from facturi,firme where firme.id_user="'.$_GET['idf'].'" and id_draft="0" group by facturi.id_furnizor) order by denumire asc limit '.$limit);
	if (mysql_num_rows($sql3)){
		while ($row3 = mysql_fetch_array($sql3)){
			echo '{"text":"<div class=\'text-imp\' style=\'color: #2e6e9e;\'>'.$row3['denumire'].'</div>","ida":"'.$row3['id_firma'].'"},';
		}
	}
}
$sqln = $db->query('select count(*) as ttl from firme where id_user="'.$_GET['idf'].'" and tip_firma="0"');
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
		{separator:false}
		],
	';
}
	echo '
		posX: $("#furnizor").offset().left,
		posY: $("#furnizor").offset().top+26
	});
});
</script>
	';
}
?>
<div class="span-22 ui-widget-header ui-corner-top">
	<div class="header">Facturi</div>
</div>
<?php
if ($row['ttl'] == 0) echo '
<div class="interior">
	<div class="span-22 box-top ui-widget-content" style="margin-bottom: 0;">
		<div class="span-20 box-functii ui-state-disabled">
			<div class="span-1 before-n">Furnizor</div>
			<div class="span-7">
				<input class="after-select span-6" style="font-size: 1em; text-transform: uppercase;" id="furnizor" readonly="readonly">
			</div>
			<div class="span3 before-n">Cautare client</div>
			<div class="span-7 search-facturi">
				<input class="after-s span-6" style="font-size: 1em; text-transform: uppercase;" type="text" id="search" readonly="readonly" onkeyup="init_query(($(\'#pagination > .current\').html()-1),\'pagination\')" search="0" autocomplete="off">
			</div>
		</div>
		<div class="box-add img-add" tips="<div class=\'tips-l\'>Emite factura</div>" onclick="add()"></div>
	</div>
	<div class="span-22 box-middle ui-state-hover" style="border-top: none;">
		<div class="span-4 text-middle-1">Serie</div>
		<div class="span-8 text-middle">Client</div>
		<div class="span-3 text-middle">Data emiterii</div>
		<div class="span-3 text-middle">Suma</div>
		<div class="span-2 text-middle" style="border-right: none;">Stare</div>
	</div>
</div>
<div class="span-22" id="container-pages" style="min-height: 475px;">
	<div class="span-19 err">
		<span style="margin-right: 5px;">Momentan nu ai nicio factura emisa. </span>
		<a href="javascript:add()">Emite o factura acum</a>
	</div>
</div>
	';
else{
	echo '
<script>
$(document).ready(function(){
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
	<div class="span-22 box-top ui-widget-content" style="margin-bottom: 0;">
		<div class="span-20 box-functii">
			<div class="span-1 before-n">Furnizor</div>
			<div class="span-7">
				<input value="'.$rowf['denumire'].'" class="after-select span-6" style="font-size: 1em; text-transform: uppercase;" id="furnizor" href="select_furnizori.php" iid="'.$rowf['id_firma'].'">
			</div>
			<div class="span3 before-n">Cautare client</div>
			<div class="span-7 search-facturi">
				<input class="after-s span-6" style="font-size: 1em; text-transform: uppercase;" type="text" id="search" onkeyup="init_query(($(\'#pagination > .current\').html()-1),\'pagination\')" search="0" autocomplete="off">
			</div>
		</div>
		<div class="box-add img-add" tips="<div class=\'tips-l\'>Emite factura</div>" onclick="add()"></div>
	</div>
	<div class="span-22 box-middle ui-state-hover" style="border-top: none;">
		<div class="span-4 text-middle-1">Serie</div>
		<div class="span-8 text-middle">Client</div>
		<div class="span-3 text-middle">Data emiterii</div>
		<div class="span-3 text-middle">Suma</div>
		<div class="span-2 text-middle" style="border-right: none;">Stare</div>
	</div>
</div>
<div class="span-23" id="container-pages" style="min-height: 475px;">
	';

$i=0;
$sqls = $db->query('select facturi.*, firme.denumire from facturi,firme where facturi.id_furnizor="'.$rowf['id_firma'].'" and facturi.id_client=firme.id_firma order by facturi.numar desc limit 8');
while ($rows = mysql_fetch_array($sqls)){
	if (isset($rows['valuta'])) $valuta = '<span class="valuta">'.$rows['valuta'].'</span>';
	else $valuta = '<span class="valuta">Lei</span>';
	if ($i%2 == 0) echo '<div class="span-22 list-container even" id_draft="'.$rows['id_draft'].'" factura="'.str_replace(' ','-',$rows['serie']).'-'.$rows['numar'].'">';
	else echo '<div class="span-22 list-container" id_draft="'.$rows['id_draft'].'" factura="'.str_replace(' ','-',$rows['serie']).'-'.$rows['numar'].'">';
	
if ($rows['id_draft'] == 0){
	if ($rows['stare_incasare'] == 0) echo '
		<div class="span-4 facturi-serie">
			<div id="'.$rows['id_factura'].'" class="list-options ui-state-hover ui-corner-all"><div class="ui-icon ui-icon-triangle-1-s"></div></div>
			<div class="serie span-3 uppercase">'.$rows['serie'].' '.$rows['numar'].'</div>
		</div>
		<div class="span-8 facturi-client uppercase">'.$rows['denumire'].'</div>
		<div class="span-3 facturi-data">'.convert_data(date('d-m-Y',strtotime($rows['data_factura']))).'</div>
		<div class="span-3 facturi-ttl">'.$rows['total_general'].' '.$valuta.'</div>
		<div class="span-2 sts0 ui-corner-all ui-state-error">Neincasat</div>
	</div>
	<div class="span-5 ui-helper-hidden box-options0 ui-state-highlight ui-corner-bottom ui-corner-tr" id="box-options'.$rows['id_factura'].'">
		<div class="span5 text-options ui-corner-all even"><a href="../../facturi-vizualizare/'.$_GET['idf'].'/'.$rows['id_furnizor'].'/'.str_replace(' ','-',$rows['serie']).'-'.$rows['numar'].'/?print_factura=1">Tiparire factura</a></div>
		<div class="span5 text-options ui-corner-all"><a href="../../facturi-vizualizare/'.$_GET['idf'].'/'.$rows['id_furnizor'].'/'.str_replace(' ','-',$rows['serie']).'-'.$rows['numar'].'/?email=1">Trimitere email</a></div>
		<div class="span5 text-options ui-corner-all even"><a href="../../facturi-vizualizare/'.$_GET['idf'].'/'.$rows['id_furnizor'].'/'.str_replace(' ','-',$rows['serie']).'-'.$rows['numar'].'/?incasare=1">Incasare factura</a></div>
		<div class="span5 text-options ui-corner-all"><a href="../../facturi-vizualizare/'.$_GET['idf'].'/'.$rows['id_furnizor'].'/'.str_replace(' ','-',$rows['serie']).'-'.$rows['numar'].'/?history=1">Istoric factura</a></div>
		<div class="span5 text-options ui-corner-all even"><a href="../../facturi-editare/'.$_GET['idf'].'/'.$rows['id_factura'].'/">Editare factura</a></div>
	</div>
	';
	if ($rows['stare_incasare'] == 1) echo '
		<div class="span-4 facturi-serie">
			<div id="'.$rows['id_factura'].'" class="list-options ui-state-hover ui-corner-all"><div class="ui-icon ui-icon-triangle-1-s"></div></div>
			<div class="serie span-3 uppercase">'.$rows['serie'].' '.$rows['numar'].'</div>
		</div>
		<div class="span-8 facturi-client uppercase">'.$rows['denumire'].'</div>
		<div class="span-3 facturi-data">'.convert_data(date('d-m-Y',strtotime($rows['data_factura']))).'</div>
		<div class="span-3 facturi-ttl">'.$rows['total_general'].' '.$valuta.'</div>
		<div class="span-2 sts1 ui-state-hover ui-corner-all">Incasat partial</div>
	</div>
	<div class="span-5 ui-helper-hidden box-options0 ui-state-highlight ui-corner-bottom ui-corner-tr" id="box-options'.$rows['id_factura'].'">
		<div class="span5 text-options ui-corner-all even"><a href="../../facturi-vizualizare/'.$_GET['idf'].'/'.$rows['id_furnizor'].'/'.str_replace(' ','-',$rows['serie']).'-'.$rows['numar'].'/?print_factura=1">Tiparire factura</a></div>
		<div class="span5 text-options ui-corner-all"><a href="../../facturi-vizualizare/'.$_GET['idf'].'/'.$rows['id_furnizor'].'/'.str_replace(' ','-',$rows['serie']).'-'.$rows['numar'].'/?email=1">Trimitere email</a></div>
		<div class="span5 text-options ui-corner-all even"><a href="../../facturi-vizualizare/'.$_GET['idf'].'/'.$rows['id_furnizor'].'/'.str_replace(' ','-',$rows['serie']).'-'.$rows['numar'].'/?incasare=1">Incasare factura</a></div>
		<div class="span5 text-options ui-corner-all"><a href="../../facturi-vizualizare/'.$_GET['idf'].'/'.$rows['id_furnizor'].'/'.str_replace(' ','-',$rows['serie']).'-'.$rows['numar'].'/?history=1">Istoric factura</a></div>
		<div class="span5 text-options ui-corner-all even"><a href="../../facturi-editare/'.$_GET['idf'].'/'.$rows['id_factura'].'/">Editare factura</a></div>
	</div>
	';
	if ($rows['stare_incasare'] == 2) echo '
		<div class="span-4 facturi-serie">
			<div id="'.$rows['id_factura'].'" class="list-options ui-state-hover ui-corner-all"><div class="ui-icon ui-icon-triangle-1-s"></div></div>
			<div class="serie span-3 uppercase">'.$rows['serie'].' '.$rows['numar'].'</div>
		</div>
		<div class="span-8 facturi-client uppercase">'.$rows['denumire'].'</div>
		<div class="span-3 facturi-data">'.convert_data(date('d-m-Y',strtotime($rows['data_factura']))).'</div>
		<div class="span-3 facturi-ttl">'.$rows['total_general'].' '.$valuta.'</div>
		<div class="span-2 sts2 ui-corner-all">Incasat</div>
	</div>
	<div class="span-5 ui-helper-hidden box-options1 ui-state-highlight ui-corner-bottom ui-corner-tr" id="box-options'.$rows['id_factura'].'">
		<div class="span5 text-options ui-corner-all even"><a href="../../facturi-vizualizare/'.$_GET['idf'].'/'.$rows['id_furnizor'].'/'.str_replace(' ','-',$rows['serie']).'-'.$rows['numar'].'/?print_factura=1">Tiparire factura</a></div>
		<div class="span5 text-options ui-corner-all"><a href="../../facturi-vizualizare/'.$_GET['idf'].'/'.$rows['id_furnizor'].'/'.str_replace(' ','-',$rows['serie']).'-'.$rows['numar'].'/?email=1">Trimitere email</a></div>
		<div class="span5 text-options ui-corner-all even"><a href="../../facturi-vizualizare/'.$_GET['idf'].'/'.$rows['id_furnizor'].'/'.str_replace(' ','-',$rows['serie']).'-'.$rows['numar'].'/?history=1">Istoric factura</a></div>
		<div class="span5 text-options ui-corner-all"><a href="../../facturi-editare/'.$_GET['idf'].'/'.$rows['id_factura'].'/">Editare factura</a></div>
	</div>
	';
	if (isset($rows['data_scadenta']) && $rows['data_scadenta'] < date('Y-m-d') && ($rows['stare_incasare'] == 0 || $rows['stare_incasare'] == 1)) echo '
	<div class="icon-restant-mod"></div>
	';
}
else echo '
		<div class="span-4 facturi-serie">
			<div id="'.$rows['id_factura'].'" class="list-options ui-state-hover ui-corner-all"><div class="ui-icon ui-icon-triangle-1-s"></div></div>
			<div class="serie span-3 uppercase">'.$rows['serie'].' '.$rows['numar'].'</div>
		</div>
		<div class="span-8 facturi-client uppercase">'.$rows['denumire'].'</div>
		<div class="span-3 facturi-data">'.convert_data(date('d-m-Y',strtotime($rows['data_factura']))).'</div>
		<div class="span-3 facturi-ttl">'.$rows['total_general'].' '.$valuta.'</div>
		<div class="interior"><div class="span-2 sts3 ui-corner-all ui-widget-content">Draft</div></div>
	</div>
	<div class="span-5 ui-helper-hidden box-options ui-state-highlight ui-corner-bottom ui-corner-tr" id="box-options'.$rows['id_factura'].'">
		<div class="span5 text-options ui-corner-all even"><a href="../../facturi-editare/'.$_GET['idf'].'/D'.$rows['id_draft'].'/">Emitere draft</a></div>
	</div>
';
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
$row['ttl'] == 1 ? $result = '<strong>1</strong> factura' : $result = ro($row['ttl']).' facturi';
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
<!-- End container -->
</div>