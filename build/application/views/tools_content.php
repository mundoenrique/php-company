<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="loader" class="hidden">
	<img src="<?= $this->asset->insertFile($loader, 'images/loading-gif') ?>" class="requesting none"
		alt="<?= lang('ALT_LOADER'); ?>">
</div>
<div id="system-info" class="hidden none" default-code="<?= lang('RESP_DEFAULT_CODE'); ?>"
	redirect="<?= lang('GEN_ENTERPRISE_LIST') ?>">
	<p class="system-content">
		<span id="system-icon" class="ui-icon"></span>
		<span id="system-msg" class="system-msg"><?= lang('RESP_MESSAGE_SYSTEM'); ?></span>
	</p>
	<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
		<div class="ui-dialog-buttonset novo-dialog-buttonset">
			<button type="button" id="cancel" class="cancel-button novo-btn-secondary-modal dialog-buttons">
				<?= lang('GEN_BTN_CANCEL'); ?>
			</button>
			<button type="button" id="accept" class="novo-btn-primary-modal dialog-buttons">
				<?= lang('GEN_BTN_ACCEPT'); ?>
			</button>
		</div>
	</div>
</div>
