<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_REP_MASTER_ACCOUNT'); ?></h1>

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
			<?php $data['name']='extMasterAcForm' ?>
			<?php $this->load->view('reports/filterMasterAccount', $data)  ?>
			<form id="extMasterAccountFormXls" class="hide">
					<input id="idExtEmp" name="idExtEmp"  type="text" value="">
					<input id="dateStart" name="dateStart"  type="text" value="">
					<input id="dateEnd" name="dateEnd"  type="text" value="">
					<input id="typeNote" name="typeNote"  type="text" value="">
					<input id="dateFilter" name="dateFilter"  type="text" value="">
			</form>
			<div class="flex pb-5 flex-column">
				<span id="titleResults" class="line-text mb-2 h4 semibold primary">Resultados</span>
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
					<table id="extMasterAccount" class="cell-border h6 display responsive w-100">
						<thead class="bg-primary secondary regular">
							<tr>
								<th><?= lang('REPORTS_TABLE_DATE'); ?></th>
								<th><?= lang('REPORTS_TABLE_DNI'); ?></th>
								<th><?= lang('GEN_TABLE_NAME_CLIENT'); ?></th>
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
</div>




