<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<footer class="main-footer">
	<div class="flex pr-2 pr-lg-0">
		<img src="<?= $this->asset->insertFile($countryUri.'/img-mark.svg'); ?>" alt="Logo Superintendencia">
	</div>
	<div class="flex flex-auto flex-wrap justify-around items-center">
		<img class="order-first" src="<?= $this->asset->insertFile($countryUri.'/img-bogota_white.svg'); ?>"
			alt="Logo Banco de Bogotá">
		<img class="order-1" src="<?= $this->asset->insertFile($countryUri.'/img-pci_compliance.svg'); ?>"
			alt="Logo PCI">
		<span class="copyright-footer mt-1 nowrap flex-auto lg-flex-none order-1 order-lg-0 center h6">© Todos los derechos
			reservados. Banco de Bogotá - 2019.</span>
	</div>
</footer>

<div id="loader" class="none">
	<span class="spinner-border secondary" role="status" aria-hidden="true"></span>
</div>

<div id="system-info" class="hide" name="system-info">
	<p class="mb-0">
		<span class="dialog-icon">
			<i id="system-icon" class="ui-icon mt-0"></i>
		</span>
		<span id="system-msg" class="system-msg"><?= lang('RESP_MESSAGE_SYSTEM'); ?></span>
	</p>
	<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix mb-1">
		<div class="ui-dialog-buttonset novo-dialog-buttonset">
			<button type="button" id="cancel" class="btn-modal btn btn-small btn-link"><?= lang('GEN_BTN_CANCEL'); ?></button>
			<button type="button" id="accept" class="btn-modal btn btn-small btn-loading btn-primary"><?= lang('GEN_BTN_ACCEPT'); ?></button>
		</div>
	</div>
</div>
<div class="cover-spin" id=""></div>
