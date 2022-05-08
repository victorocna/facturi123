<div class="container">
<div class="span-22 last" style="padding-left: 30px;">
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
});
</script>

<div class="span-22 ui-widget-header ui-corner-top">
	<div class="header">Actualizare cont</div>
</div>

<div class="span-21 c-limit ui-widget-content" style="font-weight: normal; margin-bottom: 30px;">

<!-- Tabs -->
<div class="span-23 box-meniu" id="meniu" style="padding: 0em;">
	<ul class="meniu" style="background: transparent !important; padding: .2em 1em 0;">
		<li class="span-7 box1" style="background: #fff !important;">
			<div class="span-2 last pas-meniu" onclick="pas1(false)">1</div>
			<div class="span-5"><a href="#pas1" class="pas1" style="padding: 2.2em 0 .6em .1em; font-size: 1.4em;">Informatii platitor</a></div>
			<div class="span-5"><p class="text-meniu">Aici se colecteaza informatiile asociate platitorului, care vor aparea pe instiintarea de plata.</p></div>
		</li>
		<li class="span-7 box2" style="background: #fff !important;">
			<div class="span-2 last pas-meniu" onclick="pas2(false)">2</div>
			<div class="span-5"><a href="#pas2" class="pas2" style="padding: 2.2em 0 .6em .1em; font-size: 1.4em;">Informatii perioada</a></div>
			<div class="span-5"><p class="text-meniu">Aici poti sa stabilesti perioada de valabilitate a contului tau.</p></div>
		</li>
		<li class="span-7 box3" style="background: #fff !important;">
			<div class="span-2 last pas-meniu" onclick="pas3(false)">3</div>
			<div class="span-5"><a href="#pas3" class="pas3" style="padding: 2.2em 0 .6em .1em; font-size: 1.4em;">Finalizare</a></div>
			<div class="span-5"><p class="text-meniu" style="text-align:left;">Aici poti descarca instiintarea de plata, iar contul tau va fi actualizat.</p></div>
		</li>
	</ul>
<div class="span-20" id="pas1" style="margin-top: -10px;">
	<div class="span-18 form-header ui-corner-top">Informatii cont nou</div>
	<div class="span-18 form-content">

<form id="form1" onsubmit="return false;">
<div class="span-23 box-margin-m">
	<div class="before span3">Contul tau</div>
	<div class="span-11">
		<input class="after span123 validator-required" type="text" id="subdomeniu" style="text-transform: lowercase;" tips="<div id='toggle-tips' class='span-7 tips-e'><strong>Informatie necesara</strong><br>Contul tau reprezinta adresa ta de conectare la programul de facturi.<br><span style='font-size: .9em;'><span class='activ'>Exemplu: www.facturi123.ro/<strong>contul-tau</strong></span></span></div>" autocomplete="off" onblur="verifica_subdomeniu()">
	</div>
</div>
<div class="span-23 box-margin-m">
	<div class="before span3">Email</div>
	<div class="span-11">
		<input class="after span123 validator-required validator-email" type="text" id="email" tips="<div id='toggle-tips' class='span-7 tips-e'><strong>Informatie necesara</strong><br>Adresa de email permite trimiterea online a facturilor emise catre clienti.</div>" autocomplete="off" onblur="verifica_email()">
	</div>
</div>
<div class="span-23 box-margin-m">
	<div class="before span3">Utilizator</div>
	<div class="span-11">
		<input class="after span123 validator-required" type="text" id="user" tips="<div id='toggle-tips' class='span-7 tips-e'><strong>Informatie necesara</strong><br>Dupa accesarea contului tau trebuie folosit utilizatorul si parola pentru autentificare.</div>" autocomplete="off">
	</div>
</div>
<div class="span-23 box-margin-m">
	<div class="before span3">Parola</div>
	<div class="span-11">
		<input class="after span123 validator-required validator-parola" type="password" id="parola" tips="<div id='toggle-tips' class='span-7 tips-e'><strong>Informatie necesara</strong><br><span class='activ' style='font-size: .9em;'>Parola trebuie sa contina minim <strong>5</strong> caractere.</span></div>" autocomplete="off">
	</div>
</div>
<div class="span-23 box-margin-m">
	<div class="span-9 back-box" style="margin-right: 15px;"><a href="javascript: confirm()" class="back" style="font-size: 1.2em;">Alege alt cont</a></div>
	<div class="span-4"><button class="fg-button orange ui-corner-all" style="padding: .5em 1.4em;" type="submit"><span class="button-text">Mai departe</span></button></div>
	<div class="span-1 box-loading ui-helper-hidden" style="margin-top: 2px;"></div>
</div>
</form>

	</div>
</div>

<div class="span-22" id="pas2" style="margin-top: -10px;">
	<div class="span-20 form-header ui-corner-top">Informatii platitor</div>
	<div class="span-20 form-content">

<form id="form2" onsubmit="return false;">
<div class="span-23 box-margin">
	<div class="before span3">Denumire</div>
	<div class="span-20">
		<input class="after span123 validator-required" style="text-transform: uppercase;" type="text" id="denumire" tips="<div id='toggle-tips' class='span-7 tips-e'><strong>Informatie necesara</strong><br>Se completeaza denumirea firmei, inclusiv forma sa juridica.<br><span style='font-size: .9em;'><strong>Exemplu:</strong> SC Firma SRL</span></div>" autocomplete="off">
		<span id="verifica-denumire" class="input-helper" style="left: -160px; font-weight: bold;"></span>
	</div>
