<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="login-content flex items-center justify-center bg-primary">
	<div class="flex flex-column items-center z1">
		<img class="logo-banco mb-2" src="<?= $this->asset->insertFile($countryUri.'/'.lang('GEN-LOGO-BIG')); ?>"
			alt="<?= lang('GEN_ALTERNATIVE_TEXT'); ?>">
		<span class="mb-2 secondary center h3"><?= lang('LOGIN_WELCOME_TITLE') ?></span>
		<div id="widget-signin" class="widget rounded">
			<form id="login-form" name="login-form" autocomplete="off">
				<div class="form-group">
					<label for="user_login"><?= lang('GEN_USER'); ?></label>
					<input id="user_login" name="user_login" class="form-control" type="text" autocomplete="off" disabled>
					<div class="help-block"></div>
				</div>
				<div class="form-group">
					<label for="user_pass"><?= lang('GEN_PASSWORD'); ?></label>
					<div class="input-group">
						<input id="user_pass" name="user_pass" class="form-control pwd-input" type="text" autocomplete="off" disabled>
						<div class="input-group-append">
							<span class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i class="icon-view mr-0"></i></span>
						</div>
					</div>
					<div class="help-block"></div>
				</div>
				<button id="login-btn" class="btn btn-loading-lg btn-primary w-100 mt-3 mb-5 login-btn">
					<span class="icon-lock mr-1 h3 bg-items" aria-hidden="true"></span>
					<?= lang('LOGIN_BTN') ?>
				</button>
				<?php if(lang('CONIFG_SIGIN_RECOVER_PASS') == 'ON'): ?>
				<a class="block mb-1 h5 primary hyper-link" href="<?= base_url(lang('GEN_LINK_RECOVER_ACCESS'));?>"><?= lang('LOGIN_RECOVER_PASS'); ?></a>
				<?php endif; ?>
			</form>
		</div>
	</div>
	<?php if(lang('CONF_SIGNIN_IMG') == 'ON'): ?>
	<div class="flex pr-2 pr-lg-0 img-log">
		<img src="<?= $this->asset->insertFile(lang('GEN_IMAGE_LOGIN'), 'images', $countryUri); ?> " alt="Imagen de referencia">
	</div>
	<?php endif; ?>
</div>
