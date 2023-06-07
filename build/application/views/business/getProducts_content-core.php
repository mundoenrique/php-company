<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular"><?= lang('BUSINESS_PRODUCTS_LIST'); ?></h1>
<div class="flex mb-2 items-center light">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE') ?> /</a></li>
				<li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_PRODUCTS') ?></a></li>
			</ul>
		</nav>
	</div>
</div>

<div class="flex mt-3 items-center">
	<div class="flex h6 flex-auto justify-end">
		<div id="resetBtn">
			<button class="btn btn-outline btn-small btn-rounded-left bg-white" data-jplist-control="reset" data-group="group-filter-pagination" data-name="reset"><?= lang('BUSINESS_PRODUCTS_ALL') ?></button>
		</div>
		<?php if(count($categories) > 1): ?>
		<select
			class="select-box custom-select mr-0 h6"
			data-jplist-control="select-filter"
			data-group="group-filter-pagination"
			data-name="category"
		>
			<option selected disabled><?= lang('BUSINESS_SEARCH_CATEGORY'); ?></option>
			<?php foreach($categories as $categorie): ?>
			<option value="<?= $categorie->idCategoria; ?>" data-path=".filter-<?= $categorie->idCategoria; ?>"><?= $categorie->descripcion; ?></option>
			<?php endforeach; ?>
		</select>
		<?php endif; ?>
		<?php if(count($brands) > 1): ?>
		<select
			class="select-box custom-select h6"
			data-jplist-control="select-filter"
			data-group="group-filter-pagination"
			data-name="brand"
		>
			<option selected disabled data-path="default"><?= lang('BUSINESS_SEARCH_BRAND'); ?></option>
			<?php foreach($brands as $brand): ?>
			<option value="<?= $brand->idMarca; ?>" data-path=".filter-<?= $brand->nombre; ?>"><?= $brand->nombre; ?></option>
			<?php endforeach; ?>
		</select>
		<?php endif; ?>
		<div id="sb-search" class="sb-search">
			<input data-jplist-control="textbox-filter" data-group="group-filter-pagination" data-name="description" data-path=".product-description" id="search" class="sb-search-input" type="search" name="search" value="" placeholder="Buscar...">
			<span class="sb-icon-search"><i class="icon icon-find mr-1"></i></span>
		</div>
	</div>
</div>
<div class="line mt-1"></div>

<div class="flex mx-4 my-5 pt-2 flex-wrap justify-between">
	<div id="pre-loader" class="mt-2 mx-auto">
		<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
	</div>
	<div class="flex-column hide-out hide">
		<div data-jplist-group="group-filter-pagination" id="product-list" class="flex-auto">
			<?php foreach($productList AS $pos => $products): ?>
			<div data-jplist-item class="select-product flex mb-1 pl-3 pr-4 py-1 bg-white justify-between items-center">
				<div class="flex mr-3 mx-1 items-center flex-auto filter-<?= $products->marca; ?> filter-<?= $products->idCategoria; ?> ">
					<img class="img-product-list" src="<?= $this->asset->insertFile($products->productImg, 'images', $customerFiles, 'programs'); ?>" alt="<?= $products->productImg; ?>">
					<img class="mx-2 img-brand-list" src="<?= $this->asset->insertFile($products->imgBrand, 'images', $customerFiles, 'brands'); ?>" alt="<?= $products->imgBrand; ?>">
					<div class="flex flex-column flex-auto">
						<span class="product-description semibold primary"><?= $products->descripcion; ?></span>
						<span class="h6 light text truncate">
							<?php $category = isset($products->categoria) ? ' / '.$products->categoria : ''; ?>
							<?= $products->filial.$category ?>
						</span>
					</div>
				</div>
				<div>
					<button class="product-detail btn btn-primary btn-small btn-loading flex mx-auto justify-center">
						<?= lang('GEN_BTN_SELECT') ?>
					</button>
					<form id="product-<?= $products->idProducto; ?>" action="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>" method="POST">
						<input type="hidden" name="productPrefix" value="<?= $products->idProducto; ?>">
						<input type="hidden" name="productImg" value="<?= $products->productImg; ?>">
						<input type="hidden" name="productName" value="<?= $products->descripcion; ?>">
						<input type="hidden" name="productBrand" value="<?= $products->marca; ?>">
					</form>
				</div>
			</div>
			<?php endforeach; ?>
			<!-- no results control -->
			<div class="flex-auto my-5 py-4 center" style="display: none" data-jplist-control="no-results" data-group="group-filter-pagination" data-name="no-results">
				<span class="h4"><?= lang('GEN_TABLE_SZERORECORDS') ?></span>
			</div>
		</div>

		<!-- pagination control -->
		<div id="pagination-control" class="pagination page-number mb-5 py-5 flex-auto justify-center hide" data-jplist-control="pagination" data-group="group-filter-pagination" data-items-per-page="5" data-current-page="0" data-disabled-class="disabled" data-selected-class="page-current" data-name="pagination">
			<nav class="h4">
				<a href="#" data-type="first"><?= lang('GEN_TABLE_SFIRST') ?></a>
				<a href="#" data-type="prev">«</a>
			</nav>
			<div class="h4 flex justify-center" data-type="pages">
				<span class="mx-1" data-type="page"><a href="#">{pageNumber}</a></span>
			</div>
			<nav class="h4">
				<a href="#" data-type="next">»</a>
				<a href="#" data-type="last"><?= lang('GEN_TABLE_SLAST') ?></a>
			</nav>
		</div>
	</div>
	<div id="no-product" class="flex-auto my-5 py-4 center none">
		<span class="h4"><?= lang('GEN_WARNING_PRODUCTS_LIST') ?></span>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
	<?php endif; ?>
</div>
