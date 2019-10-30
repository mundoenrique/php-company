<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<header class="main-head">
	<div class="flex">
		<img src="assets/images/img-logo.svg" alt="" />
	</div>
	<div class="flex flex-auto justify-end">
	<?php $this->load->view('widget/widget_menu-user_content'.$newViews); ?>
	</div>
</header>
