<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="sidebar">
	<div id="widget-area">
		<div class="widget tooltip" id="widget-signin">
			<h2 class="widget-title">
				<span aria-hidden="true" class="icon" data-icon="&#xe03f;"></span>
				<?= lang('WIDGET_LOGIN_TITLE'); ?>
			</h2>
			<div class="widget-content">
				<form id="login-form" name="login-form" accept-charset="utf-8">
					<fieldset>
						<label for="user_login">Usuario</label>
						<input type="text" id="user_login" name="user_login" placeholder="Usuario">
						<label for="user_pass">Contraseña</label>
						<input type="password" id="user_pass" name="user_pass" placeholder="Contraseña">
					</fieldset>
					<button id="login-btn" name="login-btn" class="btn-sidebar">Ingresar</button>
				</form>
				<div class="align-center">
					<p>Restablecer contraseña</p>
					<a href="<?= base_url('recuperar-clave') ?>" rel="section">
						¿Olvidó o bloqueó su<br> clave de acceso?
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
