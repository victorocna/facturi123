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
	$('#email').keyup(function(){
		if ($(this).hasClass('.failed')) $(this).removeClass('failed');
	});
	$('#form').validationAideEnable(
		null,
		{ fieldErrorCssClass: 'failed', showSummary: false },
		null,
		chk
	);
<?php
if (isset($_GET['user'])) echo '$("#parola").focus();';
if (!isset($_GET['user'])) echo '$("#user").focus();';
?>
});
function confirm(){
	$('#box-normal').fadeOut(100,function(){
		$('#box-email').fadeIn(100);
		$('#email').focus();
	});
}
function back(){
	$('#box-email').fadeOut(100,function(){
		$('#box-normal').fadeIn(100);
		$('#user').focus();
	});
}
function login(){
	if ($('#user').val() && $('#parola').val()){
		var query = '&subdomeniu='+$('#subdomeniu').text();
		$.each($('input'),function(i,obj){
			if ($(obj).val()) query += '&'+$(obj).attr('id')+'='+$(obj).val();
		});
		$.ajax({type:'GET',url:'../includes/functii.php?op=login'+query,success:function(raspuns){
			if (raspuns) document.location.href = raspuns;
			else{
				notify_bar(10,'Autentificare incorecta. Incercati din nou!');
				$("#parola").val('').blur();
				$("#user").val('').focus();
			}
		},beforeSend:function(){
			$('.img-loader').removeClass('ui-helper-hidden');
		},complete:function(){
			$('.img-loader').addClass('ui-helper-hidden');
		}
		});
	}
	else if ($('#user').val() == ''){
		$('input').css('border','solid 1px #ccc');
		$('#user').focus();
	}
	else{
		$('input').css('border','solid 1px #ccc');
		$('#parola').focus();
	}
}
function chk(s){
	if (!s){
		window.top.notify_bar(10,'Email incorect. Incercati din nou!');
		return false;
	}
	else{
		$(document).find('.after').css('border', 'solid 1px #ccc');
		email_parola();
		return false;
	}
}
function email_parola(){
	var query = '&email='+$('#email').val();
	$.ajax({type:'GET',url:'../includes/functii.php?op=email_parola'+query,success:function(raspuns){
			if (raspuns == 1){
				$('#email').val('').blur();
				notify_bars(20,'Emailul a fost trimis! Vei primi in curand o parola noua ce poate fi modificata ulterior.');
				back();
			}
			if (raspuns == 0) notify_bar(20,'Eroare! Emailul nu este inregistrat pentru contul <span class="activ capitalize">'+$('#subdomeniu').text()+'</span>');
			if (raspuns == -1) notify_bar(20,'Eroare! Emailul nu a putut fi trimis.');
		},beforeSend:function(){
			$('.box-loading-s').removeClass('ui-helper-hidden');
		},complete:function(){
			$('.box-loading-s').addClass('ui-helper-hidden');
		}
	});
}
</script>
<div class="span-17 box-login">
	<div class="span-15 ui-widget-header box-login-header">
		<div class="span-4">Autentificare</div>
<?php
echo '<div class="span-10 capitalize" id="subdomeniu" style="margin-left: 10px;">'.$subdomeniu.'</div>';
?>
	</div>
<div class="span-4 login-text">
	<div class="span-4">
		<div class="span-4 login-text-top" style="color: #2e6e9e; margin: 5px 0 10px 0;">
			<div class="span2 text-facturi">Facturi</div>
			<div class="span2 icon-123-s"></div>
		</div>
		<div class="span-4 img-loader ui-helper-hidden" style="margin-left: 1px;"></div>
	</div>
</div>
<div class="span-12" id="box-normal" style="margin-left: 20px;">
	<form onsubmit="login(); return false;">
	<div class="span-12 login-row">
		<div class="before span-2">Utilizator</div>
		<div class="box-after span-9">
			<input value="<?php echo $_GET['user']; ?>" class="after span-9" type="text" id="user" autocomplete="off">
		</div>
	</div>
	<div class="span-12 login-row" style="margin-bottom: 10px;">
		<div class="before span-2">Parola</div>
		<div class="box-after span-9">
			<input class="after span-9" type="password" id="parola" autocomplete="off">
		</div>
	</div>
	<div class="span-12 login-row">
		<div class="span-4"><a href="javascript: confirm()" class="back" style="float: left; padding-left: 2px;">Ai uitat parola?</a></div>
		<div class="span-7" style="text-align: right;">
			<button class="fg-button orange ui-corner-all" style="padding: .5em 1.4em;" type="submit" onclick="login()"><span class="button-text">Conectare</span></button>
		</div>
	</div>
	</form>
</div>
<div class="span-12 ui-helper-hidden" id="box-email" style="margin: -8px 0 0 20px;">
	<div class="span-12 login-row" style="margin-bottom: 10px;">
		<div class="span-11 login-email">Ai uitat parola?</div>
		<div class="span-11 login-mail">Dupa introducerea adresei de email asociata utilizatorului tau, iti vom trimite o parola noua ce poate fi modificata ulterior.</div>
	</div>
	<form id="form" onsubmit="return false;">
	<div class="span-12 login-row" style="margin-bottom: 10px;">
		<div class="before span-2" style="text-align: left;">Email</div>
		<div class="box-after span-9">
			<input class="after span-9 validator-email" type="text" id="email" autocomplete="off">
		</div>
	</div>
	<div class="span-13 login-row">
		<div class="span-8"><a href="javascript: back()" class="back" style="font-size: 1.1em; padding-left: 2px;">Pagina anterioara</a></div>
		<div class="span-3" style="text-align: right;">
			<button class="fg-button orange ui-corner-all" style="padding: .5em 1.4em;" type="submit"><span class="button-text">Trimite</span></button>
		</div>
		<div class="span-1 box-loading-s ui-helper-hidden"></div>
	</div>
	</form>
</div>
</div>
<!-- End container-s -->
</div>