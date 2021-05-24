<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="login-content flex items-center justify-center bg-primary">
	<div class="row">
		<div class="col-auto px-0">
			<div class="flex flex-column items-center z1 h-100">
				<img class="logo-banco mb-2" src="<?= $this->asset->insertFile(lang('GEN-LOGO-BIG'), 'images', $customerUri); ?>"
					alt="<?= lang('GEN_ALTERNATIVE_TEXT'); ?>">
				<span class="mb-2 secondary center h3"><?= lang('USER_WELCOME_TITLE') ?></span>
				<div id="widget-signin" class="widget rounded h-100">
					<form id="signInForm" name="signInForm">
						<div class="form-group">
							<label for="userName"><?= lang('GEN_USER'); ?></label>
							<input id="userName" name="userName" class="form-control" type="text" autocomplete="off" disabled>
							<div class="help-block"></div>
						</div>
						<div class="form-group">
							<label for="userPass"><?= lang('GEN_PASSWORD'); ?></label>
							<div class="input-group">
								<input id="userPass" name="userPass" class="form-control pwd-input" type="text" autocomplete="off" disabled>
								<div class="input-group-append">
									<span class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i class="icon-view mr-0"></i></span>
								</div>
							</div>
							<div class="help-block"></div>
						</div>
						<button id="signInBtn" class="btn btn-loading-lg btn-primary w-100 mt-3 mb-5 login-btn" disabled>
							<span class="icon-lock mr-1 h3 bg-items" aria-hidden="true"></span>
							<?= lang('LOGIN_BTN') ?>
						</button>
						<?php if(lang('CONF_SIGIN_RECOVER_PASS') == 'ON'): ?>
						<a class="block mb-1 h5 primary hyper-link" href="<?= base_url(lang('GEN_LINK_RECOVER_ACCESS'));?>"><?= lang('LOGIN_RECOVER_PASS'); ?></a>
						<?php endif; ?>
					</form>
				</div>
			</div>
		</div>

		<?php if(lang('CONF_SIGNIN_IMG') == 'ON'): ?>
		<div class="col-auto px-0">
			<div class="h-100">
				<div class="flex pr-2 pr-lg-0 img-log h-100">
					<img src="<?= $this->asset->insertFile(lang('GEN_IMAGE_LOGIN'), 'images', $customerUri); ?> " alt="Imagen de referencia">
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>
  <?php if(lang('CONF_SIGNIN_WIDGET_CONTACT') == 'ON'): ?>
  <?php $this->load->view('widget/widget_contacts_content-core') ?>
  <?php endif; ?>
</div>
