<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if(verifyDisplay('header', $module, lang('GEN_SHOW_HEADER'))): ?>
<header id="head">
	<div id="head-wrapper">
		<?php if(verifyDisplay('header', $module, lang('GEN_SHOW_HEADER_LOGO'))): ?>
		<img class="img-header" src="<?= $this->asset->insertFile(lang('GEN_LOGO_HEADER'), 'images', $customerFiles); ?>"
			alt="<?= lang('GEN_ALTERNATIVE_TEXT') ?>">
		<?php endif; ?>
		<?php if($logged) { $this->load->view('widget/widget_menu-user_content'); } ?>
	</div>
</header>
<?php endif; ?>
<?php if($logged) { $this->load->view('widget/widget_menu-business_content', $settingsMenu); } ?>
