<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_REP_CATEGORY_EXPENSE'); ?></h1>
<div class="mb-2 flex items-center">
  <div class="flex tertiary">
    <nav class="main-nav nav-inferior">
      <ul class="mb-0 h6 light tertiary list-style-none list-inline">
        <li class="inline">
          <a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>">
            <?= lang('GEN_MENU_ENTERPRISE'); ?>
          </a>
        </li> /
        <li class="inline">
          <a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>">
            <?= lang('GEN_PRODUCTS'); ?>
          </a>
        </li> /
        <li class="inline">
          <a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>">
            <?= lang('GEN_PRODUCTS_DETAIL_TITLE'); ?>
          </a>
        </li> /
        <li class="inline">
          <a class="tertiary not-pointer" href="<?= lang('SETT_NO_LINK') ?>">
            <?= lang('GEN_MENU_REPORTS'); ?>
          </a>
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
        <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SEARCH_CRITERIA') ?></span>
        <div class="flex my-2 px-5">
          <form id="cateExpenseForm" class="w-100">
            <div class="row flex ">
              <div class="form-group col-4 col-xl-3">
                <label><?= lang('GEN_ENTERPRISE'); ?></label>
                <select id="enterpriseCode" name="enterpriseCode" class="select-box custom-select flex h6 w-100 enterprise-getprod" disabled>
                  <?php foreach ($enterpriseList as $enterprise) : ?>
                    <option doc="<?= $enterprise->accodcia; ?>" name="<?= $enterprise->acrazonsocial; ?>" value="<?= $enterprise->acrif; ?>" <?= $enterprise->acrif === $enterpriseData->idFiscal ? 'selected' : '' ?> id-fiscal="<?= $enterprise->acrif; ?>">
                      <?= $enterprise->acnomcia; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <div class="help-block"></div>
              </div>
              <div class="form-group col-4 col-xl-3">
                <label><?= lang('GEN_PRODUCT'); ?></label>
                <select id="productCode" name="productCode" class="select-box custom-select flex h6 w-100" disabled>
                  <option selected disabled><?= $selectProducts ?></option>
                  <?php foreach ($productsSelect as $product) : ?>
                    <option doc="<?= $product['desc'] ?>" value="<?= $product['id']; ?>" <?= $product['id'] === $currentProd ? 'selected' : ''; ?>>
                      <?= $product['desc'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <div class="help-block"></div>
              </div>
              <div class="form-group col-4 col-xl-3">
                <label><?= lang('GEN_TABLE_CARD_NUMBER'); ?></label>
                <input type="text" id="cardNumber" name="cardNumber" class="form-control h5" autocomplete="off" req="yes" disabled>
                <div class="help-block"></div>
              </div>
              <div class="form-group col-4 col-xl-3">
                <label><?= lang('GEN_TABLE_DNI'); ?></label>
                <input type="text" id="idDocument" name="idDocument" class="form-control h5" autocomplete="off" req="yes" disabled>
                <div class="help-block"></div>
              </div>
              <div id="radio-form" class="form-group col-3">
                <label class="block"><?= lang('GEN_TABLE_RESULTS'); ?></label>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="annual" name="results" class="custom-control-input" disabled>
                  <label class="custom-control-label mr-1" for="annual">Anual</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="range" name="results" class="custom-control-input" checked disabled>
                  <label class="custom-control-label mr-1" for="range">Rango</label>
                </div>
                <div class="help-block"></div>
              </div>
              <div class="form-group col-4 col-lg-3 col-xl-3 year hide">
                <label for="yearDate">Año</label>
                <input type="text" id="yearDate" name="datepicker_year" class="form-control date-picker ignore" placeholder="<?= lang('GEN_PLACE_DATE_SHORT'); ?>" readonly disabled>
                <div class="help-block">
                </div>
              </div>
              <div class="form-group col-4 col-lg-3 col-xl-3 range">
                <label for="initialDate"><?= lang('GEN_START_DAY'); ?></label>
                <input type="text" id="initialDate" name="datepicker_start" class="form-control date-picker" placeholder="<?= lang('GEN_PLACE_DATE_COMPLETTE'); ?>" readonly disabled>
                <div class="help-block">
                </div>
              </div>
              <div class="form-group col-4 col-lg-3 col-xl-3 range">
                <label for="finalDate"><?= lang('GEN_END_DAY'); ?></label>
                <input type="text" id="finalDate" name="datepicker_end" class="form-control date-picker" placeholder="<?= lang('GEN_PLACE_DATE_COMPLETTE'); ?>" readonly disabled>
                <div class="help-block "></div>
              </div>
              <div class="flex items-center justify-end col-3 search-bnt">
                <button id="searchButton" type="submit" class="btn btn-primary btn-small" disabled>
                  <?= lang('GEN_BTN_SEARCH'); ?>
                </button>
              </div>
            </div>
          </form>
        </div>
        <div class="line mb-2"></div>
      </div>
      <div class="flex pb-5 flex-column">
        <div id="spinnerBlock" class=" hide">
          <div id="preLoader" class="mt-2 mx-auto flex justify-center">
            <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
          </div>
        </div>
        <span id="titleResults" class="line-text mb-2 h4 semibold primary hide"><?= lang('GEN_TABLE_RESULTS'); ?></span>
        <div id="blockResults" class="center mx-1 hide">
          <div class="flex">
            <div class="flex mr-2 py-3 flex-auto justify-end items-center">
              <div id="buttonFiles">
                <button class="btn px-1 big-modal" title="<?= lang('GEN_BTN_DOWN_XLS'); ?>" data-toggle="tooltip">
                  <i class="icon icon-file-excel" aria-hidden="true" type="xls"></i>
                </button>
                <button class="btn px-1 big-modal" title="<?= lang('GEN_BTN_DOWN_PDF'); ?>" data-toggle="tooltip">
                  <i class="icon icon-file-pdf" aria-hidden="true" type="pdf"></i>
                </button>
              </div>
            </div>
          </div>
          <table id="cateExpenseTable" class="cell-border h6 display responsive w-100">
            <thead class="bg-primary secondary regular">
              <tr>
                <th id="queryType" class="bold"></th>
                <?php foreach (lang('SETT_CATEG_GROUP') as $item => $value) : ?>
                  <th>
                    <span aria-hidden="true" class="<?= $value ?> h3" title="<?= lang('REPORTS_CATEG_GROUP')[$item] ?>" data-toggle="tooltip"></span>
                  </th>
                <?php endforeach; ?>
                <th class="bold">Total <?= lang('SETT_CURRENCY') ?></th>
              </tr>
            </thead>
          </table>
          <div class="line my-2"></div>
        </div>
        <div class="my-5 py-4 center none">
          <span class="h4"><?= lang('GEN_TABLE_NO_RESULTS'); ?></span>
        </div>
      </div>
    </div>
  </div>
  <?php if ($widget) : ?>
    <?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
  <?php endif; ?>
</div>