<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($this->session->logged || isset($activeHeader)): ?>
<header class="main-head">
	<?php if($this->session->logged): ?>
	<nav class="navbar py-0 flex-auto">
		<?php else: ?>
		<nav class="navbar navbar-expand-lg flex-auto">
		<?php endif; ?>
		<a class="navbar-brand">
			<img src="<?= $this->asset->insertFile($countryUri.'/'.lang('GEN-LOGO-HEADER')); ?>"
				alt=<?= lang('GEN_ALTERNATIVE_TEXT'); ?>>
		</a>
		<?php if($this->session->logged): ?>
		<div class="flex flex-auto justify-end">
			<?php $this->load->view('widget/widget_menu-user_content'.$newViews); ?>
		</div>
		<?php endif; ?>
	</nav>
</header>
<?php $this->load->view('widget/widget_menu-business_content'.$newViews, $settingsMenu); ?>
<?php endif; ?>
