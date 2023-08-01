<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('BULK_CALCULATE_ORDER'); ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="flex mb-2 items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_BULK_AUTH')) ?>"><?= lang('GEN_AUTHORIZE_BULK_TITLE') ?></a></li>
				/
				<li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_CACULATE_SERVICE_ORDERS') ?></a></li>
			</ul>
		</nav>
	</div>
</div>

<div class="flex mt-1 bg-color flex-nowrap justify-between">
	<div id="pre-loader" class="mt-2 mx-auto">
		<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
	</div>
	<div class="w-100 hide-out hide">
		<div class="flex flex-auto flex-column">
			<div class="flex pb-5 flex-column">
				<?php if(count($serviceOrdersList) > 0): ?>
				<span class="line-text mb-2 h4 semibold primary"></span>
				<div class="center mx-1">
					<table id="resultServiceOrders" class="cell-border h6 display">
						<thead class="bg-primary secondary regular">
							<tr>
								<th><?= lang('GEN_TABLE_COMMISSION'); ?></th>
								<th><?= lang('GEN_TABLE_VAT'); ?></th>
								<th><?= lang('GEN_TABLE_AMOUNT_SO'); ?></th>
								<th><?= lang('GEN_TABLE_AMOUNT'); ?></th>
								<th><?= lang('GEN_TABLE_DEPOSIT_AMOUNT'); ?></th>
								<th><?= lang('GEN_TABLE_OPTIONS'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($serviceOrdersList AS $serviceOrders): ?>
							<?php $tempOrdersId.= $serviceOrders->tempOrderId.',' ?>
							<tr bulk="<?= htmlspecialchars(json_encode($serviceOrders->bulk), ENT_QUOTES, 'UTF-8'); ?>">
								<td><?= $serviceOrders->commisAmount; ?></td>
								<td><?= $serviceOrders->VatAmount; ?></td>
								<td><?= $serviceOrders->soAmount; ?></td>
								<td><?= $serviceOrders->totalAmount; ?></td>
								<td><?= $serviceOrders->depositedAmount; ?></td>
								<td class="p-0 flex justify-center items-center">
									<button class="btn px-0 details-control" title="Ver" data-toggle="tooltip">
										<i class="icon icon-find mr-1" aria-hidden="true"></i>
									</button>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<?php endif; ?>
				<?php if(count($bulkNotBillable) > 0): ?>
				<span class="line-text mb-2 h4 semibold primary"><?= lang('BULK_NO_BILLABLE'); ?></span>
				<div class="center mx-1">
					<table id="resultServiceOrders" class="cell-border h6 display">
						<thead class="bg-primary secondary regular">
							<tr>
								<th><?= lang('GEN_TABLE_BULK_NUMBER'); ?></th>
								<th><?= lang('GEN_TABLE_BULK_DATE'); ?></th>
								<th><?= lang('GEN_TABLE_TYPE'); ?></th>
								<th><?= lang('GEN_TABLE_RECORDS'); ?></th>
								<th><?= lang('GEN_TABLE_STATUS'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($bulkNotBillable AS $NotBillable): ?>
							<?php $bulknotBill.= $NotBillable->tempOrderId.',' ?>
							<tr>
								<td><?= $NotBillable->bulkNumber; ?></td>
								<td><?= $NotBillable->bulkLoadDate; ?></td>
								<td><?= $NotBillable->bulkLoadType; ?></td>
								<td><?= $NotBillable->bulkRecords; ?></td>
								<td><?= $NotBillable->bulkStatus; ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<?php endif; ?>
					<div class="line my-2"></div>
				</div>
				<form id="auth-bulk-form">
					<div class="flex flex-column mb-4 px-5 justify-center items-center">
						<?php if (lang('SETT_SERVICE_ORDER_OTP') == 'ON'): ?>
						<div class="form-group col-6 col-xl-4 center">
							<label for="otpCode" class="pb-1 regular"><?= lang('GEN_OTP'); ?></label>
							<input id="otpCode" name="otpCode" class="form-control col-6 block m-auto" type="text" autocomplete="off">
							<div class="help-block center"></div>
						</div>
						<?PHP endif; ?>
						<div class="form-group col-4 col-lg-3">
							<input id="tempOrders" name="tempOrders" type="hidden" value="<?= $tempOrdersId; ?>">
							<input id="bulkNoBill" name="bulkNoBill" type="hidden" value="<?= $bulknotBill; ?>">
							<div class="help-block"></div>
						</div>
						<div class="flex flex-row">
							<?php if(lang('SETT_SERVICE_ORDER_CANCEL') == 'ON'): ?>
							<div class="mb-3 mr-4">
								<button id="cancel-bulk-btn" class="btn btn-link btn-small big-modal"><?= lang('GEN_BTN_CANCEL'); ?></button>
							</div>
							<?php endif; ?>
							<div class="mb-3 mr-1">
								<button id="auth-bulk-btn" class="btn btn-primary  btn-loading btn-small"><?= lang('GEN_BTN_AUTHORIZE'); ?></button>
							</div>
						</div>
					</div>
				</form>
				<div class="my-5 py-4 center none">
					<span class="h4"><?= lang('GEN_NO_LIST'); ?></span>
				</div>
			</div>
		</div>
	</div>
</div>
