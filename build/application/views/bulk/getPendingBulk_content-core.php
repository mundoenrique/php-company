<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_BULK_LOAD'); ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
  <div class="flex tertiary">
    <nav class="main-nav nav-inferior">
      <ul class="mb-0 h6 light tertiary list-style-none list-inline">
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE') ?></a></li> /
        <li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_MENU_LOTS'); ?></a></li>
      </ul>
    </nav>
  </div>
</div>
<div class="flex mt-1 mb-5 bg-color flex-nowrap justify-between">
  <div class="flex flex-auto flex-column <?= $widget ? '' : 'max-width-6';  ?> loadbulk" loadbulk="<?= $loadBulk; ?>">
    <?php if ($loadBulk): ?>
    <div class="flex flex-column">
      <span class="line-text mb-2 h4 semibold primary"><?= lang('BULK_NEW'); ?></span>
      <form id="upload-file-form">
        <div class="flex px-5 pb-4 items-center row">
          <div class="form-group col-6 col-lg-3 col-xl-3">
            <label class="mt-1 h6" for="type-bulk"><?= lang('GEN_BULK_TYPE'); ?></label>
            <select id="type-bulk" name="type-bulk" class="form-control select-box custom-select h6 w-100">
              <?php foreach($typesLot AS $pos => $type): ?>
              <option
								value="<?= $type->key; ?>"
								format="<?= $type->format; ?>"
								<?= $pos != 0 ? '' : 'selected disabled' ?>
							><?= $type->text; ?></option>
              <?php endforeach; ?>
            </select>
            <div class="help-block"></div>
          </div>
          <?php if(lang('SETT_BULK_BRANCHOFFICE') == 'ON'): ?>
          <div class="form-group col-6 col-lg-3 col-xl-3 hide">
            <label class="mt-1 h6" for="branch-office"><?= lang('BULK_BRANCH_OFFICE'); ?></label>
            <select id="branch-office" name="branch-office" class="form-control select-box custom-select h6 w-100">
              <?php foreach($branchOffices AS $pos => $branchOffice): ?>
              <?php $disabled = $branchOffice->text == lang('BULK_SELECT_BRANCH_OFFICE') ||  $branchOffice->text == lang('GEN_TRY_AGAIN') ? '  disabled' : '' ?>
              <option value="<?= $branchOffice->key; ?>" <?= $pos != 0 ? '' : 'selected'.$disabled ?>>
                <?= $branchOffice->text; ?>
              </option>
              <?php endforeach; ?>
            </select>
            <div class="help-block"></div>
          </div>
          <?php endif; ?>
          <div class="form-group col-6 bg-color">
            <input type="file" name="file-bulk" id="file-bulk" class="input-file">
            <label for="file-bulk" class="form-control label-file js-label-file mb-0">
              <i class="icon icon-upload mr-1 pr-3 right"></i>
              <span class="js-file-name h6 regular"><?= lang('BULK_SELECT_BULK_FILE'); ?></span>
            </label>
            <div class="help-block"></div>
          </div>
          <div class="col-auto mt-1 ml-auto">
            <button id="upload-file-btn" class="btn btn-primary btn-small btn-loading flex ml-auto">
              <?= lang('GEN_BTN_SEND'); ?>
            </button>
          </div>
        </div>
      </form>
    </div>
    <?php endif; ?>
    <div class="flex flex-column">
      <span class="line-text mb-2 h4 semibold primary"><?= lang('BULK_PENDING'); ?></span>
      <div class="flex">
        <div id="pre-loader" class="mt-2 mx-auto">
          <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
        </div>
      </div>
      <div id="content-datatable" class="center mx-1 hide">
        <table id="pending-bulk" class="cell-border h6 display">
          <thead class="regular secondary bg-primary">
            <tr>
              <th><?= lang('GEN_TABLE_BULK_NUMBER'); ?></th>
              <th><?= lang('GEN_BULK_TYPE'); ?></th>
              <th><?= lang('GEN_TABLE_BULK_DATE'); ?></th>
              <th><?= lang('GEN_TABLE_STATUS'); ?></th>
              <th><?= lang('GEN_TABLE_OPTIONS'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($pendingBulk AS $bulk): ?>
            <tr ticket-id="<?= $bulk->ticketId ?>" bulk-id="<?= $bulk->bulkId ?>">
              <td><?= $bulk->lotNum ?></td>
              <td><?= $bulk->fileName ?></td>
              <td class="tool-ellipsis"><?= $bulk->loadDate ?></td>
              <td>
                <div class="<?= $bulk->statusPr ?>flex items-center">
                  <div class="icon-circle <?= $bulk->statusColor ?> mx-1" alt=""></div>
                  <span class="pl-1 uppercase"><?= $bulk->statusText ?></span>
                </div>
              </td>
              <td class="py-0 px-1 flex justify-center items-center">
                <?php if(($bulk->status == 1 || $bulk->status == 6) && $this->verify_access->verifyAuthorization('TEBCAR', 'TEBCON')): ?>
                <button class="btn mx-1 px-0 big-modal" title="<?= lang('GEN_BTN_CONFIRM'); ?>" data-toggle="tooltip">
                  <i class="icon icon-ok" aria-hidden="true"></i>
                </button>
                <?php endif; ?>
                <?php if($bulk->status == 5 || $bulk->status == 6 && $this->verify_access->verifyAuthorization('TEBCAR')): ?>
                <button class="btn mx-1 px-0 big-modal" title="<?= lang('GEN_BTN_SEE'); ?>" data-toggle="tooltip">
                  <i class="icon icon-find" aria-hidden="true"></i>
                </button>
                <?php endif; ?>
                <?php if($this->verify_access->verifyAuthorization('TEBCAR', 'TEBELC')): ?>
                <button class="btn mx-1 px-0" title="<?= lang('GEN_BTN_DELETE'); ?>" data-toggle="tooltip">
                  <i class="icon icon-remove" aria-hidden="true"></i>
                </button>
                <?php endif; ?>
                <form id="bulk-<?= $bulk->ticketId; ?>" method="POST">
                  <input type="hidden" name="bulkStatus" value="<?= $bulk->status; ?>">
                  <input type="hidden" name="bulkId" value="<?= $bulk->bulkId; ?>">
                  <input type="hidden" name="bulkTicked" value="<?= $bulk->ticketId; ?>">
                  <input type="hidden" name="bulkFile" value="<?= $bulk->fileName; ?>">
                  <input type="hidden" name="bulkDate" value="<?= $bulk->loadDate; ?>">
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div class="mx-3 h3">
          <div class="flex mt-4 items-center">
            <div class="icon-square bg-being-validated" alt=""></div>
            <span class="pl-1 h6"><?= lang('BULK_VALIDATING_RECORDS'); ?></span>
          </div>
          <div class="flex mt-2 items-center">
            <div class="icon-square bg-will-processed" alt=""></div>
            <span class="pl-1 h6"><?= lang('BULK_ALL_RECORDS'); ?></span>
          </div>
          <div class="flex mt-2 items-center">
            <div class="icon-square bg-will-not-processed" alt=""></div>
            <span class="pl-1 h6"><?= lang('BULK_SOME_RECORDS'); ?></span>
          </div>
          <div class="flex mt-2 items-center">
            <div class="icon-square bg-not-processed" alt=""></div>
            <span class="pl-1 h6"><?= lang('BULK_NO_RECORDS'); ?></span>
          </div>
        </div>
      </div>
      <div id="no-bulk" class="my-5 py-4 center none">
        <span class="h4"><?= lang('BULK_NO_RENDER'); ?></span>
      </div>
    </div>
  </div>
  <?php if($widget): ?>
  <?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
  <?php endif; ?>
</div>
