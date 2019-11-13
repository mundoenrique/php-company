<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="widget order-1 p-3">
	<div class="flex flex-column items-center">
		<span class="h5 semibold center primary truncate"><?= $enterpriseName ?></span>
		<span class="my-2 h5 regular text"><?= lang('GEN_FISCAL_REGISTRY').' '.$idFiscal ?></span>
		<div class="mt-2 mb-5">
			<div class="h6 regular">
				<select class="custom-select bg-secondary" placeholder="Seleccionar otra empresa">
					<option value="Option_1">Option 1</option>
					<option value="Option_2">Option 2</option>
					<option value="Option_3">Option 3</option>
				</select>
			</div>
		</div>
		<div>
			<button class="btn btn-secondary btn-small flex  mx-auto my-2">
				Seleccionar
			</button>
		</div>
	</div>
</div>
