<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pt-3 px-5 pb-5">
	<h1 class="primary h3 regular inline">Confirmación de Lotes</h1>
	<span class="ml-2 regular tertiary"> Prepago B-Bogotá</span>
	<div class="mb-2 flex items-center">
		<div class="flex tertiary">
			<nav class="main-nav nav-inferior">
				<ul class="mb-0 h6 light tertiary list-style-none list-inline">
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>">Empresas</a></li> /
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>">Productos</a></li> /
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>">Detalle del
							producto</a>
					</li> /
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('cargar-lotes') ?>">Cargar lotes</a></li> /
					<li class="inline"><a class="tertiary" href="javascript:">Confirmar lote</a></li>
				</ul>
			</nav>
		</div>
	</div>
	<div class="flex mt-1 mb-5 bg-color flex-nowrap justify-between">
		<div class="flex flex-auto flex-column">
			<div class="flex flex-column">
				<span class="line-text mb-2 h4 semibold primary">Confirmación</span>
				<div class="row px-5">
					<div class="form-group mb-3 col-4">
						<label for="confirmNIT" id="confirmNIT">NIT</label>
						<span id="confirmNIT" class="form-control px-1" readonly="readonly">J-00000000-9</span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="confirmName" id="confirmName">Nombre de la empresa</label>
						<span id="confirmName" class="form-control px-1" readonly="readonly">EMPRESA DE ENERGIA</span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="typeLot" id="typeLot">Tipo de lote</label>
						<span id="typeLotName" class="form-control px-1 bold pink-salmon" readonly="readonly">EMISION</span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="regNumber" id="regNumber">Cantidad de registros</label>
						<span id="amountNumber" class="form-control px-1 " readonly="readonly">1</span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="amount" id="amount">Monto total</label>
						<span id="totalAmount" class="form-control px-1" readonly="readonly">0.00</span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="lot" id="lot">Lote nro.</label>
						<span id="numLot" class="form-control px-1" readonly="readonly">19062016</span>
					</div>

					<div class="form-group mb-3 col-12">
						<label for="obsConfirm" id="obsConfirm">Observaciones</label>
						<span id="comment" class="form-control px-1" readonly="readonly">Linea: 2, El empleado ya esta asociado a
							la empresa para el producto dado (1756632855)</span>
						<span id="comment" class="form-control px-1" readonly="readonly">Linea: 4, El empleado ya esta asociado a
							la empresa para el producto dado (0400892113)</span>
					</div>
				</div>

				<div class="line mb-2"></div>

				<form method="post">
					<div class="flex flex-column mb-4 mt-4 px-5 justify-center items-center">
						<div class="mb-3 mb-3 col-4 col-lg-3">
							<input id="password" class="form-control" type="password" placeholder="Ingresa tu contraseña">
						</div>
						<div class="flex flex-row">
							<div class="mb-3 mr-4">
								<a href="#" class="btn btn-link btn-small">Cancelar</a>
							</div>
							<div class="mb-3 mr-1">
								<button class="btn btn-primary  btn-loading btn-small">Confirmar</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php if($widget): ?>
		<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
		<?php endif; ?>
	</div>
</div>
