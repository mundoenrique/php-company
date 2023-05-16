<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_REP_ACCOUNT_STATUS'); ?></h1>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE'); ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS'); ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE'); ?></a></li> /
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
			<?php $data['name']='extStatusAccountForm' ?>
			<?php $this->load->view('reports/filterAccountStatus', $data)  ?>
			<div class="flex my-2 px-5">
				<form id="extStatusAccountFormXls" class="w-100 hide">
					<input id="enterpriseNameXls" name="enterpriseNameXls"  type="text" value="">
					<input id="descProductXls" name="descProductXls"  type="text" value="">
					<input id="resultByNITXls" name="resultByNITXls"  type="text" value="">
					<input id="enterpriseCodeXls" name="enterpriseCodeXls"  type="text" value="">
					<input id="productCodeXls" name="productCodeXls"  type="text" value="">
					<input id="initialDateActXls" name="initialDateActXls"  type="text" value="">
					<input id="resultSearchXls" name="resultSearchXls"  type="text" value="">
				</form>
			</div>

			<div id="blockResults" class="flex pb-5 flex-column">
				<span class="line-text mb-2 h4 semibold primary">Resultados</span>
				<div class="center mx-1">
					<div class="flex mr-2 py-3 justify-end items-center">
						<button id="export_excel" class="big-modal btn px-1" title="Exportar a EXCEL" data-toggle="tooltip">
							<i class=" icon icon-file-excel" aria-hidden="true"></i>
						</button>
						<?php if(FALSE): ?>
						<button id="export_pdf" class="big-modal btn px-1" title="Exportar a PDF" data-toggle="tooltip">
							<i class="icon icon-file-pdf" aria-hidden="true"></i>
						</button>
						<button class="btn px-1" title="Generar gráfica" data-toggle="tooltip">
							<i class="icon icon-chart-pie" aria-hidden="true"></i>
						</button>

						<button class="btn px-1" title="Generar Comprobante Masivo" data-toggle="tooltip">
							<i class="icon icon-file-blank" aria-hidden="true"></i>
						</button>
						<?php endif; ?>
					</div>
					<table id="extAccountStatusTable" class="cell-border h6 display w-100">
						<thead class="bg-primary secondary regular">
							<tr>
								<th><?= lang('REPORTS_TABLE_DATE') ?></th>
								<th><?= lang('REPORTS_ACCOUNT_CARD') ?></th>
								<th><?= lang('GEN_TABLE_NAME_CLIENT') ?></th>
								<th><?= lang('GEN_TABLE_DNI') ?></th>
								<th><?= lang('REPORTS_ACCOUNT_REFERENCE') ?></th>
								<th><?= lang('REPORTS_ACCOUNT_DESCRIPTION') ?></th>
								<th><?= lang('REPORTS_ACCOUNT_OPERATION') ?></th>
								<th><?= lang('REPORTS_ACCOUNT_AMOUNT') ?></th>
							</tr>
						</thead>
						<tbody id="tbody-datos-general" class = "tbody-reportes">
						</tbody>
					</table>
					<div id="spinnerResults" class="mt-2 mx-auto hide">
						<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
					</div>
					<div class="line my-2"></div>
				</div>
			</div>
		</div>
	</div>
</div>
