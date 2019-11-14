<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<span class="primary">Selección de producto</span>
<div class="flex mb-2 items-center light">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary" href="#">Inicio /</a></li>
				<li class="inline"><a class="tertiary" href="#">Empresas /</a></li>
				<li class="inline"><a class="tertiary" href="#">Productos</a></li>
			</ul>
		</nav>
	</div>
</div>

<div class="flex mt-3 items-center">
	<div class="flex h6 flex-auto justify-end">
		<button class="btn btn-outline btn-small btn-rounded-left bg-white">TODOS</button>

		<div class="regular">
			<select class="custom-select bg-secondary" placeholder="Buscar por categorias">
				<option value="Option_1">Option 1</option>
				<option value="Option_2">Option 2</option>
				<option value="Option_3">Option 3</option>
			</select>
		</div>
		<div class="regular">
			<select class="custom-select bg-secondary" placeholder="Buscar por marca">
				<option value="Option_1">Option 1</option>
				<option value="Option_2">Option 2</option>
				<option value="Option_3">Option 3</option>
			</select>
		<div id="sb-search" class="sb-search">
			<input id="search" class="sb-search-input" type="search" name="search" value="" placeholder="Buscar...">
      <span class="sb-icon-search"><i class="icon icon-find"></i></span>
		</div>
		<button class="btn-search bg-white"></button>
	</div>
</div>
<div class="line mt-1"></div>

<div class="flex mt-4 mx-4 flex-wrap justify-between">
	<div>
		<div class="select-product flex mb-1 pl-3 pr-4 py-1 bg-white justify-between items-center flex-wrap">
			<div class="flex mr-3 mx-1 items-center">
				<img src="<?= $this->asset->insertFile($countryUri.'/img-card_blue.svg'); ?>" alt="" />
				<img class="mx-2" src="<?= $this->asset->insertFile($countryUri.'/logo_visa.svg'); ?>" alt="" />
				<div class="flex flex-column">
					<span class="h5 semibold primary">PREPAGO B-BOGOTÁ</span>
					<span class="h6 light text">BANCO BOGOTÁ / Recursos Humano</span>
				</div>
			</div>
			<div>
				<a class="btn btn-primary btn-small flex mx-auto" href="ceo_product_lots.html">
					Seleccionar
				</a>
			</div>
		</div>
		<div class="select-product flex mb-1 pl-3 pr-4 py-1 bg-white justify-between items-center flex-wrap">
			<div class="flex mr-3 mx-1 items-center">
				<img src="<?= $this->asset->insertFile($countryUri.'/img-card_gray.svg'); ?>" alt="" />
				<img class="mx-2" src="<?= $this->asset->insertFile($countryUri.'/logo_visa.svg'); ?>" alt="" />
				<div class="flex flex-column">
					<span class="h5 semibold primary">PREPAGO B-BOGOTÁ</span>
					<span class="h6 light text">BANCO BOGOTÁ / Recursos Humano</span>
				</div>
			</div>
			<div>
				<button class="btn btn-primary btn-small flex mx-auto">
					Seleccionar
				</button>
			</div>
		</div>
	</div>
	<div>
	<?php if($widget): ?>
		<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
	<?php endif; ?>
	</div>
</div>
<div>
<div class="flex my-5 flex-auto justify-center"></div>
