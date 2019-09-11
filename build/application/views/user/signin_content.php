<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($countryUri == 'bp'): ?>
<center class="margin-bottom">
	<img src="<?= $this->asset->insertFile('logo-pichincha-azul.png', 'images'); ?>" alt="Banco PICHINCHA">
</center>
<h1 class="welcome-title-bp"><?= lang('LOGIN_WELCOME_TITLE'); ?></h1>
<?php endif; ?>
<div id="sidebar">
	<div id="widget-area">
		<div class="widget tooltip" id="widget-signin">
			<?php if($countryUri != 'bp'): ?>
			<h2 class="widget-title">
				<span aria-hidden="true" class="icon" data-icon="&#xe03f;"></span>
				<?= lang('LOGIN_WIDGET_TITLE'); ?>
			</h2>
			<?php endif; ?>
			<div class="widget-content">
				<form id="login-form" name="login-form" accept-charset="utf-8">
					<fieldset>
						<label for="user_login">Usuario</label>
						<input type="text" id="user_login" name="user_login" required disabled>
						<label for="user_pass">Contraseña</label>
						<input type="password" id="user_pass" name="user_pass" required disabled>
					</fieldset>
					<div class="general-form-msg"></div>
					<button id="login-btn" name="login-btn" class="btn-sidebar" disabled>Ingresar</button>
				</form>
				<div class="align-center">
					<p>Restablecer contraseña</p>
					<a href="<?= base_url('recuperar-clave') ?>" rel="section">
						¿Olvidaste o bloqueaste tu<br>clave de acceso?
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if($countryUri == 'bp'): ?>
<p class="align-center"><?= lang('LOGIN_WELCOME_MESSAGE') ?></p>
<?php endif; ?>
