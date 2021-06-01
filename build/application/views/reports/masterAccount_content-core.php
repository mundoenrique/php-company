<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_REP_MASTER_ACCOUNT'); ?></h1>

<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('CONF_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE'); ?></a></li> /
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
				<span class="line-text mb-2 h4 semibold primary">Criterio de búsqueda</span>
				<div class="flex my-2 px-5">

					<form id="masterAcForm" class="w-100">
						<div class="row flex ">
							<div class="form-group col-4 col-lg-4 col-xl-3">
								<label>Empresa</label>
								<select id="enterprise-report" name="enterprise_report" class="select-box custom-select mt-1 mb-4 h6 w-100">
								<?php foreach($enterpriseList AS $enterprise) : ?>
									<?php if($enterprise->acrif == $enterpriseData->idFiscal): ?>
									<?php endif;?>
									<option code="<?= $enterprise->accodcia; ?>" group="<?= $enterprise->accodgrupoe; ?>" nomOf="<?= $enterprise->acnomcia; ?>" acrif="<?= $enterprise->acrif; ?>" value="<?= $enterprise->accodcia; ?>" <?= $enterprise->acrif == $enterpriseData->idFiscal ? 'selected' : '' ?>>
										<?= $enterprise->acnomcia; ?>
									</option>
									<?php endforeach; ?>
								</select>
								<div class="help-block"></div>
							</div>
							<div id="checked-form" class="form-group col-4 col-lg-4 col-xl-4">
								<label class="block">Procedimiento</label>
								<div class="custom-control custom-switch custom-control-inline">
									<input id="debit" class="custom-control-input" type="checkbox" name="debit">
									<label class="custom-control-label" for="debit">Cargo
									</label>
								</div>
								<div class="custom-control custom-switch custom-control-inline">
									<input id="credit" class="custom-control-input" type="checkbox" name="credit">
									<label class="custom-control-label" for="credit">Abono
									</label>
								</div>
							</div>
							<input id="tamP" name="tam-p" class="hide" value="<?= $tamP ?>">
							<div id="radio-form" class="form-group col-4 col-lg-4 col-xl-5">
								<label  class="block">Resultados</label>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="trimester" name="results" class="custom-control-input" value="all">
									<label class="custom-control-label mr-1" for="trimester">Trimestre</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="semester" name="results" class="custom-control-input" value="all">
									<label class="custom-control-label mr-1" for="semester">Semestre</label>
								</div>

								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="range" name="results" class="custom-control-input" value="all" >
									<label class="custom-control-label mr-1" for="range">Rango</label>
								</div>
								<div class="help-block"></div>
							</div>

							<div  class="form-group col-4 col-lg-3 col-xl-3">
								<label for="initialDate"><?= lang('GEN_START_DAY'); ?></label>
								<input id="initialDate" name="datepicker_start" class="form-control date-picker" type="text"
									placeholder="<?= lang('GEN_PLACE_DATE_COMPLETTE'); ?>" readonly required>
								<div class="help-block">
								</div>
							</div>
							<div class="form-group col-4 col-lg-3 col-xl-3">
								<label for="finalDate"><?= lang('GEN_END_DAY'); ?></label>
								<input id="finalDate" name="datepicker_end" class="form-control date-picker" type="text"
									placeholder="<?= lang('GEN_PLACE_DATE_COMPLETTE'); ?>" readonly required>
								<div class="help-block "></div>
							</div>

							<div class="flex items-center justify-end col-4 col-lg-4 col-xl-6 ml-auto">
								<button id="masterAc-btn" name ="masterAc_btn"class="btn btn-primary btn-small" type="button">
									Buscar
								</button>
							</div>
						</div>

					</form>
				</div>
				<div class="line mb-2"></div>
			</div>
			<div class="flex pb-5 flex-column">
				<span id="titleResults" class="line-text mb-2 h4 semibold primary">Resultados</span>
				<div id="spinnerBlock" class=" hide">
					<div id="pre-loader" class="mt-2 mx-auto flex justify-center">
						<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
					</div>
				</div>
				<div id="blockMasterAccountResults" class="center mx-1">
					<div class="flex">
						<div class="flex mr-2 py-3 flex-auto justify-end items-center ">
						<div id="files-btn" class="hide">
							<button id="export_excel" class="btn px-1 big-modal" title="Exportar a EXCEL" data-toggle="tooltip">
								<i class="icon icon-file-excel" aria-hidden="true"></i>
							</button>
							<button id="export_pdf" class="btn px-1 big-modal" title="Exportar a PDF" data-toggle="tooltip">
								<i class="icon icon-file-pdf" aria-hidden="true"></i>
							</button>
							<?php if(FALSE): ?>
							<button class="btn px-1" title="Generar gráfica" data-toggle="tooltip">
								<i class="icon icon-chart-pie" aria-hidden="true"></i>
							</button>
							<?php endif; ?>
							<button id="export_excelCons" class="btn px-1 " title="Exportar a EXCEL consolidado" data-toggle="tooltip">
								<i class="icon icon-file-excel" aria-hidden="true"></i>
							</button>
							<button id="export_pdfCons" class="btn px-1 " title="Exportar a PDF consolidado" data-toggle="tooltip">
								<i class="icon icon-file-pdf" aria-hidden="true"></i>
							</button>
						</div>
						</div>
					</div>
					<table id="concenAccount" class="cell-border h6 display responsive w-100">
						<thead class="bg-primary secondary regular">
							<tr>
								<th><?= lang('REPORTS_TABLE_DATE'); ?></th>
								<th><?= lang('GEN_DESCRIPTION'); ?></th>
								<th><?= lang('REPORTS_TABLE_REFERENCE'); ?></th>
								<th><?= lang('REPORTS_TABLE_DEBIT'); ?></th>
								<th><?= lang('REPORTS_TABLE_CREDIT'); ?></th>
								<th><?= lang('REPORTS_TABLE_BALANCE'); ?></th>
							</tr>
						</thead>
						<tbody id="tbody-datos-general" class = "tbody-reportes">
            </tbody>
					</table>
					<div class="line my-2"></div>
				</div>
				<div class="my-5 py-4 center none">
					<span class="h4">No se encontraron registros</span>
				</div>
			</div>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
	<?php endif; ?>
</div>




