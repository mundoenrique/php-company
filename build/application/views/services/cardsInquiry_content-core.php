<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_SERV_CARD_INQUIRY'); ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
  <div class="flex tertiary">
    <nav class="main-nav nav-inferior">
      <ul class="mb-0 h6 light tertiary list-style-none list-inline">
        <li class="inline">
          <a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE'); ?></a>
        </li> /
        <li class="inline">
          <a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS'); ?></a>
        </li> /
        <li class="inline">
          <a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE'); ?></a>
        </li> /
        <li class="inline">
          <a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_MENU_SERVICES'); ?></a>
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
    <div class="flex flex-auto flex-column">
      <div class="search-criteria-order flex pb-3 flex-column w-100">
        <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SEARCH_CRITERIA'); ?></span>
        <div class="flex mt-2 mb-3 px-5">
          <form id="searchCardsForm" class="w-100">
            <div class="row flex justify-between">
              <div class="form-group col-4 col-xl-3">
                <label for="orderNumber"><?= lang('GEN_ORDER_TITLE'); ?></label>
                <input id="orderNumber" name="orderNumber" class="form-control h5 select-group" type="text" autocomplete="off" disabled>
                <div class="help-block mb-1"></div>
              </div>
              <div class="form-group col-4 col-xl-3">
                <label for="bulkNumber"><?= lang('GEN_BULK_NUMBER'); ?></label>
                <input id="bulkNumber" name="bulkNumber" class="form-control h5 select-group" type="text" autocomplete="off" disabled>
                <div class="help-block mb-1"></div>
              </div>
              <?php if(lang('SETT_INQUIRY_DOCTYPE') == 'ON'): ?>
              <div class="form-group col-4 col-xl-3">
                <label for="docType"><?= lang('GEN_DOCUMENT_TYPE'); ?></label>
                <select id="docType" name="docType" class="form-control select-box custom-select flex h6 w-100">
                  <?php foreach (lang('GEN_RECOVER_DOC_TYPE') AS $key => $value): ?>
                  <option value="<?= $key ?>"><?= $value ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="help-block mb-1"></div>
              </div>
              <?php endif;?>
              <div class="form-group col-4 col-xl-3">
                <label for="idNumberP"><?= lang('GEN_TABLE_DOCUMENT_NUMBER'); ?></label>
                <input id="idNumberP" name="idNumberP" class="form-control h5 select-group" type="text" autocomplete="off" disabled>
                <div class="help-block mb-1"></div>
              </div>
              <div class="form-group col-4 col-xl-3">
                <label for="cardNumberP"><?= lang('GEN_CARD_NUMBER'); ?></label>
                <input id="cardNumberP" name="cardNumberP" class="form-control h5 select-group" type="text" autocomplete="off" disabled>
                <div class="help-block mb-1"></div>
              </div>
              <div class="flex col-xl-auto items-center ml-auto mr-2">
                <button id="searchCardsBtn" class="btn btn-primary btn-small btn-loading">
                  <?= lang('GEN_BTN_SEARCH'); ?>
                </button>
              </div>
            </div>
          </form>
        </div>
        <div class="line mb-2"></div>
      </div>
      <div class="flex flex-nowrap justify-between">
        <div id="loader-table" class="mt-2 mx-auto hide">
          <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
        </div>
      </div>
      <div class="w-100 hide-table hide">
        <div class="flex pb-5 flex-column">
          <span class="line-text mb-2 h4 semibold primary">Resultados</span>
          <div class="center mx-1">
            <div class="download-icons">
              <div class="flex mr-2 py-3 flex-auto justify-end items-center">
                <button class="btn px-1" title="<?= lang('GEN_BTN_DOWN_XLS') ?>" data-toggle="tooltip">
                  <i class="icon icon-file-excel" aria-hidden="true"></i>
                </button>
              </div>
            </div>

            <table id="tableCardInquiry" class="cell-border h6 display responsive w-100">
              <thead class="bg-primary secondary regular">
                <tr>
                  <th class="toggle-all"></th>
                  <th><?= lang('GEN_EMAIL'); ?></th>
                  <th><?= lang('GEN_TABLE_MOVIL_NUMBER') ?></th>
                  <th><?= lang('GEN_TABLE_NAME'); ?></th>
                  <th><?= lang('GEN_TABLE_LASTNAME') ?></th>
                  <th><?= lang('GEN_TABLE_DNI') ?></th>
                  <th><?= lang('GEN_TABLE_CARD_NUMBER'); ?></th>
                  <th><?= lang('GEN_TABLE_ORDER_NRO'); ?></th>
                  <th><?= lang('GEN_TABLE_BULK_NUMBER'); ?></th>
                  <th><?= lang('GEN_TABLE_EMISSION_STATUS'); ?></th>
                  <th><?= lang('GEN_TABLE_PLASTIC_STATUS'); ?></th>
                  <th><?= lang('GEN_TABLE_FULL_NAME'); ?></th>
                  <th><?= lang('GEN_TABLE_DNI'); ?></th>
                  <th><?= lang('GEN_TABLE_BALANCE'); ?></th>
                  <th><?= lang('GEN_TABLE_OPTIONS'); ?></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>

            <form id="cardsInquiryForm" name="cardsInquiryForm">
              <div class="flex row mt-3 mb-2 mx-2 justify-end">
                <div class="col-4 col-lg-3 h6 regular form-group">
                  <select id="masiveOptions" name="masiveOptions" class="form-control select-box custom-select flex h6 w-100"></select>
                  <div class="help-block item-select text-left"></div>
                </div>
								<?php if (lang('SETT_REMOTE_AUTH') == 'OFF'): ?>
                <div class="col-4 col-lg-3 col-xl-3 form-group">
                  <div class="input-group">
                    <input id="passAction" name="password" class="form-control pwd-input pr-0 pwd" type="password" autocomplete="off"
                      placeholder="<?= lang('GEN_PLACE_PASSWORD'); ?>">
                    <div class="input-group-append">
                      <span id="pwd_action" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>">
                        <i class="icon-view mr-0"></i>
                      </span>
                    </div>
                  </div>
                  <div class="help-block text-left"></div>
                </div>
								<?php endif; ?>
                <div class="col-3 col-lg-auto">
                  <button id="cardsInquiryBtn" class="btn btn-primary btn-small btn-loading flex mx-auto">
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
  </div>
</div>
