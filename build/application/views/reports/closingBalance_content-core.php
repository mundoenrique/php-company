<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_REP_CLOSING_BAKANCE'); ?></h1>
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
						<div class="row flex flex items-center justify-end col-sm-12">
							<div class="form-group col-4 col-xl-3">
								<label>Empresa</label>
								<select class="select-box custom-select flex h6 w-100">
									<option selected disabled>Seleccionar</option>
									<option>Option 1</option>
									<option>Option 2</option>
									<option>Option 3</option>
								</select>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-4 col-xl-3">
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
								<label>NIT. (Opcional)</label>
								<input id="Nit" class="form-control h5" type="text" placeholder="Ingresar NIT">
								<div class="help-block"></div>
							</div>

							<div class="flex items-center justify-end col-sm-12 col-xl-3">
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
				<span class="line-text mb-2 h4 semibold primary">Resultados Saldos al cierre</span>
				<div class="center mx-1">
					<div class="flex mr-2 py-3 justify-end items-center">
						<button class="btn px-1" title="Exportar a EXCEL" data-toggle="tooltip">
							<i class="icon icon-file-excel" aria-hidden="true"></i>
						</button>
					</div>
					<table id="balancesClosing" class="cell-border h6 display responsive w-100">
						<thead class="bg-primary secondary regular">
							<tr>
								<th>Cuenta</th>
								<th>NIT.</th>
								<th>Tarjeta</th>
								<th>Saldo inicial</th>
								<th>Última actividad</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>PRUEBA UAT;SERVITEBCA1</td>
								<td>010220189</td>
								<td>************0110</td>
								<td>0.00</td>
								<td></td>
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
