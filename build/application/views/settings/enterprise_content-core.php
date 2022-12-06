<?php defined('BASEPATH') or exit('No direct script access allowed '); ?>
<div id="enterpriseView" class="option-service" style="display:none">
  <div class="flex mb-1 mx-4 flex-column">
    <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SETTINGS_ENTERPRISE') ?></span>
    <div class="px-5">
      <div class="container">
        <div class="row mb-2">
          <div class="form-group col-12 col-lg-8 col-xl-6">
            <label class="mt-1"><?= lang('GEN_ENTERPRISE') ?></label>
            <form id="enterpriseSettListForm" method="POST">
              <select id="enterpriseList" class="select-box custom-select mt-3 mb-4 h6 w-100">
                <?php if ($countEnterpriseList == 1): ?>
                <option countEnterpriseList="<?= $countEnterpriseList ?>" selected disabled>
                  <?= $enterpriseSettList[0]->acnomcia; ?>
								</option>
                <?php else: ?>
                <option selected disabled><?= lang('GEN_SELECT_ENTERPRISE'); ?></option>
                <?php foreach ($enterpriseSettList AS $enterprise): ?>
                <option idFiscal="<?= $enterprise->acrif; ?>" name="<?= $enterprise->acnomcia; ?>"
                  businessName="<?= $enterprise->acrazonsocial; ?>" contact="<?= $enterprise->acpercontac; ?>"
                  address="<?= $enterprise->acdirubica; ?>" billingAddress="<?= $enterprise->acdirenvio; ?>"
                  phone1="<?= $enterprise->actel; ?>" phone2="<?= $enterprise->actel2; ?>"
                  phone3="<?= $enterprise->actel3; ?>" countEnterpriseList="<?= $countEnterpriseList ?>">
                  <?= $enterprise->enterpriseName; ?>
                </option>
                <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </form>
          </div>
        </div>
        <div class="hide-out hide">
          <div id="pre-loader" class="mt-2 mx-auto flex justify-center">
            <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
          </div>
        </div>
        <div id="enterpriseData" class="<?= $countEnterpriseList > 1 ? 'hide' : ''; ?>">
          <form id="enterpriseDataForm">
            <div class="row" id="blockEnterprice">
              <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                <label for="idFiscal"><?= lang('GEN_FISCAL_REGISTRY'); ?></label>
                <input type="text" id="idFiscal" name="idFiscal" class="form-control px-1" value="<?= $idFiscal; ?>" readonly disabled>
              </div>
              <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                <label for="name"><?= lang('GEN_NAME') ?></label>
                <input type="text" id="name" name="name" class="form-control px-1" value="<?= $name; ?>" readonly disabled>
              </div>
              <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                <label for="businessName" id="busiName"><?= lang('GEN_BUSINESS_NAME') ?></label>
                <input type="text" id="businessName" name="businessName" class="form-control px-1" value="<?= $businessName; ?>" readonly disabled>
              </div>
              <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                <label for="contact"><?= lang('GEN_CONTAC_PERSON') ?></label>
                <input type="text" id="contact" name="contact" class="form-control px-1" value="<?= $contact; ?>" readonly disabled>
              </div>
              <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                <label for="address"><?= novoLang(lang('GEN_ADDRESS'), "") ?></label>
                <input type="text" id="address" name="address" class="form-control px-1" value="<?= $address; ?>" <?= $addressCompanyUpdate; ?>>
                <div class="help-block"></div>
              </div>
              <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                <label for="billingAddress"><?= lang('GEN_BILLING_ADDRESS') ?></label>
                <input type="text" id="billingAddress" name="billingAddress" class="form-control px-1" value="<?= $billingAddress; ?>" <?= $addressCompanyUpdate; ?>>
                <div class="help-block"></div>
              </div>
            </div>
            <?php if (lang('CONF_SETTINGS_TELEPHONES') == 'ON'): ?>
            <div class="row">
              <div id='divPhone1' class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                <label for="phone1"><?= novoLang(lang('GEN_TELEPHONE'), "1") ?></label>
                <input type="text" id="phone1" name="phone1" class="form-control" value="<?= $phone1; ?>" maxlength="15" <?= $phoneUpdate; ?>>
                <div class="help-block"></div>
              </div>

              <div id='divPhone2' class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                <label for="phone2"><?= novoLang(lang('GEN_TELEPHONE'), "2") ?></label>
                <input id="phone2" name="phone2" class="form-control" value="<?= $phone2; ?>" maxlength="15" <?= $phoneUpdate; ?>>
                <div class="help-block"></div>
              </div>

              <div id='divPhone3' class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                <label for="phone3"><?= novoLang(lang('GEN_TELEPHONE'), "3") ?></label>
                <input id="phone3" name="phone3" class="form-control" value="<?= $phone3; ?>" maxlength="15" <?= $phoneUpdate; ?>>
                <div class="help-block"></div>
              </div>
            </div>
            <?php if (lang('CONF_SETTINGS_ADDRESS_ENTERPRICE_UPDATE') == 'ON' || lang('CONF_SETTINGS_PHONES_UPDATE') == 'ON'): ?>
            <div class="row">
              <div class="col-6 flex justify-end">
                <button id="updateEnterpriceBtn" class="btn btn-primary btn-small btn-loading">
                  <?= lang('GEN_BTN_ACCEPT') ?>
                </button>
              </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>
          </form>
        </div>
      </div>
    </div>
  </div>
  <table id="tableContacts" class="cell-border h6 display w-100">
    <thead>
    </thead>
    <tbody>
    </tbody>
  </table>
  <div id="existingContactButton">
    <div class="col-3 col-lg-2 col-xl-auto">
      <button id="modifyContact" class="modifyContact btn btn-primary btn-small " type="button">Modificar</button>
    </div>
    <div class="col-3 col-lg-2 col-xl-auto">';
      <button id="deleteContact" type="button" class="btn btn-primary btn-small ">Eliminar</button>
    </div>
  </div>
  <?php if (lang('CONF_SETTINGS_CONTACT') == 'ON'): ?>
  <div id="sectionConctact">
    <div class="flex flex-auto flex-column">
      <div class="flex flex-column mx-4 mb-5">
        <span class="line-text slide-slow flex mb-2 h4 semibold primary"><?= lang('GEN_ADD_CONTACT') ?>
          <i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="true"></i>
        </span>
        <div class=" my-2 px-5">
          <form id="formAddContact">
            <div class="container">
              <div class="row">
                <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                  <label for="nameNewContact"><?= lang('GEN_POSITION') ?></label>
                  <input id="nameNewContact" name="person" type="text" class="form-control" value="" />
                  <div class="help-block"></div>
                </div>
                <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                  <label for="surnameNewContact"><?= lang('GEN_LAST_NAME') ?></label>
                  <input id="surnameNewContact" name="surnameModifyContact" type="text" class="form-control" value="" />
                  <div class="help-block"></div>
                </div>
                <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                  <label for="positionNewContact"><?= lang('GEN_POSITION') ?></label>
                  <input id="positionNewContact" name="positionModifyContact" type="text" class="form-control" value="" />
                  <div class="help-block"></div>
                </div>
                <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                  <label for="dniNewContact"><?= lang('GEN_NIT') ?></label>
                  <input id="dniNewContact" name="zoneName" type="text" class="form-control" value="" />
                  <div class="help-block"></div>
                </div>
                <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                  <label for="emailNewContact"><?= lang('GEN_EMAIL') ?></label>
                  <input type="email" class="form-control" id="emailNewContact" name="email" value="">
                  <div class="help-block"></div>
                </div>
                <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                  <label for="typeNewContact"><?= lang('GEN_ENTERPRISE') ?></label>
                  <select class="select-box custom-select mb-3 h6 w-100" name="typeModifyContact" id="typeNewContact">
                    <option selected value="" disabled>Seleccionar</option>
                    <option value="F">Contacto Administracion y finanzas</option>
                    <option value="H">Contacto RRHH</option>
                    <option value="C">Contacto</option>
                  </select>
                  <div class="help-block"></div>
                </div>
              </div>
              <div class="row flex mb-4 mt-2 justify-end items-center form-group">
                <div class="col-6 col-lg-4 col-xl-3 input-group">
                  <label for="newContPass"></label>
                  <input id="newContPass" class="form-control pwd-input" autocomplete="new-password" name="password" placeholder="Ingresa tu contraseña">
                  <div class="input-group-append">
                    <span id="pwd-addon" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>">
											<i class="icon-view mr-0"></i>
										</span>
                  </div>
                  <div class="help-block"></div>
                </div>
                <div class="row">
                  <div class="flex mb-2 justify-end col-12">
                    <div class="col-3 col-lg-2 col-xl-auto">
                      <button class="btn btn-primary btn-small flex mx-auto " id="btnLimpiar" type="button">
												<?= lang('GEN_BTN_CLEAN_UP'); ?>
											</button>
                    </div>
                    <div class="col-3 col-lg-2 col-xl-auto">
                      <button class="btn btn-primary btn-small flex mx-auto " id="btnAddContact" type="submit">
												<?= lang('GEN_BTN_ADD'); ?>
											</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>
