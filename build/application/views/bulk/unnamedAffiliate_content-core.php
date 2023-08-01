<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_UNNAMED_AFFIL'); ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE') ?></a></li> /
				<li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_MENU_BULK_UNNAMED'); ?></a></li>
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
			<div class="flex pb-5 flex-column w-100">
				<span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SEARCH_CRITERIA'); ?></span>
				<div class="flex my-2 px-5">
					<form id="unna-list-form" class="w-100">
						<div class="row">
							<div class="form-group col-1 col-lg-1">
								<div class="custom-option-c custom-radio custom-control-inline">
									<input type="radio" id="all-bulks" name="all-bulks" class="custom-option-input ignore">
									<label class="custom-option-label nowrap" for="all-bulks"><?= lang('GEN_BTN_ALL'); ?></label>
								</div>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-4 col-lg-2">
								<label for="bulkNumber"><?= lang('GEN_BULK_NUMBER'); ?></label>
								<input type="text" id="bulkNumber" name="bulk-number" class="form-control h5">
								<div class="help-block"></div>
							</div>
							<div class="form-group col-4 col-lg-3">
								<label for="initialDate"><?= lang('GEN_START_DAY'); ?></label>
								<input id="initialDate" name="datepicker_start" class="form-control" name="datepicker" type="text" placeholder="DD/MM/AAA" readonly>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-4 col-lg-3">
								<label for="finalDate"><?= lang('GEN_END_DAY'); ?></label>
								<input id="finalDate" name="datepicker_end" class="form-control" name="datepicker" type="text" placeholder="DD/MM/AAA" readonly>
								<div class="help-block "></div>
							</div>
							<div class="col-xl-auto flex items-center ml-auto">
								<button type="submit" id="unna-list-btn" class="btn btn-primary btn-small btn-loading">
									<?= lang('GEN_BTN_SEARCH'); ?>
								</button>
							</div>
						</div>
					</form>
				</div>
				<div class="line mb-2"></div>
			</div>
			<div class="flex pb-5 flex-column">
				<span class="line-text mb-2 h4 semibold primary"><?= lang('BULK_UNNA_PROCESS') ?></span>
				<div class="center mx-1">
					<table id="inventoryBulkResults" class="cell-border h6 display">
						<thead class="regular secondary bg-primary">
							<tr>
								<?php foreach($bulkHeader AS $header): ?>
									<th><?= $header; ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach($bulkRecords AS $records): ?>
								<tr>
									<td><?= $records->bulkNumber ?></td>
									<td><?= $records->totalCards ?></td>
									<td><?= $records->issuanDate ?></td>
									<td><?= $records->status ?></td>
									<td><?= $records->affiliatedCards ?></td>
									<td><?= $records->forAffiliateCards ?></td>
									<td><?= $records->availableCards ?></td>
									<td class="p-0 flex justify-center items-center">
										<form action="<?= base_url(lang('SETT_LINK_BULK_UNNAMED_DETAIL')) ?>" method="post">
											<input type="hidden" name="bulkNumber" value="<?= $records->bulkNumber; ?>">
											<input type="hidden" name="totalCards" value="<?= $records->totalCards; ?>">
											<input type="hidden" name="issuanDate" value="<?= $records->issuanDate; ?>">
											<input type="hidden" name="amount" value="<?= $records->amount; ?>">
										</form>
										<?php if ($records->forAffiliateCards != "" ): ?>
											<?php if ($records->forAffiliateCards == 0): ?>
												-
												<?php else: ?>
													<button class="btn mx-1 px-0 btn-loading" title="<?= lang('GEN_BTN_SEE'); ?>" data-toggle="tooltip">
												<i class="icon icon-find btn-loading" aria-hidden="true"></i>
											</button>
											<?php endif ?>
										<?php else: ?>
											<button class="btn mx-1 px-0 btn-loading" title="<?= lang('GEN_BTN_SEE'); ?>" data-toggle="tooltip">
												<i class="icon icon-find btn-loading" aria-hidden="true"></i>
											</button>
										<?php endif ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<div class="line mb-2"></div>
				</div>
			</div>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
	<?php endif; ?>
</div>
<form id="get-data" action="<?= base_url(lang('SETT_LINK_BULK_UNNAMED_AFFIL')); ?>" method="post"></form>
