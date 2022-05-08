<div class="container">
<div class="span-22 last" style="padding-left: 30px;">
<!--[if IE]>
<script>
$(document).ready(function(){
	$('.button').corner('5px');
});
</script>
<![endif]-->
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
function add(denumire){
<?php
$src = '/'.$subdomeniu.'/popup_add_produse/'.$_GET['idf'].'/';
echo 'var src = "'.$src.'";';
?>
	var query = '';
	if (denumire) query += '?denumire='+denumire;
	$("#dialog_add").dialog('destroy');
	$("#dialog_add").remove();
	$("body").append('<div id="dialog_add" title="<div class=\'span-22\' style=\'text-align: center;\'>Adaugare produs</div>"><iframe src="'+src+query+'" width="900" height="300" frameborder="0" border="0"></iframe>');
	$("#dialog_add").show();
	$("#dialog_add").dialog({
			height: 400,
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
$src = '/'.$subdomeniu.'/popup_mod_produse/'.$_GET['idf'].'/';
echo 'var src = "'.$src.'";';
?>
	var query = '?id_produs='+id
	$("#dialog_modify").dialog('destroy'); 
	$("#dialog_modify").remove();
	$("body").append('<div id="dialog_modify" title="<div class=\'span-22\' style=\'text-align: center;\'>Modificare produs</div>" style="text-align:left"><iframe src="'+src+query+'" width="900" height="300" frameborder="0" border="0"></iframe>');
	$("#dialog_modify").show();
	$("#dialog_modify").dialog({
			height: 400,
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
	var order = 'denumire asc';
	if ($('#search').val().length <= 2){
		if ($('#search').attr('search') == '1'){
			$('#pagination').removeClass('ui-state-disabled');
			$('#search').attr('search','0');
			if (($('#pagination > .current').html()-1) == -1) limit = 0;
			else limit = (($('#pagination > .current').html()-1)*8);
		}
		query_produse(order,limit);
	}
	if ($('#search').val().length > 2) search_produse(order);
}
function query_produse(order,limit){
	if ($('.box-functii').hasClass('.ui-state-disabled')){
		$('.box-functii').removeClass('ui-state-disabled');
		$('#search').attr('readonly','');
	}
	var query = '&order='+order+'&limit='+limit;
	$.ajax({type:'GET',dataType:'json',url:'/includes/functii.php?op=query_produse'+query,success:function(raspuns){
			$('#container-pages').html('');
			$.each(raspuns.produse,function(i,obj){
				var content;
				if (i%2 == 0) content += '<div class="span-22 list-container even">';
				else content += '<div class="span-22 list-container">';
				content +=
		'<div id="denumire'+obj.id+'" class="span-9 produse-denumire">'+obj.denumire+'</div>'+
		'<div id="unitate'+obj.id+'" class="span-3 produse-um">'+obj.unitate+'</div>'+
		'<div id="data_add'+obj.id+'" class="span-3 produse-data">'+obj.data_add+'</div>'+
		'<div class="span-3 ui-helper-hidden linc" style="margin-top: 5px;">'+
			'<button id="emitere'+obj.id+'" class="fg-button-s orange ui-corner-all button-mod span31" onclick="emitere('+obj.id+')" style="margin-top: 5px;"><span class="button-text">Emite factura</span></button>'+
		'</div>'+
		'<div class="span-2 ui-helper-hidden linc" style="margin-top: 5px;">'+
			'<button id="mod'+obj.id+'" class="fg-button-s white ui-corner-all button-mod span-2" onclick="modify('+obj.id+')" style="margin-top: 5px;"><span class="button-text">Modifica</span></button>'+
		'</div>'+
	'</div>';
				$(content).appendTo('#container-pages');
			});
			if (raspuns.ttl % 8 == 1) init_pagination(raspuns.ttl,($('#pagination > .current').html()-1));
			raspuns.ttl == 1 ? $('#ttl').html('<span style="margin-right: 1px;">Total </span> <strong>1</strong> produs') : $('#ttl').html('<span style="margin-right: 1px;">Total </span>'+ro(raspuns.ttl)+' produse');
			defaults();
		}
	});
}
function search_produse(order){
	var query = '&denumire='+$('#search').val()+'&order='+order;
	$.ajax({type:'GET',dataType:'json',url:'/includes/functii.php?op=search_produse'+query,success:function(raspuns){
			$('#container-pages').html('');
			$('#pagination').addClass('ui-state-disabled');
			$.each(raspuns,function(i,obj){
				if (obj.id){
					var content;
					if (i%2 == 0) content += '<div class="span-22 list-container even"';
					else content += '<div class="span-22 list-container">';
					content +=
		'<div id="denumire'+obj.id+'" class="span-9 produse-denumire">'+obj.denumire+'</div>'+
		'<div id="unitate'+obj.id+'" class="span-3 produse-um">'+obj.unitate+'</div>'+
		'<div id="data_add'+obj.id+'" class="span-3 produse-data">'+obj.data_add+'</div>'+
		'<div class="span-3 ui-helper-hidden linc" style="margin-top: 5px;">'+
			'<button id="emitere'+obj.id+'" class="fg-button-s orange ui-corner-all button-mod span31" onclick="emitere('+obj.id+')" style="margin-top: 5px;"><span class="button-text">Emite factura</span></button>'+
		'</div>'+
		'<div class="span-2 ui-helper-hidden linc" style="margin-top: 5px;">'+
			'<button id="mod'+obj.id+'" class="fg-button-s white ui-corner-all button-mod span-2" onclick="modify('+obj.id+')" style="margin-top: 5px;"><span class="button-text">Modifica</span></button>'+
		'</div>'+
	'</div>';
					$(content).appendTo('#container-pages');
				}
				else $('<div class="span-19 err-s">Nu a fost gasit niciun produs cu denumirea <strong>'+$('#search').val()+'</strong>. <a href="javascript:add(\''+$('#search').val()+'\')" style="margin-left: 5px;">Adauga produsul <strong>'+$('#search').val()+'</strong> acum</a></div>').appendTo('#container-pages');
			});
			$('.list-container').length == 1 ? $('#ttl').html('<strong>1</strong> produs gasit') : $('#ttl').html('<strong>'+$('.list-container').length+'</strong> produse gasite');
			$('#search').attr('search','1');
			defaults();
		}
	});
}
<?php
echo '
function emitere(id){
	document.location.href = "/'.$subdomeniu.'/facturi-emitere/'.$_GET['idf'].'/?produs="+id;
}
';
?>
</script>
<div class="span-22 ui-widget-header ui-corner-top">
	<div class="header">Produse</div>
</div>
<?php
$sql = $db->query('select count(*) as ttl from produse where id_user="'.$_GET['idf'].'"');
$row = mysql_fetch_array($sql);
if ($row['ttl'] == 0) echo '
<div class="interior">
	<div class="span-22 box-top ui-widget-content" style="margin-bottom: 0;">
		<div class="span-20 box-functii ui-state-disabled">
			<div class="span-1 before-n">Cautare</div>
			<div class="span-7">
				<input class="after-s span-6" style="font-size: 1em;" type="text" id="search" search="0" readonly="readonly" onkeyup="init_query(($(\'#pagination > .current\').html()-1),\'pagination\')" autocomplete="off">
			</div>
		</div>
		<div class="box-add img-add" tips="<div class=\'tips-add\'>Adauga produs</div>" onclick="add()"></div>
	</div>
	<div class="span-22 box-middle ui-state-hover" style="border-top: none;">
		<div class="span-9 text-middle-1">Denumire</div>
		<div class="span-3 text-middle">UM</div>
		<div class="span-4 text-middle" style="border-right: none;">Data adaugarii</div>
	</div>
</div>
<div class="span-22" id="container-pages" style="min-height: 470px;">
	<div class="span-19 err">
		<span style="margin-right: 5px;">Momentan nu ai niciun produs adaugat. </span>
		<a href="javascript:add()">Adauga un produs acum</a>
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
	<div class="span-22 box-top ui-widget-content" style="margin-bottom: 0;">
		<div class="span-20 box-functii">
			<div class="span-1 before-n">Cautare</div>
			<div class="span-7">
				<input class="after-s span-6" style="font-size: 1em;" type="text" id="search" onkeyup="init_query(($(\'#pagination > .current\').html()-1),\'pagination\')" search="0" autocomplete="off">
			</div>
		</div>
		<div class="box-add img-add" tips="<div class=\'tips-add\'>Adauga produs</div>" onclick="add()"></div>
	</div>
	<div class="span-22 box-middle ui-state-hover" style="border-top: none;">
		<div class="span-9 text-middle-1">Denumire</div>
		<div class="span-3 text-middle">UM</div>
		<div class="span-4 text-middle" style="border-right: none;">Data adaugarii</div>
	</div>
</div>
<div class="span-22" id="container-pages" style="min-height: 470px;">
	';

$i=0;
$sqls = $db->query('select * from produse where id_user="'.$_GET['idf'].'" order by denumire asc limit 8');
while ($rows = mysql_fetch_array($sqls)){
	if ($i%2 == 0) echo '<div class="span-22 list-container even">';
	else echo '<div class="span-22 list-container">';
	echo '
	<div id="denumire'.$rows['id_produs'].'" class="span-9 produse-denumire">'.$rows['denumire'].'</div>
	<div id="unitate'.$rows['id_produs'].'" class="span-3 produse-um">'.$rows['unitate'].'</div>
	<div id="data_add'.$rows['id_produs'].'" class="span-3 produse-data">'.convert_data(date('d-m-Y',strtotime($rows['data_add']))).'</div>
	<div class="span-3 ui-helper-hidden linc" style="margin-top: 5px;">
		<button id="emitere'.$rows['id_produs'].'" class="fg-button-s orange ui-corner-all button-mod span31" onclick="emitere('.$rows['id_produs'].')" style="margin-top: 5px;"><span class="button-text">Emite factura</span></button>
	</div>
	<div class="span-2 ui-helper-hidden linc" style="margin-top: 5px;">
		<button id="mod'.$rows['id_produs'].'" class="fg-button-s white ui-corner-all button-mod span-2" onclick="modify('.$rows['id_produs'].')" style="margin-top: 5px;"><span class="button-text">Modifica</span></button>
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
$row['ttl'] == 1 ? $result = '<strong>1</strong> produs' : $result = ro($row['ttl']).' produse';
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