<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="logout-content max-width-5 mx-auto p-responsive py-4">
  <?php if ($newUser) : ?>
    <h5 class="mt-1"><?= $message ?></h5>
  <?php endif; ?>
  <h1 class="h0"><?= lang("TERMS_TITLE") ?></h1>
  <hr class="separador-one">
  <div class="pt-3">
    <?= lang("TERMS_CONTENT") ?>
    <section>
      <hr class="separador-one">
      <?php if ($newUser) : ?>
        <div class="flex flex-column mt-4 px-5 justify-center items-center">
          <div class="flex flex-row">
            <div class="mb-3 mr-3">
              <a href="<?= $goBack ?>" class="btn btn-link btn-small spiner-loader">
                <?= lang('GEN_BTN_BACK'); ?>
              </a>
            </div>
            <div class="mb-3 mr-1 custom-switch">
              <input type="checkbox" id="terms" name="terms" class="custom-control-input">
              <label class="custom-control-label" for="terms"><?= lang('TERMS_ACCEPT'); ?></label>
            </div>
          </div>
        </div>
      <?php else : ?>
        <div class="flex items-center justify-center pt-3">
          <a class="btn btn-link btn-small spiner-loader" href="javascript:history.back()"><?= lang('GEN_BTN_BACK'); ?></a>
        </div>
      <?php endif; ?>
  </div>
</div>
</section>
</div>