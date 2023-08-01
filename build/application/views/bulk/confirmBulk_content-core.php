<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('BULK_CONFIRM_TITLE'); ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
  <div class="flex tertiary">
    <nav class="main-nav nav-inferior">
      <ul class="mb-0 h6 light tertiary list-style-none list-inline">
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE') ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_BULK_LOAD')) ?>"><?= lang('GEN_MENU_BULK_LOAD') ?></a></li> /
        <li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_CONFIRM_BULK_TITLE') ?></a></li>
      </ul>
    </nav>
  </div>
</div>
<div class="flex mt-1 mb-5 bg-color flex-nowrap justify-between">
  <div class="flex flex-auto flex-column <?= $widget ? '' : 'max-width-6'; ?>">
    <div class="flex flex-column">
      <span class="line-text mb-2 h4 semibold primary"><?= lang('BULK_CONFIRM'); ?></span>
      <div id="pre-loader" class="mx-auto flex justify-center">
        <span class="spinner-border spinner-border-lg mt-2 mb-3" role="status" aria-hidden="true"></span>
      </div>
      <div class="hide-out hide">
        <div class="row px-5">
          <div class="form-group mb-3 col-4">
            <label for="confirmNIT" id="confirmNIT"><?= lang('GEN_FISCAL_REGISTRY') ?></label>
            <span id="confirmNIT" class="form-control px-1" readonly="readonly"><?= $detailBulk->idFiscal ?></span>
          </div>

          <div class="form-group mb-3 col-4">
            <label for="confirmName" id="confirmName"><?= lang('BULK_ENTERPRISE_NAME') ?></label>
            <span id="confirmName" class="form-control px-1 truncate" title="<?= $detailBulk->enterpriseName ?>" data-toggle="tooltip"
              readonly="readonly"><?= $detailBulk->enterpriseName ?></span>
          </div>

          <div class="form-group mb-3 col-4">
            <label for="typeLot" id="typeLot"><?= lang('GEN_BULK_TYPE'); ?></label>
            <span id="typeLotName" class="form-control px-1 bold not-processed" readonly="readonly"><?= $detailBulk->bulkType ?></span>
          </div>

          <div class="form-group mb-3 col-4">
            <label for="regNumber" id="regNumber"><?= lang('BULK_TOTAL_RECORDS'); ?></label>
            <span id="amountNumber" class="form-control px-1 " readonly="readonly"><?= $detailBulk->totaRecords ?></span>
          </div>

          <div class="form-group mb-3 col-4">
            <label for="amount" id="amount"><?= lang('GEN_TABLE_TOTAL_AMOUNT'); ?></label>
            <span id="totalAmount" class="form-control px-1" readonly="readonly"><?= $detailBulk->amount ?></span>
          </div>

          <div class="form-group mb-3 col-4">
            <label for="lot" id="lot"><?= lang('GEN_BULK_NUMBER'); ?></label>
            <span id="numLot" class="form-control px-1" readonly="readonly"><?= $detailBulk->bulkNumber ?></span>
          </div>

					<?php if (lang('SETT_CONFIRM_MSG') === 'ON' && $detailBulk->bulkId == 'RE'): ?>
					<div class="form-group mb-4 col-12">
						<label for="msg-confirm"><?= lang('BULK_IMPORTANT'); ?></label>
						<span id="msg-confirm" class="form-control" readonly="readonly">
              <?= lang('BULK_CONFIRM_MSG'); ?>
            </span>
					</div>
					<?php endif; ?>

          <div class="form-group mb-3 col-12">
            <label for="obsConfirm" id="obsConfirm"><?= lang('BULK_OBSERVATIONS'); ?></label>
            <?php if(!empty($detailBulk->errors)): ?>
            <?php foreach($detailBulk->errors AS $pos => $error): ?>
            <span id="comment" class="form-control px-1" readonly="readonly">
              <?= $error->line; ?>, <?= $error->msg; ?> <?= $error->detail; ?>
            </span>
            <?php endforeach; ?>
            <?php else: ?>
            <span id="comment" class="form-control px-1" readonly="readonly">
              <?= $detailBulk->success; ?>
            </span>
            <?php endif; ?>
          </div>
        </div>

        <div class="line mb-2"></div>
        <form id="confirm-bulk-btn" method="post">
          <div class="flex flex-column mb-4 mt-4 px-5 justify-center items-center form-group">
            <div class="form-group mb-ie11 mb-3 col-5 col-lg-4 col-xl-3">
							<input id="bulkTicked" name="bulkTicked" type="hidden" value="<?= $detailBulk->bulkTicked ?>">
							<?php if (lang('SETT_REMOTE_AUTH') == 'OFF'): ?>
              <div class="input-group">
                <input id="password" name="password" class="form-control pwd-input" type="password" autocomplete="off"
                  placeholder="<?= lang('GEN_PLACE_PASSWORD'); ?>">
                <div class="input-group-append">
                  <span class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i class="icon-view mr-0"></i></span>
                </div>
              </div>
              <div class="help-block"></div>
						<?php endif; ?>
            </div>
            <div class="flex flex-row">
              <div class="mb-3 mr-2">
                <a href="<?= base_url(lang('SETT_LINK_BULK_LOAD')) ?>" class="btn btn-link btn-small big-modal"><?= lang('GEN_BTN_CANCEL'); ?></a>
              </div>
              <div class="mb-3 mr-2">
                <button id="confirm-bulk" class="btn btn-primary  btn-loading btn-small"><?= lang('GEN_BTN_CONFIRM'); ?></button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php if($widget): ?>
  <?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
  <?php endif; ?>
</div>
