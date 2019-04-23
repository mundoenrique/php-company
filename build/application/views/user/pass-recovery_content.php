<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="container">
	<header class="padding-left-right">
		<h1>Recuperar contraseña</h1>
	</header>
	<article class="padding-left-right">
		<p class="paragraph"><?= lang('FORGOT_PASS'); ?></p>
		<form id="pass-recovery" name="pass-recovery" accept-charset="utf-8">
			<fieldset class="column-center">
				<label for="card-holder-id" class="line-field"><?= lang('USER_USER'); ?></label>
				<input  type="text" id="user-name" name="user-name" class="input-field field-medium" maxlength="15">
				<label for="card-holder-id" class="line-field"><?= lang('RIF_NIT'); ?></label>
				<input type="text" id="id-company" name="id-company" class="input-field field-medium" maxlength="17" placeholder="<?= lang('PLACE_HOLDER_NIT'); ?>">
				<label for="email" class="line-field"><?= lang('MAIL'); ?></label>
				<input type="text" id="email" name="email" class="input-field  field-large" maxlength="64" placeholder="<?= lang('PLACE_HOLDER_MAIL') ?>">
			</fieldset>
			<div class="form-actions">
				<a class="come-back" href="<?= base_url('inicio') ?>">Cancelar</a>
				<button id="continuar" class="r-button">Continuar</button>
			</div>
		</form>
	</article>
</section>
