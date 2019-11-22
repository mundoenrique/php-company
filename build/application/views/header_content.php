<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($countryUri !== 'bp' && !$logged): ?>
<header id="head">
	<div id="head-wrapper">
		<?php if( lang('GEN-LOGO-HEAD') ): ?>
		<img class="img-header" src="<?= $this->asset->insertFile( lang('GEN-LOGO-HEAD')); ?>" alt="Banco PICHINCHA">
		<?php endif; ?>
		<?php if($logged) { $this->load->view('widget/widget_menu-user_content'); } ?>
	</div>
</header>
<?php endif; ?>
<?php if($logged) { $this->load->view('widget/widget_menu-business_content', $settingsMenu); } ?>
