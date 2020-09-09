<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="widgetSupport" class="widget widget-support">
  <div class="widget-header">
    <h2 class="mb-2 h3 regular center"><?= lang('GEN_CONTACTS_TITLE_HELP'); ?></h2>
  </div>
  <div class="widget-section">
    <p class="mb-1"><?= lang('GEN_CONTACTS_TITLE'); ?></p>
    <table class="w-100">
      <thead>
        <tr>
          <th class="px-0"><?= lang('GEN_CONTACTS_CITY'); ?></th>
          <th class="px-0 text-right"><?= lang('GEN_CONTACTS_CONTACT'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach(lang('GEN_CONTACTS') AS $city => $number): ?>
        <tr>
          <td><?= $city ?></td>
          <td class="text-right"><?= $number ?></td>
        </tr>
        <?php endforeach; ?>
        <tr class="center bold">
          <td colspan="2"><?= lang('GEN_CONTACTS_REST_COUNTRY'); ?></td>
        </tr>
        <tr class="center">
          <td colspan="2"><?= lang('GEN_CONTACTS_REST_COUNTRY_NUMBER'); ?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="widget-support-btn phone" aria-hidden="true">
    <span class="icon-phone h00 px-2"></span>
  </div>
</div>
