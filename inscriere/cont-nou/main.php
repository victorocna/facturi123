<div class="container">
<div class="span-23" style="min-height: 400px;">

<?php
$sql = $db->query('select * from useri,tip_cont where useri.subdomeniu="'.$_GET['subdomeniu'].'" and useri.id_user="'.$_GET['id_user'].'" and useri.id_tip=tip_cont.id_tip');
if (mysql_num_rows($sql) == 1){
	$row = mysql_fetch_array($sql);
	if ($row['id_tip'] == 1){
		echo '
<div class="span-14 last box-main">
	<div class="span-21 form-header ui-corner-top">Detalii cont nou <span style="text-transform:capitalize;">'.$row['denumire'].'</span></div>
	<div class="span-21 form-content">
	
	<div class="span-11" style="margin-top: -10px;">
	<div class="span-11" class="form-row">
		<div class="before-b span3">Contul tau</div>
		<div class="before-c span-8">'.$row['subdomeniu'].'</div>
	</div>
	<div class="span-11" class="form-row">
		<div class="before-b span3">Utilizator</div>
		<div class="before-c span-8">'.$row['user'].'</div>
	</div>
	<div class="span-11" class="form-row">
		<div class="before-b span3">Email</div>
		<div class="before-c span-8">'.$row['email'].'</div>
	</div>
	<div class="span-11" style="margin-top: 10px;">
		<div class="before-x">'.$row['nr_facturi'].'</div>
		<div class="before-b span-9">Facturi lunare incluse in cont</div>
	</div>
	<div class="span-11" style="margin-top: -5px;">
		<div class="before-x">'.$row['nr_furnizori'].'</div>
		<div class="before-b span-9">Furnizor inclus in cont</div>
	</div>
	</div>
	<div class="span-8" style="margin-top: 10px;">
		<button class="fg-button green ui-corner-all" onclick="document.location.href=\'/'.$row['subdomeniu'].'\'">
			<div class="span8">
				<div class="span-7 last">
					<div class="span-7 text-button">Acceseaza acum contul tau</div>
					<div class="span-7 text-button-s">Dupa autentificare poti emite imediat facturi</div>
				</div>
				<div class="span-arrow"></div>
			</div>
		</button>
	</div>
	</div>
</div>
		';
	}
	if ($row['id_tip'] != 1){

$sql2 = $db->query('select * from plati,useri where useri.subdomeniu="'.$_GET['subdomeniu'].'" and useri.id_user="'.$_GET['id_user'].'" and plati.id_user=useri.id_user');
$row2 = mysql_fetch_array($sql2);

$sql3 = $db->query('select * from firme,useri where useri.subdomeniu="'.$_GET['subdomeniu'].'" and firme.id_user=useri.id_user and firme.tip_firma="9"');
$row3 = mysql_fetch_array($sql3);

		echo '
<div class="span-14 last box-main">
	<div class="span-21 form-header ui-corner-top">Detalii cont nou <span style="text-transform:capitalize;">'.$row['denumire'].'</span></div>
	<div class="span-21 form-content">
	
	<div class="span-11" style="margin-top: -10px;">
	<div class="span-11" class="form-row">
		<div class="before-b span3">Contul tau</div>
		<div class="before-c span-8">'.$row['subdomeniu'].'</div>
	</div>
	<div class="span-11" class="form-row">
		<div class="before-b span3">Utilizator</div>
		<div class="before-c span-8">'.$row['user'].'</div>
	</div>
	<div class="span-11" class="form-row">
		<div class="before-b span3">Email</div>
		<div class="before-c span-8" id="email">'.$row['email'].'</div>
	</div>
	<div class="span-11" style="margin-top: 20px;">
		<div class="before-x span1" style="text-align:center;">'.$row['nr_facturi'].'</div>
		<div class="before-b span-9">Facturi lunare incluse in cont</div>
	</div>
	<div class="span-11" style="margin-top: -5px;">
		<div class="before-x span1" style="text-align:center;">'.$row['nr_furnizori'].'</div>
		<div class="before-b span-9">Furnizori inclusi in cont</div>
	</div>
	</div>
	
	<div class="span-9">
	<div class="span-8" style="margin-top: 10px;">
		<button class="fg-button green ui-corner-all" onclick="print()">
			<div class="span8">
				<div class="span-7 last">
					<div class="span-7 text-button">Vizualizare instiintare de plata</div>
					<div class="span8 text-button-s" style="margin-left: -15px;">Imediat dupa confirmarea platii poti emite facturi</div>
				</div>
				<div class="span-arrow"></div>
			</div>
		</button>
	</div>
	<div class="span-10 last" style="margin-top: 35px;">
		<div class="before-b span7">Inceputul perioadei de valabilitate</div>
		<div class="before-x span3" style="margin-left: -10px;">'.convert_data(date('d-m-Y',strtotime($row2['data_ini']))).'</div>
	</div>
	<div class="span-10 last" style="margin-top: -5px;">
		<div class="before-b span7">Sfarsitul perioadei de valabilitate</div>
		<div class="before-x span3" style="margin-left: -10px;">'.convert_data(date('d-m-Y',strtotime($row2['data_fin']))).'</div>
	</div>
	
	</div>
	</div>
</div>
		';
	}
	echo '
<div class="span-24" style="padding: 10px 0 40px 0;">
	<div class="span-7 box-avantaje">
		<div class="span2 icon-pdf"></div>
		<div class="span16 txt-av">
			<p>Poti salva facturile si chitantele in format PDF si le poti tipari imediat dupa emiterea facturii.</p>
		</div>
	</div>
	<div class="span-7 box-avantaje">
		<div class="span2 icon-mail"></div>
		<div class="span16 txt-av">
			<p>Facturile tale ajung mult mai rapid la clienti - le poti trimite direct pe email.</p>
		</div>
	</div>
	<div class="span-7 box-avantaje">
		<div class="span2 icon-incasare"></div>
		<div class="span16 txt-av">
			<p>Stii imediat ce facturi sunt neincasate sau restante si</p>
			<p>ce sume ai de incasat.</p>
		</div>
	</div>
	<div class="span-7 box-avantaje">
		<div class="span2 icon-draft"></div>
		<div class="span16 txt-av">
			<p>Nu iti vei pierde niciodata datele. Facturile se salveaza automat in timp ce le completezi.</p>
		</div>
	</div>
	<div class="span-7 box-avantaje">
		<div class="span2 icon-acces"></div>
		<div class="span16 txt-av">
			<p>Poti emite facturi de oriunde ai conexiune la internet. Ai nevoie doar de un browser web.</p>
		</div>
	</div>
	<div class="span-7 box-avantaje">
		<div class="span2 icon-model"></div>
		<div class="span16 txt-av">
			<p>Poti crea facturi personalizate cu sigla firmei tale si poti alege modele multiple de facturi.</p>
		</div>
	</div>
</div>
	';
?>

<script>
$(document).ready(function(){
	save_instiintare();
});
function save_instiintare(){
<?php
//alias
$idf = $row2['id_user'];
$id_furnizor = $row3['id_firma'];
$factura = $row2['id_plata'];
//end alias
$query = "&idf=".$idf."&id_furnizor=".$id_furnizor."&factura=".$factura;
echo 'var query = "'.$query.'";';
?>
	$.ajax({type:'GET',url:'/modules/popup_instiintare_plata.php?op=save_instiintare'+query,success:function(){
			email_instiintare();
		}
	});
}
function email_instiintare(){
<?php
$query = "&subdomeniu=".$_GET['subdomeniu']."&id_plata=".$row2['id_plata']."&atasament=1";
echo 'var query = "'.$query.'";';
?>
	$.ajax({type:'GET',url:'/includes/functii123.php?op=email_instiintare'+query,success:function(raspuns){
			if (raspuns == 1) notify_bars(20,'Instiintarea de plata a fost trimisa la adresa <span class="activ">'+$('#email').text()+'</span>');
		}
	});
}
function print(){
<?php
$src = "/modules/popup_instiintare_plata/".$row2['id_user']."/".$row3['id_firma']."/".$row2['id_plata']."/";
$title = "<div class='span-23' style='text-align: center;'>Instiintare de plata <span style='text-transform: uppercase;'>".$row2['serie'].' '.$row2['numar']."</span></div>";
echo '
	var src = "'.$src.'";
	var title = "'.$title.'";
';
?>
	$("#dialog_print").dialog('destroy'); 
	$("#dialog_print").remove();
	$("body").append('<div id="dialog_print" title="'+title+'" style="text-align:left"><iframe src="'+src+'" width="980" height="620" frameborder="0" border="0"></iframe></div>');
	$("#dialog_print").show(); 
	$("#dialog_print").dialog({
			height: 700,
			width: 1030,
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
</script>

<?php } ?>

</div>
</div>