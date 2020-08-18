<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div>
	<div class="widget optional mx-4 p-3">
		<div class="flex flex-column items-center">
			<span class="h5 semibold center primary truncate"><?= $enterpriseData->enterpriseName ?></span>
			<span id="fiscal-reg" class="my-2 h5 regular text" fiscal-reg="<?= $enterpriseData->idFiscal ?>">
				<?= lang('GEN_FISCAL_REGISTRY').' '.$enterpriseData->idFiscal ?>
			</span>
			<form id="enterprise-widget-form" action="<?= base_url($actionForm) ?>" method="POST" form-action="<?= $actionForm ?>">
				<select id="enterprise-select" class="select-box custom-select mt-1 mb-4 h6 w-100">
					<option selected disabled><?= lang('GEN_SELECT_ENTERPRISE'); ?></option>
					<?php foreach($enterpriseList AS $enterprise) : ?>
					<?php if($enterprise->acrif == $enterpriseData->idFiscal && !$countProducts): ?>
					<?php continue; ?>
					<?php endif;?>
					<option value="<?= $enterprise->acrif; ?>" code="<?= $enterprise->accodcia; ?>" group="<?= $enterprise->accodgrupoe; ?>">
						<?= $enterprise->nameEnterprise; ?>
					</option>
					<?php endforeach; ?>
				</select>
				<?php if(!isset($products)): ?>
				<select id="product-select" class="select-box custom-select mt-1 mb-4 h6 w-100" disabled>
					<option selected disabled><?= lang('GEN_SELECT_PRODUCTS'); ?></option>
				</select>
				<?php endif; ?>
				<div>
					<button id="enterprise-widget-btn" class="btn btn-secondary btn-small btn-loading flex mx-auto mt-1" disabled
						title="<?= $widgetBtnTitle ?>">
						<?= lang('GEN_BTN_SELECT'); ?>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
