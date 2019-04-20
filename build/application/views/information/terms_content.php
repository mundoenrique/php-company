<div id="content-condiciones">
	<h1><?= lang("TITULO_TERMINOS") ?></h1>
	<?= lang("TERMINOS") ?>
	<?php if($goBack) : ?>
		<div style="margin-top:25px; text-align: center;">
			<a href="<?= $referer;?>"><button style="float: none;">Volver atrás</button></a>
		</div>
	<?php endif; ?>
	<?php if($newUser) : ?>
	<div class='condiciones-check'>
		<input id="aceptoTerminos" name="check" type="checkbox" value="aceptoTerminos"/>
		Acepto los términos y condiciones.
		<button id="enviarTerminos" type="submit">Continuar</button>
	</div>
	<?php endif; ?>
</div>
