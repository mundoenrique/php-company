<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_REP_ACCAOUNT_STATUS'); ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>"><?= lang('GEN_MENU_ENTERPRISE'); ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>"><?= lang('GEN_PRODUCTS'); ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>"><?= lang('PRODUCTS_DETAIL_TITLE'); ?></a></li> /
				<li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_MENU_REPORTS'); ?></a></li>
			</ul>
		</nav>
	</div>
</div>
<div class="flex mt-1 bg-color flex-nowrap justify-between">
	<div id="pre-loader" class="mt-2 mx-auto">
		<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
	</div>
	<div class="w-100 hide-out hide">
		<div class="flex flex-auto flex-column <?= $widget ? '' : 'max-width-6'; ?>">
			<div class="search-criteria-order flex pb-3 flex-column w-100">
				<span class="line-text mb-2 h4 semibold primary">Criterio de búsqueda</span>
				<div class="flex my-2 px-5">
					<form method="post" class="w-100">
						<div class="row flex justify-between">
							<div class="form-group col-4 col-xl-4">
								<label>Empresa</label>
								<select class="select-box custom-select flex h6 w-100">
									<option selected disabled>Seleccionar</option>
									<option>Option 1</option>
									<option>Option 2</option>
									<option>Option 3</option>
								</select>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-4 col-xl-4">
								<label>Producto</label>
								<select class="select-box custom-select flex h6 w-100">
									<option selected disabled>Seleccionar</option>
									<option>Option 1</option>
									<option>Option 2</option>
									<option>Option 3</option>
								</select>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-4 col-xl-3">
								<label for="datepicker_start">Fecha</label>
								<input id="datepicker_start" class="form-control" name="datepicker" type="text">
								<div class="help-block"></div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-9">
								<label class="block">Resultados</label>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="allResults" name="results" class="custom-control-input" value="all">
									<label class="custom-control-label mr-1" for="allResults">Todos</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="resultByNIT" name="results" class="custom-control-input">
									<label class="custom-control-label mr-1" for="resultByNIT">DNI</label>
									<input id="resultByNIT" name="results" type="text" class="form-control col-8 col-auto visible" />
								</div>
								<div class="help-block"></div>
							</div>
							<div class="flex items-center justify-end col-3">
								<button class="btn btn-primary btn-small">
									Buscar
								</button>
							</div>
						</div>
					</form>
				</div>
				<div class="line mb-2"></div>
			</div>

			<div class="flex pb-5 flex-column">
				<span class="line-text mb-2 h4 semibold primary">Resultados Estado de Cuenta</span>
				<div class="center mx-1">
					<div class="flex">
						<div class="flex ml-4 py-3 flex-auto">
							<p class="mr-5 h5 semibold tertiary">Nombre: <span class="light text">Jhonatan Ortiz</span></p>
							<p class="mr-5 h5 semibold tertiary">Cuenta: <span class="light text">**********270300</span></p>
							<p class="mr-5 h5 semibold tertiary">Cédula: <span class="light text">1803752318</span></p>
						</div>
						<div class="flex mr-2 py-3 justify-end items-center">
							<button class="btn px-1" title="Exportar a EXCEL" data-toggle="tooltip">
								<i class="icon icon-file-excel" aria-hidden="true"></i>
							</button>
							<button class="btn px-1" title="Exportar a PDF" data-toggle="tooltip">
								<i class="icon icon-file-pdf" aria-hidden="true"></i>
							</button>
							<?php if(FALSE): ?>
							<button class="btn px-1" title="Generar gráfica" data-toggle="tooltip">
								<i class="icon icon-chart-pie" aria-hidden="true"></i>
							</button>
							<?php endif; ?>
							<button class="btn px-1" title="Generar Comprobante Masivo" data-toggle="tooltip">
								<i class="icon icon-file-blank" aria-hidden="true"></i>
							</button>
						</div>
					</div>
					<table id="resultsAccount" class="cell-border h6 display responsive w-100">
						<thead class="bg-primary secondary regular">
							<tr>
								<th>Tarjeta</th>
								<th>Fecha</th>
								<th>Fid</th>
								<th>Terminal</th>
								<th>Secuencia</th>
								<th>Referencia</th>
								<th>Descripción</th>
								<th>ABONO</th>
								<th>CARGO</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>**********270399</td>
								<td>20/06/2019</td>
								<td>10000000206</td>
								<td>02060016</td>
								<td>53000000000</td>
								<td>5758</td>
								<td>RETIRO ATM BP - LABORATORIO BANRED</td>
								<td>0</td>
								<td>20.00</td>
							</tr>
							<tr>
								<td>**********270399</td>
								<td>20/06/2019</td>
								<td>72521001</td>
								<td>00014601</td>
								<td>37210</td>
								<td>5758</td>
								<td>RETIRO ATM BP - LABORATORIO BANRED</td>
								<td>0</td>
								<td>20.00</td>
							</tr>
							<tr>
								<td>**********270399</td>
								<td>20/06/2019</td>
								<td>10000000206</td>
								<td>02060016</td>
								<td>575400000000</td>
								<td>5758</td>
								<td>RETIRO ATM BP - LABORATORIO BANRED</td>
								<td>0</td>
								<td>20.00</td>
							</tr>
						</tbody>
					</table>
					<div class="line my-2"></div>
				</div>
			</div>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
	<?php endif; ?>
</div>
