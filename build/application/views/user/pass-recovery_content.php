<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="container">
	<header class="padding-left-right">
		<h1>Recuperar contraseña</h1>
	</header>
	<article class="padding-left-right">
		<p class="paragraph"><?= lang('FORGOT_PASS'); ?></p>
		<form name="pass-recovery" id="pass-recovery" accept-charset="utf-8">
			<fieldset class="column-center">
				<label for="card-holder-id" class="line-field"><?= lang('USER_USER'); ?></label>
				<input class="input-field field-medium" maxlength="15" id="userName" name="userName" type="text" autocomplete="off">
				<label for="card-holder-id" class="line-field"><?= lang('RIF_NIT'); ?></label>
				<input class="input-field field-medium" maxlength="17" id="idEmpresa" name="idEmpresa" placeholder="<?= lang('PLACE_HOLDER_NIT'); ?>" type="text" />
				<label for="email" class="line-field"><?= lang('MAIL'); ?></label>
				<input class="input-field  field-large" id="email" maxlength="64" name="email" placeholder="<?= lang('PLACE_HOLDER_MAIL') ?>" type="text" />
			</fieldset>
			<div class="form-actions">
				<a class="come-back" href="<?= base_url('home') ?>">Cancelar</a>
				<button class="r-button" id="continuar">Continuar</button>
			</div>
		</form>
	</article>
</section>
<div id='loading' style='text-align:center' class='elem-hidden'><?php echo insert_image_cdn("loading.gif"); ?></div>
