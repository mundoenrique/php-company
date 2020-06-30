<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_REP_RECHARGE_MADE'); ?></h1>
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
				<span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SEARCH_CRITERIA'); ?></span>
				<div class="flex my-2 px-5">
					<form method="post" class="w-100">
						<div class="row flex justify-between">
							<div class="form-group col-4 col-xl-4">
								<label><?= lang('GEN_ENTERPRISE') ?></label>
								<select class="select-box custom-select flex h6 w-100">
									<option selected disabled>Seleccionar</option>
									<option>Option 1</option>
									<option>Option 2</option>
									<option>Option 3</option>
								</select>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-4 col-xl-4">
								<label for="initialDate"><?= lang('GEN_TABLE_DATE') ?></label>
								<input id="initialDate" class="form-control" name="datepicker" type="text">
								<div class="help-block"></div>
							</div>

							<div class="flex items-center justify-end col-3">
								<button type="submit" id="card-holder-btn" class="btn btn-primary btn-small btn-loading">
									<?= lang('GEN_BTN_SEARCH'); ?>
								</button>
							</div>
						</div>
					</form>
				</div>
				<div class="line mb-2"></div>
			</div>

			<div class="flex pb-5 flex-column">
				<span class="line-text mb-2 h4 semibold primary">Recargas realizadas </span>
				<div class="center mx-1">
					<div class="flex">
						<div class="flex mr-2 py-3 flex-auto justify-end items-center download">
							<div class="download-icons">
								<button class="btn px-1 big-modal" title="<?= lang('GEN_BTN_DOWN_XLS'); ?>" data-toggle="tooltip">
									<i class="icon icon-file-excel" aria-hidden="true"></i>
								</button>
								<button class="btn px-1 big-modal" title="<?= lang('GEN_BTN_DOWN_PDF'); ?>" data-toggle="tooltip">
									<i class="icon icon-file-pdf" aria-hidden="true"></i>
								</button>
								<button class="btn px-1 big-modal" title="<?= lang('GEN_BTN_SEE_GRAPH'); ?>" data-toggle="tooltip">
									<i class="icon novoglyphs icon-food" aria-hidden="true"></i>
								</button>
							</div>
							<form id="download-cardholders" action="<?= base_url('descargar-archivo'); ?>" method="post"></form>
						</div>
					</div>
					<table id="resultsAccount" class="cell-border h6 display responsive w-100">
						<thead class="bg-primary secondary regular">
							<tr>
								<th><?= lang('GEN_PRODUCT'); ?></th>
								<th>Abril</th>
								<th>Mayo</th>
								<th>Junio</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>PREPAID PICHINCHA IN</td>
								<td>1.00</td>
								<td>0.00</td>
								<td>0.00</td>
								<td>1.00</td>
							</tr>
							<tr>
								<td>Totales </td>
								<td>1.00</td>
								<td>0.00</td>
								<td>0.00</td>
								<td>1.00</td>
							</tr>
						</tbody>
					</table>
					<div class="line my-2"></div>
				</div>
			</div>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
	<?php endif; ?>
</div>
