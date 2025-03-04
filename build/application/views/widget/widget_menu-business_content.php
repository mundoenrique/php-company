<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="nav-bar2">
  <nav id="nav2">
    <ul>
      <li>
        <a href="<?= base_url($enterpriseList)?>" rel="start">
          <span aria-hidden="true" class="icon" data-icon="&#xe097;"></span>
          <?= lang('MENU_INICIO')?>
        </a>
      </li>
      <?php foreach ($menu as $lvlOneOpt): ?>
      <li>
        <a rel="section">
          <span aria-hidden="true" class="icon" data-icon="<?php echo $lvlOneOpt['icon']?>"></span>
          <?=$lvlOneOpt['text']?>
        </a>
        <?php if (isset($lvlOneOpt['suboptions'])&&!empty($lvlOneOpt['suboptions'])): ?>
        <ul>
          <div id="scrollup" style="display:none">
            <span class="ui-icon ui-icon-triangle-1-n"></span>
          </div>
          <?php foreach ($lvlOneOpt['suboptions'] as $lvlTwoOpt): ?>
          <li>
            <a href="<?= $lvlTwoOpt['route']?>">
              <?= $lvlTwoOpt['text']?>
            </a>
            <?php if (isset($lvlTwoOpt['suboptions'])&&!empty($lvlTwoOpt['suboptions'])): ?>
            <ul>
              <?php foreach ($lvlTwoOpt['suboptions'] as $lvlThreeOpt): ?>
              <li>
                <a href="<?=$lvlThreeOpt['route']?>">
                  <?=$lvlThreeOpt['text']?>
                </a>
              </li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
          </li>
          <?php endforeach; ?>
          <div id="scrolldown" style="display:none">
            <span class="ui-icon ui-icon-triangle-1-s"></span>
          </div>
        </ul>
        <?php endif; ?>
      </li>
      <?php endforeach; ?>
      <?php if(verifyDisplay('header', '', lang('GEN_TAG_GOUT_MENU'))): ?>
      <li>
        <a href="<?= base_url($pais.'/sign-out/start')?>" rel="subsection">
          <span aria-hidden="true" class="icon" data-icon="&#xe03e;"></span>
          <?= lang("SUBMENU_LOGOUT")?>
        </a>
      </li>
      <?php endif; ?>
    </ul>
  </nav>
</div>
