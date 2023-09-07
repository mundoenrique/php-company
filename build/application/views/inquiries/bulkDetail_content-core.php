<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_DETAIL_BULK_TITLE') ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="javascript:"><?= lang('GEN_MENU_CONSULTATIONS') ?></a></li>
			</ul>
		</nav>
	</div>
</div>
<div class="flex mt-1 mb-5 bg-color flex-nowrap justify-between">
	<div class="flex flex-auto flex-column <?= $widget ? '' : 'max-width-6'; ?>">
		<div id="pre-loader" class="mx-auto flex justify-center">
			<span class="spinner-border spinner-border-lg mt-2 mb-3" role="status" aria-hidden="true"></span>
		</div>
		<div class="hide-out hide">
			<div class="flex flex-column">
				<span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_DETAILS') ?></span>
				<div class="row mb-2 px-5">
					<div class="form-group mb-3 col-4">
						<label for="confirmNIT" id="confirmNIT"><?= lang('GEN_FISCAL_REGISTRY') ?></label>
						<span id="confirmNIT" class="form-control px-1" readonly="readonly"><?= $fiscalId; ?></span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="confirmName" id="confirmName"><?= lang('GEN_ENTERPRISE_NAME') ?></label>
						<span id="confirmName" class="form-control px-1 truncate" title="<?= $enterpriseName; ?>" data-toggle="tooltip" readonly="readonly"><?= $enterpriseName; ?></span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="typeLot" id="typeLot"><?= lang('GEN_BULK_TYPE') ?></label>
						<span id="typeLotName" class="form-control px-1 bold not-processed" readonly="readonly"><?= $bulkTypeText; ?></span>
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
						<label for="regNumber" id="regNumber"><?= lang('GEN_USER_LOAD') ?></label>
						<span id="amountNumber" class="form-control px-1 " readonly="readonly"><?= $loadUserName; ?></span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="regNumber" id="regNumber"><?= lang('GEN_TABLE_BULK_DATE') ?></label>
						<span id="amountNumber" class="form-control px-1 " readonly="readonly"><?= $bulkDate; ?></span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="regNumber" id="regNumber"><?= lang('GEN_TABLE_STATUS') ?></label>
						<span id="amountNumber" class="form-control px-1 " readonly="readonly"><?= $bulkStatusText; ?></span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="amount" id="amount"><?= lang('GEN_TABLE_TOTAL_AMOUNT') ?></label>
						<span id="totalAmount" class="form-control px-1" readonly="readonly"><?= $bulkAmount; ?></span>
					</div>
				</div>
			</div>
			<?php if(count($bulkRecords) > 0): ?>
			<div class="flex pb-5 flex-column">
				<span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_RECORDS_BULK') ?></span>
				<div id="loader-table" class="mt-2 mx-auto">
					<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
				</div>
				<div class="center mx-1 hide-table hide">
					<div class="flex justify-end items-center download">
						<div class="mr-3 py-1">
							<button class="btn px-1 big-modal" title="<?= lang('GEN_BTN_DOWN_XLS'); ?>" data-toggle="tooltip">
								<i class="icon icon-file-excel" aria-hidden="true"></i>
							</button>
							<button class="btn px-1 big-modal" title="<?= lang('GEN_BTN_DOWN_PDF'); ?>" data-toggle="tooltip">
								<i class="icon icon-file-pdf" aria-hidden="true"></i>
							</button>
							<form id="download-detail-bulk" action="<?= base_url(lang('SETT_LINK_DOWNLOAD_FILES')); ?>" method="post">
								<input type="hidden" name="bulkId" value="<?= $bulkId; ?>">
								<input type="hidden" name="bulkfunction" value="<?= $function; ?>">
							</form>
						</div>
					</div>
					<table id="auth-bulk-detail" class="cell-border h6 display responsive w-100">
						<thead class="bg-primary secondary regular">
							<tr>
								<?php foreach($bulkHeader AS $header): ?>
								<th><?= $header ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach($bulkRecords AS $body): ?>
							<tr>
								<?php foreach($body AS $pos => $value): ?>
								<td><?= $value ?></td>
								<?php endforeach; ?>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
			<?php endif; ?>
			<div class="line mb-2"></div>

			<div class="flex flex-column mb-4 mt-4 px-5 justify-center items-center">
				<div class="flex flex-row">
					<div class="mb-3 mr-4">
						<a href="<?= $this->agent->referrer(); ?>" class="btn btn-link btn-small big-modal"><?= lang('GEN_BTN_BACK') ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
	<?php endif; ?>
</div>
