<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pt-3 px-5 pb-5">
	<h1 class="primary h3 regular inline"><?= 'Calcular Orden de servicio' ?></h1>
	<span class="ml-2 regular tertiary"><?= $productName ?></span>
	<div class="flex mb-2 items-center">
		<div class="flex tertiary">
			<nav class="main-nav nav-inferior">
				<ul class="mb-0 h6 light tertiary list-style-none list-inline">
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>">Empresas</a></li> /
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>">Productos</a></li> /
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>">Detalle del producto</a></li> /
					<li class="inline"><a class="tertiary" href="<?= base_url('lotes-autorizacion') ?>">Autorizar lote</a></li> /
					<li class="inline"><a class="tertiary" href="javascript:">Calcular orden de servicio</a></li>
				</ul>
			</nav>
		</div>
	</div>

	<div class="flex mt-1 bg-color flex-nowrap justify-between">
		<div class="flex flex-auto flex-column">
			<div class="flex pb-5 flex-column">
				<?php if(count($serviceOrdersList) > 0): ?>
				<span class="line-text mb-2 h4 semibold primary">Órdenes de servicio</span>
				<div class="center mx-1">
					<table id="resultServiceOrders" class="cell-border h6 display">
						<thead class="bg-primary secondary regular">
							<tr>
								<th>Monto Comisión</th>
								<th>Monto Iva</th>
								<th>Monto OS</th>
								<th>Monto Total</th>
								<th>Monto depósito</th>
								<th>Opciones</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($serviceOrdersList AS $serviceOrders): ?>
							<?php $tempOrdersId.= $serviceOrders->tempOrderId.',' ?>
							<tr bulk="<?= htmlspecialchars(json_encode($serviceOrders->bulk), ENT_QUOTES, 'UTF-8'); ?>">
								<td><?= $serviceOrders->commisAmount; ?></td>
								<td><?= $serviceOrders->VatAmount; ?></td>
								<td><?= $serviceOrders->soAmount; ?></td>
								<td><?= $serviceOrders->totalAmount; ?></td>
								<td><?= $serviceOrders->depositedAmount; ?></td>
								<td class="flex justify-center items-center">
									<button class="btn px-0 details-control" title="Ver" data-toggle="tooltip">
										<i class="icon icon-find mr-1" aria-hidden="true"></i>
									</button>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<?php endif; ?>
				<?php if(count($bulkNotBillable) > 0): ?>
				<span class="line-text mb-2 h4 semibold primary">Lotes no facturables</span>
				<div class="center mx-1">
					<table id="resultServiceOrders" class="cell-border h6 display">
						<thead class="bg-primary secondary regular">
							<tr>
								<th>Número de lote</th>
								<th>Fecha</th>
								<th>Tipo</th>
								<th>Cantidad</th>
								<th>Estado</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($bulkNotBillable AS $NotBillable): ?>
							<?php $tempOrdersId.= $NotBillable->tempOrderId.',' ?>
							<tr>
								<td><?= $NotBillable->bulkNumber; ?></td>
								<td><?= $NotBillable->bulkLoadDate; ?></td>
								<td><?= $NotBillable->bulkLoadType; ?></td>
								<td><?= $NotBillable->bulkRecords; ?></td>
								<td><?= $NotBillable->bulkStatus; ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<?php endif; ?>
					<div class="line my-2"></div>
				</div>
				<form id="auth-bulk-form">
					<div class="flex flex-column mb-4 mt-4 px-5 justify-center items-center">
						<div class="form-group mb-3 mb-3 col-4 col-lg-3">
							<input id="temp-orders" name="temp-orders" type="hidden" value="<?= $tempOrdersId; ?>">
							<input id="bulk-no-bill" name="bulk-no-bill" type="hidden" value="<?= $bulknotBill; ?>">
							<div class="help-block"></div>
						</div>
						<div class="flex flex-row">
							<div class="mb-3 mr-4">
								<!-- <button id="cancel-bulk-btn" class="btn btn-link btn-small">Cancelar</button> -->
								<a href="<?= base_url('lotes-autorizacion') ?>" class="btn btn-link btn-small big-modal"><?= lang('GEN_BTN_CANCEL'); ?></a>
							</div>
							<div class="mb-3 mr-1">
								<button id="auth-bulk-btn" class="btn btn-primary  btn-loading btn-small">Autorizar</button>
							</div>
						</div>
					</div>
				</form>
				<div class="my-5 py-4 center none">
					<span class="h4">No tiene Órdenes de servicio</span>
				</div>
			</div>
		</div>
		<?php if($widget): ?>
		<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
		<?php endif; ?>
	</div>
</div>
