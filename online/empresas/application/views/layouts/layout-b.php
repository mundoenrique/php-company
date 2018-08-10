<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if(isset($header)): ?>
	{header}
<?php endif; ?>

<div id="wrapper">
	{content}

	<?php if(isset($aviso) && $aviso && $pais == 'Ve'): ?>
		{aviso}
	<?php endif; ?>

	<?php if(isset($sidebarActive) && $sidebarActive): ?>
		<div id="sidebar-products">
			{sidebar}
		</div>
	<?php endif; ?>
</div>

<?php if(isset($footer)): ?>
	{footer}
<?php endif; ?>

