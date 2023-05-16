<?php defined('BASEPATH') or exit('No direct script access allowed '); ?>
<div id="branchView" class="option-service" style="display:none">
  <div class="flex mb-1 mx-4 flex-column">
    <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SETTINGS_BRANCH') ?></span>
    <div class="px-5">
      <div class="container">
        <div class="row">
          <div class="form-group col-7">
            <form id="branchSettListForm">
              <label class="mt-1"><?= lang('GEN_ENTERPRISE') ?></label>
              <select id="idFiscalList" name="idFiscalList" class="select-box custom-select mt-3 mb-4 h6 w-100" countEnterpriseList=<?= $countEnterpriseList ?>>
                <?php if ($countEnterpriseList == 1): ?>
                <option selected disabled value="<?= $enterpriseSettList[0]->acrif; ?>">
                  <?= $enterpriseSettList[0]->enterpriseName; ?></option>
                <?php else: ?>
                <option selected disabled><?= lang('GEN_SELECT_ENTERPRISE'); ?></option>
                <?php foreach ($enterpriseSettList as $enterprise) : ?>
                <option value="<?= $enterprise->acrif; ?>">
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
      <div class="my-3 px-2">
        <span class="flex line-text mb-2 h4 semibold primary"></span>
        <div class="m-4 flex justify-end">
          <button id="loadBranchBtn" class="btn btn-primary btn-small mr-1">
            <i class="icon icon-upload mr-1" aria-hidden="true"></i><?= lang('GEN_BTN_LOAD_BRANCH') ?>
          </button>
          <button id="newBranchBtn" class="btn btn-primary btn-small" data-action="create">
            <i class="icon icon-plus mr-1" aria-hidden="true"></i><?= lang('GEN_BTN_NEW_BRANCH') ?>
          </button>
        </div>
        <table id="tableBranches" class="mt-4 cell-border h6 display w-100 center">
          <thead class="bg-primary secondary regular">
            <tr>
              <th><?= lang('GEN_TABLE_NAME_CLIENT'); ?></th>
              <th><?= lang('GEN_TABLE_CODE'); ?></th>
              <th><?= lang('GEN_CONTAC_PERSON'); ?></th>
              <th><?= lang('GEN_TABLE_TELEPHONE'); ?></th>
              <th><?= lang('GEN_TABLE_OPTIONS'); ?></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
    <div id="branchLoadSection" style="display:none">
      <div class="flex flex-column mb-5">
        <span class="line-text flex mb-2 h4 semibold primary"><?= lang('GEN_BRANCH_UPLOAD') ?>
        </span>
        <div class="my-2 px-5">
          <form id="txtBranchesForm" method="post">
            <div class="container">
              <div class="row justify-between items-center mb-2">
                <div class="form-group col-7">
                  <input type="file" name="fileBranch" id="fileBranch" class="input-file">
                  <label for="fileBranch" class="form-control label-file js-label-file mb-0">
                    <i class="icon icon-upload mr-1 pr-3 right"></i>
                    <span class="js-file-name h6 regular"><?= lang('TOOLS_SELECT_BRANCHES_FILE'); ?></span>
                  </label>
                  <div class="help-block"></div>
                </div>
                <div class="col-auto mt-1 ml-auto">
                  <button id="btnBranchUpload" type="button" class="btn btn-primary bnt-small btn-loading flex ml-auto">
                    <?= lang('GEN_BTN_SEND'); ?>
                  </button>
                </div>
              </div>
            </div>
          </form>
          <div class="col-12 center">
            <button id="backLoadBranchBtn" class="btn btn-link btn-small">
              <?= lang('GEN_BTN_BACK') ?>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div id="editAddBranchSection" style="display:none">
      <div class="flex flex-column mb-5">
        <span id="editAddBranchText" class="line-text flex mb-2 h4 semibold primary"></span>
        <div class="my-2 px-5">
          <form id="branchInfoForm" method="post">
            <div class="container">
              <div class="row">
                <input id="codB" name="codB" type="text" class="form-control hidden" />
                <input id="userNameB" name="userNameB" type="text" class="form-control hidden" />
                <div class="form-group mb-1 col-6 col-lg-4">
                  <label for="branchName"><?= lang('GEN_NAME') ?></label>
                  <input id="branchName" name="branchName" type="text" class="form-control" />
                  <div class="help-block"></div>
                </div>
                <?php if (lang('SETT_BRANCH_FIELD') == 'ON'): ?>
                <div class="form-group mb-1 col-6 col-lg-4">
                  <label for="zoneName"><?= lang('GEN_ZONE') ?></label>
                  <input id="zoneName" name="zoneName" type="text" class="form-control" />
                  <div class="help-block"></div>
                </div>
                <?php endif; ?>
                <div class="form-group mb-1 col-6 col-lg-4">
                  <label for="address1">
                    <?= lang('SETT_BRANCH_FIELD') == 'ON' ? novoLang(lang('GEN_ADDRESS'), "1") : novoLang(lang('GEN_ADDRESS'), "")  ?>
                  </label>
                  <input id="address1" name="address1" type="text" class="form-control" />
                  <div class="help-block"></div>
                </div>
                <?php if (lang('SETT_BRANCH_FIELD') == 'ON'): ?>
                <div class="form-group mb-1 col-6 col-lg-4">
                  <label for="address2"><?= novoLang(lang('GEN_ADDRESS'), "2") ?></label>
                  <input id="address2" name="address2" type="text" class="form-control" />
                  <div class="help-block"></div>
                </div>
                <div class="form-group mb-1 col-6 col-lg-4">
                  <label for="address3"><?= novoLang(lang('GEN_ADDRESS'), "3") ?></label>
                  <input id="address3" name="address3" type="text" class="form-control" />
                  <div class="help-block"></div>
                </div>
                <?php endif; ?>
                <div class="form-group mb-1 col-6 col-lg-4 hidden">
                  <label for="countryCodBranch"><?= lang('GEN_COUNTRY') ?></label>
                  <select id="countryCodBranch" name="countryCodBranch" class="form-control select-box custom-select h6 w-100"></select>
                  <div class="help-block"></div>
                </div>
                <div class="form-group mb-1 col-6 col-lg-4">
                  <label for="stateCodBranch"><?= lang('GEN_PROVINCE') ?></label>
                  <select id="stateCodBranch" name="stateCodBranch" class="form-control select-box custom-select h6 w-100"></select>
                  <div class="help-block"></div>
                </div>
                <div class="form-group mb-1 col-6 col-lg-4">
                  <label for="cityCodBranch"><?= lang('GEN_DEPARTMENT') ?></label>
                  <select id="cityCodBranch" name="cityCodBranch" class="form-control select-box custom-select h6 w-100"></select>
                  <div class="help-block"></div>
                </div>
                <?php if (lang('SETT_SETTINGS_DISCTRICT') == 'ON') : ?>
                <div id="districtBlock" class="form-group mb-1 col-6 col-lg-4">
                  <label for="districtCodBranch"><?= lang('GEN_DISTRICT') ?></label>
                  <select id="districtCodBranch" name="districtCodBranch" class="form-control select-box custom-select h6 w-100"></select>
                  <div class="help-block"></div>
                </div>
                <?php endif; ?>
                <div class="form-group mb-1 col-6 col-lg-4">
                  <label for="areaCode"><?= lang('GEN_AREA_CODE') ?></label>
                  <input id="areaCode" name="areaCode" type="text" class="form-control ignore" />
                  <div class="help-block"></div>
                </div>
                <div class="form-group mb-1 col-6 col-lg-4">
                  <label for="phone"><?= novoLang(lang('GEN_TELEPHONE'), "") ?></label>
                  <input id="phone" name="phone" type="text" class="form-control ignore" maxlength="15">
                  <div class="help-block"></div>
                </div>
                <div class="form-group mb-1 col-6 col-lg-4">
                  <label for="person"><?= lang('GEN_CONTACT_NAME') ?></label>
                  <input id="person" name="person" type="text" class="form-control ignore" />
                  <div class="help-block"></div>
                </div>
                <div class="form-group mb-1 col-6 col-lg-4">
                  <label for="branchCode"><?= lang('GEN_BRANCH_CODE') ?></label>
                  <input id="branchCode" name="branchCode" type="text" class="form-control" />
                  <div class="help-block"></div>
                </div>
              </div>
              <div class="row flex mb-4 mt-2 pl-5 justify-end form-group">
                <div class="col-4 form-group">
                  <div class="input-group">
                    <input id="password1" class="form-control pwd-input pr-0 pwd" type="password" autocomplete="off" name="password" placeholder="Contraseña">
                    <div class="input-group-append">
                      <span id="pwd-addon" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>">
												<i class="icon-view mr-0"></i>
											</span>
                    </div>
                  </div>
                  <div class="help-block text-left"></div>
                </div>
                <div class="col-auto">
                  <button id="btnSaveBranch" type="button" class="btn btn-primary btn-small btn-loading flex mx-auto justify-center">
										<?= lang('GEN_BTN_SAVE') ?>
									</button>
                </div>
              </div>
            </div>
          </form>
          <div class="col-12 center">
            <button id="backBranchBtn" class="btn btn-link btn-small">
              <?= lang('GEN_BTN_BACK') ?>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
