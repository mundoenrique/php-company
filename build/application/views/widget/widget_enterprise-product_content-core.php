<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="widget order-1 p-3">
	<div class="flex flex-column items-center">
		<span class="h5 semibold center primary truncate"><?= $enterpriseData->enterpriseName ?></span>
		<span class="my-2 h5 regular text"><?= lang('GEN_FISCAL_REGISTRY').' '.$enterpriseData->idFiscal ?></span>
		<form id="enterprise-widget-form" action="<?= base_url('productos') ?>" method="POST">
			<select id="enterprise-select" class="select-box custom-select mt-3 mb-4 h6 w-100">
				<option selected disabled>Seleccionar otra empresa</option>
				<?php foreach($enterpriseList AS $enterprise) : ?>
				<?php if($enterprise->acnomcia == $enterpriseData->enterpriseName): ?>
				<?php continue; ?>
				<?php endif;?>
				<option value="<?= $enterprise->acrif; ?>"><?= $enterprise->acnomcia; ?></option>
				<?php endforeach; ?>
			</select>
			<div>
				<button id="enterprise-widget-btn" class="btn btn-secondary btn-small flex  mx-auto my-2" disabled title="Selecciona una empresa">
					Seleccionar
				</button>
			</div>
		</form>
	</div>
</div>
