<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline">Innomindas detalle del lote</h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE'); ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS'); ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE'); ?></a></li> /
				<li class="inline"><a class="tertiary not-pointer" href="javascript:">Cuentas Innominadas</a></li>
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
			<div class="flex flex-column">
				<span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_DETAILS') ?></span>
				<div class="row mb-2 px-5">
					<div class="form-group mb-3 col-4">
						<label for="confirmNIT" id="confirmNIT"><?= lang('GEN_FISCAL_REGISTRY') ?></label>
						<span id="confirmNIT" class="form-control px-1" readonly="readonly"><?= $fiscalId; ?></span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="confirmName" id="confirmName"><?= lang('GEN_ENTERPRISE_NAME') ?></label>
						<span id="confirmName" class="form-control px-1" readonly="readonly"><?= $enterpriseName; ?></span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="regNumber" id="regNumber"><?= lang('GEN_TABLE_BULK_DATE') ?></label>
						<span id="amountNumber" class="form-control px-1 " readonly="readonly"><?= $issuanDate; ?></span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="lot" id="lot"><?= lang('GEN_BULK_NUMBER') ?></label>
						<span id="numLot" class="form-control px-1" readonly="readonly"><?= $bulkNumber; ?></span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="regNumber" id="regNumber"><?= lang('GEN_NUMBER_RECORDS') ?></label>
						<span id="amountNumber" class="form-control px-1 " readonly="readonly"><?= $totalRecords; ?></span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="regNumber" id="regNumber"><?= lang('GEN_TABLE_TOTAL_AMOUNT'); ?></label>
						<span id="amountNumber" class="form-control px-1 " readonly="readonly"><?= $ammount; ?></span>
					</div>
				</div>
			</div>
			<div class="flex pb-5 flex-column">
				<span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_RECORDS_BULK') ?></span>
				<div class="center mx-1">
					<div class="flex justify-end items-center">
						<div class="mr-3 py-1">
							<button id="download-file" class="btn px-1 big-modal" title="Exportar a EXCEL" data-toggle="tooltip">
								<i class="icon icon-file-excel" aria-hidden="true"></i>
							</button>
							<form method="POST" action="<?= base_url(lang('SETT_LINK_DOWNLOAD_FILES')); ?>">
								<input type="hidden" name="bulkNumber" value="<?= $bulkNumber; ?>">
								<input type="hidden" name="issuanDate" value="<?= $issuanDate; ?>">
								<input type="hidden" name="amount" value="<?= $ammount; ?>">
								<input type="hidden" name="totalCards" value="<?= $totalRecords; ?>">
								<input type="hidden" name="who" value="DownloadFiles">
								<input type="hidden" name="where" value="UnnmamedAffiliate">
							</form>
						</div>
					</div>
					<table id="unnamed-detail" class="cell-border h6 display responsive w-100">
						<thead class="bg-primary secondary regular">
							<tr>
								<?php foreach($bulkHeader AS $header): ?>
								<th><?= $header; ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach($bulkRecords AS $records): ?>
							<tr>
								<td><?= $records->cardNumber ?></td>
								<?php if (lang('SETT_UNNA_ACCOUNT_NUMBER') == 'ON'): ?>
								<td><?= $records->accountNumber ?></td>
								<?php endif; ?>
								<td><?= $records->idDoc ?></td>
								<td><?= $records->cardHolder ?></td>
								<td><?= $records->status ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="line mb-2"></div>
			<div class="flex flex-column mb-4 mt-4 px-5 justify-center items-center">
				<div class="flex flex-row">
					<div class="mb-3 mr-4">
						<a href="<?= base_url(lang('SETT_LINK_BULK_UNNAMED_AFFIL')) ?>" class="btn btn-link btn-small big-modal"><?= lang('GEN_BTN_BACK') ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
	<?php endif; ?>
</div>
