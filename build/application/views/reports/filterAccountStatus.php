<div class="search-criteria-order flex pb-3 flex-column w-100">
	<span class="line-text mb-2 h4 semibold primary">Criterio de búsqueda</span>
	<div class="flex my-2 px-5">
		<form id=<?php echo $name ?> class="w-100">
			<div class="row flex justify-between">
				<div class="form-group col-4 col-xl-4">
					<label><?= lang('GEN_ENTERPRISE'); ?></label>
					<select id="enterpriseCode" name="enterpriseCode" class="select-box custom-select flex h6 w-100 enterprise-getprod">
						<?php foreach ($enterpriseList as $enterprise) : ?>
							<?php if ($enterprise->acrif == $enterpriseData->idFiscal) : ?>
							<?php endif; ?>
							<option doc="<?= $enterprise->accodcia; ?>" name="<?= $enterprise->acrazonsocial; ?>" value="<?= $enterprise->acrif; ?>" <?= $enterprise->acrif == $enterpriseData->idFiscal ? 'selected' : '' ?> id-fiscal="<?= $enterprise->acrif; ?>">
								<?= $enterprise->acnomcia; ?>
							</option>
						<?php endforeach; ?>
					</select>
					<div class="help-block"></div>
				</div>
				<div class="form-group col-4 col-xl-4">
					<label><?= lang('GEN_PRODUCT'); ?></label>
					<select id="productCode" name="productCode" class="select-box custom-select flex h6 w-100">
						<option selected disabled><?= $selectProducts ?></option>
						<?php if ($productsSelect) : ?>
							<?php foreach ($productsSelect as $product) : ?>
								<option doc="<?= $product['desc'] ?>" value="<?= $product['id']; ?>" <?= $product['id'] == $currentProd ? 'selected' : ''; ?>><?= $product['desc'] ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
					<div class="help-block"></div>
				</div>
				<div class="form-group col-4 col-xl-3">
					<label for="initialDateAct"><?= lang('GEN_START_DAY'); ?></label>
					<input id="initialDateAct" name="selected-month-year" class="form-control date-picker " type="text" placeholder="MM/AAAA" readonly="" autocomplete="off">
					<div class="help-block"></div>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-9">
					<label class="block">Resultados</label>
					<div class="custom-control custom-radio custom-control-inline align-top">
						<input type="radio" id="allResults" name="results" class="custom-control-input">
						<label class="custom-control-label mr-1" for="allResults">Todos</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="resultByNIT" name="results" class="custom-control-input">
						<label class="custom-control-label mr-1" for="resultByNIT"><?= lang('GEN_TABLE_DNI'); ?></label>
					</div>
					<?php if (lang('SETT_ACCOUNT_STATUS_CARD') == 'ON') : ?>
						<div class="custom-control custom-radio custom-control-inline">
							<input type="radio" id="resultByCard" name="results" class="custom-control-input">
							<label class="custom-control-label mr-1" for="resultByCard"><?= lang('REPORTS_ACCOUNT_CARD'); ?></label>
						</div>
					<?php endif; ?>
					<?php if (lang('SETT_ACCOUNT_NAME') == 'ON') : ?>
						<div class="custom-control custom-radio custom-control-inline">
							<input type="radio" id="resultByName" name="results" class="custom-control-input">
							<label class="custom-control-label mr-1" for="resultByName"><?= lang('REPORTS_ACCOUNT_NAME'); ?></label>
						</div>
					<?php endif; ?>
					<div class="form-group col-5 mt-2 pl-0">
						<input id="resultByNITInput" name="radioDni" type="text" class="form-control visible" />
						<input id="resultByNameInput" name="radioName" type="text" class="form-control visible" />
						<div id="blockMessage" class="help-block"></div>
					</div>
				</div>
				<div class="flex items-center justify-end col-3">
					<button id="searchButton" type="button" class="btn btn-primary btn-small">
						Buscar
					</button>
				</div>
			</div>
		</form>
	</div>
	<div class="line mb-2"></div>
</div>
<div class="flex">
	<div id="spinnerBlock" class="mt-2 mx-auto hide">
		<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
	</div>
</div>
