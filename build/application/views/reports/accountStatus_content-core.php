<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_REP_ACCAOUNT_STATUS'); ?></h1>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('CONF_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE'); ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('CONF_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS'); ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('CONF_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE'); ?></a></li> /
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
				<span class="line-text mb-2 h4 semibold primary">Criterio de búsqueda</span>
				<div class="flex my-2 px-5">
					<form id="statusAccountForm" class="w-100">
						<div class="row flex justify-between">
							<div class="form-group col-4 col-xl-4">
							<label><?= lang('GEN_ENTERPRISE'); ?></label>
								<select id="enterpriseCode" name="enterpriseCode" class="select-box custom-select flex h6 w-100 enterprise-getprod">
									<?php foreach($enterpriseList AS $enterprise) : ?>
									<?php if($enterprise->acrif == $enterpriseData->idFiscal): ?>
									<?php endif;?>
									<option doc="<?= $enterprise->accodcia; ?>" name = "<?= $enterprise->acrazonsocial; ?>" value="<?= $enterprise->acrif; ?>" <?= $enterprise->acrif == $enterpriseData->idFiscal ? 'selected' : '' ?>
										id-fiscal="<?= $enterprise->acrif; ?>">
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
									<?php if($productsSelect): ?>
									<?php foreach($productsSelect AS $product): ?>
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
									<div class="form-group">
										<input id="resultByNITInput" name="radioDni" type="text" class="form-control visible" />
										<div id="blockMessage" class="help-block"></div>
									</div>
								</div>
								<?php if (lang('CONF_ACCOUNT_STATUS_CARD') == 'ON'): ?>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="resultByCard" name="results" class="custom-control-input">
									<label class="custom-control-label mr-1" for="resultByCard">Tarjeta</label>
									<div class="form-group">
										<input id="resultByCardInput" name="radioCard" type="text" class="form-control visible" />
										<div id="blockMessage2" class="help-block"></div>
									</div>
								</div>
								<?php endif; ?>
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
			<div id="spinnerBlock" class="hide">
				<div id="pre-loader" class="mt-2 mx-auto flex justify-center">
					<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
				</div>
			</div>
			<div id="blockResults" class="flex pb-5 flex-column">
				<span class="line-text mb-2 h4 semibold primary">Resultados</span>
				<div class="center mx-1">
					<div class="flex mr-2 py-3 justify-end items-center">
						<button id="export_excel" class="big-modal btn px-1" title="Exportar a EXCEL" data-toggle="tooltip">
							<i class=" icon icon-file-excel" aria-hidden="true"></i>
						</button>
						<?php if(FALSE): ?>
						<button id="export_pdf" class="big-modal btn px-1" title="Exportar a PDF" data-toggle="tooltip">
							<i class="icon icon-file-pdf" aria-hidden="true"></i>
						</button>
						<button class="btn px-1" title="Generar gráfica" data-toggle="tooltip">
							<i class="icon icon-chart-pie" aria-hidden="true"></i>
						</button>

						<button class="btn px-1" title="Generar Comprobante Masivo" data-toggle="tooltip">
							<i class="icon icon-file-blank" aria-hidden="true"></i>
						</button>
						<?php endif; ?>
						</div>
					<div id="account-status-table"></div>
					<div class="line my-2"></div>
				</div>
			</div>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
	<?php endif; ?>
</div>
