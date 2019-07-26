<?php defined('BASEPATH') or exit('No direct script access allowed '); ?>
<div id="content-condiciones">
	<h1><?= lang('BREADCRUMB_WELCOME').'(a)'; ?> <span class='first-title'> <?= $fullName ?></span></h1>
	<p id="text-alerta">
		<?= $message ?>
	</p>
	<div id="sidebar-cambioclave">
		<div id="widget-area">
			<div class="widget tooltip" id="widget-signin">
				<h2 class="widget-title">
					<?php if($countryUri != 'bp'){ ?>
					<span aria-hidden="true" class="icon" data-icon="&#xe03f;"></span>
					<?php
						};
						echo lang('INFO_CHANGE_PASS');
					?>
				</h2>
				<div class="widget-content">
					<form id="form-change-pass" name="form-change-pass" accept-charset="utf-8">
						<input type="hidden" id="status-user" name="user-type" value="<?= $userType ?>">
						<fieldset>
							<div class="field-input">
								<label for="current-pass">Contraseña actual *</label>
								<input type="password" id="current-pass" name="current-pass" class="input-middle"
									placeholder="Contraseña actual" required>
							</div>
							<div class="field-input">
								<label for="new-pass">Contraseña nueva *</label>
								<input type="password" id="new-pass" name="new-pass" class="input-middle"
									placeholder="Contraseña nueva" required>
							</div>
							<div class="field-input">
								<label for="confirm-pass">Confirme la nueva contraseña *</label>
								<input type="password" id="confirm-pass" name="confirm-pass" class="input-middle"
									placeholder="Confirmar contraseña" required>
							</div>
						</fieldset>
						<button id="btn-change-pass" name="btn-change-pass" class="btn-middle btn-sidebar">Aceptar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="psw_info" style="display: none">
		<h5>Requerimientos para configurar la contraseña. La clave debe tener:</h5>
		<ul>
			<li id="length" class="invalid">De 8 a 15 <strong>Caracteres</strong></li>
			<li id="letter" class="invalid">Al menos una <strong>letra minúscula</strong></li>
			<li id="capital" class="invalid">Al menos una <strong>letra mayúscula</strong></li>
			<li id="number" class="invalid">De 1 a 3 <strong>números</strong></li>
			<li id="especial" class="invalid">Al menos un <strong>caracter especial</strong> (ej: * & $ # . ?)</li>
			<li id="consecutivo" class="invalid">No debe tener más de 2 <strong>caracteres</strong> iguales consecutivos</li>
		</ul>
	</div>
</div>
