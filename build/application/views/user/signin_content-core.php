<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="login-content h-100 flex items-center justify-center bg-primary">
	<div class="flex flex-column items-center">
		<img src="<?= $this->asset->insertFile('logo/'.lang('GEN-LOGO-HEAD')); ?>" alt=<?= lang('GEN_ALTERNATIVE_TEXT'); ?>>
		<span class="mb-2 secondary center h3">Empresas</span>
		<div id="widget-signin" class="widget rounded" login-uri="<?= $loginUri ?>" recaptcha="<?= $activeRecaptcha; ?>">
			<form id="login-form">
				<div class="form-group">
					<label for="user_login">Usuario</label>
					<input id="user_login" name="user_login" class="form-control" type="text" placeholder="Usuario">
					<div class="help-block"></div>
				</div>
				<div class="form-group">
					<label for="user_pass">Contraseña</label>
					<input id="user_pass" name="user_pass" class="form-control" type="password" placeholder="Contraseña">
					<div class="help-block"></div>
				</div>
				<button id="login-btn" class="btn btn-primary w-100 mt-3 mb-5">
					<span aria-hidden="true" class="icon-lock h3 yellow"></span>
					Ingreso Seguro
				</button>
			</form>
		</div>
	</div>
</div>
