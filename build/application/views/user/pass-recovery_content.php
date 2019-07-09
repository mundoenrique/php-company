<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="container">
	<header class="padding-left-right">
		<h1>Recuperar contraseña</h1>
	</header>
	<article class="padding-left-right">
		<p class="paragraph"><?= lang('FORGOT_PASS'); ?></p>
		<form id="form-pass-recovery" name="form-pass-recovery" accept-charset="utf-8">
			<fieldset class="recuperar-clave-fieldset">
				<div class="field-wrapper">
					<label for="user-name" class="line-field"><?= lang('USER_USER'); ?></label>
					<input type="text" id="user-name" name="user-name" class="input-field field-large" maxlength="15" required>
				</div>
				<div class="field-wrapper">
					<label for="id-company" class="line-field"><?= lang('RIF_NIT'); ?></label>
					<input type="text" id="id-company" name="id-company" class="input-field field-large" maxlength="17"
						placeholder="<?= lang('PLACE_HOLDER_NIT'); ?>"  required>
				</div>
				<div class="field-wrapper">
					<label for="email" class="line-field"><?= lang('MAIL'); ?></label>
					<input type="text" id="email" name="email" class="input-field  field-large" maxlength="64"
						placeholder="<?= lang('PLACE_HOLDER_MAIL') ?>" required>
				</div>
			</fieldset>
			<div class="form-actions">

			<?php
			//	echo "sdjkha".base_url('inicio');
			$pais=$this->urlCountry = $this->uri->segment(1, 0);
				if($pais=='bp'){
					?>
						<center>
					<?php
				}
			?>
				<a class="cancel-anchor" href="<?= base_url('inicio') ?>">Cancelar</a>
				<button id="btn-pass-recover" class="r-button">Continuar</button>
				<?php if($pais=='bp'){
					?>
						</center>
					<?php
				}?>
			</div>
		</form>
	</article>
</section>
