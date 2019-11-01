<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<footer id="foot" class="foot">
	<div id="foot-wrapper">
		<nav id="extra-nav">
			<ul class="menu">
				<?php if(!$logged && $module !== 'login'): ?>
				<li class="menu-item signup">
					<a id="signup" href="<?= base_url($goOut); ?>" rel="section">
						<?= lang('BREADCRUMB_INICIO'); ?>
					</a>
				</li>
				<?php endif; ?>
				<?php if($module !== 'benefits' && $module !== 'change-password' && $module !== 'terms' && $settingContents['master_content']['menuFooter']): ?>
				<li class="menu-item benefits">
					<a href="<?= base_url('inf-beneficios') ?>" rel="section">
						<?= lang('BREADCRUMB_BENEFICIOS') ?>
					</a>
				</li>
				<?php endif; ?>
				<?php if($module !== 'terms' && $module !== 'change-password'  && $settingContents['master_content']['menuFooter']): ?>
				<li class="menu-item terms">
					<a href="<?= base_url('inf-condiciones'); ?>" rel="section">
						<?= lang('BREADCRUMB_CONDICIONES') ?>
					</a>
				</li>
				<?php endif; ?>
				<?php if($logged && $settingContents['master_content']['showRates'] && $module !== 'rates'): ?>
				<li class="menu-item privacy">
					<a id='tarifas' href="<?= base_url('inf-tarifas'); ?>" rel="section">
						<?= lang('SUBMENU_TARIFAS'); ?>
					</a>
				</li>
				<?php endif; ?>
				<?php if($logged):	?>
				<li class="menu-item privacy">
					<a id='exit' href="<?= base_url('cerrar-sesion'); ?>" rel="section">
						<?= lang('SUBMENU_LOGOUT'); ?>
					</a>
				</li>
				<?php endif; ?>
			</ul>
		</nav>

		<?php if($settingContents['master_content']['ownerShip'] !== FALSE): ?>
		<a id="ownership" href="<?= $settingContents['master_content']['ownerShip'] ?>" rel="me">
			<?= lang('OWNERSHIP'); ?>
		</a>
		<div class="separator"></div>
		<div id="credits">
			<p>© <?= date('Y').' '.lang('CREDITS'); ?></p>
		</div>
		<?php endif; ?>
	</div>
</footer>
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
