<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_REP_ISSUED_CARDS'); ?></h1>
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
          <a class="tertiary not-pointer" href="<?= lang('SETT_NO_LINK'); ?>"><?= lang('GEN_MENU_REPORTS'); ?></a>
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
        <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SEARCH_CRITERIA'); ?></span>
        <div class="flex my-2 px-5">
          <form id="issued-cards-form" action="<?= base_url(lang('SETT_LINK_ISSUED_CARDS')); ?>" method="post" class="w-100">
            <div class="row flex justify-between">
              <div class="form-group col-6 col-lg-4 col-xl-4">
                <label><?= lang('GEN_ENTERPRISE') ?></label>
                <select id="enterpriseCode" name="enterpriseCode" class="select-box custom-select flex h6 w-100 enterprise-getprod">
                  <?php foreach($enterpriseList AS $enterprise) : ?>
                  <option value="<?= $enterprise->accodcia ?>" <?= $enterprise->acrif == $enterpriseData->idFiscal ? 'selected' : '' ?>
                    id-fiscal="<?= $enterprise->acrif; ?>">
                    <?= $enterprise->acnomcia; ?>
                  </option>
                  <?php endforeach; ?>
                </select>
                <div class="help-block"></div>
              </div>
              <?php if (lang('SETT_ISSUED_MONTHLY') == 'ON'): ?>
              <div class="form-group col-4 col-lg-3 col-xl-3">
                <label for="monthYear"><?= lang('GEN_TABLE_DATE'); ?></label>
                <input type="text" id="monthYear" name="monthYear" class="form-control" placeholder="<?= lang('GEN_PLACE_DATE_MEDIUM'); ?>" readonly>
                <div class="help-block"></div>
              </div>
              <?php else: ?>
              <div class="form-group col-4 col-lg-3 col-xl-3">
                <label for="initDate"><?= lang('GEN_START_DAY'); ?></label>
                <input id="initDate" name="initDate" class="form-control date-picker" type="text"
                  placeholder="<?= lang('GEN_PLACE_DATE_COMPLETTE'); ?>" readonly>
                <div class="help-block">
                </div>
              </div>
              <div class="form-group col-4 col-lg-3 col-xl-3">
                <label for="finalDate"><?= lang('GEN_END_DAY'); ?></label>
                <input id="finalDate" name="finalDate" class="form-control date-picker" type="text"
                  placeholder="<?= lang('GEN_PLACE_DATE_COMPLETTE'); ?>" readonly>
                <div class="help-block "></div>
              </div>
              <?php endif; ?>
              <div class="form-group col-6 col-lg-4 col-xl-4">
                <label class="block"><?= lang('GEN_QUERY_TYPE') ?></label>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="all" name="selection" class="form-control custom-control-input" value="0">
                  <label class="custom-control-label mr-1" for="all"><?= lang('GEN_BTN_ALL') ?></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="products" name="selection" class="form-control custom-control-input" value="1">
                  <label class="custom-control-label mr-1" for="products"><?= lang('GEN_PRODUCTS') ?></label>
                </div>
								<div class="help-block "></div>
              </div>

              <div class="flex col-xl-auto items-center ml-auto mr-2">
                <button type="submit" id="issued-cards-btn" class="btn btn-primary btn-small btn-loading">
                  <?= lang('GEN_BTN_SEARCH'); ?>
                </button>
              </div>
            </div>
          </form>
        </div>
        <div class="line mb-2"></div>
      </div>

      <div class="flex">
        <div id="pre-loader-table" class="mt-2 mx-auto hide">
          <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
        </div>
      </div>

      <div class="w-100 issued-cards-result hide">
        <div class="flex pb-5 flex-column">
          <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_TABLE_RESULTS') ?></span>
          <form id="download-issuedcards"></form>

          <div id="downloads" class="center mx-1">
            <div class="flex">
              <div class="flex mr-2 py-3 flex-auto justify-end items-center">
                <div class="download-icons">
                  <button class="btn px-1 big-modal" title="<?= lang('GEN_BTN_DOWN_XLS'); ?>" data-toggle="tooltip" format="xls">
                    <i class="icon icon-file-excel" aria-hidden="true"></i>
                  </button>
                  <button class="btn px-1 big-modal" title="<?= lang('GEN_BTN_DOWN_PDF'); ?>" data-toggle="tooltip" format="pdf">
                    <i class="icon icon-file-pdf" aria-hidden="true"></i>
                  </button>
                  <?php if (FALSE): ?>
                  <button class="btn px-1 big-modal" title="<?= lang('GEN_BTN_SEE_GRAPH'); ?>" data-toggle="tooltip">
                    <i class="icon icon-graph" aria-hidden="true"></i>
                  </button>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          <div id="issued-cards-table"></div>
          <div class="line my-2"></div>
        </div>
      </div>
    </div>
  </div>
  <?php if($widget): ?>
  <?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
  <?php endif; ?>
</div>
