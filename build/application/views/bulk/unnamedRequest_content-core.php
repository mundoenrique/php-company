<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_UNNAMED_REQUEST'); ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
  <div class="flex tertiary">
    <nav class="main-nav nav-inferior">
      <ul class="mb-0 h6 light tertiary list-style-none list-inline">
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE') ?></a></li> /
        <li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_MENU_BULK_UNNAMED') ?></a></li>
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
      <div class="flex pb-5 flex-column">
        <span class="line-text mb-2 h4 semibold primary"><?= lang('BULK_UNNA_REQUEST'); ?></span>
        <form id="unnamed-request-form" name="unnamed-request-form" autocomplete="off">
          <div class="flex px-5 pb-4 items-center row">
            <div class="form-group col-4 col-xl-3">
              <label><?= lang('BULK_UNNA_EXPIRED_DATE'); ?></label>
              <input type="text" id="expiredDate" name="expired-date" class="form-control read-only h5" <?= $editable; ?>
                value="<?= $expMaxMonths; ?>" autocomplete="off">
              <div class="help-block mb-1"></div>
            </div>
            <div class="form-group col-4 col-xl-3">
              <label><?= lang('BULK_UNNA_MAX_CARDS'); ?></label>
              <input type="text" id="maxCards" name="max-cards" class="form-control h5" max-cards="<?= $maxCards ?>" autocomplete="off">
              <div class="help-block mb-1"></div>
            </div>
            <?php if(lang('SETT_UNNA_STARTING_LINE1') == 'ON'): ?>
            <div class="form-group col-4 col-xl-3">
              <label for="startingLine1"><?= lang('BULK_UNNA_STARTING_LINE1'); ?></label>
              <input type="text" id="startingLine1" name="starting-line1" class="form-control h5" maxlength="25" autocomplete="off">
              <div class="help-block mb-1"></div>
            </div>
            <?php endif; ?>
            <?php if(lang('SETT_UNNA_STARTING_LINE2') == 'ON'): ?>
            <div class="form-group col-4 col-xl-3">
              <label for="startingLine2"><?= lang('BULK_UNNA_STARTING_LINE2'); ?></label>
              <input type="text" id="startingLine2" name="starting-line2" class="form-control h5" maxlength="25" autocomplete="off">
              <div class="help-block mb-1"></div>
            </div>
            <?php endif; ?>
            <?php if(lang('SETT_UNNA_BRANCHOFFICE') == 'ON'): ?>
            <div class="form-group col-4 col-xl-3">
              <label><?= lang('BULK_BRANCH_OFFICE'); ?></label>
              <select id="branchOffice" name="branch-office" class="form-control select-box custom-select h6 w-100">
                <?php foreach($branchOffices AS $pos => $branchOffice): ?>
                <?php $disabled = $branchOffice->text == lang('BULK_SELECT_BRANCH_OFFICE') ||  $branchOffice->text == lang('GEN_TRY_AGAIN') ? '  disabled' : '' ?>
                <option value="<?= $branchOffice->key; ?>" <?= $pos != 0 ? '' : 'selected'.$disabled ?>>
                  <?= $branchOffice->text; ?>
                </option>
                <?php endforeach; ?>
              </select>
              <div class="help-block mb-1"></div>
            </div>
            <?php endif; ?>
            <?php if(lang('SETT_UNNA_PASSWORD') == 'ON'): ?>
            <div class="form-group col-4 col-xl-3">
              <label for="password"><?= lang('GEN_PASSWORD');  ?></label>
              <div class="input-group">
                <input id="password" name="password" class="form-control pwd-input h5" type="text" autocomplete="off">
                <div class="input-group-append">
                  <span class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>">
                    <i class="icon-view mr-0"></i>
                  </span>
                </div>
              </div>
              <div class="help-block mb-1"></div>
            </div>
            <?php endif; ?>
            <div class="col-auto mt-1 ml-auto">
              <button type="button" id="unnamed-request-btn" class="btn btn-primary btn-small btn-loading flex ml-auto">
                <?= lang('GEN_BTN_PROCESS'); ?>
              </button>
            </div>
          </div>
          <div class="line mb-2"></div>
        </form>
      </div>
    </div>
  </div>
  <?php if($widget): ?>
  <?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
  <?php endif; ?>
</div>
