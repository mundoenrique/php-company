<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<footer class="main-footer">
	<? if ($countryUri == 'bdb'): ?>
	<div class="flex pr-2 pr-lg-0">
		<img src="<?= $this->asset->insertFile(lang('GEN_FOTTER_MARK'), 'images', $countryUri); ?> " alt="Logo Superintendencia">
	</div>
	<? endif; ?>
	<div class="flex flex-auto flex-wrap justify-around items-center">
		<?php if(lang('CONF_FOOTER_NETWORKS') == 'ON'): ?>
		<div class="order-first networks">
			<?php foreach(lang('GEN_FOTTER_NETWORKS_IMG') AS $key => $value): ?>
			<a href="<?= lang('GEN_FOTTER_NETWORKS_LINK')[$key]; ?>" target="_blank">
				<img src="<?= $this->asset->insertFile($value, 'images/networks'); ?>"
					alt="<?= $key; ?>">
				</a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		<?php if(lang('CONF_FOOTER_LOGO') == 'ON'):?>
		<img class="order-first" src="<?= $this->asset->insertFile(lang('GEN_FOTTER_IMAGE_L'), 'images', $countryUri); ?>"
			alt="<?= lang('GEN_ALTERNATIVE_TEXT'); ?>">
		<?php endif; ?>
		<img class="order-1" src="<?= $this->asset->insertFile(lang('GEN_FOTTER_IMAGE_R'), 'images'); ?>" alt="Logo PCI">
		<span
			class="copyright-footer mt-1 nowrap flex-auto lg-flex-none order-1 order-lg-0 center h6"><?= lang('GEN_FOTTER_RIGHTS'); ?><?= date("Y") ?></span>
	</div>
</footer>

<div id="loader" class="none">
	<span class="spinner-border secondary" role="status" aria-hidden="true"></span>
</div>

<div id="system-info" class="hide" name="system-info">
	<p class="mb-0">
		<span class="dialog-icon">
			<i id="system-icon"></i>
		</span>
		<span id="system-msg" class="system-msg"><?= lang('GEN_MESSAGE_SYSTEM'); ?></span>
	</p>
	<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix mb-1">
		<div class="ui-dialog-buttonset novo-dialog-buttonset flex">
			<button type="button" id="cancel" class="btn-modal btn btn-small btn-link"><?= lang('GEN_BTN_CANCEL'); ?></button>
			<button type="button" id="accept" class="btn-modal btn btn-small btn-loading btn-primary"><?= lang('GEN_BTN_ACCEPT'); ?></button>
		</div>
	</div>
</div>
<div class="cover-spin"></div>
<form id="nonForm" class="hide"></form>
