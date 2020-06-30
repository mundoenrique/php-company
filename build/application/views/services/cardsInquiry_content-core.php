<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_SERV_CARD_INQUIRY'); ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline">
					<a class="tertiary big-modal" href="<?= base_url('empresas') ?>"><?= lang('GEN_MENU_ENTERPRISE'); ?></a></li>
				/
				<li class="inline">
					<a class="tertiary big-modal" href="<?= base_url('productos') ?>"><?= lang('GEN_PRODUCTS'); ?></a></li> /
				<li class="inline">
					<a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>"><?= lang('PRODUCTS_DETAIL_TITLE'); ?></a>
				</li> /
				<li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_MENU_CONSULTATIONS'); ?></a>
				</li>
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

			<div class="search-criteria-order flex pb-3 flex-column w-100">
				<span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SEARCH_CRITERIA'); ?></span>
				<div class="flex mt-2 mb-3 px-5">
					<form method="post" class="w-100">
						<div class="row flex justify-between">
							<div class="form-group col-4 col-xl-3">
								<label><?= lang('GEN_ORDER_TITLE'); ?></label>
								<input id="Nit" class="form-control h5" type="text" placeholder="Ingresar <?= lang('GEN_NIT'); ?>">
								<div class="help-block"></div>
							</div>
							<div class="form-group col-4 col-xl-3">
								<label><?= lang('GEN_TABLE_BULK_NUMBER'); ?></label>
								<input id="card-number" class="form-control h5" type="text" placeholder="Ingresar número">
								<div class="help-block"></div>
							</div>
							<div class="form-group col-4 col-xl-3">
								<label><?= lang('GEN_DOCUMENT_TITLE'); ?></label>
								<input id="Nit" class="form-control h5" type="text" placeholder="Ingresar <?= lang('GEN_NIT'); ?>">
								<div class="help-block"></div>
							</div>
							<div class="form-group col-4 col-xl-3">
								<label><?= lang('GEN_CARD_NUMBER'); ?></label>
								<input id="card-number" class="form-control h5" type="text" placeholder="Ingresar número">
								<div class="help-block"></div>
							</div>
							<div class="flex col-xl-auto items-center ml-auto mr-2">
								<button class="btn btn-primary btn-small btn-loading">
								<?= lang('GEN_BTN_SEARCH'); ?>
								</button>
							</div>
						</div>
					</form>
				</div>
				<div class="line mb-2"></div>
			</div>

			<div class="flex pb-5 flex-column">
				<span class="line-text mb-2 h4 semibold primary">Resultados</span>
				<div class="center mx-1">
					<div class="flex">
						<div class="flex mr-2 py-3 flex-auto justify-end items-center">
							<button class="btn px-1" title="Exportar a EXCEL" data-toggle="tooltip">
								<i class="icon icon-file-excel" aria-hidden="true"></i>
							</button>
						</div>
					</div>

					<table id="tableCardInquiry" class="cell-border h6 display responsive w-100">
						<thead class="bg-primary secondary regular">
							<tr>
								<th class="toggle-all"></th>
								<th><?= lang('GEN_TABLE_CARD_NUMBER'); ?></th>
								<th><?= lang('GEN_TABLE_ORDER_NRO'); ?></th>
								<th><?= lang('GEN_TABLE_BULK_NUMBER'); ?></th>
								<th><?= lang('GEN_TABLE_EMISSION_STATUS'); ?></th>
								<th><?= lang('GEN_TABLE_PLASTIC_STATUS'); ?></th>
								<th><?= lang('GEN_TABLE_NAME'); ?></th>
								<th><?= lang('GEN_TABLE_ID_PERSON'); ?></th>
								<th><?= lang('GEN_TABLE_BALANCE'); ?></th>
								<th><?= lang('GEN_TABLE_OPTIONS'); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td></td>
								<td>********001091</td>
								<td>10442</td>
								<td>19102300</td>
								<td>Entregada a Tarjetahabiente </td>
								<td>Cambio de Pin</td>
								<td>Rojas Jose</td>
								<td>1719708404</td>
								<td>-</td>
								<td class="pb-0 px-1">
									<div class="flex justify-center items-center">
										<button id="actualizar_datos" class="btn mx-1 px-0" title="Actualizar datos" data-toggle="tooltip">
											<i class="icon novoglyphs icon-user-edit" aria-hidden="true"></i>
										</button>
										<button id="consulta_saldo_tarjeta" class="btn mx-1 px-0" title="Consulta saldo tarjeta" data-toggle="tooltip">
											<i class="icon novoglyphs icon-envelope-open" aria-hidden="true"></i>
										</button>
										<button id="bloqueo_tarjeta" class="btn mx-1 px-0" title="Bloqueo tarjeta" data-toggle="tooltip">
											<i class="icon novoglyphs icon-lock" aria-hidden="true"></i>
										</button>
										<button id="desbloqueo" class="btn mx-1 px-0" title="Desbloqueo" data-toggle="tooltip">
											<i class="icon novoglyphs icon-chevron-up" aria-hidden="true"></i>
										</button>
										<!-- <button id="entregar_a_tarjetahabiente" class="btn mx-1 px-0" title="Entregar a tarjetahabiente" data-toggle="tooltip">
											<i class="icon novoglyphs icon-arrow-right" aria-hidden="true"></i>
										</button>
										<button id="enviar_a_empresa" class="btn mx-1 px-0" title="Enviar a empresa" data-toggle="tooltip">
											<i class="icon novoglyphs icon-user-card" aria-hidden="true"></i>
										</button>
										<button id="recibir_en_empresa" class="btn mx-1 px-0" title="Recibir en empresa" data-toggle="tooltip">
											<i class="icon novoglyphs icon-building" aria-hidden="true"></i>
										</button>
										<button id="recibir_en_banco" class="btn mx-1 px-0" title="Recibir en banco" data-toggle="tooltip">
											<i class="icon novoglyphs icon-user-building" aria-hidden="true"></i>
										</button>
										<span id="cargo_tarjeta" class="btn mx-1 px-0" title="Cargo tarjeta" data-toggle="tooltip">
											-
										</span> -->
									</div>
								</td>
							</tr>
						</tbody>
					</table>

					<form id="sign-bulk-form" method="post">
						<div class="flex row mt-3 mb-2 mx-2 justify-end">
							<div class="col-4 col-lg-3 h6 regular form-group">
								<select id="" name="" class="select-box custom-select flex h6 w-100">
									<option selected disabled >Seleccionar</option>
									<option value="0">Bloqueo tarjeta</option>
									<option value="1">Consulta saldo trajeta</option>
									<option>Option 3</option>
								</select>
								<div class="help-block"></div>
							</div>
							<div class="col-4 col-lg-3 col-xl-3 form-group">
								<div class="input-group">
									<input id="" name="password" class="form-control pwd-input pr-0" type="password" autocomplete="off"
									placeholder="<?= lang('GEN_PLACE_PASSWORD'); ?>">
									<div class="input-group-append">
										<span id="pwd_action" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i
											class="icon-view mr-0"></i></span>
									</div>
								</div>
								<div class="help-block bulk-select text-left"></div>
							</div>
							<div class="col-3 col-lg-auto">
								<button id="" class="btn btn-primary btn-small btn-loading flex mx-auto">
								<?= lang('GEN_BTN_PROCESS'); ?>
							</button>
							</div>

						</div>
					</form>
					<div class="line my-2"></div>
				</div>
			</div>

		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
	<?php endif; ?>
</div>
