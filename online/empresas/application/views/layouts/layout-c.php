<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if(isset($header)): ?>
	{header}
<?php endif; ?>

<div id="wrapper">
	{content}

	<div style="width: 230px;float: left;margin-top: 160px;">
		<?php if(isset($aviso) && $aviso && $pais == 'Ve'): ?>
			{aviso}
		<?php endif; ?>

		<?php if(isset($sidebarActive) && $sidebarActive): ?>
			<div id="sidebar-products">
				{sidebar}
			</div>
		<?php endif; ?>
	</div>

</div>

<?php if(isset($footer)): ?>
	{footer}
<?php endif; ?>


