<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('BULK_SEE'); ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_BULK_LOAD')) ?>"><?= lang('GEN_MENU_BULK_LOAD') ?></a></li> /
				<li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_SHOW_BULK') ?></a></li>
			</ul>
		</nav>
	</div>
</div>
<div class="flex mt-1 mb-5 bg-color flex-nowrap justify-between">
	<div class="flex flex-auto flex-column <?= $widget ? '' : 'max-width-6'; ?>">
		<div class="flex flex-column">
			<span class="line-text mb-2 h4 semibold primary"><?= lang('BULK_DETAIL'); ?></span>
			<div class="row px-5">
				<div class="form-group mb-3 col-4">
					<label for="confirmNIT" id="confirmNIT"><?= lang('GEN_FISCAL_REGISTRY') ?></label>
					<span id="confirmNIT" class="form-control px-1" readonly="readonly"><?= $detailBulk->idFiscal ?></span>
				</div>

				<div class="form-group mb-3 col-4">
					<label for="confirmName" id="confirmName"><?= lang('BULK_ENTERPRISE_NAME'); ?></label>
					<span id="confirmName" class="form-control px-1" readonly="readonly"><?= $detailBulk->enterpriseName ?></span>
				</div>

				<div class="form-group mb-3 col-auto">
					<label for="obsConfirm" id="obsConfirm"><?= lang('BULK_OBSERVATIONS'); ?></label>
					<?php foreach($detailBulk->errors AS $pos => $error): ?>
					<span id="comment" class="form-control px-1" readonly="readonly">
						<?= $error->line; ?>, <?= $error->msg; ?> <?= $error->detail; ?>
					</span>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="line mb-2"></div>
			<form method="post">
				<div class="flex flex-column mb-4 mt-4 px-5 justify-center items-center">
					<div class="flex flex-row">
						<div class="mb-3 mr-4">
							<a href="<?= base_url(lang('SETT_LINK_BULK_LOAD')) ?>" class="btn btn-link btn-small big-modal"><?= lang('GEN_BTN_BACK') ?></a>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
	<?php endif; ?>
</div>
