<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_REP_CLOSING_BAKANCE'); ?></h1>

<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>"><?= lang('GEN_MENU_ENTERPRISE'); ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>"><?= lang('GEN_PRODUCTS'); ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>"><?= lang('PRODUCTS_DETAIL_TITLE'); ?></a></li> /
				<li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_MENU_REPORTS'); ?></a></li>
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
			<div class="search-criteria-order flex pb-3 flex-column w-100">
				<span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SEARCH_CRITERIA') ?></span>
				<div class="flex my-2 px-5">
					<form id="closingBudgetForm" class="w-100">
						<div class="row flex flex items-center justify-end col-sm-12">
							<div class="form-group <?= lang('CONF_SETT_STYLE_SKIN') ?>">
								<label><?= lang('GEN_ENTERPRISE') ?></label>
								<select id="enterpriseReport" name="enterpriseReport" class="select-box custom-select mt-1 mb-1 h6 w-100">
								<?php foreach($enterpriseList AS $enterprise) : ?>
									<?php if($enterprise->acrif == $enterpriseData->idFiscal): ?>
									<?php endif;?>
									<option code="<?= $enterprise->accodcia; ?>" group="<?= $enterprise->accodgrupoe; ?>" nomOf="<?= $enterprise->acnomcia; ?>" acrif="<?= $enterprise->acrif; ?>" value="<?= $enterprise->accodcia; ?>" <?= $enterprise->acrif == $enterpriseData->idFiscal ? 'selected' : '' ?>>
										<?= $enterprise->acnomcia; ?>
									</option>
									<?php endforeach; ?>
								</select>
								<div class="help-block"></div>
								<input id="tamP" name="tam-p" class="hide" value="<?= $tamP ?>">
							</div>
							<div  class="form-group <?= lang('CONF_SETT_STYLE_SKIN') ?>">
								<label><?= lang('GEN_PRODUCT') ?></label>
								<select id="productCode" name = "productCode" class="select-box custom-select flex h6 w-100">
									<?php if($productsSelect): ?>
									<?php foreach($productsSelect AS $product): ?>
									<option  value="<?= $product['id']; ?>" <?= $product['id'] == $currentProd ? 'selected' : ''; ?>><?= $product['desc'] ?></option>
									<?php endforeach; ?>
									<?php endif; ?>
								</select>
										<input id="errProd" name="err-prod" class="hide" value="<?= $prod ?>">
								<div class="help-block"></div>
							</div>

							<?php if (lang('CONF_NIT_INPUT_BOOL') == 'ON' ): ?>
							<div class="form-group <?= lang('CONF_SETT_STYLE_SKIN') ?>">
								<label ><?= lang('REPORTS_ID_FISCAL') ?></label>
								<input id="Nit" class="form-control h5" name="nit" placeholder="<?= lang('REPORTS_ID_FISCAL_INPUT') ?>">
								<div class="help-block"></div>
							</div>
							<?php endif; ?>

							<div class="flex items-center justify-end col-auto ml-auto">
								<button type="button" id="closingBudgetsBtn" class="btn btn-primary btn-small">
									Buscar
								</button>
							</div>
						</div>
					</form>
				</div>
				<div class="line mb-2"></div>
			</div>

			<div id="blockResult"  class="flex pb-5 flex-column ">
				<span id="titleResults" class="line-text mb-2 h4 semibold primary">Resultados</span>
				<div id="spinnerBlockBudget" class=" hide">
									<div id="pre-loader" class="mt-2 mx-auto flex justify-center">
										<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
									</div>
						</div>
				<div id="blockBudgetResults" class="center mx-1 ">
					<div id="block-btn-excel" class="flex mr-2 py-3 justify-end items-center hide">
					<div class="cover-spin" ></div>
						<button id="export_excel" class="btn px-1 big-modal" title="Exportar a EXCEL" data-toggle="tooltip">
							<i class="icon icon-file-excel" aria-hidden="true"></i>
						</button>
					</div>

					<table id="balancesClosing" class="cell-border h6 display responsive w-100">
					<thead class="bg-primary secondary regular">
                    <tr  id="datos-principales" >
								<th><?= lang('GEN_ACCOUNT_CLOSING_BALANCE') ?></th>
								<th><?= lang('GEN_FISCAL_CLOSING_BALANCE') ?></th>
								<th>Tarjeta</th>
								<th>Saldo inicial</th>
								<?php if (lang('CONF_CLOSING_BALANCE_BOOL') == 'ON' ): ?>
								<th>Última actividad</th>
								<?php endif; ?>
							</tr>
												</thead>

                        <tbody id="tbody-datos-general" class = "tbody-reportes">
                        </tbody>
										</table>
										<div id="hid" class=" hide">
									<div id="pre-loader" class="mt-2 mx-auto flex justify-center">
										<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
									</div>
								</div>
					<div class="line my-2"></div>
				</div>
				<div class="my-5 py-4 center none">
					<span class="h4">No se encontraron registros</span>
				</div>
			</div>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
	<?php endif; ?>
</div>
