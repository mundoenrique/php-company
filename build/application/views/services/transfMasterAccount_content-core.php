<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_SERV_MASTER_ACCOUNT'); ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
  <div class="flex tertiary">
    <nav class="main-nav nav-inferior">
      <ul class="mb-0 h6 light tertiary list-style-none list-inline">
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('CONF_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('CONF_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('CONF_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE') ?></a></li> /
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
      <?php if ($showRechargeAccount): ?>
      <div class="flex pb-3 flex-column w-100">
        <span class="line-text mb-2 h4 semibold primary">Recarga cuenta/tarjeta maestra </span>
        <div class="flex my-2 px-5">
          <form id="masterAccountRechargeForm" method="post" class="w-100">
            <p class="mr-5 mb-3 sh5 semibold tertiary"><?= $balanceText ?> <span class="light text"><?= $balance; ?></span>
						<?php if($reloadBalance): ?>
							<a href="javascript:">
								<span id="reload_balance" class="bold" title="Consultar saldo">
									<i class="icon-reload mr-0"></i>
								</span>
							</a>
						<?php endif; ?>
						</p>
            <div class="row" id="recharge_account">

							<?php if (lang('CONF_SELECT_ACCOUNT') == 'ON'): ?>
								<div class="form-group col-3">
									<label for="account" id="account"><?= lang('GEN_ACCOUNT'); ?></label>
									<input type="text" id="accountUser" name="accountUser" class="form-control px-1" value="<?= $fundingAccount; ?>" autocomplete="off"
										readonly disabled>
									<div class="help-block"></div>
								</div>
							<?php endif; ?>

              <?php if (lang('CONF_SELECT_TYPE') == 'ON'): ?>
								<input type="hidden" id='bloqueoForm' value="true" />
              <div class="form-group col-3">
                <div class="custom-option-c custom-radio custom-control-inline">
                  <input type="radio" id="debit" name="transferType" class="custom-option-input" value="cargo" disabled>
                  <label class="custom-option-label nowrap" for="debit"><?= lang('SERVICES_TYPE_CARGO'); ?></label>
                </div>
                <div class="custom-option-c custom-radio custom-control-inline">
                  <input type="radio" id="pay" name="transferType" class="custom-option-input" value="abono" disabled>
                  <label class="custom-option-label nowrap" for="pay"><?= lang('SERVICES_TYPE_ABONO'); ?></label>
                </div>
                <div class="help-block"></div>
              </div>
              <?php endif; ?>
              <div class="form-group col-3">
                <label for="transferAmount"><?= lang('GEN_TABLE_AMOUNT'); ?></label>
                <input id="transferAmount" class="form-control h5 text-right" type="text" placeholder="<?= '0'.lang('CONF_DECIMAL').'00'; ?>"
                  name="transferAmount" autocomplete="off" disabled>
                <div class="help-block"></div>
              </div>
							<?php if (lang('CONF_INPUT_DESCRIPTION') == 'ON') : ?>
              <div class="form-group col-3">
                <label for="description"><?= lang('GEN_DESCRIPTION'); ?></label>
                <input id="description" class="form-control h5" type="text" placeholder="Ingresa descripción" name="description" autocomlpete="off"
                  disabled>
                <div class="help-block"></div>
              </div>
							<?php endif; ?>
              <?php if (lang('CONF_INPUT_PASS') == 'ON') : ?>
              <div class="col-3 form-group mt-3 ml-auto">
                <div class="input-group">
                  <input id="passwordTranfer" name="password" class="form-control pwd-input pr-0 pwd" type="password" autocomplete="off"
                    placeholder="<?= lang('GEN_PLACE_PASSWORD'); ?>" disabled>
                  <div class="input-group-append">
                    <span id="pwd_action" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>">
                      <i class="icon-view mr-0"></i>
                    </span>
                  </div>
                </div>
                <div class="help-block bulk-select text-left"></div>
              </div>
              <?php endif; ?>
              <div class="col-3 mt-3 <?= $skipInputPass; ?>">
                <button id="masterAccountRechargeBtn" class="btn btn-primary btn-small btn-loading flex ml-auto">
                  <?= lang('GEN_BTN_TRANSFER'); ?>
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
          <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_TABLE_RESULTS'); ?></span>
          <div class="center mx-1">
            <div class="row flex py-2">
              <div class="col-4">
                <label><?= lang('SERVICES_AVAILABLE_BALANCE'); ?></label>
                <span id="balance-aviable" class="light block py-0"></span>
              </div>
              <?php if (lang('CONF_SECTION_COMMISSION') == 'ON'): ?>
              <div class="col-4">
                <label><?= lang('SERVICES_COMMISSION_TRANS'); ?></label>
                <span id="cost-trans" class="light text block py-0"></span>
              </div>
              <div class="col-4">
                <label><?= lang('SERVICES_COMMISSION_CONSULTATION'); ?></label>
                <span id="cost-inquiry" class="light text block py-0"></span>
              </div>
              <?php endif; ?>
              <?php if (lang('CONF_BALANCE_ACC_CONCENTRATOR') == 'ON'): ?>
              <div class="col-4">
                <label><?= $balanceAccountAdmin ?></label>
                <span id="balance-acc-concentrator" class="light text form-control py-0"></span>
              </div>
              <?php endif; ?>
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
                  <?php if (lang('CONF_REMOTE_AUTH') == 'OFF'): ?>
                  <div class="input-group">
                    <input id="passAction" name="password" class="form-control pwd-input pr-0 pwd" type="password" autocomplete="off"
                      placeholder="<?= lang('GEN_PLACE_PASSWORD'); ?>">
                    <div class="input-group-append">
                      <span id="pwd_action" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i
                          class="icon-view mr-0"></i></span>
                    </div>
                  </div>
                  <?php endif; ?>
                  <div class="help-block bulk-select text-left"></div>
                </div>
                <?php if($this->verify_access->verifyAuthorization('TRAMAE', 'TRASAL')): ?>
                <div class="col-auto">
                  <button id="Consulta" class="btn btn-primary btn-small btn-loading flex mx-auto" amount="0" action="CHECK_BALANCE">
                    Consultar
                  </button>
                </div>
                <?php endif; ?>
                <?php if($this->verify_access->verifyAuthorization('TRAMAE', 'TRAABO')): ?>
                <div class="col-auto">
                  <button id="Abono" class="btn btn-primary btn-small btn-loading flex mx-auto" amount="1" action="CREDIT_TO_CARD">
                    Abono
                  </button>
                </div>
                <?php endif; ?>
                <?php if($this->verify_access->verifyAuthorization('TRAMAE', 'TRACAR')): ?>
                <div class="col-auto">
                  <button id="Cargo" class="btn btn-primary btn-small btn-loading flex mx-auto" amount="1" action="DEBIT_TO_CARD">
                    Cargo
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
