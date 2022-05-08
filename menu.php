<?php
if ($_GET['menu'] == 2) echo "
<script>
$(document).ready(function(){
	$('.box-menu').eq(2).addClass('ui-highlight-hover').removeClass('ui-highlight');
});
</script>
";
if ($_GET['menu'] != 2) echo "
<script>
$(document).ready(function(){
	$('.box-menu').eq(".$_GET['menu'].").addClass('ui-active').removeClass('ui-default');
});
</script>
";
?>
<script>
$(document).ready(function(){
	$('.ui-default').hover(
		function(){ $(this).addClass('ui-hover').removeClass('ui-default'); },
		function(){ $(this).addClass('ui-default').removeClass('ui-hover'); }
	);
	$('.ui-highlight').hover(
		function(){ $(this).addClass('ui-highlight-hover').removeClass('ui-highlight'); },
		function(){ $(this).addClass('ui-highlight').removeClass('ui-highlight-hover'); }
	);
	$('.box-last').hover(
		function(){
			if ($('.box-options').is(':hidden')){
				$(this).addClass('ui-state-hover ui-corner-all');
				$('.span-icon').addClass('ui-icon ui-icon-triangle-1-s').removeClass('ui-triangle');
			}
		},
		function(){
			if ($('.box-options').is(':hidden')){
				$(this).removeClass('ui-state-hover ui-corner-all');
				$('.span-icon').addClass('ui-triangle').removeClass('ui-icon ui-icon-triangle-1-s');
			}
		}
	).click(function(){
		if ($('.box-options').is(':hidden')){
			$('.box-options').removeClass('ui-helper-hidden');
			$('.span-icon').addClass('ui-icon-triangle-1-n').removeClass('ui-icon-triangle-1-s');
			$(this).addClass('ui-corner-top').removeClass('ui-corner-all');
			$('#subdomeniu-menu').focus();
		}
		else{
			$('.box-options').addClass('ui-helper-hidden');
			$('.span-icon').addClass('ui-icon-triangle-1-s').removeClass('ui-icon-triangle-1-n');
			$(this).addClass('ui-corner-all').removeClass('ui-corner-top');
		}
	});
	$("#form-menu").validationAideEnable(
		null,
		{ fieldErrorCssClass: 'failed', showSummary: false },
		null,
		check_menu
	);
});
function check_menu(s){
	if (!s)	return false;
	else{
		$(document).find('.after').css('border','solid 1px #ccc');
		login();
		return false;
	}	
}
function login(){
	var query = '';
	$.each($('#form-menu div input'),function(i,obj){
		if ($(obj).val()) query += '&'+$(obj).attr('id').slice(0,-5)+'='+$(obj).val();
	});
	$.ajax({type:'GET',url:'/includes/functii123.php?op=login'+query,success:function(raspuns){
		if (raspuns) document.location.href = raspuns;
		else notify_bar(10,'Autentificare incorecta!');
	},beforeSend:function(){
		$('.img-loader').removeClass('ui-helper-hidden');
	},complete:function(){
		$('.img-loader').addClass('ui-helper-hidden');
	}
	});
}
</script>
<div class="container box-container ui-header ui-corner-top">
<div class="span-24 box-container-menu">
	<div class="span-6 box-title">
	<a href="/">
		<div class="text-title">
			<div class="span-2 last">Facturi</div>
			<div class="span-2 icon-123"></div>
		</div>
	</a>
	</div>
	<div class="span3 box-menu ui-default"><a href="/">Prima pagina</a></div>
	<div class="span3 box-menu ui-default"><a href="/avantaje/">Tur & Avantaje</a></div>
	<div class="span4 box-menu ui-highlight"><a href="/inscriere/" class="text-menu2">Preturi & Inscriere</a></div>
	<div class="span-2 box-menu ui-default"><a href="/ajutor/">Ajutor</a></div>
	<div class="span3 box-last">
		<div class="span-2">Autentificare</div>
		<div class="box-icon ui-state-active"><div class="span-icon ui-triangle"></div></div>
	</div>
	<div class="span-8 box-options ui-state-hover ui-corner-bottom ui-corner-tl ui-helper-hidden">
	<form id="form-menu" onsubmit="return false;">
		<div class="span-7 before-s">Contul tau</div>
		<div class="span-7">
			<input class="after-s span-7 validator-required" type="text" id="subdomeniu-menu" autocomplete="off">
		</div>
		<div class="span-7 before-s">Utilizator</div>
		<div class="span-7">
			<input class="after-s span-7 validator-required" type="text" id="user-menu" autocomplete="off">
		</div>
		<div class="span-7 before-s">Parola</div>
		<div class="span-7">
			<input class="after-s span-7 validator-required" type="password" id="parola-menu" autocomplete="off">
		</div>
		<div class="span-9" style="padding: 10px 0;">
			<div class="span-4" style="margin-top: -5px;">
				<div class="span-3">
					<a href="/recuperare-cont/" class="back" style="float: left; font-size: .9em;">Ai uitat contul?</a>
				</div>
				<div class="span-3">
					<a href="/recuperare-parola/" class="back" style="float: left; font-size: .9em;">Ai uitat parola?</a>
				</div>
			</div>
			<div class="span-4">
				<div class="span3"><button class="fg-button orange" type="submit">
					<span class="button-text">Conectare</span></button>
				</div>
				<div class="span-1 box-loading ui-helper-hidden" style="margin-left: 10px;"></div>
			</div>
		</div>
	</form>
	</div>
</div>
<!-- End container -->
</div>