<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_REP_CATEGORY_EXPENSE'); ?></h1>
<div class="mb-2 flex items-center">
  <div class="flex tertiary">
    <nav class="main-nav nav-inferior">
      <ul class="mb-0 h6 light tertiary list-style-none list-inline">
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE'); ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS'); ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE'); ?></a></li> /
        <li class="inline"><a class="tertiary not-pointer" href="<?= lang('SETT_NO_LINK') ?>"><?= lang('GEN_MENU_REPORTS'); ?></a></li>
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
        <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SEARCH_CRITERIA') ?>"></span>
        <div class="flex my-2 px-5">
          <form id="categoryExpenseForm" class="w-100">
            <div class="row flex ">
              <div class="form-group col-4 col-xl-3">
                <label><?= lang('GEN_ENTERPRISE'); ?></label>
                <select id="enterpriseCode" name="enterpriseCode" class="select-box custom-select flex h6 w-100 enterprise-getprod">
                  <?php foreach ($enterpriseList as $enterprise) : ?>
                    <?php if ($enterprise->acrif === $enterpriseData->idFiscal) : ?>
                    <?php endif; ?>
                    <option doc="<?= $enterprise->accodcia; ?>" name="<?= $enterprise->acrazonsocial; ?>" value="<?= $enterprise->acrif; ?>" <?= $enterprise->acrif === $enterpriseData->idFiscal ? 'selected' : '' ?> id-fiscal="<?= $enterprise->acrif; ?>">
                      <?= $enterprise->acnomcia; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <div class="help-block"></div>
              </div>
              <div class="form-group col-4 col-xl-3">
                <label><?= lang('GEN_PRODUCT'); ?></label>
                <select id="productCode" name="productCode" class="select-box custom-select flex h6 w-100">
                  <option selected disabled><?= $selectProducts ?></option>
                  <?php if ($productsSelect) : ?>
                    <?php foreach ($productsSelect as $product) : ?>
                      <option doc="<?= $product['desc'] ?>" value="<?= $product['id']; ?>" <?= $product['id'] === $currentProd ? 'selected' : ''; ?>><?= $product['desc'] ?></option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
                <div class="help-block"></div>
              </div>
              <div class="form-group col-4 col-xl-3">
                <label><?= lang('GEN_TABLE_CARD_NUMBER'); ?></label>
                <input id="cardNumber" name="cardNumber" class="form-control h5" type="text" autocomplete="off">
                <div class="help-block"></div>
              </div>
              <div class="form-group col-4 col-xl-3">
                <label><?= lang('GEN_TABLE_DNI'); ?></label>
                <input id="idNumber" name="idNumber" class="form-control h5" type="text" autocomplete="off">
                <div class="help-block"></div>
              </div>
              <div id="radio-form" class="form-group col-3">
                <label class="block"><?= lang('GEN_TABLE_RESULTS'); ?></label>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="annual" name="results" class="custom-control-input" value="all">
                  <label class="custom-control-label mr-1" for="annual">Anual</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="range" name="results" class="custom-control-input" value="all">
                  <label class="custom-control-label mr-1" for="range">Rango</label>
                </div>
                <div class="help-block"></div>
              </div>
              <div class="form-group col-4 col-lg-3 col-xl-3 year hide">
                <label for="yearDate">Año</label>
                <input id="yearDate" name="datepicker_start" class="form-control date-picker" type="text" placeholder="<?= lang('GEN_PLACE_DATE_SHORT'); ?>" readonly required>
                <div class="help-block">
                </div>
              </div>
              <div class="form-group col-4 col-lg-3 col-xl-3 range">
                <label for="initialDate"><?= lang('GEN_START_DAY'); ?></label>
                <input id="initialDate" name="datepicker_start" class="form-control date-picker" type="text" placeholder="<?= lang('GEN_PLACE_DATE_COMPLETTE'); ?>" readonly required>
                <div class="help-block">
                </div>
              </div>
              <div class="form-group col-4 col-lg-3 col-xl-3 range">
                <label for="finalDate"><?= lang('GEN_END_DAY'); ?></label>
                <input id="finalDate" name="datepicker_end" class="form-control date-picker" type="text" placeholder="<?= lang('GEN_PLACE_DATE_COMPLETTE'); ?>" readonly required>
                <div class="help-block "></div>
              </div>
              <div class="flex items-center justify-end col-3 search-bnt">
                <button id="searchButton" type="button" class="btn btn-primary btn-small">
                  <?= lang('GEN_BTN_SEARCH'); ?>
                </button>
              </div>
            </div>
          </form>
        </div>
        <div class="line mb-2"></div>
      </div>
      <div class="flex">
        <div id="spinnerBlock" class="mt-2 mx-auto hide">
          <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
        </div>
      </div>
      <div id="categoryExpense" class="flex pb-5 flex-column">
        <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_TABLE_RESULTS'); ?></span>
        <div class="center mx-1">
          <div class="flex mr-2 py-3 justify-end items-center">
            <button id="export_excel" class="big-modal btn px-1" title="Exportar a EXCEL" data-toggle="tooltip">
              <i class=" icon icon-file-excel" aria-hidden="true"></i>
            </button>
            <?php if (FALSE) : ?>
              <button id="export_pdf" class="big-modal btn px-1" title="Exportar a PDF" data-toggle="tooltip">
                <i class="icon icon-file-pdf" aria-hidden="true"></i>
              </button>
              <button class="btn px-1" title="Generar gráfica" data-toggle="tooltip">
                <i class="icon icon-chart-pie" aria-hidden="true"></i>
              </button>

              <button class="btn px-1" title="Generar Comprobante Masivo" data-toggle="tooltip">
                <i class="icon icon-file-blank" aria-hidden="true"></i>
              </button>
            <?php endif; ?>
          </div>
          <div id="category-expense-table"></div>
          <div id="spinnerResults" class="mt-2 mx-auto hide">
            <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
          </div>
          <div class="line my-2"></div>
        </div>
      </div>
    </div>
  </div>
  <?php if ($widget) : ?>
    <?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
  <?php endif; ?>
</div>