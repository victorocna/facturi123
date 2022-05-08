<script>
$(document).ready(function(){
	$('.text-bottom').hover(
		function(){ $(this).addClass('ui-bottom-hover').removeClass('ui-bottom-default'); },
		function(){ $(this).addClass('ui-bottom-default').removeClass('ui-bottom-hover'); }
	);
	$('.link-bottom').hover(
		function(){ $(this).addClass('ui-link-hover').removeClass('ui-link-default'); },
		function(){ $(this).addClass('ui-link-default').removeClass('ui-link-hover'); }
	);
	$("#email-bottom").val('Email')
	.focus(function(){
		if ($.trim($(this).val()) == 'Email') $(this).val('');
		$(this).addClass('after-hover').removeClass('after-default');
	})
	.blur(function(){
		if ($.trim($(this).val()) == ''){
			$(this).val('Email');
			$(this).addClass('after-default').removeClass('after-hover');
		}
	})
	.keyup(function(){
		if ($(this).hasClass('.failed')) $(this).removeClass('failed');
	});
	$('#form-bottom').validationAideEnable(
		null,
		{ fieldErrorCssClass: 'failed', showSummary: false },
		null,
		check_bottom
	);
});
function autentificare(){
	if ($('.box-options').is(':hidden')){
		$('html,body').animate({ scrollTop: 0 }, 1000);
		$('.box-last').addClass('ui-state-hover').trigger('click');
		$('.span-icon').addClass('ui-icon ui-icon-triangle-1-s').removeClass('ui-triangle');
	}
}
function check_bottom(s){
	if (!s){
		window.top.notify_bar(10,'Email incorect. Incercati din nou!');
		return false;
	}
	else{
		email_stiri();
		return false;
	}
}
function email_stiri(){
	if ($('#email-bottom').val() != '' &&  $('#email-bottom').val() != 'Email'){
		var query = "&email="+$('#email-bottom').val();
		$.ajax({type:'GET',url:'/includes/functii123.php?op=email_stiri'+query,success:function(raspuns){
				if (raspuns == 1){
					notify_bars(20,'Multumim! Adresa de email <span class="activ">'+$('#email-bottom').val()+'</span> a fost inregistrata.');
					$('#email-bottom').val('').blur();
				}
				else notify_bar(20,'Eroare! Emailul nu a putut fi trimis.');
			},beforeSend:function(){
				$('.box-loading-s').removeClass('ui-helper-hidden');
			},complete:function(){
				$('.box-loading-s').addClass('ui-helper-hidden');
			}
		});
	}
	else $('#email-bottom').focus();
}
</script>
<div class="container" style="margin-bottom: 10px;">
<div class="span-23 box-bottom">
<div class="span-23">
	<div class="span-13" style="margin-top: 5px;">
		<div class="span-11 box-bottom-l">
			<span class="link-bottom ui-link-default" style="margin-left: 10px; padding-right: 1.1em;"><a href="/contact/">Contacteaza-ne!</a></span>
			<span class="link-bottom ui-link-default"><a href="/imbunatatiri/">Imbunatatiri</a></span>
			<span class="link-bottom ui-link-default" style="border-right: none;"><a href="javascript:autentificare()">Autentificare</a></span>
		</div>
		<div class="span-11 box-bottom-s">
			<span class="link-bottom ui-link-default"  style="margin-left: 10px;"><a href="/termeni-si-conditii/">Termeni si conditii</a></span>
			<span class="link-bottom ui-link-default"><a href="/confidentialitate/">Confidentialitate</a></span>
			<span class="link-bottom ui-link-default" style="border-right: none;"><a href="/legislatie/">Legislatie facturi</a></span>
		</div>
	</div>
	<div class="span-9 box-bottom-m" style="padding-bottom: 0;">
	<form id="form-bottom" onsubmit="return false;">
		<div class="span-11 box-margin-s"><strong>Stiri Facturi123</strong> - Afla despre imbunatatiri, promotii si altele</div>
		<div class="span-11">
			<div class="span-7 last" style="margin-right: 5px;">
				<input class="after-default span-7 validator-email" type="text" id="email-bottom" autocomplete="off">
			</div>
			<div class="span-2">
				<button class="fg-button-xs white span-2 button-mod" type="submit"><span class="button-text">Trimite</span></button>
			</div>
			<div class="span-1 box-loading-s ui-helper-hidden" style="margin-top: 5px;"></div>
		</div>
	</div>
</div>
<div class="span-23 text-footer">
<div class="span-12">
	<span class="text-bottom ui-bottom-default"><a href="/">Prima pagina</a></span>
	<span class="text-bottom ui-bottom-default"><a href="/avantaje/">Tur & Avantaje</a></span>
	<span class="text-bottom ui-bottom-default"><a href="/inscriere/">Preturi & Inscriere</a></span>
	<span class="text-bottom ui-bottom-default" style="background: none;"><a href="/ajutor/">Ajutor</a></span>
</div>
<div class="span-10" style="margin-left: 30px;">
	<span class="text-bottom-no ui-bottom-default">2010 &copy; Creative Minds Software - Toate drepturile rezervate</span>
</div>
</div>
</div>
</div>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-17683227-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>