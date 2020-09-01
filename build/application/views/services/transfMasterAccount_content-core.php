<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_SERV_MASTER_ACCOUNT'); ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>"><?= lang('PRODUCTS_DETAIL_TITLE') ?></a></li> /
				<li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_MENU_SERVICES'); ?></a></li>
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
			<?php if (lang('CONF_PAY_ACCOUNT') == 'ON'): ?>
			<div class="flex pb-3 flex-column w-100">
				<span class="line-text mb-2 h4 semibold primary">Recarga cuenta/tarjeta concentradora </span>
				<div class="flex my-2 px-5">
					<form id="#" method="post" class="w-100">
						<p class="mr-5 mb-3 sh5 semibold tertiary">Saldo disponible <span class="light text">10,393,054.68</span></p>

						<div class="row">
							<div class="form-group col-3">
								<label for="account" id="account"><?= lang('GEN_ACCOUNT'); ?></label>
								<span id="account-user" class="form-control px-1" readonly="readonly">******1426</span>
								<div class="help-block"></div>
							</div>

							<div class="form-group col-3">
								<div class="custom-option-c custom-radio custom-control-inline">
									<input type="radio" id="cash-out" name="cash" class="custom-option-input">
									<label class="custom-option-label nowrap" for="cash-out">Cargo</label>
								</div>
								<div class="custom-option-c custom-radio custom-control-inline">
									<input type="radio" id="cash-in" name="cash" class="custom-option-input">
									<label class="custom-option-label nowrap" for="cash-in">Abono</label>
								</div>
								<div class="help-block"></div>
							</div>

							<div class="form-group col-3">
								<label for="amount"><?= lang('GEN_TABLE_AMOUNT'); ?></label>
								<input id="amount" class="form-control h5" type="text" placeholder="Ingresar monto" name="amount">
								<div class="help-block"></div>
							</div>

							<div class="form-group col-3">
								<label for="description">Descripción</label>
								<input id="description" class="form-control h5" type="text" placeholder="Ingresa descripción" name="description">
								<div class="help-block"></div>
							</div>
						</div>

						<div class="row flex justify-end my-3">
							<div class="col-4 col-lg-3 col-xl-3 form-group">
								<div class="input-group">
									<input id="password-tranfer" name="password" class="form-control pwd-input pr-0 pwd" type="password"
										autocomplete="off" placeholder="<?= lang('GEN_PLACE_PASSWORD'); ?>">
									<div class="input-group-append">
										<span id="pwd_action" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i
												class="icon-view mr-0"></i></span>
									</div>
								</div>
								<div class="help-block bulk-select text-left"></div>
							</div>
							<div class="col-3">
								<button id="transfer" class="btn btn-primary btn-small btn-loading flex ml-auto">Transferir
								</button>
							</div>
						</div>
					</form>
				</div>
				<div class="line mb-2"></div>
			</div>
			<?php endif; ?>

			<div class="search-criteria-order flex pb-3 flex-column w-100">
				<span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SEARCH_CRITERIA'); ?></span>
				<div class="flex my-2 px-5">
					<form id="masterAccountForm" method="post" class="w-100">
						<div class="row flex justify-between">
							<div class="form-group col-4 col-xl-4">
								<label><?= lang('GEN_TABLE_DNI'); ?></label>
								<input id="idNumber" name="idNumber" class="form-control h5" type="text" autocomplete="off" disabled>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-4 col-xl-4">
								<label><?= lang('GEN_TABLE_CARD_NUMBER'); ?></label>
								<input id="cardNumber" name="cardNumber" class="form-control h5" type="text" autocomplete="off" disabled>
								<div class="help-block"></div>
							</div>
							<div class="flex items-center justify-end col-3">
								<button id="masterAccountBtn" class="btn btn-primary btn-small btn-loading">
									<?= lang('GEN_BTN_SEARCH'); ?>
								</button>
							</div>
						</div>
					</form>
				</div>
				<div class="line mb-2"></div>
			</div>
			<div class="flex">
				<div id="pre-loader-table" class="mt-2 mb-4 mx-auto hide">
					<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
				</div>
			</div>
			<div class="hide-table hide">
				<div class="flex pb-5 flex-column">
					<span class="line-text mb-2 h4 semibold primary">Resultados</span>
					<div class="center mx-1">
						<div class="flex ml-4 py-3 flex-auto justify-between">
							<p class="mr-5 h5 semibold tertiary">Saldo disponible: <span id="balance-aviable" class="light text"></span></p>
							<p class="mr-5 mb-0 h5 semibold tertiary">Comisión por transacción: <span id="cost-trans" class="light text">0</span></p>
							<p class="mr-5 mb-0 h5 semibold tertiary">Comisión por Consultar saldo: <span id="cost-inquiry" class="light text">0</span></p>
						</div>

						<table id="tableServicesMaster" class="cell-border h6 display w-100">
							<thead class="bg-primary secondary regular">
								<tr>
									<th class="toggle-all"></th>
									<th><?= lang('GEN_TABLE_CARD_NUMBER'); ?></th>
									<th><?= lang('GEN_TABLE_FULL_NAME') ?></th>
									<th><?= lang('GEN_TABLE_DNI') ?></th>
									<th><?= lang('GEN_TABLE_BALANCE_AVIABLE'); ?></th>
									<th><?= lang('GEN_TABLE_AMOUNT'); ?></th>
									<th><?= lang('GEN_TABLE_OPTIONS') ?></th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>

						<form id="password-table">
							<div class="flex row mt-3 mb-2 mx-2 justify-end">
								<div class="col-3 col-lg-3 col-xl-3 form-group">
									<div class="input-group">
										<input name="password" class="form-control pwd-input pr-0 pwd" type="password" autocomplete="off"
											placeholder="<?= lang('GEN_PLACE_PASSWORD'); ?>">
										<div class="input-group-append">
											<span id="pwd_action" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i
													class="icon-view mr-0"></i></span>
										</div>
									</div>
									<div class="help-block bulk-select text-left"></div>
								</div>
								<?php if($this->verify_access->verifyAuthorization('TRAMAE', 'TRASAL')): ?>
								<div class="col-auto">
									<button id="Consulta" class="btn btn-primary btn-small btn-loading flex mx-auto" amount="0">Consultar
									</button>
								</div>
								<?php endif; ?>
								<?php if($this->verify_access->verifyAuthorization('TRAMAE', 'TRAABO')): ?>
								<div class="col-auto">
									<button id="Abono" class="btn btn-primary btn-small btn-loading flex mx-auto" amount="1">Abono
									</button>
								</div>
								<?php endif; ?>
								<?php if($this->verify_access->verifyAuthorization('TRAMAE', 'TRACAR')): ?>
								<div class="col-auto">
									<button id="Cargo" class="btn btn-primary btn-small btn-loading flex mx-auto" amount="1">Cargo
									</button>
								</div>
								<?php endif; ?>
							</div>
						</form>
						<div class="line my-2"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
	<?php endif; ?>
</div>
