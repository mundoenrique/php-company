<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$urlCdn = get_cdn();

?>

<div id="content-products">
	<h1><?php echo lang('TITULO_REPORTE'); ?></h1>
	<ol class="breadcrumb">
		<li>
			<a href="<?= $urlBase; ?>/dashboard" rel="start">
				<?= lang('BREADCRUMB_INICIO'); ?>
			</a>
		</li>
		/
		<li>
			<a href="<?= $urlBase; ?>/dashboard" rel="section">
				<?= lang('BREADCRUMB_EMPRESAS'); ?>
			</a>
		</li>
		/
		<li>
			<a href="<?= $urlBase; ?>/dashboard/productos" rel="section">
				<?= lang('BREADCRUMB_PRODUCTOS'); ?>
			</a>
		</li>
		/
		<li>
			<a rel="section">
				<?= lang('BREADCRUMB_SERVICIOS'); ?>
			</a>
		</li>
		/
		<li class="breadcrumb-item-current">
			<a href="<?= $urlBase; ?>/servicios/transferencia-maestra" rel="section">
				<?= lang('BREADCRUMB_CONSTARJETA'); ?>
			</a>
		</li>
	</ol>

	<div id="lotes-general">

		<div id="top-batchs">
			<?php if($pais != 'Ec-bp'): ?>
			<span aria-hidden="true" class="icon" data-icon="&#xe07a;"></span>
			<?php endif;?>
			<?php echo lang('CRITERIOS_BUSQUEDA'); ?>
		</div>
		<div id="lotes-contenedor">
			<form id="form-criterio-busqueda" onsubmit="return false">
				<div id="lotes-2">
					<div id="search-1">
						<h5><?php echo lang('TITULO_ORDEN'); ?></h5>
						<span>
							<input id="servicio" class="cedula nro" type="text" name="numero"
								placeholder="Ingrese número">
						</span>
					</div>
					<div id="search-1">
						<h5><?php echo lang('TITULO_LOTE'); ?></h5>
						<span>
							<input id="lote" class="cedula nro" type="text" name="numero"
								placeholder="Ingrese número">
						</span>
					</div>
					<div id="search-2">
						<h5><?php echo lang('TITULO_CEDULA'); ?></h5>
						<span>
							<input id="cedula" class="cedula nro" type="text" name="numero"
								placeholder="Ingrese número">
						</span>
					</div>

					<div id="search-3">
						<h5><?php echo lang('TITULO_TARJETA'); ?></h5>
						<span>
							<input id="tarjeta" class="cedula nro" type="text" name="numero"
								placeholder="Ingrese número">
						</span>
					</div>


			</form>
		</div>
	</div>
	<div id="batchs-last">
		<?php
					if($pais=='Ec-bp'){
						?>
		<center>
			<?php
					}
				?>
			<button id="buscar" type="button"
				class="novo-btn-primary"><?php echo lang('REPORTE_BOTON_BUSCAR'); ?>
			</button>
			<?php
					if($pais=='Ec-bp'){
						?>
		</center>
		<?php
					}
				?>
	</div>

</div>

<div id='resultado-tarjetas' style='display:none'>
	<div id="top-batchs" style="width:900px !important">
		<?php if($pais != 'Ec-bp'): ?>
		<span aria-hidden="true" class="icon" data-icon="&#xe008;"></span>
		<?php endif;?>
		<?= lang('RESULTADOS') ?>
	</div>
	<div id='lotes-contenedor' style="width:900px !important">
	<div style="display:flex">
		<div id="check-all" style="width: 50%;">
			<input id="select-allR" type='checkbox' /><em id='textS'> <?= lang("SEL_ALL"); ?></em>
		</div>
		<div style="width: 50%; text-align: right; padding-top: 10px; padding-right: 10px;">
			<a id="exportXLS_a">
			<span title="Exportar Excel" aria-hidden="true" class="icon" target="_blank"
			data-icon="&#xe05a;"></span>
			</a>
		</div>
		</div>
		<table class="table-text-service" width="100%">
			<thead>
				<th class="checkbox-select">
					<span aria-hidden="true" class="icon" data-icon="&#xe083;"></span></th>
				<th id="td-nombre-2" class="bp-min-width"><?= lang('NRO_TARJETA'); ?></th>
				<th id="td-nombre-2" class="bp-min-width"><?= lang('ORDEN'); ?></th>
				<th><?= lang('LOTE'); ?></th>
				<th><?= lang('ESTATUS_EMISION'); ?></th>
				<th><?= lang('ESTATUS_PLASTICO'); ?></th>
				<th id="td-nombre-2" class="bp-min-width"><?= lang('NOMBRE') ?></th>
				<th class="bp-min-width"><?= lang('ID_PERSONA'); ?></th>
				<!-- <th><?= lang('SALDO'); ?></th>-->
				<th><?= lang('OPCIONES'); ?></th> 
			</thead>
			<tbody>

			</tbody>
		</table>
		<form id='formulario' method='post'></form>
		<div id='paginado-TM'></div>

	</div>
	<div id="batchs-last" style="width:900px !important">
	</div>
</div>
</div>

<div id='loading' style='text-align:center' class='elem-hidden'>
  <?= insert_image_cdn("loading.gif"); ?>
</div>
