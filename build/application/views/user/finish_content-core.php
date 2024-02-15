<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="pt-3 pb-5 px-5">
  <div class="logout-content flex flex-column items-center justify-center">
    <h1 class="my-4 tertiary regular"><?= lang('GEN_FINISH_MESSAGE'); ?></h1>
    <span class="mb-5 light h4"><?= $sessionEnd; ?></span>
    <?php if ($showBtn) : ?>
      <a class="mb-5 pb-1 btn btn-primary spiner-loader" href="<?= $action ?>"><?= lang('GEN_BTN_ACCEPT'); ?></a>
    <?php endif; ?>
  </div>
</div>