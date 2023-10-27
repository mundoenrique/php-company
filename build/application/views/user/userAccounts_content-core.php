<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_ADMIN_ACCOUNTS_TITLE') ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
  <div class="flex tertiary">
    <nav class="main-nav nav-inferior">
      <ul class="mb-0 h6 light tertiary list-style-none list-inline">
        <li class="inline"><a class="tertiary big-modal"
            href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
        <li class="inline"><a class="tertiary big-modal"
            href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
        <li class="inline"><a class="tertiary big-modal"
            href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE') ?></a></li> /
        <li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_MENU_USERS') ?></a></li>
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
        <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_USER_DATA') ?></span>
        <div class="flex my-2">
          <form id="user-data" action="" method="post" class="w-100">
            <div class="row mb-2 px-5">
              <div class="form-group mb-3 col-6">
                <label for="idUser"><?= lang('GEN_USER') ?></label>
                <span id="idUser" class="form-control px-1" readonly="readonly"><?= $user ?></span>
              </div>
              <div class="form-group mb-3 col-6">
                <label for="fullName"><?= lang('GEN_TABLE_FULL_NAME') ?></label>
                <span id="fullName" class="form-control px-1" readonly="readonly"><?= $name ?></span>
              </div>
              <div class="form-group mb-3 col-6">
                <label for="email"><?= lang('GEN_EMAIL') ?></label>
                <span id="email" class="form-control px-1" readonly="readonly"><?= $email ?></span>
              </div>
              <div class="form-group mb-3 col-6">
                <label for="typeUser"><?= lang('GEN_TABLE_TYPE') ?></label>
                <span id="typeUser" class="form-control px-1" readonly="readonly"><?= $type ?></span>
              </div>
            </div>
            <div id="enableSectionBtn" class="flex row mb-2 mx-2 items-center justify-end ">
              <a class="btn btn-link btn-small big-modal" href="<?= base_url(lang('SETT_LINK_USERS_MANAGEMENT')) ?>">
                <?= lang('GEN_BTN_CANCEL'); ?>
              </a>
              <button id="enableUserBtn" class="btn btn-small btn-loading btn-primary" type="submit">
                <?= lang('GEN_BTN_ENABLE'); ?>
              </button>
            </div>
          </form>
        </div>
      </div>
      <div id="sectionAccounts">
        <div class="flex">
          <div id="pre-loade-result" class="mt-2 mx-auto hide">
            <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
          </div>
        </div>
        <div class="w-100 cardholders-result ">
          <div class="flex pb-5 flex-column">
            <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_LIST_ACCOUNTS'); ?></span>
            <div class="row flex justify-between mb-3">
              <div class="form-group col-12 center flex justify-center items-end">
                <span class="h6 bold mb-0 mt-2">
                  <?= lang('GEN_NOTE'); ?>
                  <span class="light text"><?= lang('GEN_CHECK_COLOR'); ?></span>
                </span>
                <div class="custom-control custom-switch custom-control-inline p-0 pl-4 ml-1 mr-0">
                  <input class="custom-control-input" type="checkbox" disabled checked>
                  <label class="custom-control-label"></label>
                </div>
                <span class="h6 light text"><?= lang('ACCOUNT_NOTE_ACTIVE'); ?></span>
              </div>
            </div>
            <div class="row mx-3">
              <div class="form-group custom-control custom-switch col-6 col-lg-4 pb-3 my-3">
                <input id="allAccounts" class="custom-control-input" type="checkbox" name="allAccounts" value="off">
                <label class="include custom-control-label semibold"
                  for="allAccounts"><?= lang('ACCOUNTS_ALL_ACCOUNTS'); ?></span>
                </label>
              </div>
              <div class="form-group custom-control custom-switch col-6 col-lg-4 pb-3 my-3">
                <input id="removeAllAccounts" class="custom-control-input" type="checkbox" name="removeAllAccounts"
                  value="off">
                <label class="include custom-control-label semibold"
                  for="removeAllAccounts"><?= lang('ACCOUNTS_DELETE_ALL_ACCOUNTS'); ?></span>
                </label>
              </div>
            </div>

            <form id="checkFormAccounts">
              <?php $i =0; foreach($modules as $index => $value): ?>
              <div class="row mx-3 mb-1">
                <h4 class="col-12 pl-0 bold"><?=  $index?></h4>
                <?php foreach($value as $index => $subArray): ?>
                <?php foreach($subArray as $subIndex => $subValue): ?>
                <div class="form-group custom-control custom-switch col-4 col-lg-3 pb-2">
                  <input id="<?=$subValue->accodfuncion ?>" class="accounts custom-control-input" type="checkbox"
                    name=<?="checkbox". $i; $i++;?> value="<?= $subValue->status; ?>">
                  <label class="custom-control-label"
                    for="<?=$subValue->accodfuncion ?>"><?= $subValue->acnomfuncion ?></span>
                  </label>
                </div>
                <?php endforeach; ?>
                <?php endforeach; ?>
              </div>
              <?php endforeach; ?>
              <div class="flex row mb-2 mx-2 items-center justify-end">
                <a class="btn btn-link btn-small big-modal" href="<?= base_url(lang('SETT_LINK_USERS_MANAGEMENT')) ?>">
                  <?= lang('GEN_BTN_CANCEL'); ?>
                </a>
                <?php if($this->verify_access->verifyAuthorization('USEREM','COACUE')): ?>
                <button id="updateUserBtn" class="btn btn-small btn-loading btn-primary" type="submit">
                  <?= lang('GEN_BTN_UPDATE'); ?>
                </button>
                <?php endif; ?>
              </div>
            </form>
            <div class="line mb-2"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php if ($widget) : ?>
  <?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
  <?php endif; ?>
</div>