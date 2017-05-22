<?php if(isset($header)){ ?>
{header}
<?php } ?>

	<div id="wrapper">
		<!-- Begin: Content Area -->
		
					{content}
		
		<!-- End: Content Area -->
		<?php if(isset($sidebarActive) && $sidebarActive){?>
		<!-- Begin: Sidebar -->
		<div id="sidebar">
			<div id="widget-area">
				
					{sidebar}
				
			</div>
		</div>
		<!-- End: Sidebar -->
		<?php };?>
	</div>
	<?php if(isset($footer)){ ?>
{footer}
<?php } ?>