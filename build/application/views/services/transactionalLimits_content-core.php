<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_SERV_TRANS_LIMITS'); ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
  <div class="flex tertiary">
    <nav class="main-nav nav-inferior">
      <ul class="mb-0 h6 light tertiary list-style-none list-inline">
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE') ?></a></li> /
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
      <div class="search-criteria-order flex pb-3 flex-column w-100">
        <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SEARCH_CRITERIA'); ?></span>
        <div class="flex my-2 px-5">
          <form id="limitsForm" class="w-100">
            <div class="row flex justify-between">
              <div class="form-group col-4 col-xl-4">
                <label for="cardNumber"><?= lang('GEN_CARD_NUMBER'); ?></label>
                <input id="cardNumber" name="card-number" class="form-control h5 select-group" type="text" autocomplete="off" disabled>
                <div class="help-block"></div>
              </div>
              <div class="form-group col-4 col-xl-4">
              </div>
              <div class="flex items-center justify-end col-3">
                <?php if($this->verify_access->verifyAuthorization('LIMTRX', 'CONLIM') || $this->verify_access->verifyAuthorization('LIMTRX', 'ACTLIM')): ?>
                <button id="card-holder-btn" class="btn btn-primary btn-small btn-loading"><?= lang('GEN_BTN_SEARCH'); ?></button>
                <?php endif; ?>
              </div>
            </div>
          </form>
        </div>
        <div class="line mb-2"></div>
      </div>
      <div id="spinnerBlock" class="hide">
        <div id="pre-loader" class="mt-2 mx-auto flex justify-center">
          <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
        </div>
      </div>
      <div class="flex pb-5 px-2 flex-column" id="blockResults">
        <form id="limitsUpdateForm">
          <div class="flex flex-column">
            <div class="flex light items-center line-text mb-5">
              <div class="flex tertiary">
                <span class="inline h4 semibold primary">Resultados</span>
              </div>
              <div class="flex h6 flex-auto justify-end">
                <p class="h5 semibold tertiary">Fecha de actualización:
                  <span id="updateDate"></span>
                </p>
              </div>
            </div>
            <div class="row flex justify-between my-3">
              <div class="form-group col-4 center">
                <p class="h5 semibold tertiary"><?= lang('GEN_CARD_NUMBER'); ?>: <span class="light text" id="cardNumberP"></span></p>
              </div>
              <div class="form-group col-4 center">
                <p class="h5 semibold tertiary"><?= lang('GEN_TABLE_NAME'); ?>: <span class="light text" id="customerName"></span></p>
              </div>
              <div class="form-group col-4 center">
                <p class="h5 semibold tertiary"><?= lang('GEN_TABLE_DNI'); ?>: <span class="light text" id="documentId"></span></p>
              </div>
              <div class="form-group col-12 center">
                <p class="h6 bold mb-0 mt-2"><?= lang('GEN_NOTE'); ?> <span class="light text">Si el campo es igual a 0, se tomará como límite el
                    valor configurado para el producto.</span></p>
              </div>
            </div>
          </div>
          <div class="flex mb-5 flex-column">
            <span class="line-text slide-slow flex mb-2 h4 semibold primary">Con tarjeta presente
              <i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="false"></i>
            </span>
            <div class="section my-2 px-5">
              <div class="container">
                <div class="row">
                  <div class="col-10 bolck mx-auto">
                    <div class="row">
                      <div class="form-group col-12 col-lg-4">
                        <label class="pr-3" for="numberDayPurchasesCtp">Número de compras diarias</label>
                        <div class="input-group">
                          <input id="numberDayPurchasesCtp" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="numberDayPurchasesCtp" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="numberWeeklyPurchasesCtp">Número de compras semanales</label>
                        <div class="input-group">
                          <input id="numberWeeklyPurchasesCtp" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="numberWeeklyPurchasesCtp" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="numberMonthlyPurchasesCtp">Número de compras mensuales</label>
                        <div class="input-group">
                          <input id="numberMonthlyPurchasesCtp" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="numberMonthlyPurchasesCtp" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label class="pr-3" for="dailyPurchaseamountCtp">Monto diario de compras</label>
                        <div class="input-group">
                          <input id="dailyPurchaseamountCtp" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="dailyPurchaseamountCtp" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="weeklyAmountPurchasesCtp">Monto semanal de compras</label>
                        <div class="input-group">
                          <input id="weeklyAmountPurchasesCtp" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="weeklyAmountPurchasesCtp" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="monthlyPurchasesAmountCtp">Monto mensual de compras</label>
                        <div class="input-group">
                          <input id="monthlyPurchasesAmountCtp" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="monthlyPurchasesAmountCtp" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="purchaseTransactionCtp">Monto por transacción de compras</label>
                        <div class="input-group">
                          <input id="purchaseTransactionCtp" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="purchaseTransactionCtp" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="flex mb-5 flex-column">
            <span class="line-text slide-slow flex mb-2 h4 semibold primary">Sin tarjeta presente
              <i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="true"></i>
            </span>
            <div class="section my-2 px-5">
              <div class="container">
                <div class="row">
                  <div class="col-10 bolck mx-auto">
                    <div class="row">
                      <div class="form-group col-12 col-lg-4">
                        <label class="pr-3" for="numberDayPurchasesStp">Número de compras diarias</label>
                        <div class="input-group">
                          <input id="numberDayPurchasesStp" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="numberDayPurchasesStp" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="numberWeeklyPurchasesStp">Número de compras semanales</label>
                        <div class="input-group">
                          <input id="numberWeeklyPurchasesStp" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="numberWeeklyPurchasesStp" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="numberMonthlyPurchasesStp">Número de compras mensuales</label>
                        <div class="input-group">
                          <input id="numberMonthlyPurchasesStp" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="numberMonthlyPurchasesStp" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label class="pr-3" for="dailyPurchaseamountStp">Monto diario de compras</label>
                        <div class="input-group">
                          <input id="dailyPurchaseamountStp" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="dailyPurchaseamountStp" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="weeklyAmountPurchasesStp">Monto semanal de compras</label>
                        <div class="input-group">
                          <input id="weeklyAmountPurchasesStp" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="weeklyAmountPurchasesStp" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="monthlyPurchasesAmountStp">Monto mensual de compras</label>
                        <div class="input-group">
                          <input id="monthlyPurchasesAmountStp" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="monthlyPurchasesAmountStp" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="purchaseTransactionStp">Monto por transacción de compras</label>
                        <div class="input-group">
                          <input id="purchaseTransactionStp" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="purchaseTransactionStp" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="flex mb-5 flex-column ">
            <span class="line-text slide-slow flex mb-2 h4 semibold primary">Retiros
              <i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="true"></i>
            </span>
            <div class="section my-2 px-5">
              <div class="container">
                <div class="row">
                  <div class="col-10 bolck mx-auto">
                    <div class="row">
                      <div class="form-group col-12 col-lg-4">
                        <label class="pr-3" for="dailyNumberWithdraw">Número diario de retiros</label>
                        <div class="input-group">
                          <input id="dailyNumberWithdraw" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="dailyNumberWithdraw" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="weeklyNumberWithdraw">Número semanal de retiros</label>
                        <div class="input-group">
                          <input id="weeklyNumberWithdraw" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="weeklyNumberWithdraw" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="monthlyNumberWithdraw">Número mensual de retiros</label>
                        <div class="input-group">
                          <input id="monthlyNumberWithdraw" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="monthlyNumberWithdraw" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label class="pr-3" for="dailyAmountWithdraw">Monto diario de retiros</label>
                        <div class="input-group">
                          <input id="dailyAmountWithdraw" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="dailyAmountWithdraw" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="weeklyAmountWithdraw">Monto semanal de retiros</label>
                        <div clxs="input-group">
                          <input id="weeklyAmountWithdraw" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="weeklyAmountWithdraw" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="monthlyAmountwithdraw">Monto mensual de retiros</label>
                        <div class="input-group">
                          <input id="monthlyAmountwithdraw" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="monthlyAmountwithdraw" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="WithdrawTransaction">Monto por transacción de retiros</label>
                        <div class="input-group">
                          <input id="WithdrawTransaction" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="WithdrawTransaction" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="flex mb-5 flex-column ">
            <span class="line-text slide-slow flex mb-2 h4 semibold primary">Abonos
              <i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="true"></i>
            </span>
            <div class="section my-2 px-5">
              <div class="container">
                <div class="row">
                  <div class="col-10 bolck mx-auto">
                    <div class="row">
                      <div class="form-group col-12 col-lg-4">
                        <label class="pr-3" for="dailyNumberCredit">Número diario de abonos</label>
                        <div class="input-group">
                          <input id="dailyNumberCredit" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="dailyNumberCredit" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="weeklyNumberCredit">Número semanal de abonos</label>
                        <div class="input-group">
                          <input id="weeklyNumberCredit" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="weeklyNumberCredit" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="monthlyNumberCredit">Número mensual de abonos</label>
                        <div class="input-group">
                          <input id="monthlyNumberCredit" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="monthlyNumberCredit" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label class="pr-3" for="dailyAmountCredit">Monto diario de abonos</label>
                        <div class="input-group">
                          <input id="dailyAmountCredit" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="dailyAmountCredit" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="weeklyAmountCredit">Monto semanal de abonos</label>
                        <div clxs="input-group">
                          <input id="weeklyAmountCredit" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="weeklyAmountCredit" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="monthlyAmountCredit">Monto mensual de abonos</label>
                        <div class="input-group">
                          <input id="monthlyAmountCredit" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="monthlyAmountCredit" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group col-12 col-lg-4">
                        <label for="CreditTransaction">Monto por transacción de abonos</label>
                        <div class="input-group">
                          <input id="CreditTransaction" class="money form-control pwd-input text-right" type="text" autocomplete="off"
                            name="CreditTransaction" disabled maxlength="9">
                        </div>
                        <div class="help-block"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="flex row mt-3 mb-2 mx-2 justify-end">
            <div class="col-5 col-lg-3 col-xl-3 form-group">
              <?php if (lang('SETT_REMOTE_AUTH') == 'OFF'): ?>
              <div class="input-group">
                <input id="passwordAuth" name="password" class="form-control pwd-input pr-0" type="password" autocomplete="off"
                  placeholder="Contraseña" disabled>
                <div class="input-group-append">
                  <span id="pwd_action" class="input-group-text pwd-action" title="Mostrar contraseña">
                    <i class="icon-view mr-0"></i>
                  </span>
                </div>
              </div>
              <div class="help-block bulk-select text-left"></div>
              <?php endif; ?>
            </div>
            <div class="col-auto">
              <?php if($this->verify_access->verifyAuthorization('LIMTRX', 'ACTLIM')): ?>
              <button id="sign-btn" class="btn btn-primary btn-small btn-loading flex mx-auto">Actualizar</button>
              <?php endif; ?>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
  <?php if($widget): ?>
  <?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
  <?php endif; ?>
</div>
