<div class="aviso">
	<div id="widget-info" style="width: 200px;">
		<span data-icon="&#xe09d;" class="icon" aria-hidden="true" style="font-size: 30px; padding-right: 25px;"></span>
		AVISO IMPORTANTE
	</div>
	<div id="widget-info-2"  style="height: 123px; overflow-y: auto; text-align: justify">
		<?php if(isset($msg)): ?>
			<?= $msg; ?>
		<?php else: ?>
			Con la autorización del Lote, se confirma la  aceptación de las "
			<a href="<?= base_url($pais.'/condiciones'); ?>">Condiciones generales</a>,
			<a href="<?= base_url($pais.'/tarifas'); ?>">	tarifas</a>,
			<a href="<?= base_url($pais.'/'.'condiciones'); ?>">términos de uso y confidencialidad</a>"
			de la plataforma Conexión Empresas Online y  de nuestros productos y servicios.
		<? endif ?>
	</div>
</div>
