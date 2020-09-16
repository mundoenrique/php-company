<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_ACCESS_PERMISSION_TITLE') ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>"><?= lang('PRODUCTS_DETAIL_TITLE') ?></a></li> /
				<li class="inline"><a class="tertiary not-pointer" href="javascript:">Usuarios</a></li>
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
				<span class="line-text mb-2 h4 semibold text-primary"><?= lang('GEN_USER_DATA') ?></span>
				<div class="flex my-2">
					<form id="user-data" action="" method="post" class="w-100">
						<div class="row mb-2 px-5">
							<div class="form-group mb-3 col-6">
								<label for="idUser" id="idUser"><?= lang('GEN_USER') ?></label>
								<span id="idUser" class="form-control px-1" readonly="readonly">100001</span>
							</div>
							<div class="form-group mb-3 col-6">
								<label for="fullName" id="fullName"><?= lang('GEN_TABLE_FULL_NAME') ?></label>
								<span id="fullName" class="form-control px-1" readonly="readonly">José Gutierrez</span>
							</div>
							<div class="form-group mb-3 col-6">
								<label for="email" id="email"><?= lang('GEN_EMAIL') ?></label>
								<span id="email" class="form-control px-1" readonly="readonly">jose.gutierrez@mail.com</span>
							</div>
							<div class="form-group mb-3 col-6">
								<label for="typeUser" id="typeUser"><?= lang('GEN_TABLE_TYPE') ?></label>
								<span id="typeUser" class="form-control px-1" readonly="readonly">Administrador</span>
							</div>
						</div>
						<div class="flex row mb-2 mx-2 items-center justify-end">
							<a class="btn btn-link btn-small big-modal" href="#"><?= lang('GEN_BTN_CANCEL'); ?></a>
							<button id="enableUserBtn" class="btn btn-small btn-loading btn-primary" type="submit">
								<?= lang('GEN_BTN_ENABLE'); ?>
							</button>
						</div>
					</form>
				</div>
			</div>

			<div class="flex">
				<div id="pre-loade-result" class="mt-2 mx-auto hide">
					<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
				</div>
			</div>

			<div class="w-100 cardholders-result ">
				<div class="flex pb-5 flex-column">
					<span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_LIST_PERMITS'); ?></span>
					<div class="row flex justify-between mb-3">
						<div class="form-group col-12 center flex justify-center items-end">
							<span class="h6 bold mb-0 mt-2">
								Nota:
								<span class="light text">Si el check se encuentra en color</span>
							</span>
							<div class="custom-control custom-switch custom-control-inline p-0 pl-4 ml-1 mr-0">
								<input class="custom-control-input" type="checkbox" disabled checked>
								<label class="custom-control-label"></label>
							</div>
							<span class="h6 light text">el permiso está activo</span>
						</div>
					</div>
					<form id="check-form">
						<div class="row mx-3">
							<div class="form-group custom-control custom-switch col-6 col-lg-4 pb-3 my-3">
								<input id="allPermits" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label semibold" for="allPermits">Todos los permisos</span>
								</label>
							</div>
							<div class="form-group custom-control custom-switch col-6 col-lg-4 pb-3 my-3">
								<input id="removeAllPermissions" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label semibold" for="removeAllPermissions">Eliminar todos los permisos</span>
								</label>
							</div>
						</div>
						<div class="row mx-3 mb-1">
							<h4 class="col-12 pl-0 bold"><?= lang('GEN_MENU_LOTS'); ?></h4>
							<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
								<input id="bulkLoad" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label " for="bulkLoad"><?= lang('GEN_MENU_BULK_LOAD'); ?></span>
								</label>
							</div>
							<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
								<input id="bulkAuth" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label " for="bulkAuth"><?= lang('GEN_MENU_BULK_AUTH'); ?></span>
								</label>
							</div>
							<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
								<input id="bulkUnnamed" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label " for="bulkUnnamed"><?= lang('GEN_MENU_BULK_UNNAMED'); ?></span>
								</label>
							</div>
						</div>
						<div class="row mx-3 mb-1">
							<h4 class="col-12 pl-0 bold"><?= lang('GEN_MENU_CONSULTATIONS'); ?></h4>
							<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
								<input id="consOrdersServ" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label " for="consOrdersServ"><?= lang('GEN_MENU_CONS_ORDERS_SERV'); ?></span>
								</label>
							</div>
						</div>
						<div class="row mx-3 mb-1">
							<h4 class="col-12 pl-0 bold"><?= lang('GEN_MENU_SERVICES'); ?></h4>
							<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
								<input id="servMasterAccount" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label " for="servMasterAccount"><?= lang('GEN_MENU_SERV_MASTER_ACCOUNT'); ?></span>
								</label>
							</div>
							<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
								<input id="servCardInquiry" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label " for="servCardInquiry"><?= lang('GEN_MENU_SERV_CARD_INQUIRY'); ?></span>
								</label>
							</div>
							<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
								<input id="servCommMoneyOrders" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label " for="servCommMoneyOrders"><?= lang('GEN_MENU_SERV_COMM_MONEY_ORDERS'); ?></span>
								</label>
							</div>
							<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
								<input id="servTransLimits" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label " for="servTransLimits"><?= lang('GEN_MENU_SERV_TRANS_LIMITS'); ?></span>
								</label>
							</div>
						</div>
						<div class="row mx-3 mb-1">
							<h4 class="col-12 pl-0 bold"><?= lang('GEN_MENU_REPORTS'); ?></h4>
							<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
								<input id="repCardReplace" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label " for="repCardReplace"><?= lang('GEN_MENU_REP_CARD_REPLACE'); ?></span>
								</label>
							</div>
							<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
								<input id="repClosingBalance" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label " for="repClosingBalance"><?= lang('GEN_MENU_REP_CLOSING_BAKANCE'); ?></span>
								</label>
							</div>
							<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
								<input id="repAccaountStatus" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label " for="repAccaountStatus"><?= lang('GEN_MENU_REP_ACCAOUNT_STATUS'); ?></span>
								</label>
							</div>
							<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
								<input id="repUserAct" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label " for="repUserAct"><?= lang('GEN_MENU_REP_USER_ACT'); ?></span>
								</label>
							</div>
							<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
								<input id="repRechargeMade" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label " for="repRechargeMade"><?= lang('GEN_MENU_REP_RECHARGE_MADE'); ?></span>
								</label>
							</div>
							<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
								<input id="repIssuedCards" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label " for="repIssuedCards"><?= lang('GEN_MENU_REP_ISSUED_CARDS'); ?></span>
								</label>
							</div>
							<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
								<input id="repCategoryExpense" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label " for="repCategoryExpense"><?= lang('GEN_MENU_REP_CATEGORY_EXPENSE'); ?></span>
								</label>
							</div>
							<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
								<input id="repMasterAccount" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label " for="repMasterAccount"><?= lang('GEN_MENU_REP_MASTER_ACCOUNT'); ?></span>
								</label>
							</div>
						</div>
						<div class="row mx-3 mb-1">
							<h4 class="col-12 pl-0 bold"><?= lang('GEN_MENU_USERS'); ?></h4>
							<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
								<input id="usersManagement" class="custom-control-input" type="checkbox" name="checkbox" value="off">
								<label class="custom-control-label " for="usersManagement"><?= lang('GEN_MENU_USERS_MANAGEMENT'); ?></span>
								</label>
							</div>
						</div>
						<div class="flex row mt-3 mb-2 mx-2 justify-end">
							<div class="col-auto">
								<button id="sign-btn" class="btn btn-primary btn-small btn-loading flex mx-auto">
									<?= lang('GEN_BTN_UPDATE'); ?></button>
							</div>
						</div>
					</form>
					<div class="line mb-2"></div>
				</div>
			</div>
		</div>
	</div>
	<?php if ($widget) : ?>
		<?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
	<?php endif; ?>
</div>
