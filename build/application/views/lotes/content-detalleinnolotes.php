<?php

$pais = $this->uri->segment(1);
log_message('DEBUG', '****************'.$pais);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;

$ceo_name = $this->security->get_csrf_token_name();
$ceo_cook = $this->security->get_csrf_hash();

$info;
?>

<div id="content-products">
	<h1><?php echo lang('TITULO_CUENTAS_INVINNO'); ?></h1>
	<h2 class="title-marca">
		<?php echo ucwords(mb_strtolower($programa));?>
	</h2>

	<ol class="breadcrumb">
		<li>
			<a href="<?php echo $urlBase; ?>/dashboard" rel="start"><?php echo lang('BREADCRUMB_INICIO'); ?></a>
		</li>
		/
		<li>
			<a href="<?php echo $urlBase; ?>/dashboard" rel="section"><?php echo lang('BREADCRUMB_EMPRESAS'); ?></a>
		</li>
		/
		<li>
			<a href="<?php echo $urlBase; ?>/dashboard/productos" rel="section">
				<?php echo lang('BREADCRUMB_PRODUCTOS'); ?></a>
			</li>
			/
			<li>
				<a href="<?php echo $urlBase; ?>/lotes" rel="section"><?php echo lang('BREADCRUMB_LOTES'); ?></a>
			</li>
			/
			<li class="breadcrumb-item-current">
				<a href="<?php echo $urlBase; ?>/lotes/innominada/afiliacion"><?php echo lang('POSITION_INNO'); ?></a>
			</li>
			/
			<li class="breadcrumb-item-current">
				<a><?php echo "Detalle del lote";//echo lang('POSITION_INVENINNO'); ?></a>
			</li>
		</ol>

		<div style="display: block;" id="lotes-general" class="elem-hidden">

		<div id="top-batchs">
			<span aria-hidden="true" class="icon" data-icon="&#xe03c;"></span>
			<?php echo "Detalle del lote";//echo lang('TITULO_LOTES_DETALLE'); ?>
		</div>

		<div id="lotes-contenedor">

			<div id='detalle-gral'>

			<div id="detalleLote-1">
				<div id="detalleLote-1-RUC">
					<h5><?php echo "Lote nro.";//echo lang('ID_FISCAL') ?></h5>
					<p><?php echo $numLote;//echo $data[0]->acrif ?></p>
				</div>
				<div id="detalleLote-1-nombre">
					<h5><?php echo lang('ID_FISCAL'); ?>:</h5>
					<p><?php echo $acrif;//echo $data[0]->acnomcia ?></p>
				</div>
				<div id="detalleLote-1-short">
					<h5><?php echo "Nombre de la empresa";//echo lang('TABLA_LOTESPA_TIPOLOTE') ?></h5>
					<p><?php echo $acnomcia;//echo $data[0]->acnombre ?></p>
				</div>
			</div>

			<div id="detalleLote-2">
				<div id="detalleLote-2-tipo">
					<h5><?php echo "Fecha de carga";//echo lang('TABLA_LOTESPA_NROLOTE') ?></h5>
					<p><?php echo $dtfechorcarga;//echo $data[0]->acnumlote ?></p>
				</div>
				<div id="detalleLote-2-nro">
					<h5><?php echo "Monto";//echo lang('TABLA_LOTESPA_CANTIDADREGISTROS') ?></h5>
					<p><?php echo $nmonto;//echo $data[0]->ncantregs ?></p>
				</div>
				<!-- <div id="detalleLote-2-ord">
					<h5><?php echo lang('USUARIO_CARGA') ?></h5>
					<p><?php //echo $data[0]->accodusuarioc ?></p>
				</div> -->
			</div>

		</div>
</div>
			<div id="top-batchs" class="listado_2">
				<span aria-hidden="true" class="icon" data-icon=""></span>
				Lista de tarjetas
			</div>

			<div id="lotes-contenedor">
				<div id="view-results">
					<a id='downXLS'>
						<span aria-hidden="true" class="icon" data-icon="&#xe05a;" title='<?php echo lang('DWL_XLS')?>'></span>
					</a>
				</div>
				<div id="table-text-lotes_wrapper" class="dataTables_wrapper" role="grid">
					<table id="table-text-lotes-inventario" class="table-bandeja">
						<thead>
							<tr role="row">
								<th style="display: none; width: 100px;">

								</th>
								<th class="nro_cuenta">
									Nro. de tarjeta
								</th>
								<th class="ci">
									C.I.
								</th>
								<th class="nombre-persona">
									Nombre
								</th>
								<th class="fecha-carga">
									Fecha emisión
								</th>
								<th class="estatus-lote">
									Estatus
								</th>
							</tr>
						</thead>
						<tbody id="resultado_lista">
							<?php
							if($data1){
								$data1 = unserialize($data1);
								foreach ($data1->tarjetasInnominadas as $value) {
								echo '
									<tr class="odd">
										<td class="' . (($value->estatus=='0') ? 'icon-batchs-orange' : 'icon-batchs-green') . '" id="icon-batchs">
											<span data-icon="" class="icon" aria-hidden="true"></span>
										</td>
										<td class="nro_cuenta" style="text-align:center; font-size:11px">' . $value->nroTarjeta . '</td>
										<td class="ci" style="text-align:center; font-size:11px">' . $value->idExtPer . '</td>
										<td class="nombre-persona" style="text-align:center; font-size:11px; word-wrap: break-word; overflow:hidden">' . $value->nombre .' '. (($pais == 'Ec-bp') ? $value->apellido : '') . '</td>
										<td class="fecha-carga" style="text-align:center; font-size:11px">' . $value->fechaRegistro . '</td>
										<td class="estatus-lote-td" style="font-size:11px">' . (($value->estatus=='0') ? 'NO AFILIADO' : 'AFILIADO') . '</td>
									</tr>
									';
								}
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<div id="batchs-last">
				<form method='post' action="<?php echo $urlBase ?>/lotes/innominada/afiliacion">
				<input type="hidden" name="<?php echo $ceo_name ?>" class="ignore" value="<?php echo $ceo_cook ?>">
					<button type="submit">Volver</button>
				</form>
			</div>
</div>
<form id='formulario' method='post'>
<input type="hidden" name="data-numlote" value="<?php echo $numLote?>" />
</form>
