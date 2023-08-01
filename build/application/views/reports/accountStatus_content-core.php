<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_REP_ACCOUNT_STATUS'); ?></h1>
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
	<div id="pre-loader" class="mt-2 mx-auto">
		<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
	</div>
	<div class="w-100 hide-out hide">
		<div class="flex flex-auto flex-column <?= $widget ? '' : 'max-width-6'; ?>">
			<?php $data['name']='statusAccountForm' ?>
			<?php $this->load->view('reports/filterAccountStatus', $data)  ?>
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
					<div id="spinnerResults" class="mt-2 mx-auto hide">
						<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
					</div>
					<div class="line my-2"></div>
				</div>
			</div>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
	<?php endif; ?>
</div>
