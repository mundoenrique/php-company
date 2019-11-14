<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="widget order-1 p-3">
	<div class="flex flex-column items-center">
		<span class="h5 semibold center primary truncate"><?= $enterpriseName ?></span>
		<span class="my-2 h5 regular text"><?= lang('GEN_FISCAL_REGISTRY').' '.$idFiscal ?></span>
			<select class="select-box custom-select mt-3 mb-4 h6">
				<option selected disabled>Seleccionar otra empresa</option>
        <option>Option 1</option>
        <option>Option 2</option>
        <option>Option 3</option>
      </select>
		<div>
			<button class="btn btn-secondary btn-small flex  mx-auto my-2">
				Seleccionar
			</button>
		</div>
	</div>
</div>
