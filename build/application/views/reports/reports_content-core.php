<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline">Reportes</h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>">Empresas</a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>">Productos</a></li> /
				<li class="inline"><a class="tertiary not-pointer" href="javascript:">Reportes</a></li>
			</ul>
		</nav>
	</div>
</div>
<div class="flex mt-1 bg-color flex-nowrap justify-between">
	<div class="flex flex-auto flex-column">
		<div class="search-criteria-order flex pb-3 flex-column w-100">
			<span class="line-text mb-2 h4 semibold primary">Selección de reporte</span>
			<div class="flex my-2 px-5">
				<form method="post" class="w-100">
					<div class="row flex items-center justify-between">
						<div class="form-group col-6 col-xl-6">
							<label>Tipo de reporte</label>
							<select id="reports" class="select-box custom-select flex h6 w-100">
								<?php foreach($reportsList AS $pos => $value): ?>
								<option value="<?= $value->key; ?>" <?= $pos != 0 ? '' : 'selected disabled' ?> type="<?= !isset($value->type) ?: $value->type; ?>">
									<?= $value->text; ?>
								</option>
								<?php endforeach; ?>
								<!-- <option disabled selected>Seleccionar</option>
								<option value="customer-movements">Movimientos de clientes</option>
								<option value="customer-card-movements">Movimientos de tarjetas clientes</option>
								<option value="card-inquiry">Consulta de tarjetas</option>
								<option value="proof-food">Comprobante alimentación</option>
								<option value="customer-extract">Extracto de cliente</option>
								<option value="lock-query">Consulta de Desbloqueo/Bloqueos</option>
								<option value="GMF-certificate">Certificado de GMF</option value=""> -->
							</select>
							<div class="help-block"></div>
						</div>
						<div id="div-download" class="no-select none">
							<div class="flex items-start justify-end">
								<button id="btn-download" class="flex items-baseline btn btn-link btn-small big-modal">
									<i aria-hidden="true" class="icon icon-download"></i>
									&nbsp;Descargar
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="line mb-2"></div>
		</div>

		<div class="flex pb-5 flex-column">
			<span id="search-criteria" class="no-select none line-text mb-2 h4 semibold primary">Criterio de búsqueda</span>
			<div class="flex my-2 px-5">
				<form id="form-report" method="post" class="no-select reports-form w-100 none">
					<div id="customer-card-movements" class="no-select row">
						<div class="form-group col-4">
							<label for="datepicker_start">Fecha inicio</label>
							<input id="datepicker_start" class="form-control" name="datepicker" type="text" readonly placeholder="DD/MM/AAAA">
							<div class="help-block"></div>
						</div>
						<div class="form-group col-4">
							<label for="datepicker_end">Fecha fin</label>
							<input id="datepicker_end" class="form-control" name="datepicker" type="text" readonly placeholder="DD/MM/AAAA">
							<div class="help-block"></div>
						</div>
						<div class="flex items-center justify-end col-4">
							<button class="btn btn-primary btn-small btn-loading">
								Buscar
							</button>
						</div>
					</div>

					<div id="card-inquiry" class="no-select row">
						<div class="form-group col-4">
							<label>Tipo de identificacion</label>
							<select class="select-box custom-select flex h6 w-100">
								<option selected disabled>Seleccionar</option>
								<option>Option 1</option>
								<option>Option 2</option>
								<option>Option 3</option>
							</select>
							<div class="help-block"></div>
						</div>

						<div class="form-group col-4">
							<label for="id-number">Numero de identificación</label>
							<input id="id-number" name="id-number" class="form-control" type="text">
							<div class="help-block"></div>
						</div>

						<div class="form-group col-4">
							<label for="card-number">Numero de tarjeta</label>
							<input id="card-number" name="card-number" class="form-control" type="text">
							<div class="help-block"></div>
						</div>

						<div class="form-group col-4">
							<label for="datepicker_start">Fecha inicio</label>
							<input id="datepicker_start" class="form-control" name="datepicker" type="text" readonly placeholder="DD/MM/AAAA">
							<div class="help-block"></div>
						</div>
						<div class="form-group col-4">
							<label for="datepicker_end">Fecha fin</label>
							<input id="datepicker_end" class="form-control" name="datepicker" type="text" readonly placeholder="DD/MM/AAAA">
							<div class="help-block"></div>
						</div>
						<div class="flex items-center justify-end col-4">
							<button class="btn btn-primary btn-small btn-loading">
								Buscar
							</button>
						</div>
					</div>

					<div id="proof-food" class="no-select">
						<div class="row">
							<div class="form-group col-6 col-lg-4">
								<label for="card-number">Numero de tarjeta</label>
								<input id="card-number" name="card-number" class="form-control" type="text">
								<div class="help-block"></div>
							</div>

							<div class="flex items-center justify-end col-8">
								<button class="btn btn-primary btn-small btn-loading">
									Buscar
								</button>
							</div>
						</div>

						<div class="flex pb-5 flex-column">
							<span class="line-text mb-2 h4 semibold primary"></span>
							<div class="center mx-1">
								<table id="reports-results" class="cell-border h6 display">
									<thead class="bg-primary secondary regular">
										<tr>
											<th>Orden nro.</th>
											<th>Fecha</th>
											<th>RUC</th>
											<th>Empresa</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>10363</td>
											<td>2019-09-26 09:53:12</td>
											<td>1792067782001</td>
											<td>Directv Colombia C. Ltd</td>
										</tr>
										<tr>
											<td>10380</td>
											<td>2019-09-27 16:43:49</td>
											<td>1792067782001</td>
											<td>Directv Colombia C. Ltd</td>
										</tr>
										<tr>
											<td>10381</td>
											<td>2019-09-27 16:52:11</td>
											<td>1792067782001</td>
											<td>Directv Colombia C. Ltd</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>

					</div>

					<div id="customer-extract" class="no-select row">
						<div class="form-group col-6 col-lg-4">
							<label for="datepicker">Fecha</label>
							<input class="form-control month-year" name="datepicker" type="text" readonly placeholder="MMMM AAAA">
							<div class="help-block"></div>
						</div>
						<div class="flex items-center justify-end col-6 col-lg-8">
							<button class="btn btn-primary btn-small btn-loading">
								Buscar
							</button>
						</div>
					</div>

					<div id="lock-query" class="no-select row">
						<div class="form-group col-4">
							<label>Tipo de tarjeta</label>
							<select class="select-box custom-select flex h6 w-100">
								<option selected disabled>Seleccionar</option>
								<option>Option 1</option>
								<option>Option 2</option>
								<option>Option 3</option>
							</select>
							<div class="help-block"></div>
						</div>

						<div class="form-group col-4">
							<label for="datepicker">Fecha</label>
							<input class="form-control month-year" name="datepicker" type="text" readonly placeholder="MMMM AAAA">
							<div class="help-block"></div>
						</div>

						<div class="flex items-center justify-end col-4">
							<button class="btn btn-primary btn-small btn-loading">
								Buscar
							</button>
						</div>

					</div>

					<div id="GMF-certificate" class="no-select row">
						<div class="form-group col-3">
							<label>Tipo de consulta</label>
							<select class="select-box custom-select flex h6 w-100">
								<option selected disabled>Seleccionar</option>
								<option>Option 1</option>
								<option>Option 2</option>
								<option>Option 3</option>
							</select>
							<div class="help-block"></div>
						</div>

						<div class="form-group col-3">
							<label for="datepicker">Fecha del proceso</label>
							<input class="form-control month-year" name="datepicker" type="text" readonly placeholder="MMMM AAAA">
							<div class="help-block"></div>
						</div>

						<div class="form-group col-3">
							<label>Año gravable</label>
							<select class="select-box custom-select flex h6 w-100">
								<option selected disabled>Seleccionar</option>
								<option>Option 1</option>
								<option>Option 2</option>
								<option>Option 3</option>
							</select>
							<div class="help-block"></div>
						</div>
						<div class="flex items-center justify-end col-3">
							<button class="btn btn-primary btn-small btn-loading">
								Buscar
							</button>
						</div>
					</div>
				</form>
			</div>

			<div id="line-reports" class="no-select line mb-2 none"></div>

			<div class="none">
				<div class="flex items-start justify-center mt-5">
					<button class="flex items-baseline btn btn-link btn-small">
						<i aria-hidden="true" class="icon icon-download"></i>
						&nbsp;Descargar
					</button>
				</div>
			</div>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
	<?php endif; ?>
</div>
