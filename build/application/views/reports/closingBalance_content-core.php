<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_REP_CLOSING_BAKANCE'); ?></h1>

<div class="mb-2 flex items-center">
  <div class="flex tertiary">
    <nav class="main-nav nav-inferior">
      <ul class="mb-0 h6 light tertiary list-style-none list-inline">
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE'); ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS'); ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE'); ?></a></li> /
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
        <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SEARCH_CRITERIA') ?></span>
        <div class="flex my-2 px-5">
          <form id="closingBudgetForm" class="w-100">
            <div class="row flex flex items-center justify-start col-sm-12">
              <div class="form-group <?= lang('SETT_SETT_STYLE_SKIN') ?>">
                <label><?= lang('GEN_ENTERPRISE') ?></label>
                <select id="enterpriseReport" name="enterpriseReport" class="select-box custom-select mt-1 mb-1 h6 w-100">
                  <?php foreach ($enterpriseList as $enterprise) : ?>
                    <option code="<?= $enterprise->accodcia; ?>" group="<?= $enterprise->accodgrupoe; ?>" nomOf="<?= $enterprise->acnomcia; ?>" acrif="<?= $enterprise->acrif; ?>" value="<?= $enterprise->accodcia; ?>" <?= $enterprise->acrif == $enterpriseData->idFiscal ? 'selected' : '' ?>>
                      <?= $enterprise->acnomcia; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <div class="help-block"></div>
                <input id="tamP" name="tam-p" class="hide" value="<?= $tamP ?>">
              </div>
              <div class="form-group <?= lang('SETT_SETT_STYLE_SKIN') ?>">
                <label><?= lang('GEN_PRODUCT') ?></label>
                <select id="productCode" name="productCode" class="select-box custom-select flex h6 w-100">
                  <?php if ($productsSelect) : ?>
                    <?php foreach ($productsSelect as $product) : ?>
                      <option value="<?= $product['id']; ?>" nomProd="<?= $product['desc'] ?>" <?= $product['id'] == $currentProd ? 'selected' : ''; ?>><?= $product['desc'] ?></option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
                <input id="errProd" name="err-prod" class="hide" value="<?= $prod ?>">
                <div class="help-block"></div>
              </div>

              <?php if (lang('SETT_NIT_INPUT_BOOL') === 'ON') : ?>
                <div class="form-group <?= lang('SETT_SETT_STYLE_SKIN') ?>">
                  <label><?= lang('GEN_DOCUMENT_ID') ?> (Opcional)</label>
                  <input type="text" id="idDocument" class="form-control h5" name="idDocument">
                  <div class="help-block"></div>
                </div>
              <?php endif; ?>

              <input type="hidden" id="typeDownload" name="typeDownload" value="<?= lang('SETT_DOWNLOAD_SERVER') ?>">

              <div class="flex items-center justify-end col-auto ml-auto">
                <button id="closingBudgetsBtn" class="btn btn-primary btn-small">
                  Buscar
                </button>
              </div>
            </div>
          </form>
        </div>
        <div class="line mb-2"></div>
      </div>

      <div id="blockResult" class="flex pb-5 flex-column ">
        <span id="titleResults" class="line-text mb-2 h4 semibold primary">Resultados</span>
        <div id="spinnerBlock" class=" hide">
          <div id="pre-loader" class="mt-2 mx-auto flex justify-center">
            <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
          </div>
        </div>
        <div id="blockBudgetResults" class="center mx-1 ">
          <div id="block-btn-excel" class="flex mr-2 py-3 justify-end items-center hide">
            <div class="cover-spin"></div>
            <?php if (lang('SETT_FILE_CLOSE_BALANCE_TXT') === 'ON') : ?>
              <button id="export_txt" class="big-modal btn px-1" title="Exportar a TXT" data-toggle="tooltip">
                <i class="icon icon-file-txt" aria-hidden="true"></i>
              </button>
            <?php endif; ?>
            <button id="export_excel" class="btn px-1 big-modal" title="Exportar a EXCEL" data-toggle="tooltip">
              <i class="icon icon-file-excel" aria-hidden="true"></i>
            </button>
            <?php if (lang('SETT_FILE_CLOSE_BALANCE_PDF') === 'ON') : ?>
              <button id="export_pdf" class="big-modal btn px-1" title="Exportar a PDF" data-toggle="tooltip">
                <i class="icon icon-file-pdf" aria-hidden="true"></i>
              </button>
            <?php endif; ?>
          </div>
          <table id="balancesClosing" class="cell-border h6 display w-100">
            <thead class="bg-primary secondary regular">
              <tr>
                <th><?= lang('REPORTS_TABLE_CARD') ?></th>
                <th><?= lang('REPORTS_TABLE_CARDHOLDER') ?></th>
                <th><?= lang('GEN_DOCUMENT_ID') ?></th>
                <th><?= lang('REPORTS_TABLE_BALANCE') ?></th>
                <?php if (lang('SETT_CLOSING_BALANCE_BOOL') === 'ON') : ?>
                  <th><?= lang('REPORTS_TABLE_LAST_ACTIVITY') ?></th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody id="tbody-datos-general" class="tbody-reportes">
            </tbody>
          </table>
          <div id="hid" class=" hide">
            <div id="pre-loader" class="mt-2 mx-auto flex justify-center">
              <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
            </div>
          </div>
          <div class="line my-2"></div>
        </div>
        <div class="my-5 py-4 center none">
          <span class="h4">No se encontraron registros</span>
        </div>
      </div>
    </div>
  </div>
  <?php if ($widget) : ?>
    <?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
  <?php endif; ?>
</div>