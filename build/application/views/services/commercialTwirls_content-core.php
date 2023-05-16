<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_SERV_COMM_MONEY_ORDERS'); ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
  <div class="flex tertiary">
    <nav class="main-nav nav-inferior">
      <ul class="mb-0 h6 light tertiary list-style-none list-inline">
        <li class="inline">
          <a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a>
        </li> /
        <li class="inline">
          <a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS') ?></a>
        </li> /
        <li class="inline">
          <a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE') ?></a>
        </li> /
        <li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_MENU_SERVICES'); ?></a></li>
      </ul>
    </nav>
  </div>
</div>
<div class="flex mt-1 bg-color flex-nowrap justify-between">
  <div id="spinnerBlockBudget" class=" hide">
    <div id="pre-loader" class="mt-2 mx-auto flex justify-center">
      <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
    </div>
  </div>
  <div class="w-100 hide-out hide">
    <div class="flex flex-auto flex-column <?= $widget ? '' : 'max-width-6'; ?>">
      <div class="search-criteria-order flex pb-3 flex-column w-100">
        <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SEARCH_CRITERIA'); ?></span>
        <div class="flex my-2 px-5">
          <form id="formTwirls" class="w-100">
            <div class="row flex justify-between">
              <div class="form-group col-4 col-xl-4">
                <label for="cardNumber"><?= lang('GEN_CARD_NUMBER'); ?></label>
                <input id="cardNumber" name="card-number" class="form-control h5 select-group" type="text" autocomplete="off" disabled>
                <div class="help-block"></div>
              </div>
              <div class="form-group col-4 col-xl-4">
                <div class="help-block"></div>
              </div>

              <div class="flex items-center justify-end col-3">
                <?php if($this->verify_access->verifyAuthorization('GIRCOM', 	'CONGIR') || $this->verify_access->verifyAuthorization('GIRCOM', 	'ACTGIR')): ?>
                <button id="card-holder-btn" class="btn btn-primary btn-small btn-loading">
                  <?= lang('GEN_BTN_SEARCH'); ?>
                </button>
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
      <div class="flex pb-5 flex-column" id="blockResults">
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
            <p class="h5 semibold tertiary"><?= lang('GEN_CARD_NUMBER'); ?>:
              <span class="light text" id="cardNumberP"></span>
            </p>
          </div>
          <div class="form-group col-4 center">
            <p class="h5 semibold tertiary"><?= lang('GEN_TABLE_NAME'); ?>:
              <span class="light text" id="customerName"></span>
            </p>
          </div>
          <div class="form-group col-4 center">
            <p class="h5 semibold tertiary"><?= lang('GEN_TABLE_DNI'); ?>:
              <span class="light text" id="documentId"></span>
            </p>
          </div>
          <div class="form-group col-12 center flex justify-center items-end">
            <span class="h6 bold mb-0 mt-2">
              <?= lang('GEN_NOTE'); ?>
              <span class="light text"><?= lang('GEN_CHECK_COLOR'); ?></span>
            </span>
            <div class="custom-control custom-switch custom-control-inline p-0 pl-4 ml-1 mr-0">
              <input class="custom-control-input" type="checkbox" disabled checked>
              <label class="custom-control-label"></label>
            </div>
            <span class="h6 light text">las compras en este tipo de comercio están restringidas.</span>
          </div>
        </div>
        <form id="check-form">
          <div class="row mx-3">
            <div id="checked-form" class="form-group col-4 col-lg-4 col-xl-3 pb-3">
              <label class="block">Agencia de viajes</label>
              <div class="custom-control custom-switch custom-control-inline">
                <input id="travelAgency" class="custom-control-input" type="checkbox" name="checkbox" value="off">
                <label class="custom-control-label" for="travelAgency"></label>
              </div>

            </div>
            <div id="checked-form" class="form-group col-4 col-lg-4 col-xl-3 pb-3">
              <label class="block">Aseguradoras</label>
              <div class="custom-control custom-switch custom-control-inline">
                <input id="insurers" class="custom-control-input" type="checkbox" name="checkbox" value="off">
                <label class="custom-control-label" for="insurers"></label>
              </div>
            </div>
            <div id="checked-form" class="form-group col-4 col-lg-4 col-xl-3 pb-3">
              <label class="block">Beneficiencia</label>
              <div class="custom-control custom-switch custom-control-inline">
                <input id="charity" class="custom-control-input" type="checkbox" name="checkbox" value="off">
                <label class="custom-control-label" for="charity"></label>
              </div>
            </div>
            <div id="checked-form" class="form-group col-4 col-lg-4 col-xl-3 pb-3">
              <label class="block">Entretenimiento</label>
              <div class="custom-control custom-switch custom-control-inline">
                <input id="entertainment" class="custom-control-input" type="checkbox" name="checkbox" value="off">
                <label class="custom-control-label" for="entertainment"></label>
              </div>
            </div>
            <div id="checked-form" class="form-group col-4 col-lg-4 col-xl-3 pb-3">
              <label class="block">Estacionamientos</label>
              <div class="custom-control custom-switch custom-control-inline">
                <input id="parking" class="custom-control-input" type="checkbox" name="checkbox" value="off">
                <label class="custom-control-label" for="parking"></label>
              </div>
            </div>
            <div id="checked-form" class="form-group col-4 col-lg-4 col-xl-3 pb-3">
              <label class="block">Gasolineras</label>
              <div class="custom-control custom-switch custom-control-inline">
                <input id="gaStations" class="custom-control-input" type="checkbox" name="checkbox" value="off">
                <label class="custom-control-label" for="gaStations"></label>
              </div>
            </div>
            <div id="checked-form" class="form-group col-4 col-lg-4 col-xl-3 pb-3">
              <label class="block">Gobiernos</label>
              <div class="custom-control custom-switch custom-control-inline">
                <input id="governments" class="custom-control-input" type="checkbox" name="checkbox" value="off">
                <label class="custom-control-label" for="governments"></label>
              </div>
            </div>
            <div id="checked-form" class="form-group col-4 col-lg-4 col-xl-3 pb-3">
              <label class="block">Hospitales</label>
              <div class="custom-control custom-switch custom-control-inline">
                <input id="hospitals" class="custom-control-input" type="checkbox" name="checkbox" value="off">
                <label class="custom-control-label" for="hospitals"></label>
              </div>
            </div>
            <div id="checked-form" class="form-group col-4 col-lg-4 col-xl-3 pb-3">
              <label class="block">Hoteles</label>
              <div class="custom-control custom-switch custom-control-inline">
                <input id="hotels" class="custom-control-input" type="checkbox" name="checkbox" value="off">
                <label class="custom-control-label" for="hotels"></label>
              </div>
            </div>
            <div id="checked-form" class="form-group col-4 col-lg-4 col-xl-3 pb-3">
              <label class="block">Peaje</label>
              <div class="custom-control custom-switch custom-control-inline">
                <input id="debit" class="custom-control-input" type="checkbox" name="checkbox" value="off">
                <label class="custom-control-label" for="debit"></label>
              </div>
            </div>
            <div id="checked-form" class="form-group col-4 col-lg-4 col-xl-3 pb-3">
              <label class="block">Renta de autos</label>
              <div class="custom-control custom-switch custom-control-inline">
                <input id="toll" class="custom-control-input" type="checkbox" name="checkbox" value="off">
                <label class="custom-control-label" for="toll"></label>
              </div>
            </div>
            <div id="checked-form" class="form-group col-4 col-lg-4 col-xl-3 pb-3">
              <label class="block">Restaurantes</label>
              <div class="custom-control custom-switch custom-control-inline">
                <input id="restaurants" class="custom-control-input" type="checkbox" name="checkbox" value="off">
                <label class="custom-control-label" for="restaurants"></label>
              </div>
            </div>
            <div id="checked-form" class="form-group col-4 col-lg-4 col-xl-3 pb-3">
              <label class="block">Supermercados</label>
              <div class="custom-control custom-switch custom-control-inline">
                <input id="supermarkets" class="custom-control-input" type="checkbox" name="checkbox" value="off">
                <label class="custom-control-label" for="supermarkets"></label>
              </div>
            </div>
            <div id="checked-form" class="form-group col-4 col-lg-4 col-xl-3 pb-3">
              <label class="block">Telecomunicaciones</label>
              <div class="custom-control custom-switch custom-control-inline">
                <input id="telecommunication" class="custom-control-input" type="checkbox" name="checkbox" value="off">
                <label class="custom-control-label" for="telecommunication"></label>
              </div>
            </div>
            <div id="checked-form" class="form-group col-4 col-lg-4 col-xl-3 pb-3">
              <label class="block">Transporte aéreo</label>
              <div class="custom-control custom-switch custom-control-inline">
                <input id="airTransport" class="custom-control-input" type="checkbox" name="checkbox" value="off">
                <label class="custom-control-label" for="airTransport"></label>
              </div>
            </div>
            <div id="checked-form" class="form-group col-4 col-lg-4 col-xl-3 pb-3">
              <label class="block">Colegios y universidades</label>
              <div class="custom-control custom-switch custom-control-inline">
                <input id="collegesUniversities" class="custom-control-input" type="checkbox" name="checkbox" value="off">
                <label class="custom-control-label" for="collegesUniversities"></label>
              </div>
            </div>
            <div id="checked-form" class="form-group col-4 col-lg-4 col-xl-3 pb-3">
              <label class="block">Ventas al detalle (retail)</label>
              <div class="custom-control custom-switch custom-control-inline">
                <input id="retailSales" class="custom-control-input" type="checkbox" name="checkbox" value="off">
                <label class="custom-control-label" for="retailSales"></label>
              </div>
            </div>
            <div id="checked-form" class="form-group col-4 col-lg-4 col-xl-3 pb-3">
              <label class="block">Transporte terrestre de pasajeros</label>
              <div class="custom-control custom-switch custom-control-inline">
                <input id="passengerTransportation" class="custom-control-input" type="checkbox" name="checkbox" value="off">
                <label class="custom-control-label" for="passengerTransportation"></label>
              </div>
            </div>
          </div>
          <div class="mx-3 h3">
            <div class="flex mt-4 items-center">
              <div class="icon-square bg-option-active" alt=""></div>
              <span class="pl-1 h6">Opción activa.</span>
            </div>
            <div class="flex mt-2 items-center">
              <div class="icon-square bg-option-not-active" alt=""></div>
              <span class="pl-1 h6">Opción no activa.</span>
            </div>
          </div>
          <div class="flex row mt-3 mb-2 mx-2 justify-end">
            <?php if (lang('SETT_REMOTE_AUTH') == 'OFF'): ?>
            <div class="col-5 col-lg-3 col-xl-3 form-group">
              <div class="input-group">
                <input id="passwordAuth" name="password" class="form-control pwd-input pr-0 pwd" type="password" autocomplete="new-password"
                  placeholder="<?= lang('GEN_PLACE_PASSWORD'); ?>" disabled>
                <div class="input-group-append">
                  <span id="pwd_action" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>">
                    <i class="icon-view mr-0"></i>
                  </span>
                </div>
              </div>
              <div class="help-block bulk-select text-left"></div>
            </div>
            <?php endif; ?>
            <div class="col-auto">
              <?php if($this->verify_access->verifyAuthorization('GIRCOM', 'ACTGIR')): ?>
              <button id="sign-btn" class="btn btn-primary btn-small btn-loading flex mx-auto">
                Actualizar</button>
              <?php endif; ?>
            </div>
          </div>
        </form>
        <div class="line my-2"></div>

      </div>
    </div>
  </div>
  <?php if($widget): ?>
  <?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
  <?php endif; ?>
</div>
