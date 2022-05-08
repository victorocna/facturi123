<?php
session_start();
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	ob_start("ob_gzhandler");
else
	ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<meta name="description" content="Program de facturare online. Incearca gratuit Facturi123 si emiti facturi in 2 minute. Nu trebuie sa instalezi nimic!" />
<meta name="keywords" content="facturi, factura, facturare, factura online, factura fiscala, program facturare, factura model, factura proforma, emitere facturi" />
<meta name="google-site-verification" content="WVW5SBNqmj0PGmtqjOXmSOuh1OHn1gnwNAMvNHIxnBk" />
<?php
if (isset($_GET['title'])) echo '<title>Facturi123 | '.$_GET['title'].'</title>';
else echo '<title>Facturi123</title>';
?>
<link href="/imagini/123/favicon.ico" type="image/x-icon" rel="shortcut icon"> 
<link href="/imagini/123/favicon.ico" type="image/x-icon" rel="icon">

<link href="/css/style.min.css" rel="stylesheet" type="text/css" media="screen">
<link href="/includes/css/123.min.css" rel="stylesheet" type="text/css" media="screen">
<!--[if IE]><link href="/css/style-ie.css" rel="stylesheet" type="text/css" media="screen"><![endif]-->
<!--[if IE]><link href="/includes/css/screen-ie.min.css" rel="stylesheet" type="text/css" media="screen"><![endif]-->

<script src="/includes/js/jquery.min.js" type="text/javascript"></script>
<script src="/includes/js/123.min.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
	$('.after, .after-s, .after-adv, .after-xs').focus(
		function(){ $(this).css('border', 'solid 1px #f3c809'); }
	).blur(
		function(){ $(this).css('border', 'solid 1px #aaa'); }
	);
	$('.orange, .button-mod').hover(
		function(){ $(this).children('.button-text').addClass('underline'); },
		function(){ $(this).children('.button-text').removeClass('underline'); }
	);
	$('.back').hover(
		function(){ $(this).css('color', '#333 !important'); },
		function(){ $(this).css('color', '#666 !important'); }
	);
	$('input[tips]').qtip({
		position:{
			corner:{
				target: 'rightMiddle',
				tooltip: 'leftMiddle'
			}
		},
		style:{
			width: 280,
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
});
function hide_bar(obj){
	$('.'+obj).slideUp(300,function(){
		$(this).remove();
	});
}
// default notify
function notify_bar(seconds,text){
	$.notifyBar({
		delay: seconds*1000,
		animationSpeed: "normal",
		html: "<div class='bar-cross' onclick=\"hide_bar('bar')\"></div>"+text
	});
}
// ok notify
function notify_bars(seconds,text){
	$.notifyBar({
		delay: seconds*1000,
		animationSpeed: "normal",
		html: "<div class='bar-cross' onclick=\"hide_bar('bars')\"></div>"+text,
		obj: '<div class="bars"></div>'
	});
}
</script>
</head>
<body>