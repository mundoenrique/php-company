<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pt-3 px-5 pb-5">
	<h1 class="primary h3 regular"><?= lang('PRODUCTS_LIST'); ?></h1>
	<div class="flex mb-2 items-center light">
		<div class="flex tertiary">
			<nav class="main-nav nav-inferior">
				<ul class="mb-0 h6 light tertiary list-style-none list-inline">
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>">Empresas /</a></li>
					<li class="inline"><a class="tertiary not-pointer" href="javascript:">Productos</a></li>
				</ul>
			</nav>
		</div>
	</div>

	<div class="flex mt-3 items-center">
		<div class="flex h6 flex-auto justify-end">
			<button class="btn btn-outline btn-small btn-rounded-left bg-white"><?= lang('PRODUCTS_ALL') ?></button>
			<?php if(verifyDisplay('body', $module,  lang('GEN_TAG_SEARCH_CAT'))): ?>
			<select class="select-box custom-select mr-0 h6">
				<option selected disabled><?= lang('PRODUCTS_SEARCH_CATEGORY'); ?></option>
				<?php foreach($categories AS $categorie): ?>
				<option value="<?= $categorie->idCategoria; ?>"><?= $categorie->descripcion; ?></option>
				<?php endforeach; ?>
			</select>
			<?php endif; ?>
			<select class="select-box custom-select h6">
				<option selected disabled><?= lang('PRODUCTS_SEARCH_BRAND'); ?></option>
				<?php foreach($brands AS $brand): ?>
				<option value="<?= $brand->idMarca; ?>"><?= $brand->nombre; ?></option>
				<?php endforeach; ?>
			</select>
			<div id="sb-search" class="sb-search">
				<input id="search" class="sb-search-input" type="search" name="search" value="" placeholder="Buscar...">
				<span class="sb-icon-search"><i class="icon icon-find"></i></span>
			</div>
		</div>
	</div>
	<div class="line mt-1"></div>

	<div class="flex mx-4 my-5 pt-2 flex-wrap justify-between">
		<div id="product-list" class="flex-auto">
			<?php foreach($productList AS $pos => $products): ?>
			<div class="select-product flex mb-1 pl-3 pr-4 py-1 bg-white justify-between items-center">
				<div class="flex mr-3 mx-1 items-center">
					<img src="<?= $this->asset->insertFile('programs/'.$products->programImg); ?>" alt="<?= $products->programImg; ?>">
					<img class="mx-2 img-brand-list" src="<?= $this->asset->insertFile('brands/'.$products->imgBrand); ?>" alt="<?= $products->imgBrand; ?>">
					<div class="flex flex-column">
						<span class="semibold primary"><?= $products->descripcion; ?></span>
						<span class="h6 light text">
							<?php $category = isset($products->categoria) ? ' / '.$products->categoria : ''; ?>
							<?= $products->filial.$category ?>
						</span>
					</div>
				</div>
				<div>
					<button class="product-detail btn btn-primary btn-small btn-loading flex mx-auto justify-center">
						<?= lang('GEN_BTN_SELECT') ?>
					</button>
					<form id="product-<?= $products->idProducto; ?>" action="<?= base_url('detalle-producto') ?>" method="POST">
						<input type="hidden" name="productPrefix" value="<?= $products->idProducto; ?>">
						<input type="hidden" name="productName" value="<?= $products->descripcion; ?>">
						<input type="hidden" name="productBrand" value="<?= $products->marca; ?>">
					</form>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<div id="no-product" class="flex-auto my-5 py-4 center none">
			<span class="h4">No fue posible obtener la lista de productos asociados</span>
		</div>
		<?php if($widget): ?>
		<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
		<?php endif; ?>
	</div>
</div>
