<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
?>

<div id="content-products">
	<article>
		<header>
			<h1>Restablecer contraseña</h1>
		</header>
		<section>
			<div id="content-holder">
				<p><?= lang('FORGOT_PASS'); ?> </p>
				<form accept-charset="utf-8" id="form-validar">
					<fieldset class="fieldset-column-center">
						<label for="card-holder-id"><?= lang('USER_USER'); ?></label>
						<input class="field-medium" maxlength="15" id="userName" name="userName" type="text" />
						<label for="card-holder-id"><?= lang('RIF_NIT'); ?></label>
						<input class="field-medium" maxlength="17" id="idEmpresa" name="idEmpresa" placeholder="<?= lang('PLACE_HOLDER_NIT'); ?>" type="text" />
						<label for="email"><?= lang('MAIL'); ?></label>
						<input class="field-large" id="email" maxlength="64" name="email" placeholder="<?= lang('PLACE_HOLDER_MAIL') ?>" type="text" />
					</fieldset>
				</form>
				<div id="msg"></div>
				<div class="form-actions">
					<a href="<?php echo $urlBase;?>/login"><button type="reset">Cancelar</button></a>
					<button id="continuar">Continuar</button>
				</div>
			</div>
		</section>
	</article>
</div>
<div id='loading' style='text-align:center' class='elem-hidden'><?php echo insert_image_cdn("loading.gif"); ?></div>
