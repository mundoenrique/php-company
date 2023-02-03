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
						<select id="idEnterpriseList" name="idEnterpriseList" class="select-box custom-select mt-3 mb-4 h6 w-100" countEnterpriseList=<?= $countEnterpriseList ?>>
							<?php if ($countEnterpriseList == 1): ?>
							<option value="<?= $enterpriseSettList[0]->acrif; ?>">
								<?= $enterpriseSettList[0]->enterpriseName; ?></option>
							<?php else: ?>
							<option selected disabled><?= lang('GEN_SELECT_ENTERPRISE'); ?></option>
							<?php foreach ($enterpriseSettList AS $enterprise): ?>
							<option value="<?= $enterprise->acrif; ?>" idFiscal="<?= $enterprise->acrif; ?>"
							name="<?= $enterprise->acnomcia; ?>" businessName="<?= $enterprise->acrazonsocial; ?>"
							contact="<?= $enterprise->acpercontac; ?>" address="<?= $enterprise->acdirubica; ?>"
							billingAddress="<?= $enterprise->acdirenvio; ?>" phone1="<?= $enterprise->actel; ?>"
							phone2="<?= $enterprise->actel2; ?>" phone3="<?= $enterprise->actel3; ?>"
							countEnterpriseList="<?= $countEnterpriseList ?>">
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
  <?php if (lang('CONF_ENTERPRICE_CONTACT') == 'ON'): ?>
  <div id="sectionConctact">
    <div class="flex flex-auto flex-column">
      <div class="flex flex-column mx-4 mb-5">
        <span class="line-text  flex mb-2 h4 semibold primary"><?= lang('PRUE_ENTERPRICE_CONTACTS') ?>
        </span>
        <div class=" my-2 px-5">
        <div class="m-4 flex justify-end">
          <button id="newContactBtn" class="btn btn-primary btn-small" data-action="create">
            <i class="icon icon-plus mr-1" aria-hidden="true"></i><?= lang('GEN_BTN_NEW') ?>
          </button>
        </div>
        <table id="tableContacts" name="tableContacts" class="mt-4 cell-border h6 display w-100 center">
          <thead class="bg-primary secondary regular">
            <tr>
							<th><?= lang('GEN_TABLE_NAME_CLIENT'); ?></th>
              <th><?= lang('GEN_LAST_NAME'); ?></th>
              <th><?= lang('GEN_POSITION'); ?></th>
              <th><?= lang('GEN_TABLE_DNI'); ?></th>
              <th><?= lang('GEN_EMAIL'); ?></th>
              <th><?= lang('GEN_TABLE_TYPE'); ?></th>
              <th><?= lang('GEN_TABLE_OPTIONS'); ?></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        </div>
      </div>
    </div>
  </div>
	<div id="editAddContactSection" style="display:none">
		<div class="flex flex-column mb-5">
			<span id="editAddContactText" class="line-text flex mb-2 h4 semibold primary"></span>
			<div class="my-2 px-5">
				<form id="addContactForm" method="post">
					<div class="container">
						<div class="row">
							<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
								<label for="contactNames"><?= lang('GEN_TABLE_NAME_CLIENT') ?></label>
								<input id="contactNames" name="contactNames" type="text" class="form-control" value="" />
								<div class="help-block"></div>
							</div>
							<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
								<label for="contactLastNames"><?= lang('GEN_LAST_NAME') ?></label>
								<input id="contactLastNames" name="contactLastNames" type="text" class="form-control" value="" />
								<div class="help-block"></div>
							</div>
							<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
								<label for="contactPosition"><?= lang('GEN_POSITION') ?></label>
								<input id="contactPosition" name="contactPosition" type="text" class="form-control" value="" />
								<div class="help-block"></div>
							</div>
							<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
								<label for="idExtPer"><?= lang('GEN_TABLE_DNI') ?></label>
								<input id="idExtPer" name="idExtPer" type="text" class="form-control" value="" />
								<div class="help-block"></div>
							</div>
							<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
								<label for="contactEmail"><?= lang('GEN_EMAIL') ?></label>
								<input type="email" class="form-control" id="contactEmail" name="contactEmail" value="">
								<div class="help-block"></div>
							</div>
							<div class="form-group mb-1 col-6 col-lg-4">
								<label for="contactType"><?= lang('GEN_TABLE_TYPE') ?></label>
								<select id="contactType" name="contactType" class="form-control select-box custom-select h6 w-100">
								</select>
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
								<button id="btnSaveContact" type="button" class="btn btn-primary btn-small btn-loading flex mx-auto justify-center">
									<?= lang('GEN_BTN_SAVE') ?>
								</button>
							</div>
						</div>
					</div>
				</form>
				<div class="col-12 center">
					<button id="backContactBtn" class="btn btn-link btn-small">
						<?= lang('GEN_BTN_BACK') ?>
					</button>
				</div>
			</div>
		</div>
	</div>
  <?php endif; ?>
</div>
