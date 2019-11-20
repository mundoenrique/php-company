<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular">Prepago B-Bogotá</h1>
<div class="flex mb-2 items-center light">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-inline list-style-none">
				<li class="inline"><a class="tertiary" href="ceo_dashboard.html">Inicio /</a></li>
				<li class="inline"><a class="tertiary" href="ceo_dashboard.html">Empresas /</a></li>
				<li class="inline"><a class="tertiary" href="ceo_product.html">Productos</a></li>
			</ul>
		</nav>
	</div>
</div>

<div class="flex mt-1 flex-wrap justify-between">
	<div class="flex flex-auto flex-column">
		<div class="flex mb-3 mx-4 justify-center">
			<div class="product-presentation relative">
				<img class="card-image" src="<?= $this->asset->insertFile($countryUri.'/img-card_blue.svg'); ?>" alt="Card-Image" />
				<img class="item-network" src="<?= $this->asset->insertFile($countryUri.'/logo_visa_white.svg'); ?>" alt="Logo-Visa" />
			</div>
		</div>

		<div class="flex flex-column">
			<div class="flex flex-column">
				<span class="mb-1 h3 semibold primary">Producto</span>
				<span class="h5 light">Prepago B-Bogotá - Visa</span>
			</div>

			<div class="flex mt-3 flex-column items-start">
				<a class="btn btn-link btn-small-xs mx-4 px-0" href="ceo_load_lots.html">Cargar Lotes</a>
        <a class="btn btn-link btn-small-xs mx-4 px-0" href="ceo_authorization_lots.html">Lotes: 6 (3 Por firmar / 1 Por autorizar)</a>
        <a class="btn btn-link btn-small-xs mx-4 px-0" href="ceo_service_orders.html">Órdenes de servicio: 11 No conciliadas / 0 Conciliadas</a>
        <a class="btn btn-link btn-small-xs mx-4 px-0">Tarjetas: 119 Activas / 0 Inactivas</a>
			</div>

		</div>
	</div>
	<div>
		<div class="widget order-1 p-3">
			<div class="flex flex-column items-center">
				<span class="h5 semibold center primary">Empresa de Servicios Públicos / Bogotá - DC</span>
				<span class="my-2 h5 regular text">NIT: J-00000000-9</span>
				<select class="select-box custom-select mt-3 mb-4 h6">
					<option selected disabled>Seleccionar otra empresa</option>
					<option>Option 1</option>
					<option>Option 2</option>
					<option>Option 3</option>
				</select>
				<div>
					<button class="btn btn-secondary btn-small flex  mx-auto my-2">
						Seleccionar
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="flex my-5 flex-auto justify-center"></div>
