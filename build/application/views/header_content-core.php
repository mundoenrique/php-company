<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($logged): ?>
<header class="main-head">
	<nav class="navbar py-0 flex-auto">
		<a class="navbar-brand">
			<img src="<?= $this->asset->insertFile($countryUri.'/'.lang('GEN-LOGO-HEADER')); ?>"
				alt=<?= lang('GEN_ALTERNATIVE_TEXT'); ?>>
		</a>
		<div class="flex flex-auto justify-end">
			<?php $this->load->view('widget/widget_menu-user_content'.$newViews); ?>
		</div>
	</nav>
</header>
<?php $this->load->view('widget/widget_menu-business_content'.$newViews, $settingsMenu); ?>
<?php endif; ?>
