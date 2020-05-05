<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_BULK_UNNAMED'); ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>"><?= lang('PRODUCTS_DETAIL_TITLE') ?></a></li> /
				<li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_BULK_UNNAMED_REQUEST') ?></a></li>
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
			<div class="flex pb-5 flex-column">
				<span class="line-text mb-2 h4 semibold primary"><?= lang('BULK_UNNA_REQUEST'); ?></span>
				<form id="unnamed-request-form">
					<div class="flex px-5 pb-4 items-center row">
						<div class="form-group col-4 col-xl-3">
							<label><?= LANG('BULK_UNNA_EXPIRED_DATE'); ?></label>
							<input type="text" id="expiredDate" name="expired-date" class="form-control read-only h5" <?= $editable; ?>
								value="<?= $expMaxMonths; ?>">
							<div class="help-block"></div>
						</div>
						<div class="form-group col-4 col-xl-3">
							<label><?= LANG('BULK_UNNA_MAX_CARDS'); ?></label>
							<input type="text" id="maxCards" name="max-cards" class="form-control h5" max-cards="<?= $maxCards ?>">
							<div class="help-block"></div>
						</div>
						<?php if(lang('CONF_UNNA_STARTING_LINE1')): ?>
						<div class="form-group col-4 col-xl-3">
							<label><?= lang('BULK_UNNA_STARTING_LINE1'); ?></label>
							<input type="text" id="startingLine1" name="starting-line1" class="form-control h5">
							<div class="help-block"></div>
						</div>
						<?php endif; ?>
						<?php if(lang('CONF_UNNA_STARTING_LINE2')): ?>
						<div class="form-group col-4 col-xl-3">
							<label><?= lang('BULK_UNNA_STARTING_LINE2'); ?></label>
							<input type="text" id="startingLine2" name="starting-line2" class="form-control h5">
							<div class="help-block"></div>
						</div>
						<?php endif; ?>
						<?php if(lang('CONF_UNNA_BRANCHOFFICE')): ?>
						<div class="form-group col-4 col-xl-3">
							<label><?= lang('BULK_BRANCH_OFFICE'); ?></label>
							<select id="branchOffice" name="branch-office" class="form-control select-box custom-select h6 w-100">
								<?php foreach($branchOffices AS $pos => $branchOffice): ?>
								<?php $disabled = $branchOffice->text == lang('BULK_SELECT_BRANCH_OFFICE') ||  $branchOffice->text == lang('RESP_TRY_AGAIN') ? '  disabled' : '' ?>
								<option value="<?= $branchOffice->key; ?>" <?= $pos != 0 ? '' : 'selected'.$disabled ?>>
									<?= $branchOffice->text; ?>
								</option>
								<?php endforeach; ?>
							</select>
							<div class="help-block"></div>
						</div>
						<?php endif; ?>
						<?php if(lang('CONF_UNNA_PASSWORD')): ?>
						<div class="form-group col-4 col-xl-3">
							<label><?= lang('GEN_PASSWORD');  ?></label>
							<div class="input-group">
								<input type="password" id="password" name="password" class="form-control pwd-input h5" type="password" autocomplete="off">
								<div class="input-group-append">
									<span class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>">
										<i class="icon-view mr-0"></i>
									</span>
								</div>
							</div>
							<div class="help-block"></div>
						</div>
						<?php endif; ?>
						<div class="col-auto mt-1 ml-auto">
							<button id="unnamed-request-btn" class="btn btn-primary btn-small btn-loading flex ml-auto">
								<?= lang('GEN_BTN_PROCESS'); ?>
							</button>
						</div>
					</div>
					<div class="line mb-2"></div>
				</form>
			</div>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
	<?php endif; ?>
</div>
