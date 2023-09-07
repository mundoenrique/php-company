<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_REP_STATUS_MASTER_ACCOUNT'); ?></h1>
<div class="mb-2 flex items-center">
  <div class="flex tertiary">
    <nav class="main-nav nav-inferior">
      <ul class="mb-0 h6 light tertiary list-style-none list-inline">
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE'); ?></a>
        </li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS'); ?></a></li> /
        <li class="inline"><a class="tertiary big-modal"
            href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE'); ?></a></li> /
        <li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_MENU_REPORTS'); ?></a></li>
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
        <span class="line-text mb-2 h4 semibold primary">Criterio de búsqueda</span>
        <div class="flex my-2 px-5">
          <form id="statusAccountForm" class="w-100">
            <div class="row flex justify-between">
              <div class="form-group col-4">
                <label><?= lang('GEN_ENTERPRISE'); ?></label>
                <select id="enterpriseCode" name="enterpriseCode" class="select-box custom-select flex h6 w-100 enterprise-getprod">
                  <?php foreach($enterpriseList AS $enterprise) : ?>
                  <?php if($enterprise->acrif == $enterpriseData->idFiscal): ?>
                  <?php endif;?>
                  <option doc="<?= $enterprise->accodcia; ?>" name="<?= $enterprise->acrazonsocial; ?>" value="<?= $enterprise->acrif; ?>"
                    <?= $enterprise->acrif == $enterpriseData->idFiscal ? 'selected' : '' ?> id-fiscal="<?= $enterprise->acrif; ?>">
                    <?= $enterprise->acnomcia; ?>
                  </option>
                  <?php endforeach; ?>
                </select>
                <div class="help-block"></div>
              </div>
              <div class="form-group col-4">
                <label for="initialDateAct"><?= lang('GEN_START_DAY'); ?></label>
                <input id="initialDateAct" name="selected-month-year" class="form-control date-picker " type="text" placeholder="MM/AAAA" readonly=""
                  autocomplete="off">
                <div class="help-block"></div>
              </div>
              <div class="flex items-center justify-end col-3">
                <button id="searchButton" class="flex items-baseline btn btn-link btn-small">
                  <i aria-hidden="true" class="icon icon-download"></i>
                  &nbsp;<?= lang('GEN_BTN_DOWNLOAD'); ?>
                </button>
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
    </div>
  </div>
  <?php if($widget): ?>
  <?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
  <?php endif; ?>
</div>
