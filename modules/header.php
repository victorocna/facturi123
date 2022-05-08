<script>
$(document).ready(function(){
	$('.h-link').hover(
		function(){ $(this).addClass('h-hover').removeClass('h-link'); },
		function(){ $(this).addClass('h-link').removeClass('h-hover'); }
	);
});
function iesire(){
	$("#dialog_confirm").dialog('destroy');
	$("#dialog_confirm").remove();
	$("body").append('<div id="dialog_confirm" title="<div class=\'text-title\'>Iesire</div>" style="text-align:left"><div class="span-6 text-iesire">Doriti sa iesiti din program?</div>');
	$("#dialog_confirm").show();
	$("#dialog_confirm").dialog({
		height: 'auto',
		width: 400,
		modal:true,
		resizable: false,
		overlay:{
				"background-color": "#333",
				"opacity": "0.75",
				"-moz-opacity": "0.75"
		},
		buttons:{
			"Da":function(){
				$("#dialog_confirm").dialog('close');
				var query = '&subdomeniu=<?php echo $subdomeniu; ?>';
				$.ajax({type:'GET',url:'/includes/functii.php?op=logout'+query,success:function(raspuns){
						eval (raspuns);
					}
				});
			},
			"Nu":function(){
				$("#dialog_confirm").dialog('close');
			}
		}
	});
}
</script>
<div class="container" style="background: transparent; padding: .5em 0 1em 0;">
	<div class="span-24 h-header">
<?php
echo '
<div class="span-13 h-primary">
	<div class="span-13 box-titlu">
	<a href="/'.$subdomeniu.'/facturi/'.$_GET['idf'].'/">
		<div class="span2 text-titlu">Facturi</div>
		<div class="span2 icon-123"></div>
	</a>
	</div>
	<div class="span-13 h-user">'.$_SESSION['user'].'</div>
</div>
<div class="span-10 h-options">
	<a class="h-link" id="cont-tip" href="/'.$subdomeniu.'/cont-tip/'.$_GET['idf'].'/">Tipuri de cont</a>
	<a class="h-link" id="cont" href="/'.$subdomeniu.'/cont/'.$_GET['idf'].'/">Contul tau</a>
	<a class="h-link" id="ajutor" href="/ajutor/" target="tab">Ajutor</a>
	<a class="h-link" href="javascript: iesire()">Iesire</a>
</div>
';
?>
	</div>
</div>