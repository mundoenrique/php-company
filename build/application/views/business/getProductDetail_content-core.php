<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular"><?= $detailProductName; ?></h1>
<div class="flex mb-2 items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-inline list-style-none">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
				<li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_PRODUCTS_DETAIL_TITLE') ?></a></li>
			</ul>
		</nav>
	</div>
</div>

<div class="flex mt-1 mb-5 flex-wrap justify-between">
	<div class="flex flex-auto flex-column <?= $widget ? '' : 'max-width-6'; ?>">
		<div id="pre-loader" class="mx-auto flex justify-center">
			<span class="spinner-border spinner-border-lg my-2" role="status" aria-hidden="true"></span>
		</div>
		<div class="hide-out hide">
			<div class="flex mb-3 mx-4 justify-center">
				<div class="product-presentation relative">
					<img class="card-image" src="<?= $this->asset->insertFile($productImg, 'images/programs', $customerProgram); ?>" alt="<?= $productName; ?>" />
					<?php if(lang('SETT_FRANCHISE_LOGO') === 'ON'):?>
					<img class="item-network" src="<?= $this->asset->insertFile($productImgBrand, 'images/brands'); ?>" alt="<?= $productBrand; ?>" />
					<?php endif; ?>
				</div>
			</div>

			<div class="flex flex-column">
				<div class="flex flex-column">
					<span class="mb-1 h3 semibold primary"><?= lang('GEN_PRODUCT') ?></span>
					<span class="light"><?= $detailProductName; ?> - <?= $productBrand ?></span>
				</div>

				<div class="flex mt-3 flex-column items-start">
					<a class="btn btn-link btn-small-xs mx-4 px-0 big-modal <?= $loadDisabled; ?>" href="<?= $loadBulkLink; ?>">
						<?= lang('GEN_MENU_BULK_LOAD') ?>
					</a>
					<a class="btn btn-link btn-small-xs mx-4 px-0 big-modal <?= $authDisabled; ?>" href="<?= $bulkAuthLink; ?>">
						<?= novoLang(lang('BUSINESS_LOTS_TOTAL'), [$lotsTotal, $toSign, $toAuthorize]) ?>
					</a>
					<a class="btn btn-link btn-small-xs mx-4 px-0 big-modal <?= $orderDisabled; ?>" href="<?= $OrderServLink; ?>">
						<?= novoLang(lang('BUSINESS_ORDERSERV_TOTAL'), [$serviceOrders, $serviceOrdersNoCon, $serviceOrdersCon]) ?>
					</a>
					<?php if($viewSomeAttr): ?>
					<a class="btn btn-link btn-small-xs mx-4 px-0 big-modal <?= $masterTransDisabled ?>" href="<?= $masterTransLink; ?>">
						<?= novoLang(lang('BUSINESS_CARDS_TOTAL'), [$totalCards, $activeCards, $inactiveCards]) ?>
					</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
	<?php endif; ?>
</div>
