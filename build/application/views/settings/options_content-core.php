<?php defined('BASEPATH') or exit('No direct script access allowed '); ?>
<div class="bg-color">
  <div class="pt-3 pb-5 px-5">
    <h1 class="primary h3 regular inline"><?= lang('GEN_SETTINGS_TITLE') ?></h1>
    <div class="flex mt-3 bg-color justify-between">
      <div class="flex mx-2">
        <nav class="nav-config">
          <ul class="nav-config-box">
            <?php if (lang('CONF_SETTINGS_USER') == 'ON' ): ?>
            <li id="user" class="nav-item-config">
              <a href="javascript:">
                <i class="icon-config icon-user-config"></i>
                <h5><?= lang('GEN_BTN_USER') ?></h5>
                <div class="box up left">
                  <i class="bg icon-user-config"></i>
                  <h4><?= lang('GEN_BTN_USER') ?></h4>
                </div>
              </a>
            </li>
            <?php endif; ?>
            <?php if (lang('CONF_SETTINGS_ENTERPRISE') == 'ON'): ?>
            <li id="enterprise" class="nav-item-config">
              <a href="javascript:">
                <i class="icon-config icon-brief-config"></i>
                <h5><?= lang('GEN_BTN_ENTERPRISE') ?></h5>
                <div class="box up left">
                  <i class="bg icon-brief-config"></i>
                  <h4><?= lang('GEN_BTN_ENTERPRISE') ?></h4>
                </div>
              </a>
            </li>
            <?php endif; ?>
            <?php if (lang('CONF_SETTINGS_BRANCHES') == 'ON'): ?>
            <li id="branch" class="nav-item-config">
              <a href="javascript:">
                <i class="icon-config icon-build-config"></i>
                <h5><?= lang('GEN_BTN_BRANCH') ?></h5>
                <div class="box up left">
                  <i class="bg icon-build-config"></i>
                  <h4><?= lang('GEN_BTN_BRANCH') ?></h4>
                </div>
              </a>
            </li>
            <?php endif; ?>
            <?php if (lang('CONF_SETTINGS_DOWNLOADS') == 'ON'): ?>
            <li id="downloads" class="nav-item-config">
              <a href="javascript:">
                <i class="icon-config icon-downl-config"></i>
                <h5><?= lang('GEN_BTN_DOWNLOADS') ?></h5>
                <div class="box up left">
                  <i class="bg icon-downl-config"></i>
                  <h4><?= lang('GEN_BTN_DOWNLOADS') ?></h4>
                </div>
              </a>
            </li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
      <div class="flex flex-auto flex-column" style="display:none">
        <?php if (lang('CONF_SETTINGS_USER') == 'ON'): ?>
        <div id="userView" class="option-service" style="display:none">
          <div class="flex mb-1 mx-4 flex-column">
            <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SETTINGS_USER') ?></span>
            <div class="px-5">
              <div class="container">
                <div class="row my-2">
                  <div class="form-group col-12">
                    <span aria-hidden="true" class="icon icon-user"></span>
                    <span><?= $userName; ?></span>
                  </div>
                </div>
                <form id="userDataForm">
                  <div class="row mb-2">
                    <div class="form-group col-3">
                      <label for="firstName">Nombre</label>
                      <input type="text" id="firstName" name="firstName" class="form-control px-1" value="<?= $firstName; ?>" readonly disabled>
                    </div>
                    <div class="form-group col-3">
                      <label for="lastName">Apellido</label>
                      <input type="text" id="lastName" name="lastName" class="form-control px-1" value="<?= $lastName; ?>" readonly disabled>
                    </div>
                    <div class="form-group col-3">
                      <label for="position">Cargo</label>
                      <input type="text" id="position" name="position" class="form-control px-1" value="<?= $position; ?>" readonly disabled>
                    </div>
                    <div class="form-group col-3">
                      <label for="area">Área</label>
                      <input type="text" id="areaUser" name="areaUser" class="form-control px-1" value="<?= $area; ?>" readonly disabled>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-6 col-lg-5 col-xl-6">
                      <label for="email">Correo</label>
                      <input type="email" id="currentEmail" name="email" class="form-control" value="<?= $email; ?>" maxlength="40"
                        <?= $emailUpdate ?>>
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div id="loader" class="none">
                    <span class="spinner-border secondary" role="status" aria-hidden="true"></span>
                  </div>
                  <?php if (lang('CONF_SETTINGS_EMAIL_UPDATE') == 'ON'): ?>
                  <div class="row">
                    <div class="col-6 flex justify-end">
                      <button id="userDataBtn" class="btn btn-primary btn-small btn-loading" type="submit"><?= lang('GEN_BTN_ACCEPT') ?></button>
                    </div>
                  </div>
                  <?php endif; ?>
                </form>
              </div>
            </div>
          </div>
          <?php if (lang('CONF_SETTINGS_CHANGE_PASSWORD') == 'ON'): ?>
          <div class="flex flex-auto flex-column">
            <div class="flex mb-5 mx-4 flex-column ">
              <span class="line-text slide-slow flex mb-2 h4 semibold primary"><?= lang('GEN_CHANGE_PASS') ?>
                <i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="true"></i>
              </span>
              <div class="section my-2 px-5">
                <form id="passwordChangeForm" method="post">
                  <input type="hidden" id="userType" name="user-type" value="<?= $userType ?>">
                  <div class="container">
                    <div class="row">
                      <div class="col-6">
                        <div class="row">
                          <div class="form-group col-12 col-lg-12">
                            <label for="currentPass"><?= lang('PASSWORD_CURRENT');?></label>
                            <div class="input-group">
                              <input id="currentPass" class="form-control pwd-input" type="password" autocomplete="off" name="current-pass" required>
                              <div class="input-group-append">
                                <span id="pwd-addon" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i
                                    class="icon-view mr-0"></i></span>
                              </div>
                            </div>
                            <div class="help-block"></div>
                          </div>
                          <div class="form-group col-12 col-lg-6">
                            <label for="newPass"><?= lang('PASSWORD_NEW'); ?></label>
                            <div class="input-group">
                              <input id="newPass" class="form-control pwd-input" type="password" autocomplete="off" name="new-pass" required>
                              <div class="input-group-append">
                                <span id="pwd-addon" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i
                                    class="icon-view mr-0"></i></span>
                              </div>
                            </div>
                            <div class="help-block"></div>
                          </div>
                          <div class="form-group col-12 col-lg-6">
                            <label for="confirmPass"><?= lang('PASSWORD_CONFIRM'); ?></label>
                            <div class="input-group">
                              <input id="confirmPass" class="form-control pwd-input" type="password" autocomplete="off" name="confirm-pass" required>
                              <div class="input-group-append">
                                <span id="pwd-addon" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i
                                    class="icon-view mr-0"></i></span>
                              </div>
                            </div>
                            <div class="help-block"></div>
                          </div>
                        </div>
                      </div>

                      <div class="cover-spin" id=""></div>
                      <div class="col-6 flex justify-center">
                        <div class="field-meter" id="password-strength-meter">
                          <h4><?= lang('PASSWORD_INFO_TITLE'); ?></h4>
                          <ul class="pwd-rules">
                            <li id="length" class="pwd-rules-item rule-invalid"><?= lang('PASSWORD_INFO_1'); ?></li>
                            <li id="letter" class="pwd-rules-item rule-invalid"><?= lang('PASSWORD_INFO_2'); ?></li>
                            <li id="capital" class="pwd-rules-item rule-invalid"><?= lang('PASSWORD_INFO_3'); ?></li>
                            <li id="number" class="pwd-rules-item rule-invalid"><?= lang('PASSWORD_INFO_4'); ?></li>
                            <li id="special" class="pwd-rules-item rule-invalid"><?= lang('PASSWORD_INFO_5'); ?></li>
                            <li id="consecutive" class="pwd-rules-item rule-invalid"><?= lang('PASSWORD_INFO_6'); ?></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6 flex justify-end">
                        <button id="passwordChangeBtn" class="btn btn-primary btn-small btn-loading">
                          <?= lang('GEN_BTN_ACCEPT') ?>
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if (lang('CONF_SETTINGS_ENTERPRISE') == 'ON'): ?>
        <div id="enterpriseView" class="option-service" style="display:none">
          <div class="flex mb-1 mx-4 flex-column">
            <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SETTINGS_ENTERPRISE') ?></span>
            <div class="px-5">
              <div class="container">
                <div class="row mb-2">
                  <div class="form-group col-12 col-lg-8 col-xl-6">
                    <label class="mt-1">Empresa</label>
                    <form id="enterpriseSettListForm" method="POST">
                      <select id="enterpriseList" class="select-box custom-select mt-3 mb-4 h6 w-100">
                        <?php if ($countEnterpriseList == 1): ?>
                        <option selected disabled><?= $enterpriseSettList[0]->acnomcia; ?></option>
                        <?php else: ?>
                        <option selected disabled><?= lang('GEN_SELECT_ENTERPRISE'); ?></option>
                        <?php foreach ($enterpriseSettList AS $enterprise): ?>
                        <option idFiscal="<?= $enterprise->acrif; ?>" name="<?= $enterprise->acnomcia; ?>"
                          businessName="<?= $enterprise->acrazonsocial; ?>" contact="<?= $enterprise->acpercontac; ?>"
                          address="<?= $enterprise->acdirubica; ?>" billingAddress="<?= $enterprise->acdirenvio; ?>"
                          phone1="<?= $enterprise->actel; ?>" phone2="<?= $enterprise->actel2; ?>" phone3="<?= $enterprise->actel3; ?>">
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
                        <label for="name">Nombre</label>
                        <input type="text" id="name" name="name" class="form-control px-1" value="<?= $name; ?>" readonly disabled>
                      </div>
                      <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                        <label for="businessName" id="busiName">Razón social</label>
                        <input type="text" id="businessName" name="businessName" class="form-control px-1" value="<?= $businessName; ?>" readonly
                          disabled>
                      </div>
                      <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                        <label for="contact">Contacto</label>
                        <input type="text" id="contact" name="contact" class="form-control px-1" value="<?= $contact; ?>" readonly disabled>
                      </div>
                      <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                        <label for="address">Dirección</label>
                        <input type="text" id="address" name="address" class="form-control px-1" value="<?= $address; ?>" readonly disabled>
                      </div>
                      <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                        <label for="billingAddress">Dirección de facturación</label>
                        <input type="text" id="billingAddress" name="billingAddress" class="form-control px-1" value="<?= $billingAddress; ?>"
                          readonly disabled>
                      </div>
                    </div>
                    <?php if (lang('CONF_SETTINGS_TELEPHONES') == 'ON'): ?>
                    <div class="row">
                      <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                        <label for="phone1">Teléfono 1</label>
                        <input type="text" id="phone1" name="phone1" class="form-control" value="<?= $phone1; ?>" maxlength="15" <?= $phoneUpdate; ?>>
                        <div class="help-block"></div>
                      </div>
                      <?php if ($phone2 != ''): ?>
                      <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                        <label for="phone2">Teléfono 2</label>
                        <input id="phone2" name="phone2" class="form-control" value="<?= $phone2; ?>" maxlength="15" <?= $phoneUpdate; ?>>
                        <div class="help-block"></div>
                      </div>
                      <?php endif; ?>
                      <?php if ($phone3 != ''): ?>
                      <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                        <label for="phone3">Teléfono 3</label>
                        <input id="phone3" name="phone3" class="form-control" value="<?= $phone3; ?>" maxlength="15" <?= $phoneUpdate; ?>>
                        <div class="help-block"></div>
                      </div>
                      <?php endif; ?>
                    </div>
                    <?php if (lang('CONF_SETTINGS_PHONES_UPDATE') == 'ON'): ?>
                    <div class="row">
                      <div class="flex mb-2 justify-end col-12">
                        <button id="btnChangeTelephones" class="btn btn-primary btn-small " type="submit">
                          Guardar cambios
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
          <?php if (lang('CONF_SETTINGS_CONTACT') == 'ON'): ?>
          <div class="flex flex-auto flex-column">
            <div class="flex flex-column mx-4 mb-5">
              <span class="line-text slide-slow flex mb-2 h4 semibold primary"><?= lang('GEN_ADD_CONTACT') ?>
                <i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="true"></i>
              </span>
              <div class="section my-2 px-5">
                <form id="formAddContact">
                  <div class="container">
                    <div class="row">
                      <div class="form-group mb-3 col-6 col-lg-4 col-xl-4" hidden>
                        <label for="contUser"></label>
                        <input id="contUser" name="contUser" type="text" class="form-control" />
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group mb-3 col-6 col-lg-4 col-xl-4" hidden>
                        <label for="contAcrif"></label>
                        <input id="contAcrif" name="contAcrif" type="text" class="form-control" />
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="contName">Nombre</label>
                        <input id="contName" name="contName" type="text" class="form-control" />
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="surname">Apellido</label>
                        <input id="surname" name="surname" type="text" class="form-control" value="" />
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="contOcupation">Cargo</label>
                        <input id="contOcupation" name="contOcupation" type="text" class="form-control" value="" />
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="contNIT">NIT</label>
                        <input id="contNIT" name="contNIT" type="text" class="form-control" value="" />
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="contEmail">Correo Electrónico</label>
                        <input type="email" class="form-control" id="contEmail" name="email" value="">
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="contType">Empresa</label>
                        <select class="select-box custom-select mb-3 h6 w-100" name="contType" id="contType">
                          <option selected disabled>Seleccionar</option>
                          <option value="F">Contacto Administracion y finanzas</option>
                          <option value="H">Contacto RRHH</option>
                          <option value="C">Contacto</option>
                        </select>
                      </div>
                    </div>
                    <div class="row flex mb-4 mt-2 justify-end items-center form-group">
                      <div class="col-6 col-lg-4 col-xl-3 input-group">
                        <label for="contPass"></label>
                        <input id="contPass" class="form-control pwd-input" autocomplete="new-password" name="Ingresa tu contraseña"
                          placeholder="Ingresa tu contraseña">
                        <div class="input-group-append">
                          <span id="pwd-addon" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i
                              class="icon-view mr-0"></i></span>
                        </div>
                      </div>
                      <div class="col-3 col-lg-2 col-xl-auto">
                        <button class="btn btn-primary btn-small flex mx-auto " id="btnLimpiar" type="button">Limpiar</button>
                      </div>
                      <div class="col-3 col-lg-2 col-xl-auto">
                        <button class="btn btn-primary btn-small flex mx-auto " id="btnAddContact" type="submit">Agregar</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if (lang('CONF_SETTINGS_BRANCHES') == 'ON'): ?>
        <div id="branchView" class="option-service" style="display:none">
          <div class="flex mb-1 mx-4 flex-column">
            <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SETTINGS_BRANCH') ?></span>
            <div class="px-5">
              <div class="container">
                <div class="row">
                  <div class="form-group col-7">
                    <label class="mt-1" or="">Sucursal</label>
                    <select class="select-box custom-select mb-2 h6 w-100">
                      <option selected disabled>Seleccionar</option>
                      <option>Option 1</option>
                      <option>Option 2</option>
                      <option>Option 3</option>
                    </select>
                  </div>
                </div>
                <form method="post">
                  <div class="row mb-2">
                    <div class="col-7 mt-1 bg-color">
                      <input type="file" name="file" id="file" class="input-file">
                      <label for="file" class="label-file js-label-file">
                        <i class="icon icon-upload mr-1 pr-3 right"></i>
                        <span class="js-file-name h6 regular">Selecciona archivo de sucursales.</span>
                      </label>
                    </div>
                    <div class="col-auto mt-5">
                      <button class="btn btn-primary btn-small">
                        Seleccionar
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="flex flex-auto flex-column">
            <div class="flex flex-column mx-4 mb-5">
              <span class="line-text slide-slow flex mb-2 h4 semibold primary"><?= lang('GEN_ADD_BRANCH') ?>
                <i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="true"></i>
              </span>
              <div class="section my-2 px-5">
                <form method="post">
                  <div class="container">
                    <div class="row">
                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="branchName">Nombre</label>
                        <input id="branchName" name="branchName" type="text" class="form-control" value="" placeholder="Nombre de la empresa" />
                        <div class="help-block"></div>
                      </div>

                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="zoneName">Zona</label>
                        <input id="zoneName" name="zoneName" type="text" class="form-control" value="" placeholder="Punto de referencia" />
                        <div class="help-block"></div>
                      </div>

                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="address1">Dirección 1</label>
                        <input id="address1" name="address1" type="text" class="form-control" value="" placeholder="Dirección principal" />
                        <div class="help-block"></div>
                      </div>

                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="address2">Dirección 2</label>
                        <input id="address2" name="address1" type="text" class="form-control" value="" placeholder="Dirección alternativa" />
                        <div class="help-block"></div>
                      </div>

                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="address3">Dirección 3</label>
                        <input id="address3" name="address1" type="text" class="form-control" value="" placeholder="Dirección alternativa" />
                        <div class="help-block"></div>
                      </div>

                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label or="">País</label>
                        <select class="select-box custom-select mb-3 h6 w-100">
                          <option selected disabled>Seleccionar</option>
                          <option>Option 1</option>
                          <option>Option 2</option>
                          <option>Option 3</option>
                        </select>
                      </div>

                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label or="">Departamento</label>
                        <select class="select-box custom-select mb-3 h6 w-100">
                          <option selected disabled>Seleccionar Departamento</option>
                          <option>Option 1</option>
                          <option>Option 2</option>
                          <option>Option 3</option>
                        </select>
                      </div>
                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label or="">Provincia</label>
                        <select class="select-box custom-select mb-3 h6 w-100">
                          <option selected disabled>Seleccionar provincia</option>
                          <option>Option 1</option>
                          <option>Option 2</option>
                          <option>Option 3</option>
                        </select>
                      </div>
                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="areaCode">Código de área</label>
                        <input id="areaCode" name="areaCode" type="text" class="form-control" value="" placeholder="Código de área" />
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="phone">Teléfono</label>
                        <input id="phone" name="phone" type="text" class="form-control" value="" placeholder="Teléfono" maxlength="15">
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="contact">Nombre del contacto</label>
                        <input id="contact" name="contact" type="text" class="form-control" value="" placeholder="Nombre del contacto" />
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="branchCode">Código de sucursal</label>
                        <input id="branchCode" name="branchCode" type="text" class="form-control" value="" placeholder="Código de la sucursal" />
                        <div class="help-block"></div>
                      </div>
                    </div>
                    <div class="row flex mb-4 mt-2 pl-5 justify-end items-center form-group">
                      <div class="col-7 col-lg-4 col-xl-3 input-group">
                        <input id="password1" class="form-control pwd-input" type="password" autocomplete="off" name="Ingresa tu contraseña"
                          placeholder="Ingresa tu contraseña">
                        <div class="input-group-append">
                          <span id="pwd-addon" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i
                              class="icon-view mr-0"></i></span>
                        </div>
                      </div>
                      <div class="col-auto">
                        <button class="btn btn-primary btn-small flex mx-auto">Agregar</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <?php if (lang('CONF_SETTINGS_DOWNLOADS') == 'ON'): ?>
        <div id="downloadsView" class="option-service" style="display:none">
          <div class="flex mb-1 mx-4 flex-column">
            <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_BTN_DOWNLOADS') ?></span>
            <div class="px-5">
              <div class="container">
                <?php if (count(lang('CONF_MANUAL_FILE')) > 0): ?>
                <div class="my-2 tertiary h4 semibold">
                  <span><?= lang('GEN_MANUALS') ?></span>
                </div>
                <div class="row">
                  <?php foreach(lang('CONF_MANUAL_FILE') AS $value): ?>
                  <div class="mb-3 col-auto col-lg-6 col-xl-5">
                    <a href="<?= $this->asset->insertFile($value[0], 'statics', $customerUri); ?>" download>
                      <div class="files btn-link flex items-center">
                        <div class="file">
                          <img src="<?= $this->asset->insertFile(lang('CONF_PDF_ICON'), 'images/icons');?>" />
                        </div>
                        <span class="ml-2 flex justify-center"><?= $value[1]?></span>
                      </div>
                    </a>
                  </div>
                  <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (count(lang('CONF_APPS_FILE')) > 0 || count(lang('CONF_APPS_DOWNLOAD')) > 0): ?>
                <div class="my-2 tertiary h4 semibold">
                  <span><?= lang('CONF_APPLICATIONS') ?></span>
                </div>
                <div class="row">
                  <?php foreach(lang('CONF_APPS_FILE') as $value): ?>
                  <div class="mb-3 col-auto col-lg-6 col-xl-5">
                    <a href="<?= $this->asset->insertFile($value[0], 'statics', $customerUri); ?>" download>
                      <div class="files btn-link flex items-center">
                        <div class="file">
                          <img src="<?= $this->asset->insertFile(lang('CONF_ZIP_ICON'), 'images/icons');?>" />
                        </div>
                        <span class="ml-2 flex justify-center"><?= $value[1] ?></span>
                      </div>
                    </a>
                  </div>
                  <?php endforeach; ?>
                  <?php foreach(lang('CONF_APPS_DOWNLOAD') as $value): ?>
                  <div class="mb-3 col-auto col-lg-6 col-xl-5">
                    <a href="<?= lang('CONF_NO_LINK'); ?>" class="<?= $disabled.' '.$value[0]; ?>" title="<?= $titleIniFile; ?>" download>
                      <div class="files btn-link flex items-center">
                        <div class="file download">
                          <img src="<?= $this->asset->insertFile(lang('CONF_SETT_ICON'), 'images/icons');?>" />
                        </div>
                        <span class="ml-2 flex justify-center"><?= $value[1] ?></span>
                      </div>
                    </a>
                  </div>
                  <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (count(lang('CONF_FILES_MANAGMENT')) > 0): ?>
                <div class="my-2 tertiary h4 semibold">
                  <span><?= lang('CONF_FILE') ?></span>
                </div>
                <div class="row">
                  <?php foreach(lang('CONF_FILES_MANAGMENT') as $value): ?>
                  <div class="form-group col-auto mb-3 col-xl-5">
                    <a href="<?= $this->asset->insertFile($value[0], 'statics', $customerUri); ?>" download>
                      <div class="files btn-link flex items-center">
                        <div class="file">
                          <img src=<?= $this->asset->insertFile(lang('CONF_RAR_ICON'), 'images/icons');?> />
                        </div>
                        <span class="ml-2 flex justify-center"><?= $value[1]  ?></span>
                      </div>
                    </a>
                  </div>
                  <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (count(lang('CONF_MP4_VIDEO')) > 0): ?>
                <div class="my-2 tertiary h4 semibold">
                  <span><?= lang('GEN_VIDEOS') ?></span>
                </div>
                <div class="row">
                  <?php foreach(lang('CONF_MP4_VIDEO') AS $value): ?>
                  <div class="col-sm-12 col-lg-11 col-xl-12 py-2">
                    <div class="manual-video">
                      <video controls preload>
                        <source src="<?= $this->asset->insertFile($value, 'statics', $customerUri);?>" type="video/mp4">
                      </video>
                    </div>
                  </div>
                  <?php endforeach; ?>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
