<script>
$(document).ready(function(){
	$('.a-help').hover(
		function(){
			if (!$(this).hasClass('.a-help-active')) $(this).addClass('a-help-hover').removeClass('a-help');
		},
		function(){
			if (!$(this).hasClass('.a-help-active')) $(this).addClass('a-help').removeClass('a-help-hover');
		}
	).click(function(){
		if (!$(this).hasClass('.a-help-active')){
			$(this).addClass('a-help-active');
			$(this).parent().addClass('li-help-active').removeClass('li-help');
		}
		else{
			$(this).removeClass('a-help-active');
			$(this).parent().addClass('li-help').removeClass('li-help-active');
		}
	});
});
function details(id){
	if ($('#detalii-'+id).length == 1){
		$('#details-'+id).show('blind',500);
		$('#detalii-'+id).attr('id','hide-detalii-'+id);
		return;
	}
	if ($('#hide-detalii-'+id).length == 1){
		$('#details-'+id).hide('blind',500);
		$('#hide-detalii-'+id).attr('id','detalii-'+id);
		return;
	}
}
</script>

<div class="container">
<div class="span-23 box-main">

<div class="span-11" style="font-size: 1.2em;">
	<div class="span-10 text-help">Facturi</div>
	<div class="span-10 box-help">
<ul>
	<li class="span-10 li-help"><a href="javascript:details('10')" class="a-help" id="detalii-10">Cum emit o factura?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-10">
		<div class="span-10 box-margin-s">Pentru a emite facturi noi apasa butonul <span class="icon-add-s">Emite factura</span> din pagina de facturi. In pagina nou aparuta completeaza factura si apasa butonul de emitere.</div>
		<div class="span-10">Pentru a schimba cota TVA, valuta, data scadenta sau pentru a adauga delegati sau reprezentanti acceseaza linkul <span class="text-strong">Configurare avansata factura</span></div>
	</div>
	
	<li class="span-10 li-help"><a href="javascript:details('11')" class="a-help" id="detalii-11">Cum salvez o factura ca draft?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-11">
		<div class="span-10 box-margin-s">Dupa completarea informatiilor despre client si furnizor, poti salva o factura ca draft acccesand linkul <span class="text-strong">Salveaza draft</span> aflat in stanga butonului de emitere.</div>
		<div class="span-10">In plus, facturile se autosalveaza in timp ce le completezi.</div>
	</div>

	<li class="span-10 li-help"><a href="javascript:details('12')" class="a-help" id="detalii-12">Cum emit o factura salvata ca draft?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-12">
		<div class="span-10">Pentru emiterea unei factura salvate ca draft selecteaza optiunea <span class="text-strong">Emitere draft</span> din meniul aflat in stanga facturii cu starea Draft.</div>
	</div>

	<li class="span-10 li-help"><a href="javascript:details('13')" class="a-help" id="detalii-13">Cum editez o factura emisa?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-13">
		<div class="span-10 box-margin-s">Pentru a edita o factura apasa butonul <span class="icon-edit-s">Editare factura</span> din pagina de vizualizare a facturii sau selecteaza optiunea <span class="text-strong">Editare factura</span> din meniul aflat in stanga seriei facturii.</div>
		<div class="span-10"><span class="text-strong">Atentie!</span> In cazul in care totalul facturii in curs de editare este mai mic decat sumele incasate pana in acel moment, toate incasarile vor fi sterse.</div>
	</div>

	<li class="span-10 li-help"><a href="javascript:details('14')" class="a-help" id="detalii-14">Cum tiparesc o factura?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-14">
		<div class="span-10 box-margin-s">Pentru a tipari o factura apasa butonul <span class="icon-pdf-s">Tiparire factura</span> din pagina de vizualizare a facturii sau selecteaza optiunea <span class="text-strong">Tiparire factura</span> din meniul aflat in stanga seriei facturii.</div>
		<div class="span-10">Intr-o fereastra noua va fi generat un document PDF si vei putea tipari sau salva factura in format PDF.</div>
	</div>

	<li class="span-10 li-help"><a href="javascript:details('15')" class="a-help" id="detalii-15">Cum tiparesc o chitanta?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-15">
		<div class="span-10 box-margin-s">Pentru a tipari o chitanta apasa butonul <span class="icon-history-s">Istoric factura</span> din pagina de vizualizare a facturii, iar din zona Incasari acceseaza linkul <span class="text-strong">Tipareste chitanta acum</span></div>
		<div class="span-10">Intr-o fereastra noua va fi generat un document PDF si vei putea tipari sau salva chitanta in format PDF.</div>
	</div>

	<li class="span-10 li-help"><a href="javascript:details('16')" class="a-help" id="detalii-16">Cum incasez o factura?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-16">
		<div class="span-10 box-margin-s">Pentru a incasa o factura apasa butonul <span class="icon-incasare-s">Incasare factura</span> din pagina de vizualizare a facturii sau selecteaza optiunea <span class="text-strong">Incasare factura</span> din meniul aflat in stanga seriei facturii.</div>
		<div class="span-10">Pentru facturile cu totalul general mai mic de 5.000 Lei poti <span class="text-strong">incasa factura cu chitanta</span>, iar aceasta poate fi tiparita imediat dupa incasarea facturii.</div>
	</div>
	
	<li class="span-10 li-help"><a href="javascript:details('17')" class="a-help" id="detalii-17">Cum trimit pe email o factura?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-17">
		<div class="span-10 box-margin-s">Pentru a trimite o factura emisa pe email apasa butonul <span class="icon-mail-s">Trimite email</span> din pagina de vizualizare a facturii sau selecteaza optiunea <span class="text-strong">Trimite email</span> din meniul aflat in stanga seriei facturii.</div>
		<div class="span-10">Clientul tau va primi automat pe email un link la facturii de unde o poate tipari sau salva in format PDF.</div>
	</div>
	
	<li class="span-10 li-help"><a href="javascript:details('18')" class="a-help" id="detalii-18">Cum vizualizez istoricul unei facturi?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-18">
		<div class="span-10 box-margin-s">Pentru a vizualiza istoricul unei facturi emise, in pagina de vizualizare a facturii, apasa butonul <span class="icon-history-s">Istoric factura</span> sau selecteaza optiunea <span class="text-strong">Istoric factura</span> din meniul aflat in stanga seriei facturii.</div>
		<div class="span-10">In cazul in care incasarea a fost facuta cu chitanta, din zona de <span class="text-strong">Incasari</span> poti tipari acest document.</div>
	</div>
	
	<li class="span-10 li-help"><a href="javascript:details('19')" class="a-help" id="detalii-19">Cum vizualizez mai multe modele de facturi?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-19">
		<div class="span-10 box-margin-s">Pentru conturile <span class="text-strong">Basic</span>, <span class="text-strong">Best</span> si <span class="text-strong">Premium</span> poti alege intre mai multe modele de facturi. In pagina de vizualizare a facturii, selecteaza din meniul <span class="text-strong">Model factura</span> varianta preferata.</div>
	</div>
