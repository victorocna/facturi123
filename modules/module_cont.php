<div class="container">
<div class="span-22 last" style="padding-left: 30px;">
<script>
$(document).ready(function(){
	$('.ui-tabs-selected').removeClass('ui-tabs-selected ui-state-active');
	$('body').find('#cont').addClass('h-active');
	$('.menu-tips').qtip({
		content: '<div class="tips-m">Alege alt cont</div>',
		position:{
			corner:{
				target: 'rightMiddle',
				tooltip: 'leftMiddle'
			}
		},
		style:{
			name: 'light',
			tip:{
				corner:'leftMiddle',
				size:{ x:10, y:10 }
			},
			border:{ width: 1, radius: 4 }
		},
		hide:{
			when: 'mouseout',
			fixed: true
		}
	});
	$('#adv').toggle(
		function(){
			$(this).text('Ascunde modificare parola');
			$('#box-adv').removeClass('ui-helper-hidden');
			$('#parola').addClass('validator-required').focus();
		},
		function(){
			$(this).text('Modificare parola');
			$('#box-adv').addClass('ui-helper-hidden');
			$('#parola').removeClass('validator-required');
		}
	);
	var reguli = jQuery.validationAide.getDefaultValidationRules();
	reguli.add('validator-corect', '', function(v, obj){
		if ($(obj).attr('fault') != 0) return false;
		return true;
	});
	reguli.add('validator-parola', '', function(v, obj){
		if ($(obj).val().length > 0 && $(obj).val().length < 5) return false;
		return true;
	});
	$('#form').validationAideEnable(
		reguli,
		{ fieldErrorCssClass: 'failed', showSummary: false },
		null,
		chk
	);
});
function verifica_email(){
	if ($.trim($("#email").val())){
		var query = '&email='+$.trim($("#email").val())+'&id_user='+<?php echo $_GET['idf']; ?>;
		$.ajax({type:'GET',dataType:'json',url:'/includes/functii.php?op=verifica_email'+query,success:function(raspuns){
				if (raspuns.fault == '0'){
					$('#verifica-email').html('');
					$('#email').attr('fault', '0').removeClass('img-no failed').addClass('img-yes');
				}
				if (raspuns.fault == '1'){
					$('#verifica-email').html('Emailul exista deja');
					$('#email').attr('fault', '1').removeClass('img-yes').addClass('img-no');
					$('input').blur();
					$('#email').focus();
				}
			}
		});
	}
}
function chk(s){
	if (!s){
		window.top.notify_bar(10,'Eroare la completarea datelor!');
		return false;
	}
	else{
		$(document).find('.after').css('border', 'solid 1px #ccc');
		update_cont();
		return false;
	}	
}
function update_cont(){
	var query = '&id_user=<?php echo $_GET['idf']; ?>';
	$.each($('input'),function(i,obj){
		if ($(obj).hasClass('.validator-required')) query += '&'+$(obj).attr('id')+'='+$(obj).val();
	});
	$.ajax({type:'GET',url:'/includes/functii.php?op=update_cont'+query,success:function(raspuns){
			if (raspuns) notify_bars(10,'Datele utilizatorului <span class="activ">'+$('#user').val()+'</span> au fost modificate');
		},beforeSend:function(){
			$('.box-loading').removeClass('ui-helper-hidden');
		},complete:function(){
			$('.box-loading').addClass('ui-helper-hidden');
		}
	});
}
</script>
<?php
//$sqlf = $db->query('select count(facturi.id_furnizor) as nr,facturi.id_furnizor,firme.denumire,firme.id_firma from facturi,firme where firme.id_firma=facturi.id_furnizor and firme.id_user="'.$_GET['idf'].'" and firme.tip_firma="0" group by facturi.id_furnizor order by nr desc limit 1');
//$rowf = mysql_fetch_array($sqlf);
?>
<div class="span-22 ui-widget-header ui-corner-top">
	<div class="header">Contul tau</div>
</div>
<div class="span-20 b-limit ui-widget-content" style="font-weight: normal; margin-bottom: 10px;">
<div class="span-20" style="margin: 10px 0;">
<form id="form" onsubmit="return false;">

<?php
$sql = $db->query('select * from useri,tip_cont where useri.id_user="'.$_GET['idf'].'" and useri.subdomeniu="'.$subdomeniu.'" and useri.id_tip=tip_cont.id_tip');
$row = mysql_fetch_array($sql);

//furnizori
$sf = $db->query('select count(*) as nr from firme where id_user="'.$_GET['idf'].'" and tip_firma="0"');
$rf = mysql_fetch_array($sf);
$nr_furnizori = $row['nr_furnizori'] - $rf['nr'];
$nr_furnizori == 1 ? $nr_furnizori = '1 furnizor' : $nr_furnizori = ro($nr_furnizori).' furnizori';

