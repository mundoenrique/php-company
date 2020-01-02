<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="detail-product" class="pt-3 px-5 pb-5" prefix-prod="<?= $prefix ?>">
	<h1 class="primary h3 regular"><?= $productName; ?></h1>
	<div class="flex mb-2 items-center">
		<div class="flex tertiary">
			<nav class="main-nav nav-inferior">
				<ul class="mb-0 h6 light tertiary list-inline list-style-none">
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>">Empresas</a></li> /
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>">Productos</a></li> /
					<li class="inline"><a class="tertiary" href="#">Detalle del producto</a></li>
				</ul>
			</nav>
		</div>
	</div>

	<div class="flex mt-1 mb-5 flex-wrap justify-between">
		<div class="flex flex-auto flex-column">
			<div class="flex mb-3 mx-4 justify-center">
				<div class="product-presentation relative">
					<img class="card-image" src="<?= $this->asset->insertFile('programs/'.$productImg); ?>" alt="<?= $productName; ?>" />
					<img class="item-network" src="<?= $this->asset->insertFile('brands/'.$productImgBrand); ?>" alt=<?= $productName; ?> />
				</div>
			</div>

			<div class="flex flex-column">
				<div class="flex flex-column">
					<span class="mb-1 h3 semibold primary"><?= lang('PRODUCT') ?></span>
					<span class="light"><?= $productName ?> - <?= $productBrand ?></span>
				</div>

				<div class="flex mt-3 flex-column items-start">
					<?php if($this->verify_access->verifyAuthorization('TEBCAR')): ?>
					<a class="btn btn-link btn-small-xs mx-4 px-0 big-modal" href="<?= lang('GEN_LINK_BULK_LOAD') ?>"><?= lang('GEN_MENU_BULK_LOAD') ?></a>
					<?php endif; ?>
					<?php if($this->verify_access->verifyAuthorization('TEBAUT')): ?>
					<a class="btn btn-link btn-small-xs mx-4 px-0 big-modal" href="<?= lang('GEN_LINK_BULK_AUTH'); ?>">
						<?= novoLang(lang('PRODUCTS_LOTS_TOTAL'), [$lotsTotal, $toSign, $toAuthorize]) ?>
					</a>
					<?php endif; ?>
					<?php if($viewSomeAttr && $this->verify_access->verifyAuthorization('TEBORS')): ?>
					<a class="btn btn-link btn-small-xs mx-4 px-0" href="#">
						<?= novoLang(lang('PRODUCTS_ORDERSERV_TOTAL'), [$serviceOrders, $serviceOrdersNoCon, $serviceOrdersCon]) ?>
					</a>
					<?php endif; ?>
					<?php if($viewSomeAttr): ?>
					<a class="btn btn-link btn-small-xs mx-4 px-0" href="<?= $masterTransLink; ?>">
						<?= novoLang(lang('PRODUCTS_CARDS_TOTAL'), [$totalCards, $activeCards, $inactiveCards]) ?>
					</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php if($widget): ?>
		<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
		<?php endif; ?>
	</div>
</div>