</div>
<div class="span-23 box-margin">
	<div class="before span3">CIF / CUI</div>
	<div class="span-20">
		<input class="after span123 validator-required validator-corect" style="text-transform: uppercase;" type="text" id="cif" onblur="verifica_cif()" tips="<div class='span-7 tips-e'><strong>Informatie necesara</strong><br>Se completeaza codul <strong>RO</strong> daca firma este platitoare de TVA.</div>" tva="1" fault="0" autocomplete="off">
		<span id="verifica-cif" class="input-helper" style="left: -220px;"></span>
	</div>
</div>
<div class="span-23 box-margin">	
	<div class="before span3">Adresa</div>
	<div class="span-20">
		<input class="after span123" type="text" id="adresa" style="text-transform: capitalize;" tips="<div class='tips-n'><strong>Informatie optionala</strong><br>Se poate completa sediul social sau adresa unui punct de lucru.</div>" autocomplete="off">
	</div>
</div>
<div class="span-23 box-margin">
	<div class="before span3">Reg Com</div>
	<div class="span-20">
		<input class="after span123" type="text" id="reg_com" style="text-transform: uppercase;" tip="<div class='tips-c' style='font-weight: bold;'>Informatie optionala</div>" autocomplete="off">
	</div>
</div>
<div class="span-23 box-margin">
	<div class="before span3">Banca</div>
	<div class="span-20">
		<input class="after span123" type="text" id="banca" style="text-transform: capitalize;" tips="<div class='tips-n'><strong>Informatie optionala</strong><br>Se poate completa denumirea si sucursala sau agentia bancii.</div>" autocomplete="off">
	</div>
</div>
<div class="span-23 box-margin">	
	<div class="before span3">IBAN</div>
	<div class="span-20">
		<input class="after span123 validator-corect" style="text-transform: uppercase;" type="text" id="iban" onblur="verifica_iban()" tip="<div class='tips-c' style='font-weight: bold;'>Informatie optionala</div>" fault="0" autocomplete="off">
		<span id="verifica-iban" class="input-helper"></span>
	</div>
</div>
<div class="span-23 box-margin-m">
	<div class="span-9 back-box" style="margin-right: 15px;"><a href="javascript: pas1()" class="back" style="font-size: 1.2em;">Inapoi</a></div>
	<div class="span-4"><button class="fg-button orange ui-corner-all" style="padding: .5em 1.4em;" type="submit"><span class="button-text">Mai departe</span></button></div>
	<div class="span-1 box-loading ui-helper-hidden" style="margin-top: 2px;"></div>
</div>
</form>

	</div>
</div>

<div class="span-22" id="pas3" style="margin-top: -10px;">
	<div class="span-20 form-header ui-corner-top">Informatii perioada de valabilitate</div>
	<div class="span-20 form-content" style="min-height: 170px;">

<form id="form3" onsubmit="return false;">
<div class="span-23">
	<div class="span-10 last">
		<div class="span3 before" style="padding-left:5px;">Perioada</div>
		<div class="span-7 last">
			<select class="span-7 after-s" id="perioada" onchange="change($(this).val())">
				<option value="3">3 Luni</option>
				<option value="5.4">6 Luni - discount 10%</option>
				<option value="9.6">12 Luni - discount 20%</option>
			</select>
		</div>
		<div class="span-9 last text-upgrade-s box-margin-m">
			<p>Ai discounturi de pana la 20% si poti extinde oricand aceasta perioada. In plus, vei fi anuntat automat prin email inaintea expirarii perioadei de valabilitate.</p>
		</div>
		<div class="span-9 last">
			<a class="link text-upgrade-s" href="#" id="text-adv">Configurare instiintare de plata</a>
			<div class="span-10 ui-helper-hidden" id="box-adv" style="padding: 10px 5px;">
<!-- Box adv -->
<div class="span-10 box-margin-m">
	<div class="span-3 before-s" style="padding-top: 2px;">Inceputul perioadei de valabilitate</div>
	<div class="span6">
		<input value="<?php echo date('d-m-Y'); ?>" class="span6 after-s img-data validator-date-ddmmyyyy" type="text" id="data_ini">
	</div>
</div>
<div class="span-10">
	<div class="span-3 before-s" style="padding-top: 2px;">Data scadenta a instiintarii de plata</div>
	<div class="span6">
		<input value="<?php echo date('d-m-Y',strtotime('+5 days')); ?>" class="span6 after-s img-data validator-date-ddmmyyyy" type="text" id="data_scadenta">
	</div>
</div>
<!-- End Box adv -->
			</div>
		</div>
	</div>

	<div class="span-10 last" style="margin-left: 40px;">
		<div class="span-8 text-pret" style="text-align: center;" id="text-money" alt="n9p3c2S">
			<span style="font-size: 2em; padding-right: 5px;">45</span>
			<span style="font-size: 1.8em;">Lei</span>
		</div>
		<div class="span-9" style="margin-top: 10px;">
			<button class="fg-button orange ui-corners-all" type="submit">
				<div class="span8">
					<div class="span-7 last">
						<div class="span-7 text-button">Creare cont</div>
						<div class="span-7 text-button-s" style="font-size: 1.1em;">Noul tau cont Basic este gata!</div>
					</div>
					<div class="span-arrow"></div>
				</div>
			</button>
		</div>
		<div class="span-4 img-loader ui-helper-hidden" style="margin-left: 90px;"></div>
	</div>
</div>
</form>

	</div>
</div>
</div>
<!-- End tabs -->
</div>

</div>
<!-- End container -->
</div>