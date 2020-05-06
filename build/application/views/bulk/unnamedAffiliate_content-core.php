<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline">Cuentas innominadas</h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>"><?= lang('PRODUCTS_DETAIL_TITLE') ?></a></li> /
				<li class="inline"><a class="tertiary not-pointer" href="javascript:">Cuentas innominadas</a></li>
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
				<form id="#" method="post" class="w-100">
					<div class="row">
						<div class="form-group col-4 col-xl-3">
							<label for="lot"><?= LANG('BULK_NUMBER'); ?></label>
							<input type="text" id="lot" name="lot" class="form-control h5" >
							<div class="help-block"></div>
						</div>
						<div class="form-group col-4 col-lg-3 col-xl-auto">
							<label for="datepicker_start"><?= lang('GEN_START_DAY'); ?></label>
							<input id="datepicker_start" name="datepicker_start" class="form-control" name="datepicker" type="text" placeholder="DD/MM/AAA" readonly>
							<div class="help-block"></div>
						</div>
						<div class="form-group col-4 col-lg-3 col-xl-auto">
							<label for="datepicker_end"><?= lang('GEN_END_DAY'); ?></label>
							<input id="datepicker_end" name="datepicker_end" class="form-control" name="datepicker" type="text" placeholder="DD/MM/AAA" readonly>
							<div class="help-block "></div>
						</div>
						<div class="col-xl-auto flex items-center ml-auto">
							<button id="#" class="btn btn-primary btn-small btn-loading">
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
							<tr id="headerRow">
								<th><?= lang('GEN_TABLE_BULK_NUMBER'); ?></th>
								<th><?= lang('GEN_TABLE_NUMBER_CARDS'); ?></th>
								<th><?= lang('GEN_TABLE_BULK_ISSUE_DATE'); ?></th>
								<th><?= lang('GEN_TABLE_STATUS'); ?></th>
								<th><?= lang('GEN_TABLE_OPTIONS'); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>20040900</td>
								<td>1500</td>
								<td>09/04/2020</td>
								<td>PROCESADO</td>
								<td class="p-0 flex justify-center items-center">

									<button class="btn mx-1 px-0 big-modal" title="<?= lang('GEN_BTN_SEE'); ?>" data-toggle="tooltip">
										<i class="icon icon-find" aria-hidden="true"></i>
									</button>
								</td>
							</tr>

						</tbody>
					</table>
					<div class="line mb-2"></div>
				</div>
			</div>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
	<?php endif; ?>
</div>
