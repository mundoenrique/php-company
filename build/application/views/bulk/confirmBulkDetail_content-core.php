<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline">Detalle del lote</h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>">Empresas</a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>">Productos</a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>">Detalle del producto</a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('lotes-autorizacion') ?>">Autorizar lote</a></li> /
				<li class="inline"><a class="tertiary not-pointer" href="javascript:">Detalle del lote</a></li>
			</ul>
		</nav>
	</div>
</div>
<div class="flex mt-1 mb-5 bg-color flex-nowrap justify-between">
	<div class="flex flex-auto flex-column <?= $widget ? '' : 'max-width-6'; ?>">
		<div class="flex flex-column">
			<span class="line-text mb-2 h4 semibold primary">Detalles</span>
			<div class="row mb-2 px-5">
				<div class="form-group mb-3 col-4">
					<label for="confirmNIT" id="confirmNIT">NIT</label>
					<span id="confirmNIT" class="form-control px-1" readonly="readonly"><?= $fiscalId; ?></span>
				</div>

				<div class="form-group mb-3 col-4">
					<label for="confirmName" id="confirmName">Nombre de la empresa</label>
					<span id="confirmName" class="form-control px-1" readonly="readonly"><?= $enterpriseName; ?></span>
				</div>

				<div class="form-group mb-3 col-4">
					<label for="typeLot" id="typeLot">Tipo de lote</label>
					<span id="typeLotName" class="form-control px-1 bold pink-salmon" readonly="readonly"><?= $bulkTypeText; ?></span>
				</div>

				<div class="form-group mb-3 col-4">
					<label for="lot" id="lot">Lote nro.</label>
					<span id="numLot" class="form-control px-1" readonly="readonly"><?= $bulkNumber; ?></span>
				</div>

				<div class="form-group mb-3 col-4">
					<label for="regNumber" id="regNumber">Cantidad de registros</label>
					<span id="amountNumber" class="form-control px-1 " readonly="readonly"><?= $totalRecords; ?></span>
				</div>

				<div class="form-group mb-3 col-4">
					<label for="regNumber" id="regNumber">Usuario carga</label>
					<span id="amountNumber" class="form-control px-1 " readonly="readonly"><?= $loadUserName; ?></span>
				</div>

				<div class="form-group mb-3 col-4">
					<label for="regNumber" id="regNumber">Fecha de carga</label>
					<span id="amountNumber" class="form-control px-1 " readonly="readonly"><?= $bulkDate; ?></span>
				</div>

				<div class="form-group mb-3 col-4">
					<label for="regNumber" id="regNumber">Estado</label>
					<span id="amountNumber" class="form-control px-1 " readonly="readonly"><?= $bulkStatusText; ?></span>
				</div>

				<div class="form-group mb-3 col-4">
					<label for="amount" id="amount">Monto total</label>
					<span id="totalAmount" class="form-control px-1" readonly="readonly"><?= $bulkAmount; ?></span>
				</div>
			</div>
		</div>
		<?php if(count($bulkRecords) > 0): ?>
		<div class="flex pb-5 flex-column">
			<span class="line-text mb-2 h4 semibold primary">Registros del lote</span>
			<div class="center mx-1">
				<div class="flex justify-end items-center">
					<div class="mr-3 py-1">
						<button class="btn px-1" title="Exportar a EXCEL" data-toggle="tooltip">
							<i class="icon icon-file-excel" aria-hidden="true"></i>
						</button>
						<button class="btn px-1" title="Exportar a PDF" data-toggle="tooltip">
							<i class="icon icon-file-pdf" aria-hidden="true"></i>
						</button>
					</div>
				</div>
				<table id="authLotDetail" class="cell-border h6 display responsive w-100">
					<thead class="bg-primary secondary regular">
						<tr>
							<th>Cédula de identidad</th>
							<th>Cuenta</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1725234379</td>
							<td>*************5621</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="my-5 py-4 center none">
				<span class="h4">No se encontraron registros</span>
			</div>
		</div>
		<?php endif; ?>
		<div class="line mb-2"></div>

		<div class="flex flex-column mb-4 mt-4 px-5 justify-center items-center">
			<div class="flex flex-row">
				<div class="mb-3 mr-4">
					<a href="<?= base_url('lotes-autorizacion') ?>" class="btn btn-link btn-small big-modal">Volver</a>
				</div>
			</div>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
	<?php endif; ?>
</div>
