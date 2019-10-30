<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if( lang('GEN-LOGO-HEAD')): ?>
	<center class="margin-bottom">
		<img src="<?= $this->asset->insertFile(lang('GEN-LOGO-HEAD'), 'images'); ?>" alt="Banco PICHINCHA">
	</center>
	<h1 class="welcome-title-bp"><?= lang('LOGIN_WELCOME_TITLE'); ?></h1>
<?php endif; ?>

<div id="sidebar">
	<div id="widget-area">
		<div id="widget-signin" class="widget tooltip" login-uri="<?= $loginUri ?>">

			<?php if( $settingContents['signin_content']['loginTitle'] ): ?>
				<h2 class="widget-title">
					<span aria-hidden="true" class="icon" data-icon="&#xe03f;"></span>
					<?= lang('LOGIN_WIDGET_TITLE'); ?>
				</h2>
			<?php endif; ?>

			<div class="widget-content">
				<form id="login-form" name="login-form" accept-charset="utf-8">
					<fieldset>
						<label for="user_login"><?= lang('GEN_USER'); ?></label>
						<input type="text" id="user_login" name="user_login" required disabled>
						<label for="user_pass"><?= lang('GEN_PASSWORD'); ?></label>
						<input type="password" id="user_pass" name="user_pass" required disabled>
					</fieldset>
					<div class="general-form-msg"></div>
					<button id="login-btn" name="login-btn" class="btn-sidebar" disabled><?= lang('SIGNIN'); ?></button>
				</form>

				<div class="align-center">
					<p><?= lang('GEN_RECOVER_PASS_TITLE'); ?></p>
					<a href="<?= base_url('recuperar-clave') ?>" rel="section">
						<?= lang('RECOVERY_PASSWORD_LINK'); ?>
					</a>
				</div>
			</div>

		</div>
	</div>
</div>
<?php if($settingContents['signin_content']['welcomeMessage']): ?>
	<p class="align-center"><?= lang('LOGIN_WELCOME_MESSAGE') ?></p>
<?php endif; ?>
