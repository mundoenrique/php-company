<?php defined('BASEPATH') OR exit('No direct script access alloewd'); ?>

<h1 class="primary h3 regular inline"><?= lang('GEN_SERVICE_ORDERS_TITLE'); ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="flex mb-2 items-center">
  <div class="flex tertiary">
    <nav class="main-nav nav-inferior">
      <ul class="mb-0 h6 light tertiary list-style-none list-inline">
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('CONF_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE'); ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('CONF_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS'); ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>"><?= lang('PRODUCTS_DETAIL_TITLE'); ?></a></li> /
        <li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_MENU_CONS_ORDERS_SERV'); ?></a></li>
      </ul>
    </nav>
  </div>
</div>

<div class="flex mt-1 bg-color flex-nowrap justify-between">
  <div id="pre-loader" class="mx-auto flex justify-center">
    <span class="spinner-border spinner-border-lg my-2" role="status" aria-hidden="true"></span>
  </div>
  <div class="w-100 hide-out hide">
    <div class="flex flex-auto flex-column <?= $widget ? '' : 'max-width-6';  ?>">
      <div class="search-criteria-order flex pb-3 flex-column w-100">
        <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SEARCH_CRITERIA'); ?></span>
        <div class="flex mt-2 mb-3 px-5">
          <form id="service-orders-form" method="post" class="w-100">
            <div class="row">
              <div class="form-group mr-auto col-3 col-lg-auto col-xl-auto">
                <div class="custom-option-c custom-radio custom-control-inline">
                  <input type="radio" id="five-days" name="days" class="custom-option-input" value="5">
                  <label class="custom-option-label nowrap" for="five-days"><?= lang('GEN_RANGE_ONE_DAYS'); ?></label>
                </div>
                <div class="custom-option-c custom-radio custom-control-inline">
                  <input type="radio" id="ten-days" name="days" class="custom-option-input" value="10">
                  <label class="custom-option-label nowrap" for="ten-days"><?= lang('GEN_RANGE_TWO_DAYS'); ?></label>
                </div>
                <div class="help-block"></div>
              </div>
              <div class="form-group col-4 col-lg-3 col-xl-auto">
                <label for="datepicker_start"><?= lang('GEN_START_DAY'); ?></label>
                <input id="datepicker_start" name="datepicker_start" class="form-control date-picker" type="text" placeholder="DD/MM/AAA" readonly>
                <div class="help-block"></div>
              </div>
              <div class="form-group col-4 col-lg-3 col-xl-auto">
                <label for="datepicker_end"><?= lang('GEN_END_DAY'); ?></label>
                <input id="datepicker_end" name="datepicker_end" class="form-control date-picker" type="text" placeholder="DD/MM/AAA" readonly>
                <div class="help-block "></div>
              </div>
              <div class="form-group col-4 col-lg-3 col-xl-3">
                <label><?= lang('GEN_TABLE_STATUS'); ?></label>
                <select id="status-order" name="status-order" class="select-box custom-select flex h6 w-100 form-control">
                  <?php foreach($orderStatus AS $pos => $value): ?>
                  <option value="<?= $value->key; ?>" <?= $pos != 0 ? '' : 'selected disabled' ?>>
                    <?= $value->text; ?>
                  </option>
                  <?php endforeach; ?>
                </select>
                <div class="help-block"></div>
              </div>
              <div class="col-xl-auto flex items-center ml-auto mr-2">
                <button id="service-orders-btn" class="btn btn-primary btn-small btn-loading">
                  <?= lang('GEN_BTN_SEARCH'); ?>
                </button>
              </div>
            </div>
          </form>
        </div>
        <div class="line mb-2"></div>
      </div>
      <?php if($renderOrderList): ?>
      <div id="loader-table" class="mt-2 mx-auto">
        <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
      </div>
      <div class="w-100 hide-table hide">
        <div class="flex pb-5 flex-column">
          <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SERVICE_ORDERS_TITLE'); ?></span>
          <div class="center mx-1">
            <table id="resultServiceOrders" class="cell-border h6 display"
              orderList="<?= htmlspecialchars(json_encode($orderList), ENT_QUOTES, 'UTF-8'); ?>">
              <thead class="bg-primary secondary regular">
                <tr>
                  <th><?= lang('GEN_TABLE_ORDER_NRO'); ?></th>
                  <th><?= lang('GEN_TABLE_DATE'); ?></th>
                  <th><?= lang('GEN_TABLE_COMMISSION'); ?></th>
                  <th><?= lang('GEN_TABLE_VAT'); ?></th>
                  <th><?= lang('GEN_TABLE_AMOUNT_SO'); ?></th>
                  <th><?= lang('GEN_TABLE_DEPOSIT_AMOUNT'); ?></th>
                  <th><?= lang('GEN_TABLE_OPTIONS'); ?></th>
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
                  <td class="p-0 nowrap">
                    <?php if(lang('CONF_SERVICEORDERS_ICON') == 'ON' && $list->warningEnabled == TRUE): ?>
										<span class="btn mx-1 px-0">
                      <i class="icon icon-warning warning not-pointer" aria-hidden="true"></i>
                    </span>
                    <?php endif; ?>
                    <?php if($this->verify_access->verifyAuthorization('TEBORS')):?>
                    <button class="btn mx-1 px-0 details-control" title="<?= lang('GEN_BTN_SEE'); ?>" data-toggle="tooltip">
                      <i class="icon icon-find" aria-hidden="true"></i>
                    </button>
                    <?php endif; ?>
                    <button class="btn mx-1 px-0 big-modal" title="<?= lang('GEN_BTN_DOWN_PDF'); ?>" data-toggle="tooltip">
                      <i class="icon icon-file-pdf" aria-hidden="true"></i>
                    </button>
                    <?php if($this->verify_access->verifyAuthorization('TEBORS', 'TEBANU') && $list->OrderVoidable): ?>
                    <button class="btn mx-1 px-0" title="<?= lang('GEN_BTN_CANCEL_ORDER'); ?>" data-toggle="tooltip">
                      <i class="icon icon-remove" aria-hidden="true"></i>
                    </button>
                    <?php endif; ?>
                    <form method="POST" action="<?= base_url('descargar-archivo'); ?>">
                      <input type="hidden" name="OrderNumber" value="<?= $list->OrderNumber; ?>">
                      <input type="hidden" name="who" value="Inquiries">
                      <input type="hidden" name="where" value="ExportFiles">
                    </form>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <div class="line my-2"></div>
          </div>

          <div class="my-5 py-4 center none">
            <span class="h4"><?= lang('GEN_WARNING_SERVICE_ORDERS'); ?>"</span>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <?php if($widget): ?>
  <?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
  <?php endif; ?>
</div>
