<script>
function change(val){
	var textMoney; var textMoney0;
	var money = 15; var valuta = 'Lei';
	textMoney0 = val*money;
	textMoney = new String(textMoney0);

	if (textMoney.indexOf('.') != -1) textMoney = $.fn.autoNumeric.Format('text-money',textMoney);
	$('#text-money').html('<span style="font-size: 2em; padding-right: 5px;">'+textMoney+'</span><span style="font-size: 1.8em;">'+valuta+'</span>');
}
</script>
<div class="container">
<div class="span-24 last box-main" style="margin-top: -10px;">

<!-- Tabs -->
<div class="span-23 box-meniu" id="meniu">
	<ul class="meniu">
		<li class="span-7 box1">
			<div class="span-2 last pas-meniu" onclick="pas1(false)">1</div>
			<div class="span-5"><a href="#pas1" class="pas1">Informatii cont nou</a></div>
			<div class="span-5"><p class="text-meniu">Aici se colecteaza informatiile necesare accesului in program</p></div>
		</li>
		<li class="span-7 box2">
			<div class="span-2 last pas-meniu" onclick="pas2(false)">2</div>
			<div class="span-5"><a href="#pas2" class="pas2">Informatii platitor</a></div>
			<div class="span-5"><p class="text-meniu">Aici se colecteaza informatiile asociate platitorului, care vor aparea pe instiintarea de plata.</p></div>
		</li>
		<li class="span-7 box3">
			<div class="span-2 last pas-meniu" onclick="pas3(false)">3</div>
			<div class="span-5"><a href="#pas3" class="pas3">Finalizare</a></div>
			<div class="span-5"><p class="text-meniu">Aici poti sa stabilesti perioada de valabilitate a contului tau.</p><p>Si contul tau este gata!</p></div>
		</li>
	</ul>
<div class="span-22" id="pas1" style="margin-top: -10px;">
	<div class="span-20 form-header ui-corner-top">Informatii cont nou</div>
	<div class="span-20 form-content">

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

<div class="span-23 box-helper" style="margin-top: 50px;">
	<div class="span-11 last">
		<div class="span-10 text-helper">Cum pot achita suma aferenta contului?</div>
		<div class="span-10 text-helper-small" style="margin-bottom: 10px;">Dupa crearea contului vei primi pe email o instiintare de plata in care vei gasi toate informatiile necesare pentru efectuarea platii. Plata se va face prin virament bancar in contul bancar precizat in instiintarea de plata.</div>
		<div class="span-10 text-helper">In cat timp pot achita suma aferenta contului?</div>
		<div class="span-10 text-helper-small">Poti achita suma aferenta contului tau in termen de maxim 5 zile. Contul tau va fi sters in cazul in care plata nu este confirmata in sistemul nostru informatic.</div>
	</div>
	<div class="span-11">
		<div class="span-10 text-helper">Cand pot emite prima factura?</div>
		<div class="span-10 text-helper-small" style="margin-bottom: 10px;">Poti emite prima factura dupa ce contul tau devine <strong>activ</strong> - imediat dupa confirmarea platii in sistemul nostru informatic. Vei fi anuntat pe email in momentul in care poti folosi contul tau.</div>
		<div class="span-10 text-helper">Pot sa aleg un alt tip de cont?</div>
		<div class="span-10 text-helper-small">Poti alege oricand un alt tip de cont dupa autentificarea in program prin accesarea paginii <strong>Tipuri de conturi</strong> si selectarea noului cont. Poti sa maresti sau sa micsorezi numarul maxim de facturi lunare si de furnizori in functie de necesitatile tale.</div>
	</div>
</div>
</div>
<div class="ascuns"><input type="text" id="ascuns"></div>
</div>