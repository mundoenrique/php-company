<?php defined('BASEPATH') or exit('No direct script access allowed '); ?>
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
              <label for="firstName"><?= lang('GEN_NAME') ?></label>
              <input type="text" id="firstName" name="firstName" class="form-control px-1" value="<?= $firstName; ?>" readonly disabled>
            </div>
            <div class="form-group col-3">
              <label for="lastName"><?= lang('GEN_LAST_NAME') ?></label>
              <input type="text" id="lastName" name="lastName" class="form-control px-1" value="<?= $lastName; ?>" readonly disabled>
            </div>
            <div class="form-group col-3">
              <label for="position"><?= lang('GEN_POSITION') ?></label>
              <input type="text" id="position" name="position" class="form-control px-1" value="<?= $position; ?>" readonly disabled>
            </div>
            <div class="form-group col-3">
              <label for="area"><?= lang('GEN_AREA') ?></label>
              <input type="text" id="areaUser" name="areaUser" class="form-control px-1" value="<?= $area; ?>" readonly disabled>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-6 col-lg-5 col-xl-6">
              <label for="email"><?= lang('GEN_EMAIL') ?></label>
              <input type="email" id="currentEmail" name="email" class="form-control" value="<?= $email; ?>" maxlength="40" <?= $emailUpdate ?>>
              <div class="help-block"></div>
            </div>
          </div>
          <div id="loader" class="none">
            <span class="spinner-border secondary" role="status" aria-hidden="true"></span>
          </div>
          <?php if (lang('CONF_SETTINGS_EMAIL_UPDATE') == 'ON'): ?>
          <div class="row">
            <div class="col-6 flex justify-end">
              <button id="userDataBtn" class="btn btn-primary btn-small btn-loading" type="submit">
								<?= lang('GEN_BTN_ACCEPT') ?>
							</button>
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
                        <span id="pwd-addon" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>">
													<i class="icon-view mr-0"></i>
												</span>
                      </div>
                    </div>
                    <div class="help-block"></div>
                  </div>
                  <div class="form-group col-12 col-lg-6">
                    <label for="newPass"><?= lang('PASSWORD_NEW'); ?></label>
                    <div class="input-group">
                      <input id="newPass" class="form-control pwd-input" type="password" autocomplete="off" name="new-pass" required>
                      <div class="input-group-append">
                        <span id="pwd-addon" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>">
													<i class="icon-view mr-0"></i>
												</span>
                      </div>
                    </div>
                    <div class="help-block"></div>
                  </div>
                  <div class="form-group col-12 col-lg-6">
                    <label for="confirmPass"><?= lang('PASSWORD_CONFIRM'); ?></label>
                    <div class="input-group">
                      <input id="confirmPass" class="form-control pwd-input" type="password" autocomplete="off" name="confirm-pass" required>
                      <div class="input-group-append">
                        <span id="pwd-addon" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>">
													<i class="icon-view mr-0"></i>
												</span>
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
