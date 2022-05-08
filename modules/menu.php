<?php
if (isset($_GET['menu'])) echo "
<script>
	$(document).ready(function(){
		$('#menu').tabs();
		$('#menu').tabs('select',".$_GET['menu'].");
	});
</script>
";
if (!isset($_GET['menu'])) echo "
<script>
	$(document).ready(function(){
		$('#menu').tabs();
		$('#menu').tabs('select',0);
	});
</script>
";
?>

<!--[if IE]>
<script src="/includes/js/corner.min.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
	$('.ui-corner-top').corner('5px top');
});
</script>
<![endif]-->

<div class="container" style="background: transparent;">
<?php
echo '
<div id="menu">
	<ul>
		<li><a href="#facturi" onclick="document.location.href=\'/'.$subdomeniu.'/facturi/'.$_GET['idf'].'/\'">Facturi</a></li>
		<li><a href="#clienti" onclick="document.location.href=\'/'.$subdomeniu.'/clienti/'.$_GET['idf'].'/\'">Clienti</a></li>
		<li><a href="#furnizori" onclick="document.location.href=\'/'.$subdomeniu.'/furnizori/'.$_GET['idf'].'/\'">Furnizori</a></li>
		<li><a href="#produse" onclick="document.location.href=\'/'.$subdomeniu.'/produse/'.$_GET['idf'].'/\'">Produse</a></li>
	</ul>
	<div id="facturi"></div>
	<div id="clienti"></div>
	<div id="furnizori"></div>
	<div id="produse"></div>
</div>
';
?>
</div>