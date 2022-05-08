<div class="container-s" style="margin-top: 100px;">
<script>
$(document).ready(function(){
	$('.after').focus(
		function(){ $(this).css('border','solid 1px #f3c809'); }
	).blur(
		function(){ $(this).css('border','solid 1px #ccc'); }
	);
	$('.orange').hover(
		function(){
			$(this).children('.button-text').addClass('underline');
		},
		function(){
			$(this).children('.button-text').removeClass('underline');
		}
	);
	$('.back').hover(
		function(){ $(this).css('color','#333'); },
		function(){ $(this).css('color','#666'); }
	);
	$('#cod').focus().keyup(function(){
		if ($(this).hasClass('.failed')) $(this).removeClass('failed');
	});
	$('#form').validationAideEnable(
		null,
		{ fieldErrorCssClass: 'failed', showSummary: false },
		null,
		chk
	);
});
function chk(s){
	if (!s)	return false;
	else{
		$(document).find('.after').css('border', 'solid 1px #ccc');
		verifica_cod();
		return false;
	}
}
function verifica_cod(){
	var query = '&cod='+$('#cod').val();
	$.ajax({type:'GET',url:'/includes/functii.php?op=verifica_cod'+query,success:function(raspuns){
			if (raspuns) document.location.href = raspuns;
			else{
				$('#cod').val('').focus();
				notify_bar(10,'Cod de acces incorect!');
			}
		},beforeSend:function(){
			$('.img-loader').removeClass('ui-helper-hidden');
		},complete:function(){
			$('.img-loader').addClass('ui-helper-hidden');
		}
	});
}
</script>
<div class="span-17 box-login">
	<div class="span-15 ui-widget-header box-login-header">
		<div class="span-4">Vizualizare factura</div>
	</div>
<div class="span-4 login-text">
	<div class="span-4">
		<div class="span-4 login-text-top" style="color: #2e6e9e; margin: 10px 0 13px 0;">
			<div class="span2 text-facturi">Facturi</div>
			<div class="span2 icon-123-s"></div>
		</div>
		<div class="span-4 img-loader ui-helper-hidden" style="margin-left: 1px;"></div>
	</div>
</div>
<div class="span-12" id="box-email" style="margin-left: 20px;">
	<div class="span-12 login-row" style="margin-bottom: 10px;">
		<div class="span-11 login-email">Validare cod de acces</div>
		<div class="span-11 login-mail">Dupa introducerea codului de acces primit pe email, vei putea tipari sau salva factura in format PDF.</div>
	</div>
	<form id="form" onsubmit="return false;">
	<div class="span-12 login-row" style="margin-bottom: 10px;">
		<div class="before span-3" style="text-align: left; margin: 2px 10px 0 0;">Cod de acces</div>
		<div class="box-after span-8">
			<input class="after span-8 validator-required" type="text" id="cod" autocomplete="off">
		</div>
	</div>
	<div class="span-12 login-row">
		<div class="span-8" style="text-align: left;"><a href="../" class="back" style="float: left; margin-left: 2px;">Inapoi</a></div>
		<div class="span-3" style="text-align: right;"><button class="fg-button orange ui-corner-all" type="submit"><span class="button-text">Validare</span></button></div>
	</div>
	</form>
</div>
</div>
<!-- End container-s -->
</div>