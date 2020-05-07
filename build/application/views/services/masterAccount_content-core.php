<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline">Cuenta maestra</h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>"><?= lang('PRODUCTS_DETAIL_TITLE') ?></a></li> /
				<li class="inline"><a class="tertiary not-pointer" href="javascript:">Cuenta maestra</a></li>
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
				<span class="line-text mb-2 h4 semibold primary">Criterio de búsqueda</span>
				<div class="flex my-2 px-5">
					<form method="post" class="w-100">
						<div class="row flex justify-between">
							<div class="form-group col-4 col-xl-4">
								<label>NIT.</label>
								<input id="Nit" class="form-control h5" type="text" placeholder="Ingresar NIT">
								<div class="help-block"></div>
							</div>
							<div class="form-group col-4 col-xl-4">
								<label>Número de tarjeta</label>
								<input id="Nit" class="form-control h5" type="text" placeholder="Ingresar número">
								<div class="help-block"></div>
							</div>
							<div class="flex items-center justify-end col-3">
								<button class="btn btn-primary btn-small">
									Buscar
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
					<div class="flex ml-4 py-3 flex-auto justify-between">
						<p class="mr-5 h5 semibold tertiary">Saldo disponible <span class="light text">10,393,054.68</span></p>
						<p class="mr-5 mb-0 h5 semibold tertiary">Comisión por transacción <span class="light text">0</span></p>
						<p class="mr-5 mb-0 h5 semibold tertiary">Comisión por consulta saldo <span class="light text">0</span></p>
					</div>

					<table id="tableServicesMaster" class="cell-border h6 display w-100">
						<thead class="bg-primary secondary regular">
							<tr>
								<th class="toggle-all"></th>
								<th>Número de tarjeta</th>
								<th>Estatus</th>
								<th>Nombre</th>
								<th>NIT.</th>
								<th>Saldo</th>
								<th>Monto</th>
								<th>Opciones</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td></td>
								<td> ************9117</td>
								<td>-</td>
								<td>Juan Gonzalez</td>
								<td class="tool-ellipsis"> 43865838</td>
								<td>-</td>
								<td>
									<input id="Nit" class="form-control h6" type="text">
								</td>

								<td class="pb-0 px-1 flex justify-center items-center">
									<button id="consulta_saldo" class="btn mx-1 px-0" title="Consulta saldo" data-toggle="tooltip">
										<i class="icon novoglyphs icon-balance" aria-hidden="true"></i>
									</button>
									<button id="abono_tarjeta" class="btn mx-1 px-0" title="Abono tarjeta" data-toggle="tooltip">
										<i class="icon novoglyphs icon-credit-card" aria-hidden="true"></i>
									</button>
									<button id="cargo_tarjeta" class="btn mx-1 px-0" title="Cargo tarjeta" data-toggle="tooltip">
										<i class="icon novoglyphs icon-card-fee" aria-hidden="true"></i>
									</button>
								</td>
							</tr>

						</tbody>
					</table>

					<form id="sign-bulk-form" method="post">
						<div class="flex row mt-3 mb-2 mx-2 justify-end">
							<div class="col-3 col-lg-3 col-xl-3 form-group">
								<input id="password-sign" name="password" class="form-control h6" type="password" placeholder="Contraseña">
								<div class="help-block bulk-select text-left"></div>
							</div>
							<div class="col-auto">
								<button id="sign-bulk-btn" class="btn btn-primary btn-small btn-loading flex mx-auto">Consultar
								</button>
							</div>

							<div class="col-auto">
								<button id="del-sign-bulk-btn" class="btn btn-primary btn-small btn-loading flex mx-auto">Abono
								</button>
							</div>

							<div class="col-auto">
								<button id="del-sign-bulk-btn" class="btn btn-primary btn-small btn-loading flex mx-auto">Cargo
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