</ul>
	</div>
	<div class="span-10 text-help">Contul tau</div>
	<div class="span-10 box-help box-margin">
<ul>
	<li class="span-10 li-help"><a href="javascript:details('20')" class="a-help" id="detalii-20">Cum modific utilizatorul sau parola?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-20">
		<div class="span-10 box-margin-s">Dupa accesarea paginii <span class="text-strong">Contul tau</span>, poti modifica utilizatorul, emailul si parola salvate anterior.</div>
		<div class="span-10"><span class="text-strong">Atentie!</span> Adresa de conectare nu poate fi modificata.</div>
	</div>

	<li class="span-10 li-help"><a href="javascript:details('21')" class="a-help" id="detalii-21">Cum aleg un alt tip de cont?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-21">
		<div class="span-10 box-margin-s">Poti alege oricand un alt tip de cont prin accesarea paginii <span class="text-strong">Tipuri de conturi</span> si selectarea noului cont.</div>
		<div class="span-10 box-margin-s">Pentru conturile <span class="text-strong">Basic</span>, <span class="text-strong">Best</span> si <span class="text-strong">Premium</span> vei primi pe email o instiitare de plata cu suma care trebuie platita pentru contul selectat.</div>
		<div class="span-10">Noul cont va deveni activ imediat dupa confirmarea platii in sistemul nostru informatic.</div>
	</div>
	
	<li class="span-10 li-help"><a href="javascript:details('22')" class="a-help" id="detalii-22">Ai uitat contul sau parola?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-22">
		<div class="span-10 box-margin-s">In cazul in care ai uitat contul sau parola poti accesa pagina de <a class="link" href="/recuperare-cont/">recuperare cont</a> sau de <a class="link" href="/recuperare-parola/">recuperare parola</a>.</div>
		<div class="span-10">Dupa completarea adresei de email si a utilizatorului, iti vom trimite informatiile asociate contului tau sau o parola noua ce poate fi modificata.</div>
	</div>
