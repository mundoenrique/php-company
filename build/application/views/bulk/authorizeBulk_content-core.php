<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pt-3 px-5 pb-5">
	<h1 class="primary h3 regular inline"><?= lang('GEN_AUTHORIZE_BULK_TITLE') ?></h1>
	<span class="ml-2 regular tertiary"><?= $productName ?></span>
	<div class="mb-2 flex items-center">
		<div class="flex tertiary">
			<nav class="main-nav nav-inferior">
				<ul class="mb-0 h6 light tertiary list-style-none list-inline">
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>">Empresas</a></li> /
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>">Productos</a></li> /
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>">Detalle del producto</a></li> /
					<li class="inline"><a class="tertiary" href="javascript:">Autorizar lote</a></li>
				</ul>
			</nav>
		</div>
	</div>
	<div class="flex mt-1 bg-color flex-nowrap justify-between">
		<div class="flex flex-auto flex-column <?= $widget ? '' : 'max-width-6';  ?>">
			<?php if($signBulk != new stdClass()): ?>
			<div class="flex pb-5 flex-column">
				<span class="line-text mb-2 h4 semibold primary">Lotes pendientes por firmar</span>
				<div class="center mx-1">
					<table id="sign-bulk" class="cell-border h6 display" sign="<?= $authorizeAttr->sign; ?>">
						<thead class="regular secondary bg-primary">
							<tr id="headerRow">
								<th class="toggle-all"></th>
								<th>Nro. Lote</th>
								<th>Id de lote</th>
								<th>Fecha de carga</th>
								<th>Tipo</th>
								<th>Id Tipo</th>
								<th>Registros</th>
								<th>Monto</th>
								<th>Opciones</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($signBulk AS $bulk): ?>
							<tr>
								<td></td>
								<td><?= $bulk->bulkNumber; ?></td>
								<td><?= $bulk->idBulk; ?></td>
								<td><?= $bulk->loadDate; ?></td>
								<td><?= $bulk->type; ?></td>
								<td><?= $bulk->idType; ?></td>
								<td><?= $bulk->records; ?></td>
								<td><?= $bulk->amount; ?></td>
								<td class="flex justify-center">
									<button class="btn mx-1 px-0" title="Ver" data-toggle="tooltip"
										onclick="window.location.href = 'ceo_auth_see_lot.php'">
										<i class="icon icon-find" aria-hidden="true"></i>
									</button>
									<button class="btn mx-1 px-0" title="Eliminar" data-toggle="tooltip">
										<i class="icon icon-remove mr-1" aria-hidden="true"></i>
									</button>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<form method="post">
						<div class="flex mb-4 mt-1 px-5 justify-end items-center row">
							<div class="col-4 col-lg-4 col-xl-3">
								<input id="password-sign" name="password-sign" class="form-control" type="password" placeholder="<?= lang('GEN_PLACE_PASSWORD'); ?>">
							</div>
							<div class="col-auto">
								<button class="btn btn-primary btn-small flex mx-auto">
									Firmar
								</button>
							</div>
							<div class="col-auto">
								<button class="btn btn-primary btn-small flex mx-auto">
									Eliminar
								</button>
							</div>
						</div>
					</form>
					<div class="line mb-2"></div>
				</div>

				<div class="my-5 py-4 center none">
					<span class="h4">No fue posible obtener el listado</span>
				</div>
			</div>
			<?php endif; ?>
			<div class="flex pb-5 flex-column">
				<span class="line-text mb-2 h4 semibold primary">Lotes pendientes por autorizar</span>
				<div class="center mx-1">
					<table id="authorize-bulk" class="cell-border h6 display"  auth="<?= $authorizeAttr->auth; ?>" order-to-pay="<?= $authorizeAttr->toPAy; ?>">
						<thead class="bg-primary secondary regular">
							<tr>
								<th class="<?= $authorizeAttr->allBulk; ?>"></th>
								<th>Nro. Lote</th>
								<th>Id de lote</th>
								<th>Fecha de carga</th>
								<th>Tipo</th>
								<th>Id Tipo</th>
								<th>Registros</th>
								<th>Monto</th>
								<th>Opciones</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($authorizeBulk AS $bulk): ?>
							<tr class="<?= $bulk->selectRow; ?>">
								<td class="<?= $bulk->selectRow; ?>"><?= $bulk->selectRowContent; ?></td>
								<td><?= $bulk->bulkNumber; ?></td>
								<td><?= $bulk->idBulk; ?></td>
								<td><?= $bulk->loadDate; ?></td>
								<td><?= $bulk->type; ?></td>
								<td><?= $bulk->idType; ?></td>
								<td><?= $bulk->records; ?></td>
								<td><?= $bulk->amount; ?></td>
								<td class="flex justify-center">
									<button class="btn mx-1 px-0" title="Ver" data-toggle="tooltip">
										<i class="icon icon-find" aria-hidden="true"></i>
									</button>
									<button class="btn mx-1 px-0" title="Eliminar" data-toggle="tooltip">
										<i class="icon icon-remove mr-1" aria-hidden="true"></i>
									</button>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<div class="flex py-4 items-center row">
						<div class="flex col-6">
							<div class="col-auto h6 regular">
								<select class="select-box custom-select h6">
									<option selected disabled>Procesar por tipo de lote</option>
									<option>Option 1</option>
									<option>Option 2</option>
									<option>Option 3</option>
								</select>
							</div>
							<div class="col-auto">
								<input id="password-auth" name="password-auth" class="form-control" type="password" placeholder="<?= lang('GEN_PLACE_PASSWORD'); ?>">
							</div>
						</div>

						<div class="flex flex-auto justify-end col-6">
							<div class="col-auto">
								<button class="btn btn-primary btn-small flex mx-auto">
									Autorizar
								</button>
							</div>
							<div class="col-auto">
								<button class="btn btn-primary btn-small flex mx-auto">
									Eliminar
								</button>
							</div>
						</div>
					</div>
					<div class="line mb-2"></div>
				</div>

				<div class="my-5 py-4 center none">
					<span class="h4">No fue posible obtener el listado</span>
				</div>

			</div>
		</div>
		<?php if($widget): ?>
		<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
		<?php endif; ?>
	</div>
</div>
