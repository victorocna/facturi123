<?php
if (mysql_num_rows($sqlz) == 0) echo "
<script>
$(document).ready(function(){
	$('.menu-tips').qtip({
		content: '<div class=\"tips-m\">Pentru facturi salvate ca draft adauga un furnizor</div>',
		position:{
			corner:{
				target: 'leftMiddle',
				tooltip: 'rightMiddle'
			}
		},
		style:{
			width: 200,
			name: 'light',
			tip: 'rightMiddle',
			border: {
				width: 1,
				radius: 4
			}
		},
		hide:{
			when: 'mouseout',
			fixed: true
		}
	});
});
</script>
";
if (mysql_num_rows($sqlz) >= 1) echo "
<script>
$(document).ready(function(){
	$('.menu-tips').qtip({
		content: '<div class=\"tips-m\">Pentru facturi salvate ca draft selecteaza un furnizor</div>',
		position:{
			corner:{
				target: 'leftMiddle',
				tooltip: 'rightMiddle'
			}
		},
		style:{
			width: 200,
			name: 'light',
			tip: 'rightMiddle',
			border: {
				width: 1,
				radius: 4
			}
		},
		hide:{
			when: 'mouseout',
			fixed: true
		}
	});
});
</script>
";
?>
<div class="span-1" style="margin-top: 200px;">
	<div class="menu-icon">
		<div class="item">
			<div class="menu-tips icon-draft"></div>
		</div>
	</div>
</div>