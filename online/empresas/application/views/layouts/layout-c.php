<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if(isset($header)): ?>
	{header}
<?php endif; ?>

<div id="wrapper">
	{content}

	<div style="width: 230px;float: left;margin-top: 160px;">
		<?php if(isset($aviso) && $aviso && $pais == 'Ve'): ?>
			<div class="aviso">
				<div id="widget-info" style="width: 200px;">
					<span data-icon="&#xe09d;" class="icon" aria-hidden="true" style="font-size: 30px; padding-right: 25px;"></span>
					AVISO IMPORTANTE
				</div>

				<div id="widget-info-2"  style="height: 123px; overflow-y: auto; text-align: justify">
					Con la autorización del Lote, se confirma la  aceptación de las "<a href="<?= base_url($pais.'/condiciones'); ?>">Condiciones generales</a>, <a href="<?= base_url($pais.'/tarifas'); ?>">	tarifas</a>, <a href="<?= base_url($pais.'/'.'condiciones'); ?>">términos de uso y confidencialidad</a>" de la plataforma Conexión Empresas Online y  de nuestros productos y servicios.
				</div>
			</div>
		<?php endif; ?>

		<?php if(isset($sidebarActive) && $sidebarActive): ?>
			<div id="sidebar-products">
				{sidebar}
			</div>
		<?php endif; ?>
	</div>

</div>

<?php if(isset($footer)): ?>
	{footer}
<?php endif; ?>