</ul>
	</div>
</div>

<div class="span-11" style="font-size: 1.2em;">
	<div class="span-10 text-help">Clienti</div>
	<div class="span-10 box-help box-margin">
<ul>
	<li class="span-10 li-help"><a href="javascript:details('30')" class="a-help" id="detalii-30">Cum adaug un client?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-30">
		<div class="span-10 box-margin-s">Pentru a adauga clienti noi apasa butonul <span class="icon-add-s">Adauga client</span> din pagina de clienti. In fereastra nou aparuta completeaza datele clientului si apasa butonul de salvare.</div>
		<div class="span-10">In plus, poti adauga un client din pagina de emitere facturi, accesand linkul <span class="text-strong">Adauga client</span> din zona <span class="text-strong">Informatii client</span>.</div>
	</div>

	<li class="span-10 li-help"><a href="javascript:details('31')" class="a-help" id="detalii-31">Cum modific datele unui client?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-31">
		<div class="span-10 box-margin-s">Pentru a modifica datele unui client apasa butonul <span class="text-strong">Modifica</span> aflat in dreapta clientului selectat.</div>
		<div class="span-10"><span class="text-strong">Atentie!</span> Prin modificarea datelor unui client, toate facturile emise pentru respectivul client vor fi modificate!</div>
	</div>

	<li class="span-10 li-help"><a href="javascript:details('32')" class="a-help" id="detalii-32">Cum emit o factura pentru un client?</a></li>
	<div class="span-10 details-help-s ui-helper-hidden" id="details-32">
		<div class="span-10">Pentru a emite o factura pentru un anumit client apasa butonul <span class="text-strong">Emite factura</span> aflat in dreapta clientului selectat.</div>
	</div>
</ul>
	</div>
	<div class="span-10 text-help">Furnizori</div>
	<div class="span-10 box-help box-margin">
<ul>
	<li class="span-10 li-help"><a href="javascript:details('40')" class="a-help" id="detalii-40">Cum adaug un furnizor?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-40">
		<div class="span-10 box-margin-s">Pentru a adauga un furnizor nou, din pagina de furnizori, apasa butonul <span class="icon-add-s">Adauga furnizor</span>. In fereastra nou aparuta completeaza datele furnizorului si apasa butonul de salvare.</div>
		<div class="span-10">In plus, poti adauga un furnizor din pagina de emitere facturi, accesand linkul <span class="text-strong">Adauga furnizor</span> din zona <span class="text-strong">Informatii furnizor</span>.</div>
	</div>
	
	<li class="span-10 li-help"><a href="javascript:details('41')" class="a-help" id="detalii-41">Cum modific datele unui furnizor?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-41">
		<div class="span-10 box-margin-s">Pentru a modifica datele unui furnizor apasa butonul <span class="text-strong">Modifica</span> aflat in dreapta furnizorului selectat.</div>
		<div class="span-10"><span class="text-strong">Atentie!</span> Prin modificarea datelor unui furnizor, toate facturile emise pentru respectivul furnizor vor fi modificate!</div>
	</div>
	
	<li class="span-10 li-help"><a href="javascript:details('42')" class="a-help" id="detalii-42">Cum emit o factura pentru un furnizor?</a></li>
	<div class="span-10 details-help-s ui-helper-hidden" id="details-42">
		<div class="span-10">Pentru a emite o factura pentru un anumit furnizor apasa butonul <span class="text-strong">Emite factura</span> aflat in dreapta furnizorului selectat.</div>
	</div>
	
	<li class="span-10 li-help"><a href="javascript:details('43')" class="a-help" id="detalii-43">Cum personalizez o factura?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-43">
		<div class="span-10 box-margin-s">Pentru conturile <span class="text-strong">Basic</span>, <span class="text-strong">Best</span> si <span class="text-strong">Premium</span> poti adauga o sigla furnizorului - sigla va aparea pe facturile in format PDF</div>
		<div class="span-10 box-margin">Sigla trebuie sa aiba formatul <span class="text-strong">jpg</span>, <span class="text-strong">png</span> sau <span class="text-strong">gif</span> si poate fi adaugata in paginile de adaugare sau modificare furnizori, apasand butonul <span class="text-strong">Adauga sigla</span>.</div>
	</div>
