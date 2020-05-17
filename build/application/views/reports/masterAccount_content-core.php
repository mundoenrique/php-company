<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_REP_MASTER_ACCOUNT'); ?></h1>
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
						<div class="row flex ">
							<div class="form-group col-4 col-lg-4 col-xl-3">
								<label>Empresa</label>
								<select class="select-box custom-select flex h6 w-100">
									<option selected disabled>Seleccionar</option>
									<option>Option 1</option>
									<option>Option 2</option>
									<option>Option 3</option>
								</select>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-4 col-lg-4 col-xl-4">
								<label class="block">Procedimiento</label>
								<div class="custom-control custom-switch custom-control-inline">
									<input id="debit" class="custom-control-input" type="checkbox" name="debit">
									<label class="custom-control-label" for="debit">Cargo
									</label>
								</div>
								<div class="custom-control custom-switch custom-control-inline">
									<input id="credit" class="custom-control-input" type="checkbox" name="credit">
									<label class="custom-control-label" for="credit">Abono
									</label>
								</div>
							</div>
							<div class="form-group col-4 col-lg-4 col-xl-5">
								<label class="block">Resultados</label>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="trimester" name="results" class="custom-control-input" value="all">
									<label class="custom-control-label mr-1" for="trimester">Trimestre</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="semester" name="results" class="custom-control-input" value="all">
									<label class="custom-control-label mr-1" for="semester">Semestre</label>
								</div>

								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="range" name="results" class="custom-control-input" value="all">
									<label class="custom-control-label mr-1" for="range">Rango</label>
								</div>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-4 col-lg-4 col-xl-3">
								<label for="datepicker_start">Fecha Inicial</label>
								<input id="datepicker_start" class="form-control" name="datepicker" type="text">
								<div class="help-block"></div>
							</div>
							<div class="form-group col-4 col-lg-4 col-xl-3">
								<label for="datepicker_end">Fecha Final</label>
								<input id="datepicker_end" class="form-control" name="datepicker" type="text">
								<div class="help-block"></div>
							</div>
							<div class="flex items-center justify-end col-4 col-lg-4 col-xl-6 ml-auto">
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
				<span class="line-text mb-2 h4 semibold primary">Cuenta concentradora</span>
				<div class="center mx-1">
					<div class="flex">

						<div class="flex mr-2 py-3 flex-auto justify-end items-center">
							<button class="btn px-1" title="Exportar a EXCEL" data-toggle="tooltip">
								<i class="icon icon-file-excel" aria-hidden="true"></i>
							</button>
							<button class="btn px-1" title="Exportar a PDF" data-toggle="tooltip">
								<i class="icon icon-file-pdf" aria-hidden="true"></i>
							</button>
							<button class="btn px-1" title="Generar gráfica" data-toggle="tooltip">
								<i class="icon icon-chart-pie" aria-hidden="true"></i>
							</button>
						</div>
					</div>
					<table id="concenAccount" class="cell-border h6 display responsive w-100">
						<thead class="bg-primary secondary regular">
							<tr>
								<th>Fecha</th>
								<th>Descripción</th>
								<th>Ref.</th>
								<th>Débito</th>
								<th>Crédito</th>
								<th>Saldo</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>05/12/2019 10:51</td>
								<td>Crédito por abono</td>
								<td>******7336</td>
								<td>- 200.00</td>
								<td></td>
								<td>4,100.00</td>
							</tr>
							<tr>
								<td>02/12/2019 13:02</td>
								<td>Cargo a Cuenta Maestra</td>
								<td>******7336</td>
								<td></td>
								<td>+ 100.00</td>
								<td>3,900.00</td>
							</tr>
							<tr>
								<td>02/12/2019 11:00</td>
								<td>Crédito por abono</td>
								<td>******7336 </td>
								<td>- 100.00</td>
								<td></td>
								<td>4,000.00</td>
							</tr>
						</tbody>
					</table>
					<div class="line my-2"></div>
				</div>
				<div class="my-5 py-4 center none">
					<span class="h4">No se encontraron registros</span>
				</div>
			</div>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
	<?php endif; ?>
</div>
