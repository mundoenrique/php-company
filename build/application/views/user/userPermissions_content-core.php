<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_USER_PERMISSION_TITLE') ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>"><?= lang('PRODUCTS_DETAIL_TITLE') ?></a></li> /
				<li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_MENU_USERS') ?></a></li>
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
								<input id="idUser" class="form-control px-1" readonly="readonly" value= <?= $user ?>>
							</div>
							<div class="form-group mb-3 col-6">
								<label for="fullName" id="fullName"><?= lang('GEN_TABLE_FULL_NAME') ?></label>
								<span id="fullName" class="form-control px-1" readonly="readonly"><?= $name ?></span>
							</div>
							<div class="form-group mb-3 col-6">
								<label for="email" id="email"><?= lang('GEN_EMAIL') ?></label>
								<span id="email" class="form-control px-1" readonly="readonly"><?= $email ?></span>
							</div>
							<div class="form-group mb-3 col-6">
								<label for="typeUser" id="typeUser"><?= lang('GEN_TABLE_TYPE') ?></label>
									<span id="typeUser" class="form-control px-1" readonly="readonly"><?= $type ?></span>
							</div>
						</div>
						<div id="enableSectionBtn" class="flex row mb-2 mx-2 items-center justify-end ">
							<a class="btn btn-link btn-small big-modal" href="<?= base_url('administracion-usuarios') ?>"><?= lang('GEN_BTN_CANCEL'); ?></a>
							<button id="enableUserBtn" class="btn btn-small btn-loading btn-primary" type="submit">
								<?= lang('GEN_BTN_ENABLE'); ?>
							</button>
						</div>
					</form>
				</div>
			</div>
			<div id="sectionPermits">
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
								<?= lang('GEN_NOTE'); ?>
									<span class="light text"><?= lang('GEN_CHECK_COLOR'); ?></span>
								</span>
								<div class="custom-control custom-switch custom-control-inline p-0 pl-4 ml-1 mr-0">
									<input class="custom-control-input" type="checkbox" disabled checked>
									<label class="custom-control-label"></label>
								</div>
								<span class="h6 light text"><?= lang('PERMITS_NOTE_ACTIVE'); ?></span>
							</div>
						</div>
						<form id="checkFormPermits">
							<input id="idUser" name="idUser" type="hidden" value= <?= $user ?>>
							<div class="row mx-3">
								<div class="form-group custom-control custom-switch col-6 col-lg-4 pb-3 my-3">
									<input id="allPermits" class="custom-control-input" type="checkbox" name="allPermits" value="off">
									<label class="include custom-control-label semibold" for="allPermits"><?= lang('PERMITS_ALL_PERMITS'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-6 col-lg-4 pb-3 my-3">
									<input id="removeAllPermissions" class="custom-control-input" type="checkbox" name="removeAllPermissions" value="off">
									<label class="include custom-control-label semibold" for="removeAllPermissions"><?= lang('PERMITS_DELETE_ALL_PERMITS'); ?></span>
									</label>
								</div>
							</div>
							<div class="row mx-3 mb-1">
							<h4 class="col-12 pl-0 bold"><?= lang('GEN_MENU_CONS_ORDERS_SERV'); ?></h4>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="deleteServiceOrder" class="permissions custom-control-input" type="checkbox" name="checkbox0" value=<?= $deleteServiceOrder ?>>
									<label class="custom-control-label " for="deleteServiceOrder"><?= lang('PERMITS_DELETE_ORDER_SERVICE'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="consultOrderService" class="permissions custom-control-input" type="checkbox" name="checkbox1" value=<?= $consultOrderService ?>>
									<label class="custom-control-label " for="consultOrderService"><?= lang('PERMITS_CONSULT_ORDER_SERVICE'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="payOrderService" class="permissions custom-control-input" type="checkbox" name="checkbox2" value=<?= $payOrderService ?>>
									<label class="custom-control-label " for="payOrderService"><?= lang('PERMITS_PAY_ORDER_SERVICE'); ?></span>
									</label>
								</div>
							</div>
							<div class="row mx-3 mb-1">
								<h4 class="col-12 pl-0 bold"><?= lang('GEN_MENU_LOTS'); ?></h4>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="deleteBulk" class="permissions custom-control-input" type="checkbox" name="checkbox3" value=<?= $deleteBulk ?>>
									<label class="custom-control-label " for="deleteBulk"><?= lang('PERMITS_DELETE_BULK'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="confirmBulk" class="permissions custom-control-input" type="checkbox" name="checkbox4" value=<?= $confirmBulk ?>>
									<label class="custom-control-label " for="confirmBulk"><?= lang('PERMITS_CONFIRM_BULK'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="deleteBulkForConfirm" class="permissions custom-control-input" type="checkbox" name="checkbox5" value=<?= $deleteBulkForConfirm ?>>
									<label class="custom-control-label " for="deleteBulkForConfirm"><?= lang('PERMITS_DELETE_BULK_CONFIRM'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="generationBulk" class="permissions custom-control-input" type="checkbox" name="checkbox6" value=<?= $generationBulk ?>>
									<label class="custom-control-label " for="generationBulk"><?= lang('PERMITS_BULK_GENERATION'); ?></span>
									</label>
								</div>
							</div>

							<div class="row mx-3 mb-1">
								<h4 class="col-12 pl-0 bold"><?= lang('GEN_MENU_REPORTS'); ?></h4>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="unnamedReport" class="permissions custom-control-input" type="checkbox" name="checkbox7" value=<?= $unnamedReport ?>>
									<label class="custom-control-label " for="unnamedReport"><?= lang('PERMITS_UNNAMED_REPORT'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="expensesByCategory" class="permissions custom-control-input" type="checkbox" name="checkbox8" value=<?= $expensesByCategory ?>>
									<label class="custom-control-label " for="expensesByCategory"><?= lang('PERMITS_EXPENSES_BY_CATEGORY_REPORT'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="concentratingAccount" class="permissions custom-control-input" type="checkbox" name="checkbox9" value=<?= $concentratingAccount ?>>
									<label class="custom-control-label " for="concentratingAccount"><?= lang('PERMITS_CONCENTRATOR_ACCOUNT_REPORT'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="generateConsolid" class="permissions custom-control-input" type="checkbox" name="checkbox10" value=<?= $generateConsolid ?>>
									<label class="custom-control-label " for="generateConsolid"><?= lang('PERMITS_CONSOLID_GENERATE'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="stateAccount" class="permissions custom-control-input" type="checkbox" name="checkbox11" value=<?= $stateAccount ?>>
									<label class="custom-control-label " for="stateAccount"><?= lang('PERMITS_ACCOUNT_STATUS_REPORT'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="statusBulk" class="permissions custom-control-input" type="checkbox" name="checkbox12" value=<?= $statusBulk ?>>
									<label class="custom-control-label " for="statusBulk"><?= lang('PERMITS_STATUS_BULK_REPORT'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="rechargesMade" class="permissions custom-control-input" type="checkbox" name="checkbox13" value=<?= $rechargesMade ?>>
									<label class="custom-control-label " for="rechargesMade"><?= lang('PERMITS_DONE_REFILLS_REPORT'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="replacementReport" class="permissions custom-control-input" type="checkbox" name="checkbox14" value=<?= $replacementReport ?>>
									<label class="custom-control-label " for="replacementReport"><?= lang('PERMITS_REPLACEMENT_REPORT'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="rechargeWithCommissions" class="permissions custom-control-input" type="checkbox" name="checkbox15" value=<?= $rechargeWithCommissions ?>>
									<label class="custom-control-label " for="rechargeWithCommissions"><?= lang('PERMITS_CHARGE_OF_COMMISSIONS_REPORT'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="closingBalance" class="permissions custom-control-input" type="checkbox" name="checkbox16" value=<?= $closingBalance ?>>
									<label class="custom-control-label " for="closingBalance"><?= lang('PERMITS_CLOSING_BALANCE_REPORT'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="cardIssued" class="permissions custom-control-input" type="checkbox" name="checkbox17" value=<?= $cardIssued ?>>
									<label class="custom-control-label " for="cardIssued"><?= lang('PERMITS_ISSUED_CARD_REPORT'); ?></span>
									</label>
								</div>

								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="userEnterprise" class="permissions custom-control-input" type="checkbox" name="checkbox18" value=<?= $userEnterprise ?>>
									<label class="custom-control-label " for="userEnterprise"><?= lang('PERMITS_USER_ENTERPRISE_REPORT'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="balancesIssued" class="permissions custom-control-input" type="checkbox" name="checkbox19" value=<?= $balancesIssued ?>>
									<label class="custom-control-label " for="balancesIssued"><?= lang('PERMITS_BALANCES_DAWN'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="cardHolder" class="permissions custom-control-input" type="checkbox" name="checkbox20" value=<?= $cardHolder ?>>
									<label class="custom-control-label " for="cardHolder"><?= lang('PERMITS_CARDHOLDER_REPORT'); ?></span>
									</label>
								</div>
							</div>
							<div class="row mx-3 mb-1">
								<h4 class="col-12 pl-0 bold"><?= lang('GEN_MENU_USERS'); ?></h4>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="updateUser" class="permissions custom-control-input" type="checkbox" name="checkbox21" value=<?= $updateUser ?>>
									<label class="custom-control-label " for="updateUser"><?= lang('PERMITS_USER_UPDATE'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="assignPermit" class="permissions custom-control-input" type="checkbox" name="checkbox22" value=<?= $assignPermit ?>>
									<label class="custom-control-label " for="assignPermit"><?= lang('PERMITS_ASSIGMENT_OF_PERMITS'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="consultPermit" class="permissions custom-control-input" type="checkbox" name="checkbox23" value=<?= $consultPermit ?>>
									<label class="custom-control-label " for="consultPermit"><?= lang('PERMITS_CONSULT_OF_PERMITS'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="consultUser" class="permissions custom-control-input" type="checkbox" name="checkbox24" value=<?= $consultUser ?>>
									<label class="custom-control-label " for="consultUser"><?= lang('PERMITS_CONSULT_USER'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="createUser" class="permissions custom-control-input" type="checkbox" name="checkbox25" value=<?= $createUser ?>>
									<label class="custom-control-label " for="createUser"><?= lang('PERMITS_CREATE_USER'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="deletePermit" class="permissions custom-control-input" type="checkbox" name="checkbox26" value=<?= $deletePermit ?>>
									<label class="custom-control-label " for="deletePermit"><?= lang('PERMITS_DELETE_PERMITS'); ?></span>
									</label>
								</div>
							</div>
							<div class="row mx-3 mb-1">
								<h4 class="col-12 pl-0 bold"><?= lang('GEN_MENU_SERVICES'); ?></h4>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="consultStateOperation" class="permissions custom-control-input" type="checkbox" name="checkbox27" value=<?= $consultStateOperation ?>>
									<label class="custom-control-label " for="consultStateOperation"><?= lang('PERMITS_CONSULT_STATE_CARDS'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="updateCardTwirl" class="permissions custom-control-input" type="checkbox" name="checkbox28" value=<?= $updateCardTwirl ?>>
									<label class="custom-control-label " for="updateCardTwirl"><?= lang('PERMITS_UPDATE_CARD_TWIRLS'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="consultCardTwirl" class="permissions custom-control-input" type="checkbox" name="checkbox29" value=<?= $consultCardTwirl ?>>
									<label class="custom-control-label " for="consultCardTwirl"><?= lang('PERMITS_CONSULT_CARD_TWIRLS'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="updateCardLimit" class="permissions custom-control-input" type="checkbox" name="checkbox30" value=<?= $updateCardLimit ?>>
									<label class="custom-control-label " for="updateCardLimit"><?= lang('PERMITS_UPDATE_CARD_LIMITS'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="consultCardLimit" class="permissions custom-control-input" type="checkbox" name="checkbox31" value=<?= $consultCardLimit ?>>
									<label class="custom-control-label " for="consultCardLimit"><?= lang('PERMITS_CONSULT_CARD_LIMITS'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="issuancePolicy" class="permissions custom-control-input" type="checkbox" name="checkbox32" value=<?= $issuancePolicy ?>>
									<label class="custom-control-label " for="issuancePolicy"><?= lang('PERMITS_POLICY_ISSUANCE'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="creditCards" class="permissions custom-control-input" type="checkbox" name="checkbox33" value=<?= $creditCards ?>>
									<label class="custom-control-label " for="creditCards"><?= lang('PERMITS_CARD_PAYMENT'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="reassingCard" class="permissions custom-control-input" type="checkbox" name="checkbox34" value=<?= $reassingCard ?>>
									<label class="custom-control-label " for="reassingCard"><?= lang('PERMITS_CARD_REASSIGNMENT'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="cardLock" class="permissions custom-control-input" type="checkbox" name="checkbox35" value=<?= $cardLock ?>>
									<label class="custom-control-label " for="cardLock"><?= lang('PERMITS_CARD_LOCK'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="chargedCards" class="permissions custom-control-input" type="checkbox" name="checkbox36" value=<?= $chargedCards ?>>
									<label class="custom-control-label " for="chargedCards"><?= lang('PERMITS_CHARGES_CARDS'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="cardUnlock" class="permissions custom-control-input" type="checkbox" name="checkbox37" value=<?= $cardUnlock ?>>
									<label class="custom-control-label " for="cardUnlock"><?= lang('PERMITS_UNLOCK_CARDS'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="payConcentratorAccount" class="permissions custom-control-input" type="checkbox" name="checkbox38" value=<?= $payConcentratorAccount ?>>
									<label class="custom-control-label " for="payConcentratorAccount"><?= lang('PERMITS_PAY_CONCENTRATOR_ACCOUNT'); ?></span>
									</label>
								</div>
								<div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
									<input id="consultCardsTrasal" class="permissions custom-control-input" type="checkbox" name="checkbox39" value=<?= $consultCardsTrasal ?>>
									<label class="custom-control-label " for="consultCardsTrasal"><?= lang('PERMITS_CONSULT_CARDS'); ?></span>
									</label>
								</div>
							</div>
							<div class="flex row mb-2 mx-2 items-center justify-end">
								<a class="btn btn-link btn-small big-modal" href="<?= base_url('administracion-usuarios') ?>"><?= lang('GEN_BTN_CANCEL'); ?></a>
								<button id="updateUserBtn" class="btn btn-small btn-loading btn-primary" type="submit">
								<?= lang('GEN_BTN_UPDATE'); ?>
								</button>
							</div>
						</form>
						<div class="line mb-2"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if ($widget) : ?>
		<?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
	<?php endif; ?>
</div>