</ul>
	</div>
	<div class="span-10 text-help">Produse</div>
	<div class="span-10 box-help box-margin">
<ul>
	<li class="span-10 li-help"><a href="javascript:details('50')" class="span-10 a-help" id="detalii-50">Cum adaug un produs?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-50">
		<div class="span-10 box-margin-s">Pentru a adauga un produs nou, din pagina de produse, apasa butonul <span class="icon-add-s">Adauga produs</span>. In fereastra nou aparuta completeaza datele produsului si apasa butonul de salvare.</div>
		<div class="span-10">In plus, poti adauga un produs din pagina de emitere facturi, completand liniile facturii. Dupa emiterea facturii, produsele vor fi salvate automat.</div>
	</div>
	
	<li class="span-10 li-help"><a href="javascript:details('51')" class="span-10 a-help" id="detalii-51">Cum modific datele un produs?</a></li>
	<div class="span-10 details-help ui-helper-hidden" id="details-51">
		<div class="span-10 box-margin-s">Pentru a modifica datele unui produs apasa butonul <span class="text-strong">Modifica</span> aflat in dreapta produsului selectat.</div>
		<div class="span-10"><span class="text-strong">Atentie!</span> Prin modificarea datelor unui produs, toate facturile emise pentru respectivul produs vor fi modificate!</div>
	</div>
	
	<li class="span-10 li-help"><a href="javascript:details('52')" class="a-help" id="detalii-52">Cum emit o factura pentru un produs?</a></li>
	<div class="span-10 details-help-s ui-helper-hidden" id="details-52">
		<div class="span-10">Pentru a emite o factura pentru un anumit produs apasa butonul <span class="text-strong">Emite factura</span> aflat in dreapta produsului selectat.</div>
	</div>
</ul>
	</div>
</div>
</div>

<div class="span-23 box-main">
<div class="span-23 box-helper" style="margin-top: -30px;">
	<div class="span-11">
		<div class="span-10 text-helper">Nu ai gasit raspuns la intrebarea ta?</div>
		<div class="span-10 text-helper-small" style="margin-bottom: 10px;">
			<p>Poti oricand sa ne contactezi <a class="link" href="/contact/">accesand pagina de contact</a> sau prin email la adresa contact@facturi123.ro</p>
			<p>Iti vom raspunde in cel mai scurt timp, oricare ar fi intrebarea ta.</p>
		</div>
		<div class="span-10 text-helper">Ai o sugestie pentru imbunatatirea programului?</div>
		<div class="span-10 text-helper-small">Ajuta-ne sa iti imbunatatim utilizarea programului!<br>Poti oricand sa ne contactezi <a class="link" href="/imbunatatiri/">accesand pagina de imbunatatiri</a> sau prin email la adresa imbunatatiri@facturi123.ro</div>
	</div>
	<div class="span-11">
		<div class="span-10 text-helper">Ce trebuie sa instalez pentru a folosi Facturi123?</div>
		<div class="span-10 text-helper-small" style="margin-bottom: 10px;">Singurul program de care ai nevoie este Adobe Reader. Daca nu il ai deja instalat, il poti descarca <a class="link" href=" http://get.adobe.com/reader/" target="tab">de aici</a><br>Nu trebuie sa instalezi sau sa descarci altceva!</div>
		<div class="span-10 text-helper">Ce browsere web pot folosi?</div>
		<div class="span-10 text-helper-small">Poti folosi <strong>orice browser web</strong> pentru a-ti accesa contul si a putea emite facturi. In plus, fiind un program online, poti emite facturi de oriunde ai acces la internet.</div>
	</div>
</div>

</div>
</div>