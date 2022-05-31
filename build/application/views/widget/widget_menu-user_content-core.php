<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<nav class="main-nav main-nav-dropdown">
  <ul class="flex my-0 items-center list-style-none list-inline">
    <li>
      <a class="mt-1 mx-1 regular text-decoration-none white flex" href="#">
				<span class="inline-block tool-ellipsis"><?= $fullName ?></span>
        <i class="ml-3 icon icon-chevron-down" aria-hidden="true"></i>
      </a>
      <ul class="dropdown regular tertiary bg-secondary">
        <?php if(lang('CONF_SETT_CONFIG') == 'ON'): ?>
        <li>
          <a class="pl-2 pr-1 h6 big-modal" href="<?= base_url(lang('CONF_LINK_SETTING')) ?>">
            <?= lang('GEN_SETTINGS_TITLE'); ?>
          </a>
        </li>
        <?php endif; ?>
        <li>
          <a class="pl-2 pr-1 h6 big-modal"
            href="<?= base_url(lang('CONF_LINK_SIGNOUT').lang('CONF_LINK_SIGNOUT_START')) ?>">
            <?= lang('GEN_MENU_SIGN_OFF'); ?>
          </a>
        </li>
      </ul>
      <span class="line-nav"></span>
    </li>
  </ul>
</nav>
