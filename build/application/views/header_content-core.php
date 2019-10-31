<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($logged): ?>
<header class="main-head">
	<div class="flex">
		<img src="<?= $this->asset->insertFile('logo/'.lang('GEN-LOGO-HEAD')); ?>" alt=<?= lang('GEN_ALTERNATIVE_TEXT'); ?>>
	</div>
	<div class="flex flex-auto justify-end">
	<?php $this->load->view('widget/widget_menu-user_content'.$newViews); ?>
	</div>
</header>
<?php $this->load->view('widget/widget_menu-business_content'.$newViews); ?>
<?php endif; ?>
