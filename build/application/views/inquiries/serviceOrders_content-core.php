<?php defined('BASEPATH') OR exit('No direct script access alloewd'); ?>
<div class="pt-3 px-5 pb-5">
	<h1 class="primary h3 regular inline"><?= lang('GEN_SERVICE_ORDERS_TITLE'); ?></h1>
	<span class="ml-2 regular tertiary"><?= $productName ?></span>
	<div class="flex mb-2 items-center">
		<div class="flex tertiary">
			<nav class="main-nav nav-inferior">
				<ul class="mb-0 h6 light tertiary list-style-none list-inline">
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>">Empresas</a></li> /
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>">Productos</a></li> /
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>">Detalle del producto</a></li> /
					<li class="inline"><a class="tertiary not-pointer" href="javascript:">Ordenes de servicio</a></li>
				</ul>
			</nav>
		</div>
	</div>

	<div class="flex mt-1 bg-color flex-nowrap justify-between">
		<div class="flex flex-auto flex-column <?= $widget ? '' : 'max-width-6';  ?>">
			<div class="search-criteria-order flex pb-3 flex-column w-100">
				<span class="line-text mb-2 h4 semibold primary">Criterio de búsqueda</span>
				<div class="flex my-2 px-5">
					<form id="service-orders-form" method="post" class="w-100">
						<div class="row">
							<div class="form-group mr-auto col-3 col-lg-auto col-xl-auto">
								<div class="custom-option-c custom-radio custom-control-inline">
									<input type="radio" id="five-days" name="days" class="custom-option-input" value="5">
									<label class="custom-option-label nowrap" for="five-days">5 días</label>
								</div>
								<div class="custom-option-c custom-radio custom-control-inline">
									<input type="radio" id="ten-days" name="days" class="custom-option-input" value="10">
									<label class="custom-option-label nowrap" for="ten-days">10 días</label>
								</div>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-4 col-lg-3 col-xl-auto">
								<label for="datepicker_start">Fecha inicial</label>
								<input id="datepicker_start" name="datepicker_start" class="form-control" name="datepicker" type="text" placeholder="DD/MM/AAA">
								<div class="help-block"></div>
							</div>
							<div class="form-group col-4 col-lg-3 col-xl-auto">
								<label for="datepicker_end">Fecha final</label>
								<input id="datepicker_end" name="datepicker_end" class="form-control" name="datepicker" type="text" placeholder="DD/MM/AAA">
								<div class="help-block "></div>
							</div>
							<div class="form-group col-4 col-lg-2 col-xl-3">
								<label>Estado</label>
								<select id="status-order" name="status-order" class="select-box custom-select flex h6 w-100 form-control">
									<?php foreach($orderStatus AS $pos => $value): ?>
									<option value="<?= $value->key; ?>" <?= $pos != 0 ? '' : 'selected disabled' ?>>
										<?= $value->text; ?>
									</option>
									<?php endforeach; ?>
								</select>
								<div class="help-block"></div>
							</div>
							<div class="col-xl-auto flex items-center ml-auto">
								<button id="service-orders-btn" class="btn btn-primary btn-small btn-loading">
									Buscar
								</button>
							</div>
						</div>
					</form>
				</div>
				<div class="line mb-2"></div>
			</div>
			<?php if($renderOrderList): ?>
			<div id="pre-loader" class="mt-2 mx-auto">
				<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
			</div>
			<div class="w-100 hide-out hide">
				<div class="flex pb-5 flex-column">
					<span class="line-text mb-2 h4 semibold primary">Órdenes de servicio</span>
					<div class="center mx-1">
						<table id="resultServiceOrders" class="cell-border h6 display">
							<thead class="bg-primary secondary regular">
								<tr>
									<th>Orden nro.</th>
									<th>Fecha</th>
									<th>Monto comisión</th>
									<th>Monto IVA</th>
									<th>Monto OS</th>
									<th>Monto depositado</th>
									<th>Opciones</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($orderList AS $list): ?>
								<tr bulk="<?= htmlspecialchars(json_encode($list->bulk), ENT_QUOTES, 'UTF-8'); ?>">
									<td><?= $list->OrderNumber; ?></td>
									<td><?= $list->Orderdate; ?></td>
									<td><?= $list->OrderCommission; ?></td>
									<td><?= $list->OrderTax; ?></td>
									<td><?= $list->OrderAmount; ?></td>
									<td><?= $list->OrderDeposit; ?></td>
									<td class="p-0 flex justify-center">
										<button class="btn mx-1 px-0 details-control" title="Ver" data-toggle="tooltip">
											<i class="icon icon-find" aria-hidden="true"></i>
										</button>
										<button class="btn mx-1 px-0" title="Descargar PDF" data-toggle="tooltip">
											<i class="icon icon-download" aria-hidden="true"></i>
										</button>
										<button class="btn mx-1 px-0" title="Eliminar" data-toggle="tooltip">
											<i class="icon icon-remove" aria-hidden="true"></i>
										</button>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<div class="line my-2"></div>
					</div>

					<div class="my-5 py-4 center none">
						<span class="h4">No tiene Órdenes de servicio</span>
					</div>
				</div>
			</div>
			<?php endif; ?>

		</div>
		<?php if($widget): ?>
		<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
		<?php endif; ?>
	</div>
</div>
