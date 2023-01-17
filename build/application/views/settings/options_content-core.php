<?php defined('BASEPATH') or exit('No direct script access allowed '); ?>
<div class="bg-color">
  <div class="pt-3 pb-5 px-5">
    <h1 class="primary h3 regular inline"><?= lang('GEN_SETTINGS_TITLE') ?></h1>
    <div class="flex mt-3 bg-color justify-between">
      <div class="flex mx-2">
        <nav class="nav-config">
          <ul class="nav-config-box">
            <?php if (lang('CONF_SETTINGS_USER') == 'ON' ): ?>
            <li id="user" class="nav-item-config">
              <a href="javascript:">
                <i class="icon-config icon-user-config"></i>
                <h5><?= lang('GEN_BTN_USER') ?></h5>
                <div class="box up left">
                  <i class="bg icon-user-config"></i>
                  <h4><?= lang('GEN_BTN_USER') ?></h4>
                </div>
              </a>
            </li>
            <?php endif; ?>
            <?php if (lang('CONF_SETTINGS_ENTERPRISE') == 'ON'): ?>
            <li id="enterprise" class="nav-item-config">
              <a href="javascript:">
                <i class="icon-config icon-brief-config"></i>
                <h5><?= lang('GEN_BTN_ENTERPRISE') ?></h5>
                <div class="box up left">
                  <i class="bg icon-brief-config"></i>
                  <h4><?= lang('GEN_BTN_ENTERPRISE') ?></h4>
                </div>
              </a>
            </li>
            <?php endif; ?>
            <?php if (lang('CONF_SETTINGS_BRANCHES') == 'ON'): ?>
            <li id="branch" class="nav-item-config">
              <a href="javascript:">
                <i class="icon-config icon-build-config"></i>
                <h5><?= lang('GEN_BTN_BRANCH') ?></h5>
                <div class="box up left">
                  <i class="bg icon-build-config"></i>
                  <h4><?= lang('GEN_BTN_BRANCH') ?></h4>
                </div>
              </a>
            </li>
            <?php endif; ?>
            <?php if (lang('CONF_SETTINGS_DOWNLOADS') == 'ON'): ?>
            <li id="downloads" class="nav-item-config">
              <a href="javascript:">
                <i class="icon-config icon-downl-config"></i>
                <h5><?= lang('GEN_BTN_DOWNLOADS') ?></h5>
                <div class="box up left">
                  <i class="bg icon-downl-config"></i>
                  <h4><?= lang('GEN_BTN_DOWNLOADS') ?></h4>
                </div>
              </a>
            </li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
      <div class="flex flex-auto flex-column" style="display:none">
			<?php if (lang('CONF_SETTINGS_USER') == 'ON'): ?>
				<?php $this->load->view('/settings/user_content-core') ?>
 			<?php endif; ?>

			<?php if (lang('CONF_SETTINGS_ENTERPRISE') == 'ON'): ?>
				<?php $this->load->view('/settings/company_content-core') ?>
			<?php endif; ?>

			<?php if (lang('CONF_SETTINGS_BRANCHES') == 'ON'): ?>
				<?php $this->load->view('/settings/branch_content-core') ?>
			<?php endif; ?>

			<?php if (lang('CONF_SETTINGS_DOWNLOADS') == 'ON'): ?>
				<?php $this->load->view('/settings/downloads_content-core') ?>
      <?php endif; ?>
    </div>
  </div>
</div>
