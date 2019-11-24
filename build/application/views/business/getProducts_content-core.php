<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pt-3 px-5 pb-5">
	<h1 class="primary h3 regular">Selección de producto</h1>
	<div class="flex mb-2 items-center light">
		<div class="flex tertiary">
			<nav class="main-nav nav-inferior">
				<ul class="mb-0 h6 light tertiary list-style-none list-inline">
					<li class="inline"><a class="tertiary" href="ceo_dashboard.html">Inicio /</a></li>
					<li class="inline"><a class="tertiary" href="ceo_dashboard.html">Empresas /</a></li>
					<li class="inline"><a class="tertiary" href="ceo_product.html">Productos</a></li>
				</ul>
			</nav>
		</div>
	</div>

	<div class="flex mt-3 items-center">
		<div class="flex h6 flex-auto justify-end">
			<button class="btn btn-outline btn-small btn-rounded-left bg-white">TODOS</button>
			<select class="select-box custom-select mr-0 h6">
				<option selected disabled>Buscar por categorias</option>
				<?php foreach($categories AS $categorie): ?>
				<option value="<?= $categorie->idCategoria; ?>"><?= $categorie->descripcion; ?></option>
				<?php endforeach; ?>
			</select>
			<select class="select-box custom-select h6">
				<option selected disabled>Buscar por marca</option>
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
		<div class="flex-auto">
			<?php foreach($productList AS $pos => $products): ?>
			<div class="select-product flex mb-1 pl-3 pr-4 py-1 bg-white justify-between items-center">
				<div class="flex mr-3 mx-1 items-center">
					<img src="<?= $this->asset->insertFile('programs/'.$products->programImg); ?>" alt="" />
					<img class="mx-2" src="<?= $this->asset->insertFile('brands/'.$products->imgBrand); ?>" alt="" />
					<div class="flex flex-column">
						<span class="semibold primary"><?= $products->descripcion ?></span>
						<span class="h6 light text"><?= $products->filial ?> / <?= $products->categoria ?></span>
					</div>
				</div>
				<div>
					<button class="product-detail btn btn-primary btn-small btn-loading flex mx-auto justify-center">
						Seleccionar
					</button>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<div>
			<?php if($widget): ?>
			<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
			<?php endif; ?>
		</div>
	</div>
</div>
