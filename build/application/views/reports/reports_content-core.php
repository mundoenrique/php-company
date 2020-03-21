<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline">Reportes</h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>">Empresas</a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>">Productos</a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>">Detalle del producto</a></li> /
				<li class="inline"><a class="tertiary not-pointer" href="javascript:">Reportes</a></li>
			</ul>
		</nav>
	</div>
</div>
<div class="flex mt-1 bg-color flex-nowrap justify-between">
	<div class="flex flex-auto flex-column">
		<div class="search-criteria-order flex pb-3 flex-column w-100">
			<span class="line-text mb-2 h4 semibold primary">Selección de reporte</span>
			<div class="flex my-2 px-5">
				<form method="post" class="w-100">
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
						<div id="div-download" class="no-select none">
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
			<span id="search-criteria" class="no-select none line-text mb-2 h4 semibold primary"><?= lang('GEN_SEARCH_CRITERIA') ?></span>
			<div class="flex my-2 px-5">
				<form id="form-report" method="post" class="no-select reports-form w-100 none">
					<div id="repMovimientoPorEmpresa" class="no-select row">
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
						<div id="MovimientoPorTarjeta" class="no-select row">
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
								<button class="btn-report btn btn-primary btn-small btn-loading" cards="repTarjetasPorPersona">
									<?= lang('GEN_BTN_SEARCH'); ?>
								</button>
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
								<input id="peopleDateBegin" class="form-control date-picker" name="datepicker_start" type="text" readonly
									placeholder="<?= lang('GEN_PLACE_DATE_COMPLETTE'); ?>" disabled>
								<div class="help-block"></div>
							</div>

							<div class="form-group col-3">
								<label for="peopleDateEnd"><?= lang('GEN_END_DAY'); ?></label>
								<input id="peopleDateEnd" class="form-control date-picker" name="datepicker_end" type="text" readonly
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


					<div id="repTarjeta" class="no-select">
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

					<div id="repComprobantesVisaVale" class="no-select row">
						<div class="form-group col-6 col-lg-4">
							<label for="datepicker">Fecha</label>
							<input id="date" class="form-control month-year" name="selected-date" type="text" readonly
								placeholder="<?= lang('GEN_PLACE_DATE_PARTIAL') ?>" disabled>
							<div class="help-block"></div>
						</div>

						<div class="flex items-center justify-end col-6 col-lg-8">
							<button class="btn-report btn btn-primary btn-small btn-loading">
								<?= lang('GEN_BTN_SEARCH'); ?>
							</button>
						</div>
					</div>

					<div id="repExtractoCliente" class="no-select row">
						<div class="form-group col-6 col-lg-4">
							<label for="datepicker">Fecha</label>
							<input id="dateEx" class="form-control month-year" name="selected-date" type="text" readonly
								placeholder="<?= lang('GEN_PLACE_DATE_PARTIAL') ?>" disabled>
							<div class="help-block"></div>
						</div>

						<div class="flex items-center justify-end col-6 col-lg-8">
							<button class="btn-report btn btn-primary btn-small btn-loading">
								<?= lang('GEN_BTN_SEARCH'); ?>
							</button>
						</div>
					</div>
				</form>
			</div>

			<div id="line-reports" class="no-select line mb-2 none"></div>

		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
	<?php endif; ?>
</div>
