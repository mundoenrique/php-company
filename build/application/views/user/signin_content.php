<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (verifyDisplay('body', $module,  lang('GEN_SIGNIN_TOP'))) : ?>
  <center class="margin-bottom">
    <img src="<?= $this->asset->insertFile(lang('GEN_LOGO_HEADER'), 'images', $customerFiles); ?>" alt="<?= lang('GEN_ALTERNATIVE_TEXT') ?>">
  </center>
  <h1 class="welcome-title-bp"><?= lang('USER_WELCOME_TITLE'); ?></h1>
<?php endif; ?>

<div id="sidebar">
  <div id="widget-area">
    <div id="widget-signin" class="widget tooltip">

      <?php if (verifyDisplay('body', $module,  lang('GEN_SIGNIN_HEADER'))) : ?>
        <h2 class="widget-title">
          <span aria-hidden="true" class="icon" data-icon="&#xe03f;"></span>
          <?= lang('LOGIN_WIDGET_TITLE'); ?>
        </h2>
      <?php endif; ?>

      <div class="widget-content">
        <form id="signInForm" name="signInForm" accept-charset="utf-8">

          <label for="userName"><?= lang('GEN_USER'); ?></label>
          <input type="text" id="userName" name="userName" disabled autocomplete="username">
          <div class="form-group">
            <label for="userPass"><?= lang('GEN_PASSWORD'); ?></label>
            <input type="password" id="userPass" name="userPass" disabled autocomplete="current-password">
            <div class="general-form-msg help-block"></div>
          </div>
          <button type="submit" id="signInBtn" name="signInBtn" class="btn-sidebar" disabled><?= lang('LOGIN_BTN') ?></button>
        </form>
        <?php if (lang('SETT_SIGIN_RECOVER_PASS') == 'ON') : ?>
          <div class="align-center">
            <p><?= lang('GEN_RECOVER_PASS_TITLE'); ?></p>
            <a href="<?= base_url('recuperar-clave') ?>" rel="section">
              <?= lang('LOGIN_RECOVERY_PASS_LINK'); ?>
            </a>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>
<?php if (verifyDisplay('body', $module, lang('GEN_TAG_WELCOME_MESSAGE'))) : ?>
  <p class="align-center"><?= lang('LOGIN_WELCOME_MESSAGE') ?></p>
<?php endif; ?>