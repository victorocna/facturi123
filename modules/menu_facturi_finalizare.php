<script>
$(document).ready(function(){
	$('.menu-tips[tips]').qtip({
		position:{
			corner:{
				target: 'leftMiddle',
				tooltip: 'rightMiddle'
			}
		},
		style:{
			width:{
				min: 50
			},
			name: 'light',
			tip:{
				corner:'rightMiddle',
				size:{ x:10, y:10 }
			},
			border:{ width: 1, radius: 5 }
		},
		hide:{
			when: 'mouseout'
		},
		show:{
			when: 'mouseover'
		}
	});
});
</script>
<div class="span1">
	<div id="sts-incasare">
<?php
	if (isset($factura['facturi']['rest_plata'])){
		if ($factura['facturi']['rest_plata'] == 0) echo '<div class="img2"></div>';
		else echo '<div class="img1"></div>';
	}
	else echo '<div class="img0"></div>';
?>
	</div>
	<div class="menu-icon">
		<div class="item">
			<div class="menu-tips icon-pdf" tips="<div class='tips-m'>Tiparire factura</div>" onclick="print_factura()"></div>
			<div class="icon-check ui-helper-hidden"></div>
		</div>
		<div class="item">
			<div class="menu-tips icon-mail" tips="<div class='tips-m'>Trimitere email</div>" onclick="email()"></div>
		</div>
		<div class="item">
<?php
	if (isset($factura['facturi']['rest_plata']) && $factura['facturi']['rest_plata'] == 0) echo '<div class="menu-tips icon-incasare priority-secondary" tips="<div class=\'tips-m\'>Factura a fost incasata</div>" onclick="incasare()"></div>';
	else echo '<div class="menu-tips icon-incasare" tips="<div id=\'toggle-tips\' class=\'tips-m\'>Incasare factura</div>" onclick="incasare()"></div>';
$src = '/'.$subdomeniu.'/facturi-editare/'.$_GET['idf'].'/'.$factura['facturi_attr']['id'].'/';
?>
		</div>
		<div class="item">
			<div class="menu-tips icon-history" tips="<div class='tips-m'>Istoric factura</div>" onclick="history()"></div>
		</div>
		<div class="item">
			<div class="menu-tips icon-edit" tips="<div class='tips-m'>Editare factura</div>" onclick="document.location.href='<?php echo $src; ?>'"></div>
		</div>
	</div>
</div>