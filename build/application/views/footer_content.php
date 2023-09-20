<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<footer id="foot" class="foot">
	<div id="foot-wrapper">
		<nav id="extra-nav">
			<ul class="menu">
				<?php if (verifyDisplay('footer', $module, lang('GEN_FOTTER_START'))) : ?>
					<li class="menu-item signup">
						<a id="signup" href="<?= base_url($goOut); ?>" rel="section">
							<?= lang('GEN_FOTTER_START'); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if (verifyDisplay('footer', $module, lang('GEN_FOTTER_BENEFITS'))) : ?>
					<li class="menu-item benefits">
						<a href="<?= base_url('inf-beneficios') ?>" rel="section">
							<?= lang('GEN_FOTTER_BENEFITS'); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if (verifyDisplay('footer', $module, lang('GEN_FOTTER_TERMS'))) : ?>
					<li class="menu-item terms">
						<a href="<?= base_url('inf-condiciones'); ?>" rel="section">
							<?= lang('GEN_FOTTER_TERMS'); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if (verifyDisplay('footer', $module, lang('GEN_FOTTER_RATES'))) : ?>
					<li class="menu-item privacy">
						<a id='tarifas' href="<?= base_url('inf-tarifas'); ?>" rel="section">
							<?= lang('GEN_FOTTER_RATES'); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if (verifyDisplay('footer', $module, lang('GEN_FOTTER_LOGOUT'))) :	?>
					<li class="menu-item privacy">
						<a id='exit' href="<?= base_url('cerrar-sesion'); ?>" rel="section">
							<?= lang('GEN_FOTTER_LOGOUT'); ?>
						</a>
					</li>
				<?php endif; ?>
			</ul>
		</nav>

		<?php if (verifyDisplay('footer', $module, lang('GEN_FOTTER_OWNERSHIP'))) : ?>
			<a id="ownership" href="<?= lang('GEN_FOTTER_OWNER_URL') ?>" rel="me" target="_blank">
				<?= lang('GEN_FOTTER_OWNERSHIP'); ?>
			</a>
			<div class="separator"></div>
			<div id="credits">
				<p>© <?= date('Y') . ' ' . lang('GEN_FOTTER_RIGHTS'); ?></p>
			</div>
		<?php endif; ?>
	</div>
</footer>
<div id="loader" class="hidden">
	<img src="<?= $this->asset->insertFile(lang('IMG_LOADER'), 'images', $customerFiles) ?>" class="requesting" alt="<?= lang('GEN_ALT_LOADER'); ?>">
</div>
<div id="system-info" class="hidden">
	<p class="system-content">
		<span id="system-icon"></span>
		<span id="system-msg" class="system-msg"></span>
	</p>
	<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
		<div class="ui-dialog-buttonset novo-dialog-buttonset modal-buttonset">
			<button type="button" id="cancel" class="<?= lang('SETT_MODAL_BTN_CLASS')['cancel']; ?>">
				<?= lang('GEN_BTN_CANCEL'); ?>
			</button>
			<button type="button" id="accept" class="<?= lang('SETT_MODAL_BTN_CLASS')['accept']; ?>">
				<?= lang('GEN_BTN_ACCEPT'); ?>
			</button>
		</div>
	</div>
</div>