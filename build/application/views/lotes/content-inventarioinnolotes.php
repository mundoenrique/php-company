<?php

$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;

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
				<a ><?php echo lang('POSITION_INNO'); ?></a>
			</li>
			/
			<li class="breadcrumb-item-current">
				<a ><?php echo lang('POSITION_INVENINNO'); ?></a>
			</li>
		</ol>

		<div style="display: block;" id="lotes-general" class="elem-hidden">	

		<div id='filtroOS' >

			<div id="top-batchs">
				<span aria-hidden="true" class="icon" data-icon="&#xe07a;"></span>
				<?php echo lang('TITULO_CRITERIOSBUSQ'); ?>
			</div>
			<form action="/innominada/inventario"></form>
			<div id="lotes-contenedor" >
				<span class="info-OD">
					<h5>Nro. Lote</h5>
					<input id='nro_lote' class="required login" placeholder="" value="" onfocus="javascript:this.value=''"/>
				</span>
				<span class="info-OD">
					<h5>Fecha Inicial</h5>
					<input id='fecha_inicial' class="required login" placeholder="DD/MM/AA" value="" onfocus="javascript:this.value=''"/>
				</span>
				<span class="info-OD">
					<h5>Fecha Final</h5>
					<input id='fecha_final' class="required login" placeholder="DD/MM/AA" value="" onfocus="javascript:this.value=''"/>
				</span>
			</div>
			
			<div id="batchs-last">
				<button id='buscarOS'>Buscar</button>
			</div>

		</div>

			<div id="top-batchs">
				<span aria-hidden="true" class="icon" data-icon=""></span>
				Lotes Procesados				
			</div>
			<div id="lotes-contenedor-2">
				<div id="table-text-lotes_wrapper" class="dataTables_wrapper" role="grid">
					<table id="table-text-lotes-inventario" class="table-bandeja">
						<thead>
							<tr role="row">
								<th style="display: none; width: 100px;">

								</th>
								<th>
									Nro. Lote
								</th>
								<th id="td-nombre">
									Cant. Tarjetas
								</th>
								<th>
									Fecha Emisi&oacute;n
								</th>
								<th>
									Estatus
								</th>
								<th>
									Opciones
								</th>
							</tr>
						</thead>
						<tbody id="resultado_lista">
							<?php
							if(isset($data1) && $data1 != '')
							{
								$data1 = unserialize($data1);
								foreach ($data1->lista as $value) {
								echo '
									<tr class="odd">
										<td class="icon-batchs-green" id="icon-batchs">
											<span data-icon="" class="icon" aria-hidden="true"></span>
										</td>
										<td>' . $value->acnumlote . '</td>
										<td id="td-nombre">' . $value->ncantregs . '</td>
										<td>' . $value->dtfechorcarga . '</td>
										<td>' . $value->status . '</td>
										<td id="icons-options">
											<a data-acnumlote="' . $value->acnumlote . '" data-acrif="' . $value->acrif . '" data-acnomcia="' . $value->acnomcia . '" data-dtfechorcarga="' . $value->dtfechorcarga . '" data-nmonto="' . $value->nmonto . '" title="Ver lote" class="detalle-item" id="detalle">
												<span data-icon="" class="icon" aria-hidde="true"></span>
											</a>
										</td>
									</tr>
									';
								}
							}
							?>
						</tbody>
					</table>
				</div>
			</div>

			<div id="top-batchs" class="listado_2" style="display:none">
				<span aria-hidden="true" class="icon" data-icon=""></span>
				Tarjetas			
			</div>
			<div id="lotes-contenedor-2" class="listado_2" style="display:none">
				<div>
					<table id="table-text-lotes-inventario-2" class="table-bandeja">
						<thead style='display:none'>
							<tr role="row">
								<th style="display: none; width: 100px;">

								</th>
								<th class="fecha-carga">
									Nro. Lote
								</th>
								<th id="td-nombre">
									Nro. Tarjeta
								</th>
								<th>
									DNI
								</th>
								<th>
									Nombre
								</th>
								<th>
									Estatus
								</th>
								<th>
									Opciones
								</th>
							</tr>
						</thead>
						<tbody id="resultado_lista_2">
							<h3 id='loading-lista-2' style='display:none'><?echo lang('CARGANDO')?></h3>
						</tbody>
					</table>
				</div>
			</div>

</div>
</div>

<form id='formulario' method='post'></form>