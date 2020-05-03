<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="flex flex-column flex-auto">
	<?php if($newUser): ?>
	<h2><?= $message ?></h2>
	<?php endif; ?>
	<h1><?= lang("TERMS_TITLE") ?></h1>
	<?= lang("TERMS_CONTENT") ?>
	<?php if($newUser): ?>
	<div class="flex flex-column mt-4 px-5 justify-center items-center">
		<div class="flex flex-row">
			<div class="mb-3 mr-4">
				<a href="<?= base_url($goOut); ?>" class="btn btn-link btn-small big-modal"><?= lang('GEN_BTN_CANCEL'); ?></a>
			</div>
			<div class="mb-3 mr-1 custom-switch">
				<input id="terms" name="terms" class="custom-control-input" type="checkbox">
				<label class="custom-control-label" for="terms"><?= lang('TERMS_ACCEPT');?></label>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<?php if($goBack): ?>
	<div class="flex flex-column mb-4 mt-4 px-5 justify-center items-center">
		<div class="flex flex-row">
			<div class="mb-3 mr-4">
				<a href="<?= $referer; ?>" class="btn btn-link btn-small big-modal"><?= lang('GEN_BTN_BACK'); ?></a>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>
