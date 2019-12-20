<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="login-content flex items-center justify-center bg-primary">
	<div class="flex flex-column items-center z1">
		<img class="logo-banco mb-2" src="<?= $this->asset->insertFile($countryUri.'/'.lang('GEN-LOGO-BIG')); ?>" alt="<?= lang('GEN_ALTERNATIVE_TEXT'); ?>">
		<span class="mb-2 secondary center h3"><?= lang('LOGIN_WELCOME_TITLE') ?></span>
		<div id="widget-signin" class="widget rounded">
			<form id="login-form">
				<div class="form-group">
					<label for="user_login"><?= lang('GEN_USER'); ?></label>
					<input id="user_login" name="user_login" class="form-control" type="text" autocomplete="off" disabled>
					<div class="help-block"></div>
				</div>
				<div class="form-group">
					<label for="user_pass"><?= lang('GEN_PASSWORD'); ?></label>
					<input id="user_pass" name="user_pass" class="form-control" type="password" autocomplete="off" disabled>
					<div class="help-block"></div>
				</div>
				<button id="login-btn" class="btn btn-loading-lg btn-primary w-100 mt-3 mb-5"><?= lang('LOGIN_BTN') ?></button>
			</form>
		</div>
	</div>
</div>
