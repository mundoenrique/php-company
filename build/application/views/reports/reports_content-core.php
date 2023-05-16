<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_REPORTS'); ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE'); ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS'); ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE'); ?></a></li> /
				<li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_MENU_REPORTS'); ?></a></li>
			</ul>
		</nav>
	</div>
</div>
<div class="flex mt-1 bg-color flex-nowrap justify-between">
	<div class="flex flex-auto flex-column">
		<div class="search-criteria-order flex pb-3 flex-column w-100">
			<span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SELECTION_REPORTS'); ?></span>
			<div class="flex my-2 px-5">
				<form method="post" class="w-100" onsubmit="return false">
					<div class="row flex items-center justify-between">
						<div class="form-group col-6 col-xl-6">
							<label>Tipo de reporte</label>
							<select id="reports" name="reports" class="select-box custom-select flex h6 w-100">
								<?php foreach($reportsList AS $pos => $value): ?>
								<option value="<?= $value->key; ?>" <?= $pos != 0 ? '' : 'selected disabled' ?> type="<?= !isset($value->type) ?: $value->type; ?>">
									<?= $value->text; ?>
								</option>
								<?php endforeach; ?>
							</select>
							<div class="help-block"></div>
						</div>
						<div id="div-download" class="none">
							<div class="flex items-start justify-end">
								<button id="btn-download" class="flex items-baseline btn btn-link btn-small big-modal">
									<i aria-hidden="true" class="icon icon-download"></i>
									&nbsp;<?= lang('GEN_BTN_DOWNLOAD'); ?>
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="line mb-2"></div>
		</div>

		<div class="flex pb-5 flex-column">
			<span id="search-criteria" class="none line-text mb-2 h4 semibold primary"><?= lang('GEN_SEARCH_CRITERIA') ?></span>
			<div class="flex my-2 px-5">
				<form id="form-report" method="post" class="reports-form w-100 none" onsubmit="return false">
					<div id="repMovimientoPorEmpresa" class="row">
						<div class="form-group col-4">
							<label for="enterpriseDateBegin"><?= lang('GEN_START_DAY'); ?></label>
							<input id="enterpriseDateBegin" class="form-control date-picker" name="datepicker_start" type="text" readonly placeholder="DD/MM/AAAA"
								disabled>
							<div class="help-block"></div>
						</div>
						<div class="form-group col-4">
							<label for="enterpriseDateEnd"><?= lang('GEN_END_DAY'); ?></label>
							<input id="enterpriseDateEnd" class="form-control date-picker" name="datepicker_end" type="text" readonly placeholder="DD/MM/AAAA"
								disabled>
							<div class="help-block"></div>
						</div>
						<div class="flex items-center justify-end col-4">
							<button class="btn-report btn btn-primary btn-small btn-loading">
								<?= lang('GEN_BTN_SEARCH'); ?>
							</button>
						</div>
					</div>

					<div id="repMovimientoPorTarjeta">
						<div id="MovimientoPorTarjeta">
							<div class="row mb-3">
								<div class="form-group col-12">
									<div class="custom-control custom-radio custom-control-inline align-top">
										<input type="radio" id="resultIdNumber" name="results" class="custom-control-input" value="byIdNumber">
										<label class="custom-control-label mr-1" for="resultIdNumber">Por número de identificación</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="resultByCard" name="results" class="custom-control-input" value="ByCard">
										<label class="custom-control-label mr-1" for="resultByCard">Por tarjeta</label>
									</div>
								</div>
							</div>
							<div id="sectionByIdNumber" class="row none">
								<div class="form-group col-3">
									<label><?= lang('REPORTS_ID_TYPE'); ?></label>
									<select id="idType" name="id-type" class="select-box custom-select flex h6 w-100 form-control" disabled>
										<?php foreach($IdTypeList AS $pos => $value): ?>
										<option value="<?= $value->key; ?>" <?= $pos != 0 ? '' : 'selected disabled' ?>>
											<?= $value->text; ?>
										</option>
										<?php endforeach; ?>
									</select>
									<div class="help-block"></div>
								</div>
								<div class="form-group col-3">
									<label for="idNumber"><?= lang('REPORTS_ID_NUMBER') ?></label>
									<input id="idNumber" name="id-number" class="form-control read-only" type="text" autocomplete="off" disabled>
									<div class="help-block"></div>
								</div>
								<div class="flex items-center justify-end col-6">
									<button id="repTarjetasPorPersona" class="btn-report btn btn-primary btn-small btn-loading" cards="repTarjetasPorPersona">
										<?= lang('GEN_BTN_SEARCH'); ?>
									</button>
								</div>
							</div>
							<div id="sectionByCard" class="row none">
								<div class="form-group col-3">
									<label for="cardNumber2"><?= lang('REPORTS_CARD_NUMBER') ?></label>
									<input id="cardNumber2" name="card-number" class="form-control" type="text" disabled>
									<div class="help-block"></div>
								</div>

								<div class="form-group col-3">
									<label for="cardDateBegin"><?= lang('GEN_START_DAY'); ?></label>
									<input id="cardDateBegin" class="form-control date-picker-card" name="datepicker_start" type="text" readonly
										placeholder="<?= lang('GEN_PLACE_DATE_COMPLETTE'); ?>" disabled>
									<div class="help-block"></div>
								</div>

								<div class="form-group col-3">
									<label for="cardDateEnd"><?= lang('GEN_END_DAY'); ?></label>
									<input id="cardDateEnd" class="form-control date-picker-card" name="datepicker_end" type="text" readonly
										placeholder="<?= lang('GEN_PLACE_DATE_COMPLETTE'); ?>" disabled>
									<div class="help-block"></div>
								</div>

								<div class="flex items-center justify-end col-3">
									<button class="btn-report btn btn-primary btn-small btn-loading">
										<?= lang('GEN_BTN_SEARCH'); ?>
									</button>
								</div>
							</div>
						</div>

						<div id="result-repMovimientoPorTarjeta" class="row none">
							<div class="form-group col-3">
								<label><?= lang('REPORTS_CARD_NUMBER'); ?></label>
								<select id="cardNumberId" name="card-number-sel" class="select-box custom-select flex h6 w-100 form-control" disabled>
								</select>
								<div class="help-block"></div>
							</div>

							<div class="form-group col-3">
								<label for="peopleDateBegin"><?= lang('GEN_START_DAY'); ?></label>
								<input id="peopleDateBegin" class="form-control date-picker-card" name="datepicker_start" type="text" readonly
									placeholder="<?= lang('GEN_PLACE_DATE_COMPLETTE'); ?>" disabled>
								<div class="help-block"></div>
							</div>

							<div class="form-group col-3">
								<label for="peopleDateEnd"><?= lang('GEN_END_DAY'); ?></label>
								<input id="peopleDateEnd" class="form-control date-picker-card" name="datepicker_end" type="text" readonly
									placeholder="<?= lang('GEN_PLACE_DATE_COMPLETTE'); ?>" disabled>
								<div class="help-block"></div>
							</div>

							<div class="flex items-center justify-end col-3">
								<button class="btn-report btn btn-primary btn-small btn-loading">
									<?= lang('GEN_BTN_SEARCH'); ?>
								</button>
							</div>
						</div>
					</div>


					<div id="repTarjeta" class="no-select .repTarjeta">
						<div class="row">
							<div class="form-group col-6 col-lg-4">
								<label for="cardNumber"><?= lang('REPORTS_CARD_NUMBER') ?></label>
								<input id="cardNumber" name="card-number" class="form-control" type="text" disabled>
								<div class="help-block"></div>
							</div>

							<div class="flex items-center justify-end col-8">
								<button class="btn-report btn btn-primary btn-small btn-loading">
									<?= lang('GEN_BTN_SEARCH'); ?>
								</button>
							</div>
						</div>

						<div id="repTarjeta-result" class="none">
							<div class="flex pb-5 flex-column">
								<span class="line-text mb-2 h4 semibold primary"></span>
								<div class="center mx-1">
									<table id="reports-results" class="cell-border h6 display responsive w-100">
										<thead class="bg-primary secondary regular">
											<tr>
												<?php foreach($headerCardsRep AS $header): ?>
												<th><?= $header; ?></th>
												<?php endforeach; ?>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

					<div id="repComprobantesVisaVale" class="row .repComprobantes">
						<div class="form-group col-6 col-lg-4">
							<label for="datepicker"><?= lang('GEN_TABLE_DATE') ?></label>
							<input id="date" class="form-control month-year" name="selected-date" type="text" readonly
								placeholder="<?= lang('GEN_PLACE_DATE_MEDIUM') ?>" disabled>
							<div class="help-block"></div>
						</div>

						<div class="flex items-center justify-end col-6 col-lg-8">
							<button class="btn-report btn btn-primary btn-small btn-loading">
								<?= lang('GEN_BTN_SEARCH'); ?>
							</button>
						</div>
					</div>

					<div id="repExtractoCliente" class="row">
						<div class="form-group col-6 col-lg-4">
							<label for="datepicker"><?= lang('GEN_TABLE_DATE') ?></label>
							<input id="dateEx" class="form-control month-year" name="selected-date" type="text" readonly
								placeholder="<?= lang('GEN_PLACE_DATE_MEDIUM') ?>" disabled>
							<div class="help-block"></div>
						</div>

						<div class="flex items-center justify-end col-6 col-lg-8">
							<button class="btn-report btn btn-primary btn-small btn-loading">
								<?= lang('GEN_BTN_SEARCH'); ?>
							</button>
						</div>
					</div>

					<div id="repCertificadoGmf" class="row" firts-year="<?= $mindateGmfReport; ?>">

						<div class="form-group col-3 col-lg-4">
							<label for="datepicker"><?= lang('GEN_TAXABLE_YEAR') ?></label>
							<input id="dateG" class="form-control year" name="selected-year" type="text" readonly
								placeholder="<?= lang('GEN_PLACE_DATE_SHORT') ?>" disabled>
							<div class="help-block"></div>
						</div>

						<div class="flex items-center justify-end col-8">
							<button class="btn-report btn btn-primary btn-small btn-loading">
								<?= lang('GEN_BTN_SEARCH'); ?>
							</button>
						</div>
					</div>
				</form>
			</div>
			<div id="line-reports" class="line mb-2 none"></div>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
	<?php endif; ?>
</div>