//facturi
if ($row['id_tip'] == 1){
	$zi = substr($row['data_add'],8,2);
	$data_ini = date('Y-m-'.$zi.' 00:00:01');
	$data_fin = date('Y-m-d 23:59:59');
	$data_limit = convert_data(date('d-m-Y',strtotime($data_ini . '+1 month -1 day')));
}
else{
	$sp = $db->query('select * from plati where id_user="'.$_GET['idf'].'" order by data_add desc limit 1');
	if (mysql_num_rows($sp) != 0){
		$rp = mysql_fetch_array($sp);
		$zi = substr($rp['data_ini'],8,2);
	}
	else $zi = '01';
	$data_ini = date('Y-m-'.$zi.' 00:00:01');
	$data_fin = date('Y-m-d 23:59:59');
	$data_limit = convert_data(date('d-m-Y',strtotime($data_ini . '+1 month -1 day')));
}

$sa = $db->query('select count(*) as nr from facturi,firme where firme.id_user="'.$_GET['idf'].'" and facturi.id_furnizor=firme.id_firma and facturi.data_add between "'.$data_ini.'" and "'.$data_fin.'" and facturi.id_draft="0"');
$ra = mysql_fetch_array($sa);
$nr_facturi = $row['nr_facturi'] - $ra['nr'];
$nr_facturi == 1 ? $nr_facturi = '1 factura' : $nr_facturi = ro($nr_facturi).' facturi';
		
		echo '
<div class="span-13 form-row ui-state-active" style="padding: 3px 10px; margin-bottom: 20px; min-height: 140px;">
	<div class="span-13 cont-row">
		<div class="before-c span4">Adresa de conectare</div>
		<div class="capitalize-n" style="text-transform: none;">www.facturi123.ro/<span class="text-hover">'.$row['subdomeniu'].'</span></div>
	</div>
	<div class="span-13 cont-row">
		<div class="before-c span4">Data crearii contului</div>
		<div class="capitalize-n">'.convert_data(date('d-m-Y',strtotime($row['data_add']))).'</div>
	</div>
	<div class="span-13 cont-row">
		<div class="before-c span4">Tipul contului</div>
		<div class="capitalize-n"><a href="../../cont-tip/'.$_GET['idf'].'/" class="menu-tips" style="text-decoration: underline; color: #1d5987; padding-right: 10px;">'.$row['denumire'].'</a></div>
	</div>
	<div class="span-13 cont-row">
		<div class="before-c span5">Numar de furnizori ramasi</div>
		<div class="capitalize-n" style="text-transform: none;">'.$nr_furnizori.'</div>
	</div>
	<div class="span-13 cont-row">
		<div class="before-c span5">Numar de facturi ramase</div>
		<div class="capitalize-n" style="text-transform: none;">'.$nr_facturi.' <span style="font-size: 14px;">pana pe '.$data_limit.'</span></div>
	</div>
</div>

<div class="span-20 form-row">
	<div class="before span3">Utilizator</div>
	<div class="box-after span-16 last">
		<input value="'.$row['user'].'" class="after span-11 validator-required" type="text" id="user" autocomplete="off">
	</div>
</div>
<div class="span-20 form-row">
	<div class="before span3">Email</div>
	<div class="box-after span-16 last">
		<input value="'.$row['email'].'" class="after span-11 validator-required validator-corect" type="text" id="email" autocomplete="off" onblur="verifica_email()" fault="0">
		<span id="verifica-email" class="input-helper" style="left: -150px; font-weight: bold;"></span>
	</div>
</div>
		';
?>
<div class="span-20 last" style="font-size: 1.1em; margin-bottom: 5px;"><div id="adv" class="klink span-6">Modificare parola</div></div>
<div id="box-adv" class="ui-helper-hidden">
	<div class="span-20 form-row">
		<div class="before span3">Parola</div>
		<div class="box-after span-16 last">
			<input class="after span-11 validator-parola" type="password" id="parola" tips="<div id='toggle-tips' class='span-6 tips-e'><strong>Informatie necesara</strong><br><span class='activ' style='font-size: .8em;'>Parola trebuie sa contina minim <strong>5</strong> caractere.</span></div>" autocomplete="off">
		</div>
	</div>
</div>
<div class="span-17" style="margin: 10px 0;">
	<div class="span-10" style="text-align: right; margin-left: 20px;"><a href="../../facturi/<?php echo $_GET['idf']; ?>/" class="back">Inapoi</a></div>
	<div class="span-3" style="text-align: right;"><button class="fg-button orange ui-corner-all" type="submit"><span class="button-text">Modifica</span></button></div>
	<div class="span-1 box-loading ui-helper-hidden"></div>
</div>
</form>
</div>
</div>

</div>
<!-- End container-m -->
</div>