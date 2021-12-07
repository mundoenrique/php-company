<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (lang('CONF_FOOTER_INFO') == 'ON') : ?>
	<?php if (lang('CONF_MENU_CIRCLE') == 'ON') : ?>
		<div class="widget-menu">
			<div class="help">
				<div class="content-help">
					<div class="icon-help">
						<i class="items-center m-auto icon-help-me h2"></i>
					</div>
				</div>
			</div>
			<div class="menu-help none" id="widget-menu">
				<?php if (lang('CONF_BTN_LANG') == 'ON') : ?>
					<div class="menu-item">
						<div class="btn-lang-circle">
							<div class="btn-lang-img">
								<a id="change-lang" class="big-modal"  href="<?= lang('GEN_NO_LINK') ?>">
									<img src="<?= $this->asset->insertFile(lang('GEN_LANG_IMG'), 'images/lang'); ?>">
									<span class="text bold"><?= lang('GEN_AFTER_COD_LANG'); ?></span>
									<p class="text-icon text-btn-lang mb-0 inline-block">Cambiar idioma</p>
								</a>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<?php if ($module !== 'benefits_info') : ?>
					<div class="menu-item">
						<a class="text-icon" href="<?= base_url(lang('CONF_LINK_BENEFITS_INF')); ?>">
							<i class="items-center m-auto icon-card-allocation h3">&nbsp;</i>
							<?= lang('GEN_FOTTER_BENEFITS'); ?>
						</a>
					</div>
				<?php endif; ?>
				<?php if ($module !== 'terms') : ?>
					<div class="menu-item">
						<a class="text-icon" href="<?= base_url(lang('CONF_LINK_TERMS')); ?>">
							<i class="items-center m-auto icon-document h3">&nbsp;</i>
							<?= lang('GEN_FOTTER_TERMS'); ?>
						</a>
					</div>
				<?php endif; ?>
				<?php if ($module !== 'signIn' && !$this->session->has_userdata('logged')) : ?>
					<div class="menu-item">
						<a class="text-icon" href="<?= base_url($goOut); ?>" rel="section">
							<i class="items-center m-auto icon-home h3">&nbsp;</i>
							<?= lang('GEN_FOTTER_START'); ?>
						</a>
					</div>
				<?php endif; ?>
				<?php if (($this->session->has_userdata('logged') && !isset($skipmenu)) && lang('CONF_FOOTER_RATES') == 'ON') : ?>
					<div class="menu-item">
						<a class="text-icon" href="<?= base_url(lang('CONF_LINK_RATES')); ?>" rel="section">
							<i class="items-center m-auto icon-rates h3">&nbsp;</i>
							<?= lang('GEN_FOTTER_RATES'); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
	<footer class="main-footer">
		<?php if (lang('CONF_SUPERINTENDENCY_LOGO') == 'ON') : ?>
			<div class="flex pr-2 pr-lg-0">
				<img src="<?= $this->asset->insertFile(lang('GEN_FOTTER_MARK'), 'images', $customerUri); ?> " alt="Logo Superintendencia">
			</div>
		<?php endif; ?>
		<div class="flex flex-auto flex-wrap justify-around items-center">
			<?php if (lang('CONF_FOOTER_NETWORKS') == 'ON') : ?>
				<div class="order-first networks">
					<?php foreach (lang('GEN_FOTTER_NETWORKS_IMG') as $key => $value) : ?>
						<a href="<?= lang('CONF_FOTTER_NETWORKS_LINK')[$key]; ?>" target="_blank">
							<img src="<?= $this->asset->insertFile($value, 'images/networks'); ?>" alt="<?= $key; ?>">
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php if (lang('CONF_FOOTER_LOGO') == 'ON') : ?>
				<img class="order-first" src="<?= $this->asset->insertFile(lang('GEN_FOTTER_IMAGE_L'), 'images', $customerUri); ?>" alt="<?= lang('GEN_ALTERNATIVE_TEXT'); ?>">
			<?php endif; ?>
			<img class="order-1" src="<?= $this->asset->insertFile(lang('GEN_FOTTER_IMAGE_R'), 'images'); ?>" alt="Logo PCI">
			<span class="copyright-footer mt-1 nowrap flex-auto lg-flex-none order-1 order-lg-0 center h6"><?= lang('GEN_FOTTER_RIGHTS'); ?><?= date("Y") ?></span>
		</div>
	</footer>
<?php endif; ?>
<?php if (lang('CONF_SIGNIN_WIDGET_CONTACT') == 'ON') : ?>
	<?php $this->load->view('widget/widget_contacts_content-core') ?>
<?php endif; ?>

<?php if (lang('CONF_BTN_LANG') == 'ON' && lang('CONF_MENU_CIRCLE') == 'OFF') : ?>
	<div class="btn-lang">
		<div class="btn-lang-img">
			<a id="change-lang" class="big-modal"  href="<?= lang('GEN_NO_LINK') ?>">
				<img src="<?= $this->asset->insertFile(lang('GEN_LANG_IMG'), 'images/lang'); ?>">
				<span class="text"><?= lang('GEN_AFTER_COD_LANG'); ?></span>
			</a>
		</div>
	</div>
<?php endif; ?>

<div id="loader" class="none">
	<span class="spinner-border secondary" role="status" aria-hidden="true"></span>
</div>

<div id="system-info" class="hide" name="system-info">
	<p class="mb-0">
		<span class="dialog-icon">
			<i id="system-icon"></i>
		</span>
		<span id="system-msg" class="system-msg"><?= lang('GEN_SYSTEM_MESSAGE'); ?></span>
	</p>
	<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix mb-1">
		<div class="ui-dialog-buttonset flex">
			<button type="button" id="cancel" class="btn-modal btn btn-small btn-link"><?= lang('GEN_BTN_CANCEL'); ?></button>
			<button type="button" id="accept" class="btn-modal btn btn-small btn-loading btn-primary"><?= lang('GEN_BTN_ACCEPT'); ?></button>
		</div>
	</div>
</div>
<div class="cover-spin"></div>
<form id="nonForm" class="hide"></form>
