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
                      <select id="enterpriseList" class="select-box custom-select mt-3 mb-4 h6 w-100" >
                        <?php if ($countEnterpriseList == 1): ?>
                        <option countEnterpriseList="<?= $countEnterpriseList ?>" selected disabled ><?= $enterpriseSettList[0]->acnomcia; ?></option>
                        <?php else: ?>
                        <option selected disabled><?= lang('GEN_SELECT_ENTERPRISE'); ?></option>
                        <?php foreach ($enterpriseSettList AS $enterprise): ?>
                        <option idFiscal="<?= $enterprise->acrif; ?>" name="<?= $enterprise->acnomcia; ?>"
                          businessName="<?= $enterprise->acrazonsocial; ?>" contact="<?= $enterprise->acpercontac; ?>"
                          address="<?= $enterprise->acdirubica; ?>" billingAddress="<?= $enterprise->acdirenvio; ?>"
                          phone1="<?= $enterprise->actel; ?>" phone2="<?= $enterprise->actel2; ?>" phone3="<?= $enterprise->actel3; ?>" countEnterpriseList="<?= $countEnterpriseList ?>">
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
                        <input type="text" id="address" name="address" class="form-control px-1" value="<?= $address; ?>"
                        <?= $addressCompanyUpdate; ?>>
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                        <label for="billingAddress">Dirección de facturación</label>
                        <input type="text" id="billingAddress" name="billingAddress" class="form-control px-1" value="<?= $billingAddress; ?>"
                        <?= $addressCompanyUpdate; ?>>
                        <div class="help-block"></div>
                      </div>
                    </div>
                    <?php if (lang('CONF_SETTINGS_TELEPHONES') == 'ON'): ?>
                    <div class="row">
                      <div id='divPhone1' class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                        <label for="phone1">Teléfono 1</label>
                        <input type="text" id="phone1" name="phone1" class="form-control" value="<?= $phone1; ?>" maxlength="15" <?= $phoneUpdate; ?>>
                        <div class="help-block"></div>
                      </div>

                      <div  id='divPhone2' class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                        <label for="phone2">Teléfono 2</label>
                        <input id="phone2" name="phone2" class="form-control" value="<?= $phone2; ?>" maxlength="15" <?= $phoneUpdate; ?>>
                        <div class="help-block"></div>
                      </div>

                      <div  id='divPhone3' class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                        <label for="phone3">Teléfono 3</label>
                        <input id="phone3" name="phone3" class="form-control" value="<?= $phone3; ?>" maxlength="15" <?= $phoneUpdate; ?>>
                        <div class="help-block"></div>
                      </div>
                    </div>
                    <?php if (lang('CONF_SETTINGS_ADDRESS_ENTERPRICE_UPDATE') == 'ON' || lang('CONF_SETTINGS_PHONES_UPDATE') == 'ON'): ?>
                   <!-- <div class="row">
                      <div class="flex mb-2 justify-end col-12">
                        <button id="btnChangeTelephones" class="btn btn-primary btn-small " type="submit">
                          Guardar cambios
                        </button>
												<button id="showContacts" type="button" class="btn btn-primary btn-small ">
                          Mostrar contactos
                        </button>
                      </div>
                    </div>-->
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
						<div id="sectionConctact" >
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
													<label for="nameNewContact">Nombre</label>
													<input id="nameNewContact" name="person" type="text" class="form-control" value=""/>
													<div class="help-block"></div>
												</div>
												<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
													<label for="surnameNewContact">Apellido</label>
													<input id="surnameNewContact" name="surnameModifyContact" type="text" class="form-control" value="" />
													<div class="help-block"></div>
												</div>
												<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
													<label for="positionNewContact">Cargo</label>
													<input id="positionNewContact" name="positionModifyContact" type="text" class="form-control" value="" />
													<div class="help-block"></div>
												</div>
												<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
													<label for="dniNewContact">NIT</label>
													<input id="dniNewContact" name="zoneName" type="text" class="form-control" value="" />
													<div class="help-block"></div>
												</div>
												<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
													<label for="emailNewContact">Correo Electrónico</label>
													<input type="email" class="form-control" id="emailNewContact" name="email" value="">
													<div class="help-block"></div>
												</div>
												<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
													<label for="typeNewContact">Empresa</label>
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
													<input id="newContPass" class="form-control pwd-input" autocomplete="new-password" name="password"
														placeholder="Ingresa tu contraseña">
													<div class="input-group-append">
														<span id="pwd-addon" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i
																class="icon-view mr-0"></i></span>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="row">
                      		<div class="flex mb-2 justify-end col-12">
														<div class="col-3 col-lg-2 col-xl-auto">
															<button class="btn btn-primary btn-small flex mx-auto " id="btnLimpiar" type="button">Limpiar</button>
														</div>
														<div class="col-3 col-lg-2 col-xl-auto">
															<button class="btn btn-primary btn-small flex mx-auto " id="btnAddContact" type="submit">Agregar</button>
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
        <?php endif; ?>

        <?php if (lang('CONF_SETTINGS_BRANCHES') == 'ON'): ?>
        <div id="branchView" class="option-service" style="display:none">
          <div class="flex  mx-4 flex-column">
            <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SETTINGS_BRANCH') ?></span>
            <div class="px-5">
              <div class="container">
                <div class="row">
                  <div class="form-group col-7">
                    <form id="branchSettListForm" >
											<label class="mt-1" or="">Empresa</label>
                      <select type="submit" id="branchListBr" name="branchListBr" class="select-box custom-select mt-3 mb-4 h6 w-100">
                        <?php if ($countEnterpriseList == 1): ?>
                        <option selected disabled value="<?= $enterpriseSettList[0]->acrif; ?>" ><?= $enterpriseSettList[0]->acnomcia; ?></option>
                        <?php else: ?>
                        <option selected disabled><?= lang('GEN_SELECT_ENTERPRISE'); ?></option>
                        <?php foreach ($enterpriseSettList AS $enterprise): ?>
                        <option value="<?= $enterprise->acrif; ?>" >
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
          		</div>
          	</div>
						<div id="partedSection" class="figure">
							<div class="col-auto m-5 right">
								<button id="newBranchBtn" class="btn btn-primary btn-small">
									<?= lang('GEN_ADD_BRANCH') ?>
								</button>
							</div>
								<table id="tableBranches" class="cell-border h6 display w-100 center">
									<thead class="bg-primary secondary regular">
										<tr>
											<th><?= 'Nombre' ?></th>
											<th><?= 'Código' ?></th>
											<th><?= 'Contacto' ?></th>
											<th><?= 'Teléfono' ?></th>
											<th><?= 'Editar' ?></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
            		</table>
								<div class="secondarySectionBranch" id="secondarySectionBranch">
									<form id="txtBranchesForm" method="post">
										<div class="row justify-between items-center mb-2 mx-2">
											<div class="form-group col-7">
												<input type="file" name="file-branch" id="file-branch" class="input-file">
												<label for="file-branch" class="form-control label-file js-label-file mb-0">
													<i class="icon icon-upload mr-1 pr-3 right"></i>
													<span class="js-file-name h6 regular"><?= lang('SETTINGS_SELECT_BRANCHES_FILE'); ?></span>
												</label>
												<div class="help-block"></div>
											</div>
											<div class="col-auto mt-2">
												<button id="btnTxtSend" type="button" class="btn btn-primary bnt-small disabled" >
													Seleccionar
												</button>
											</div>
										</div>
									</form>
									<div class="flex flex-auto flex-column mt-5 completeSection hidden">
								</div>
								<div class="flex flex-column mx-4 mb-5">
									<span class="line-text slide-slow flex mb-2 h4 semibold primary"><?= lang('SETTINGS_EDIT_ADD_BRANCH') ?>
										<i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="true"></i>
									</span>
										<div class="section secondSection my-2 px-5">
											<form id="branchInfoForm" method="post">
												<div class="container">
													<div class="row">
														<input id="rifB" name="rifB"type="text" class="form-control hidden" />
														<input id="codB" name="codB"type="text" class="form-control hidden" />
														<input id="userNameB" name="userNameB"type="text" class="form-control hidden" />
														<div class="form-group mb-1 col-6 col-lg-4">
															<label for="branchName">Nombre</label>
															<input id="branchName" name="branchName" type="text" class="form-control"/>
															<div class="help-block"></div>
														</div>
														<div class="form-group mb-1 col-6 col-lg-4">
															<label for="zoneName">Zona</label>
															<input id="zoneName" name="zoneName" type="text" class="form-control"/>
															<div class="help-block"></div>
														</div>
														<div class="form-group mb-1 col-6 col-lg-4">
															<label for="address1">Dirección 1</label>
															<input id="address1" name="address" type="text" class="form-control"/>
															<div class="help-block"></div>
														</div>
														<div class="form-group mb-1 col-6 col-lg-4">
															<label for="address2">Dirección 2</label>
															<input id="address2" name="address2" type="text" class="form-control"/>
															<div class="help-block"></div>
														</div>
														<div class="form-group mb-1 col-6 col-lg-4">
															<label for="address3">Dirección 3</label>
															<input id="address3" name="address3" type="text" class="form-control"  />
															<div class="help-block"></div>
														</div>
														<div class="form-group mb-1 col-6 col-lg-4 hidden">
															<label or="countryCodeBranch">País</label>
															<select id="countryCodeBranch" name="countryCodeBranch" class="form-control select-box custom-select h6 w-100">
															</select>
														</div>
														<div class="form-group mb-1 col-6 col-lg-4">
															<label or="stateCodeBranch">Provincia</label>
															<select id="stateCodeBranch" name="stateCodeBranch" class="form-control select-box custom-select h6 w-100">
															<option value="" selected><?= lang('GEN_BTN_SELECT') ?></option>
															</select>
															<div class="help-block"></div>
														</div>
														<div class="form-group mb-1 col-6 col-lg-4">
															<label or="cityCodeBranch">Departamento</label>
															<select id="cityCodeBranch" name="cityCodeBranch" class="form-control select-box custom-select h6 w-100" disabled>
															<option value="" selected><?= lang('GEN_BTN_SELECT') ?></option>
															</select>
															<div class="help-block"></div>
														</div>
														<?php if (lang('CONF_SETTINGS_DISCTRICT') == 'ON'): ?>
														<div id="districtBlock" class="form-group mb-1 col-6 col-lg-4">
															<label or="districtCodeBranch">Distrito</label>
															<select id="districtCodeBranch" name="districtCodeBranch" class="form-control select-box custom-select h6 w-100">
															<option value="" selected><?= lang('GEN_BTN_SELECT') ?></option>
															</select>
															<div class="help-block"></div>
														</div>
														<?php endif; ?>
														<div class="form-group mb-1 col-6 col-lg-4">
															<label for="areaCode">Código de área</label>
															<input id="areaCode" name="areaCode" type="text" class="form-control"/>
															<div class="help-block"></div>
														</div>
														<div class="form-group mb-1 col-6 col-lg-4">
															<label for="phone">Teléfono</label>
															<input id="phone" name="phone" type="text" class="form-control" maxlength="15">
															<div class="help-block"></div>
														</div>
														<div class="form-group mb-1 col-6 col-lg-4">
															<label for="person">Nombre del contacto</label>
															<input id="person" name="person" type="text" class="form-control"/>
															<div class="help-block"></div>
														</div>
														<div class="form-group mb-1 col-6 col-lg-4">
															<label for="branchCode">Código de sucursal</label>
															<input id="branchCode" name="branchCode" type="text" class="form-control"/>
															<div class="help-block"></div>
														</div>
													</div>
													<div class="row flex mb-4 mt-2 pl-5 justify-end form-group">
														<div class="col-4 form-group">
															<div class="input-group">
																<input id="password1" class="form-control pwd-input pr-0 pwd" type="password" autocomplete="off" name="password" placeholder="Ingresa tu contraseña">
																<div class="input-group-append">
																	<span id="pwd-addon" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i class="icon-view mr-0"></i></span>
																</div>
															</div>
															<div class="help-block text-left"></div>
														</div>
														<div class="col-auto">
															<button id="btn-update-branch" type="button" class="btn btn-primary btn-small flex mx-auto">Guardar</button>
														</div>
													</div>
												</div>
											</form>
										</div>
								</div>
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
                <?php if (count(lang('SETTINGS_FILES_DOWNLOAD')) > 0): ?>
                <?php foreach(lang('SETTINGS_FILES_DOWNLOAD') as $title => $detail): ?>
                <div class="my-2 tertiary h4 semibold">
                  <span><?= $title ?></span>
                </div>
                <div class="row">
                <?php foreach($detail as $index => $value): ?>
                  <?php if ($value[3] == 'download'): ?>
                  <div class="form-group col-auto mb-3 col-xl-5">
                    <a href="<?= $this->asset->insertFile($value[0].'.'.$value[1], 'statics', $customerUri) ?>" download>
                      <div class="files btn-link flex items-center">
                        <div class="file">
                        <?php switch ($value[1]): case 'xls': case 'xlsm': ?>
                          <img src=<?= $this->asset->insertFile(lang('CONF_XLS_ICON'), 'images/icons');?> />
                        <?php break; case 'pdf': ?>
                          <img src=<?= $this->asset->insertFile(lang('CONF_PDF_ICON'), 'images/icons');?> />
                        <?php break; case 'rar': ?>
                          <img src=<?= $this->asset->insertFile(lang('CONF_RAR_ICON'), 'images/icons');?> />
                        <?php break; case 'zip': ?>
                          <img src=<?= $this->asset->insertFile(lang('CONF_ZIP_ICON'), 'images/icons');?> />
                        <?php break; endswitch; ?>
                        </div>
                        <span class="ml-2 flex justify-center"><?= $value[2]  ?></span>
                      </div>
                    </a>
                  </div>
                  <?php elseif ($value[3] == 'request'): ?>
                  <div class="form-group col-auto mb-3 col-xl-5">
                  <a href="<?= lang('CONF_NO_LINK'); ?>" class="<?= $disabled.' '.$value[0]; ?>" title="<?= $titleIniFile; ?>" download>
                      <div class="files btn-link flex items-center">
                        <div class="file download">
                          <img src="<?= $this->asset->insertFile(lang('CONF_SETT_ICON'), 'images/icons');?>" />
                        </div>
                        <span class="ml-2 flex justify-center"><?= $value[2] ?></span>
                      </div>
                    </a>
                  </div>
                  <?php elseif ($value[3] == 'video'): ?>
                  <div class="col-sm-12 col-lg-11 col-xl-12 py-2">
                    <div class="manual-video">
                      <video controls preload>
                        <source src="<?= $this->asset->insertFile($value[0].'.'.$value[1], 'statics', $customerUri);?>" type="video/mp4">
                      </video>
                    </div>
                  </div>
                  <?php endif; ?>
                <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
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
