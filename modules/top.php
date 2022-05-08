<?php
session_start();
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	ob_start("ob_gzhandler");
else
	ob_start();
include '../includes/config.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Facturi123 | <?php echo $_GET['title']; ?></title>
<link href="/imagini/123/favicon.ico" type="image/x-icon" rel="shortcut icon"> 
<link href="/imagini/123/favicon.ico" type="image/x-icon" rel="icon">

<link href="/modules/style.css" rel="stylesheet" type="text/css" media="screen">
<link href="/includes/css/this.min.css" rel="stylesheet" type="text/css" media="screen">
<link href="/includes/css/that.min.css" rel="stylesheet" type="text/css" media="screen">
<!--[if IE]><link href="/modules/style-ie.css" rel="stylesheet" type="text/css" media="screen"><![endif]-->
<!--[if IE]><link href="/includes/css/screen-ie.min.css" rel="stylesheet" type="text/css" media="screen"><![endif]-->

<script src="/includes/js/jquery.min.js" type="text/javascript"></script>
<script src="/includes/js/this.min.js" type="text/javascript"></script>
<script src="/includes/js/that.min.js" type="text/javascript"></script>

<script>
$(document).ready(function(){
	$('.after, .after-s, .after-adv, .after-xs').focus(
		function(){ $(this).css({border: 'solid 1px #f3c809'}); }
	).blur(
		function(){ $(this).css({border: 'solid 1px #ccc'}); }
	);
	$('.button-default').hover(
		function(){
			$(this).addClass('ui-state-hover').removeClass('ui-state-default');
			$(this).children('.button-text').addClass('underline');
		},
		function(){
			$(this).addClass('ui-state-default').removeClass('ui-state-hover');
			$(this).children('.button-text').removeClass('underline');
		}
	);
	$('.orange').hover(
		function(){
			$(this).children('.button-text').addClass('underline');
		},
		function(){
			$(this).children('.button-text').removeClass('underline');
		}
	);
	$('.button-mod').hover(
		function(){
			$(this).addClass('fg-header-hover');
			$(this).children('.button-text').addClass('underline');
		},
		function(){
			$(this).removeClass('fg-header-hover');
			$(this).children('.button-text').removeClass('underline');
		}
	);
	$('.back').hover(
		function(){ $(this).css({color: '#333'}); },
		function(){ $(this).css({color: '#666'}); }
	);

	$('input[tips]').qtip({
		position:{
			corner:{
				target: 'rightMiddle',
				tooltip: 'leftMiddle'
			}
		},
		style:{
			width:{
				min: 70
			},
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
function hide_now(obj){
	$('.'+obj).fadeOut(0,function(){
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

function ro(nr){
	if (nr.length == 1) return '<strong>'+nr+'</strong>';
	else{
		if ((nr.slice(-2,-1) == 0 && nr.slice(-1) != 0) || nr.slice(-2,-1) == 1) return '<strong>'+nr+'</strong>';
		else return '<strong>'+nr+'</strong> de';
	}
}
</script>
</head>
<body>