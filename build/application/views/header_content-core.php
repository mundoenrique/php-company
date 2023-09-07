<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if(($this->session->logged || isset($activeHeader)) && !isset($skipmenu)): ?>
<header class="main-head">
	<?php if($this->session->logged): ?>
	<nav class="navbar py-0 flex-auto">
		<?php else: ?>
		<nav class="navbar navbar-expand-lg flex-auto <?= lang('SETT_PADDING_LOGO') == 'ON' ? ' py-0' : '' ?>">
		<?php endif; ?>
		<a class="navbar-brand">
			<img src="<?= $this->asset->insertFile(lang('GEN_LOGO_HEADER'), 'images', $customerFiles); ?>"
				alt=<?= lang('GEN_ALTERNATIVE_TEXT'); ?>>
				<?php if(lang('SETT_HEADER_BORDER') == 'ON'):?>
				<span class="vertical-line mx-1"></span>
				<span class="h3 white"><?= lang('GEN_TITLE_NAVBAR') ?></span>
				<?php endif; ?>
		</a>
		<?php if($this->session->logged): ?>
		<div class="flex flex-auto justify-end">
			<?php $this->load->view('widget/widget_menu-user_content-core'); ?>
		</div>
		<?php endif; ?>
	</nav>
</header>
<?php $this->load->view('widget/widget_menu-business_content-core', $settingsMenu); ?>
<?php endif; ?>
