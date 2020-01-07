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
		<div id="pre-loader" class="mt-2 mx-auto">
			<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
		</div>
		<div class="w-100 hide-out hide">
			<div class="flex flex-auto flex-column <?= $widget ? '' : 'max-width-6'; ?>">
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
									<td class="flex justify-center p-0">
										<button class="btn mx-1 px-0" title="<?= lang('GEN_BTN_SEE') ?>" data-toggle="tooltip"
											onclick="window.location.href = 'ceo_auth_see_lot.php'">
											<i class="icon icon-find" aria-hidden="true"></i>
										</button>
										<button class="btn mx-1 px-0" title="<?= lang('GEN_BTN_DELETE') ?>" data-toggle="tooltip">
											<i class="icon icon-remove mr-1" aria-hidden="true"></i>
										</button>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<form id="sign-bulk-form" method="post">
							<div class="flex row mt-3 mb-2 mx-2 justify-end">
								<div class="col-4 col-lg-3 col-xl-3 form-group">
									<input id="password-sign" name="password" class="form-control h6" type="password" placeholder="<?= lang('GEN_PLACE_PASSWORD'); ?>">
									<div class="help-block bulk-select text-left"></div>
								</div>
								<div class="col-auto">
									<button id="sign-bulk-btn" class="btn btn-primary btn-small btn-loading flex mx-auto">
										<?= lang('GEN_BTN_SIGN'); ?>
									</button>
								</div>
								<div class="col-auto">
									<button id="del-sign-bulk-btn" class="btn btn-primary btn-small btn-loading flex mx-auto">
										<?= lang('GEN_BTN_DELETE'); ?>
									</button>
								</div>
							</div>
						</form>
						<div class="line mb-2"></div>
					</div>
				</div>
				<?php endif; ?>
				<div class="flex pb-5 flex-column">
					<span class="line-text mb-2 h4 semibold primary">Lotes pendientes por autorizar</span>
					<div class="center mx-1">
						<table id="authorize-bulk" class="cell-border h6 display" auth="<?= $authorizeAttr->auth; ?>" order-to-pay="<?= $authorizeAttr->toPAy; ?>">
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
									<td class="p-0 <?= $bulk->selectRow; ?>"><?= $bulk->selectRowContent; ?></td>
									<td><?= $bulk->bulkNumber; ?></td>
									<td><?= $bulk->idBulk; ?></td>
									<td><?= $bulk->loadDate; ?></td>
									<td><?= $bulk->type; ?></td>
									<td><?= $bulk->idType; ?></td>
									<td><?= $bulk->records; ?></td>
									<td><?= $bulk->amount; ?></td>
									<td class="flex justify-center p-0">
										<button class="btn mx-1 px-0" title="<?= lang('GEN_BTN_SEE') ?>" data-toggle="tooltip">
											<i class="icon icon-find" aria-hidden="true"></i>
										</button>
										<button class="btn mx-1 px-0" title="<?= lang('GEN_BTN_DELETE') ?>" data-toggle="tooltip">
											<i class="icon icon-remove mr-1" aria-hidden="true"></i>
										</button>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<form id="auth-bulk-form" method="post">
							<div class="flex row mt-3 mb-2 mx-2 justify-end">
								<div class="col-4 col-lg-3 h6 regular form-group">
									<?php if(verifyDisplay('body', $module,  lang('GEN_TAG_ORDER_TYPE'))): ?>
									<select id="type-order" name="type-order" class="select-box custom-select h6">
										<option value="0">Procesar por lote</option>
										<option value="1" selected>Procesar por tipo de lote</option>
									</select>
									<?php else: ?>
										<input type="hidden" id="type-order" name="type-order" value="0">
									<?php endif; ?>
									<div class="help-block"></div>
								</div>
								<div class="col-6 col-lg-auto form-group">
									<input id="password-auth" name="password" class="form-control h6" type="password" placeholder="<?= lang('GEN_PLACE_PASSWORD'); ?>">
									<div class="help-block bulk-select text-left"></div>
								</div>
								<div class="col-3 col-lg-auto">
									<button id="auth-bulk-btn" class="btn btn-primary btn-small btn-loading flex mx-auto">
										<?= lang('GEN_BTN_AUTHORIZE'); ?>
									</button>
								</div>
								<div class="col-3 col-lg-auto">
									<button id="del-auth-bulk-btn" class="btn btn-primary btn-small btn-loading flex mx-auto">
										<?= lang('GEN_BTN_DELETE'); ?>
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
			</div>
		</div>
		<?php if($widget): ?>
		<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
		<?php endif; ?>
	</div>
</div>
