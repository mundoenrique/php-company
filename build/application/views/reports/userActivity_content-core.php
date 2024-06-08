<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_REP_USER_ACT'); ?></h1>
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
  <div id="preLoader" class="mt-2 mx-auto">
    <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
  </div>
  <div class="w-100 hide-out hide">
    <div class="flex flex-auto flex-column <?= $widget ? '' : 'max-width-6'; ?>">
      <div class="search-criteria-order flex pb-3 flex-column w-100">
        <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SEARCH_CRITERIA'); ?></span>
        <div class="flex mt-2 px-5">
          <form id="userActivityForm" class="w-100">
            <div class="row flex ">
              <div class="form-group col-4 col-lg-4 col-xl-3">
                <label for="enterpriseCode"><?= lang('GEN_ENTERPRISE'); ?></label>
                <select id="enterpriseCode" name="enterpriseCode" class="select-box custom-select mb-4 h6 w-100">
                  <?php foreach ($enterpriseList as $enterprise) : ?>
                    <option code="<?= $enterprise->accodcia; ?>" group="<?= $enterprise->accodgrupoe; ?>" nomOf="<?= $enterprise->acnomcia; ?>" acrif="<?= $enterprise->acrif; ?>" value="<?= $enterprise->accodcia; ?>" <?= $enterprise->acrif == $enterpriseData->idFiscal ? 'selected' : '' ?>>
                      <?= $enterprise->acnomcia; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <div class="help-block"></div>
              </div>
              <div class="form-group col-4 col-lg-3 col-xl-3">
                <label for="initialDate"><?= lang('GEN_START_DAY'); ?></label>
                <input id="initialDate" name="initialDate" class="form-control date-picker " type="text" placeholder="DD/MM/AAAA" readonly="" autocomplete="off">
                <div class="help-block">
                </div>
              </div>
              <div class="form-group col-4 col-lg-3 col-xl-3">
                <label for="finalDate"><?= lang('GEN_END_DAY'); ?></label>
                <input id="finalDate" name="finalDate" class="form-control date-picker " type="text" placeholder="DD/MM/AAAA" readonly="" required="required" autocomplete="off">
                <div class="help-block "></div>
              </div>
              <div class="flex items-center justify-end col-4 col-lg-4 col-xl-3 ml-auto mb-1">
                <button id="userActivityBtn" name="userActivityBtn" class="btn btn-primary btn-small mb-5" type="button">
                  <?= lang('GEN_BTN_SEARCH'); ?>
                </button>
              </div>
            </div>
          </form>
        </div>
        <div class="line mb-2"></div>
      </div>
      <div class="flex pb-5 flex-column">
        <span id="titleResults" class="line-text mb-2 h4 semibold primary"><?= lang('GEN_TABLE_RESULTS'); ?></span>
        <div id="spinnerBlock" class=" hide">
          <div id="preLoader" class="mt-2 mx-auto flex justify-center">
            <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
          </div>
        </div>
        <div id="blockResultsUser" class="center mx-1 hide">
          <div class="flex">
            <div id="buttonFiles" class="flex mr-2 py-3 flex-auto justify-end items-center">
              <?php if (lang('SETT_FILE_USER_ACTIVITY_TXT') === 'ON') : ?>
                <button id="exportTxt" format="Txt" class="big-modal btn px-1 downloadReport" title="Exportar a TXT" data-toggle="tooltip">
                  <i class="icon icon-file-txt" aria-hidden="true"></i>
                </button>
              <?php endif; ?>
              <button id="exportExcel" format="Excel" class="btn px-1 big-modal downloadReport" title="Exportar a EXCEL" data-toggle="tooltip">
                <i class="icon icon-file-excel" aria-hidden="true"></i>
              </button>
              <button id="exportPDF" format="PDF" class="btn px-1 big-modal downloadReport" title="Exportar a PDF" data-toggle="tooltip">
                <i class="icon icon-file-pdf" aria-hidden="true"></i>
              </button>
            </div>
          </div>
          <table id="usersActivity" class="cell-border h6 display responsive w-100">
            <thead class="bg-primary secondary regular">
              <tr>
                <th><?= lang('GEN_USER'); ?></th>
                <th><?= lang('GEN_TABLE_STATUS'); ?></th>
                <th><?= lang('GEN_TABLE_LAST_SESSION'); ?></th>
                <th><?= lang('GEN_TABLE_OPTIONS'); ?></th>
              </tr>
            </thead>
            <tbody id="usersActivityOptions"></tbody>
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