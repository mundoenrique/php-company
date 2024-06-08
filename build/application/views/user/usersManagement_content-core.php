<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_USERS_MANAGEMENT') ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
  <div class="flex tertiary">
    <nav class="main-nav nav-inferior">
      <ul class="mb-0 h6 light tertiary list-style-none list-inline">
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_ENTERPRISES')) ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
        <li class="inline"><a class="tertiary big-modal" href="<?= base_url(lang('SETT_LINK_PRODUCT_DETAIL')) ?>"><?= lang('GEN_PRODUCTS_DETAIL_TITLE') ?></a></li> /
        <li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_MENU_USERS') ?></a></li>
      </ul>
    </nav>
  </div>
</div>
<div class="flex mt-1 bg-color flex-nowrap justify-between">
  <div id="pre-loader" class="mt-2 mx-auto ">
    <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
  </div>
  <div class="w-100 hide-out hide">
    <div class="flex flex-auto flex-column <?= $widget ? '' : 'max-width-6'; ?>">
      <span class="line-text mb-2 h4 semibold primary"><?= lang('MANAGEMENT_LIST_USERS') ?></span>
      <table id="consultAdminTable" class="cell-border h6 display center">
        <thead class="regular secondary bg-primary">
          <tr>
            <th>Usuario</th>
            <th>Nombre/Apellido</th>
            <th>Correo Electrónico</th>
            <th>Tipo usuario</th>
            <?php if (($this->verify_access->verifyAuthorization('USEREM', 'CREUSU') && $userRegistered == 'ON') || $this->verify_access->verifyAuthorization('USEREM', 'ASGPER', 'COACUE')) : ?>
              <th>Opciones</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($userList as $user) : ?>
            <tr>
              <td><?= $user->idUser ?></td>
              <td><?= $user->name ?></td>
              <td><?= $user->mail ?></td>
              <td><?= $user->type ?></td>
              <?php if (($this->verify_access->verifyAuthorization('USEREM', 'CREUSU') && $userRegistered == 'ON') || $this->verify_access->verifyAuthorization('USEREM', 'ASGPER', 'COACUE')) : ?>

                <td class="py-0 px-1 flex justify-center items-center">
                  <?php if ($this->verify_access->verifyAuthorization('USEREM', 'CREUSU')) : ?>
                    <?php if ($user->registered == "false") : ?>
                      <button class="btn mx-1 px-0 big-modal" title="<?= lang('GEN_BTN_ENABLE_USER'); ?>" data-toggle="tooltip">
                        <i class="icon icon-user-building" aria-hidden="true"></i>
                      </button>
                    <?php endif; ?>
                  <?php endif; ?>
                  <?php if ($this->verify_access->verifyAuthorization('USEREM', 'ASGPER')) : ?>
                    <?php if ($user->registered == "true") : ?>
                      <button id="editButton" class="btn mx-1 px-0 big-modal" title="<?= lang('GEN_BTN_EDIT_PERMITS'); ?>" data-toggle="tooltip">
                        <i class="icon icon-edit-permits" aria-hidden="true"></i>
                      </button>
                    <?php endif; ?>
                  <?php endif; ?>
                  <?php if ($this->verify_access->verifyAuthorization('USEREM', 'COACUE')) : ?>
                    <button id="editAccountButton" class="btn mx-1 px-0 big-modal" title="<?= lang('GEN_BTN_EDIT_ACCOUNTS'); ?>" data-toggle="tooltip">
                      <i class="icon icon-accounts" aria-hidden="true"></i>
                    </button>
                  <?php endif; ?>
                  <form name="formManagement" method="post">
                    <input type="hidden" name="idUser" value="<?= $user->idUser ?>">
                    <input type="hidden" name="nameUser" value="<?= $user->name ?>">
                    <input type="hidden" name="mailUser" value="<?= $user->mail ?>">
                    <input type="hidden" name="typeUser" value="<?= $user->type ?>">
                  </form>
                </td>
              <? else : ?>

                <td class="hide"></td>

              <?php endif; ?>

            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php if ($widget) : ?>
    <?php $this->load->view('widget/widget_enterprise-product_content-core', $widget) ?>
  <?php endif; ?>
</div>