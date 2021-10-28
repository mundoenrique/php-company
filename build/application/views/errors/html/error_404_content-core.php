<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pt-3 px-5 pb-5 bg-color">
	<div class="logout-content flex flex-column items-center justify-center">
		<i class="icon icon-404 bug-view" aria-hidden="true"></i>
		<h1 class="tertiary semibold h01"><?= lang('ERROR_404'); ?></h1>
		<span class="tertiary regular h3 uppercase mb-2"><?= LANG('ERROR_UPS') ?></span>
		<span class="tertiary regular h3"><?= lang('ERROR_PAGE_NOT_FOUND') ?></span>
		<a class="btn btn-primary my-5" href="javascript:history.back()"><?= lang('GEN_BTN_BACK'); ?></a>
	</div>
</div>
