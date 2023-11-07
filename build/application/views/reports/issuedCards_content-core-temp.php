<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_REP_ISSUED_CARDS'); ?></h1>
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
          <a class="tertiary not-pointer" href="<?= lang('SETT_NO_LINK'); ?>">
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
        <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SEARCH_CRITERIA'); ?></span>
        <div class="flex my-2 px-5">
          <form id="issued-cards-form" action="<?= base_url(lang('SETT_LINK_ISSUED_CARDS')); ?>" method="post" class="w-100">
            <div class="row flex justify-between">
              <div class="form-group col-6 col-lg-4 col-xl-4">
                <label><?= lang('GEN_ENTERPRISE') ?></label>
                <select id="enterpriseCode" name="enterpriseCode" class="select-box custom-select flex h6 w-100 enterprise-getprod">
                  <?php foreach ($enterpriseList as $enterprise) : ?>
                    <option value="<?= $enterprise->accodcia ?>" <?= $enterprise->acrif == $enterpriseData->idFiscal ? 'selected' : '' ?> id-fiscal="<?= $enterprise->acrif; ?>">
                      <?= $enterprise->acnomcia; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <div class="help-block"></div>
              </div>

              <div class="form-group col-4 col-lg-3 col-xl-3">
                <label for="monthYear"><?= lang('GEN_TABLE_DATE'); ?></label>
                <input id="monthYear" name="monthYear" class="form-control" name="datepicker" type="text" placeholder="<?= lang('GEN_PLACE_DATE_MEDIUM'); ?>" readonly>
                <input id="endDate" name="endDate" class="form-control date-picker" type="hidden">
                <div class="help-block"></div>
              </div>

              <div class="form-group col-4 col-lg-3 col-xl-3">
                <label for="initialDate"><?= lang('GEN_START_DAY'); ?></label>
                <input id="initialDate" name="initialDate" class="form-control date-picker" type="text" placeholder="<?= lang('GEN_PLACE_DATE_COMPLETTE'); ?>" readonly>
                <div class="help-block">
                </div>
              </div>

              <div class="form-group col-4 col-lg-3 col-xl-3">
                <label for="finalDate"><?= lang('GEN_END_DAY'); ?></label>
                <input id="finalDate" name="finalDate" class="form-control date-picker" type="text" placeholder="<?= lang('GEN_PLACE_DATE_COMPLETTE'); ?>" readonly>
                <div class="help-block "></div>
              </div>

              <div class="form-group col-6 col-lg-4 col-xl-4">
                <label class="block">Resultados</label>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="allResults" name="results" class="custom-control-input" value="0">
                  <label class="custom-control-label mr-1" for="allResults"><?= lang('GEN_BTN_ALL') ?></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="resultByProduct" name="results" class="custom-control-input" value="1" checked>
                  <label class="custom-control-label mr-1" for="resultByProduct"><?= lang('GEN_PRODUCTS') ?></label>
                </div>
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
        <div id="pre-loade-result" class="mt-2 mx-auto hide">
          <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
        </div>
      </div>

      <div class="w-100 issuedCards-result hide">
        <div class="flex pb-5 flex-column">
          <span class="line-text mb-2 h4 semibold primary">Resultados</span>

          <form id="download-issuedcards" action="<?= base_url(lang('SETT_LINK_DOWNLOAD_FILES')); ?>" method="post"></form>

          <div class="center mx-1">
            <div class="flex">
              <div class="flex mr-2 py-3 flex-auto justify-end items-center">
                <div class="download-icons">
                  <button class="btn px-1 big-modal" title="<?= lang('GEN_BTN_DOWN_XLS'); ?>" data-toggle="tooltip">
                    <i class="icon icon-file-excel" aria-hidden="true"></i>
                  </button>
                  <button class="btn px-1 big-modal" title="<?= lang('GEN_BTN_DOWN_PDF'); ?>" data-toggle="tooltip">
                    <i class="icon icon-file-pdf" aria-hidden="true"></i>
                  </button>
                  <button class="btn px-1 big-modal" title="<?= lang('GEN_BTN_SEE_GRAPH'); ?>" data-toggle="tooltip">
                    <i class="icon icon-graph" aria-hidden="true"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div id="issued-cards-table">

            <table class="cell-border h6 display responsive w-100 dataTable">
              <thead class="bg-primary secondary regular">
                <tr role="row">
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">CONSUMOS ESTABLECIDOS</th>
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">Emisión</th>
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">Rep. Tarjeta</th>
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">Rep. Clave</th>
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">Total</th>
                </tr>
              </thead>
              <tbody>
                <tr role="row" class="odd">
                  <td tabindex="0">Principal</td>
                  <td>48</td>
                  <td>0</td>
                  <td>0</td>
                  <td>48</td>
                </tr>
                <tr role="row" class="even">
                  <td tabindex="0">Suplementaria</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                </tr>
                <tr role="row" class="odd">
                  <td tabindex="0">Total</td>
                  <td>48</td>
                  <td>0</td>
                  <td>0</td>
                  <td>48</td>
                </tr>
              </tbody>
            </table>

            <div class="center mx-1">
              <div class="flex">
                <div class="flex mr-2 py-3 flex-auto justify-end items-center">
                  <div class="download-icons">
                    <button class="btn px-1 big-modal" title="<?= lang('GEN_BTN_DOWN_PDF'); ?>" data-toggle="tooltip">
                      <i class="icon icon-graph" aria-hidden="true"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <table class="cell-border h6 display responsive w-100 dataTable">
              <thead class="bg-primary secondary regular">
                <tr role="row">
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">PREPAGO EMPRESARIAL</th>
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">Emisión</th>
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">Rep. Tarjeta</th>
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">Rep. Clave</th>
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">Total</th>
                </tr>
              </thead>
              <tbody>
                <tr role="row" class="odd">
                  <td tabindex="0">Principal</td>
                  <td>1</td>
                  <td>0</td>
                  <td>0</td>
                  <td>1</td>
                </tr>
                <tr role="row" class="even">
                  <td tabindex="0">Suplementaria</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                </tr>
                <tr role="row" class="odd">
                  <td tabindex="0">Total</td>
                  <td>1</td>
                  <td>0</td>
                  <td>0</td>
                  <td>1</td>
                </tr>
              </tbody>
            </table>

            <div class="center mx-1">
              <div class="flex">
                <div class="flex mr-2 py-3 flex-auto justify-end items-center">
                  <div class="download-icons">
                    <button class="btn px-1 big-modal" title="<?= lang('GEN_BTN_DOWN_XLS'); ?>" data-toggle="tooltip">
                      <i class="icon icon-file-excel" aria-hidden="true"></i>
                    </button>
                    <button class="btn px-1 big-modal" title="<?= lang('GEN_BTN_DOWN_PDF'); ?>" data-toggle="tooltip">
                      <i class="icon icon-file-pdf" aria-hidden="true"></i>
                    </button>
                    <button class="btn px-1 big-modal" title="<?= lang('GEN_BTN_SEE_GRAPH'); ?>" data-toggle="tooltip">
                      <i class="icon icon-graph" aria-hidden="true"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <table class="cell-border h6 display responsive center w-100 my-5 dataTable no-footer dtr-inline" id="resultsIssued" role="grid" style="width: 0px;">
              <thead class="bg-primary secondary regular">
                <tr role="row">
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">Producto</th>
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">Emisión</th>
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">Rep. Tarjeta</th>
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">Rep. Clave</th>
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">Total</th>
                </tr>
              </thead>
              <tbody>
                <tr role="row" class="odd">
                  <td tabindex="0">CONSUMOS ESTABLECIDOS</td>
                  <td>48</td>
                  <td>0</td>
                  <td>0</td>
                  <td>48</td>
                </tr>
                <tr role="row" class="even">
                  <td tabindex="0">PREPAGO EMPRESARIAL</td>
                  <td>1</td>
                  <td>0</td>
                  <td>0</td>
                  <td>1</td>
                </tr>
              </tbody>
            </table>
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