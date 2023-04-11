<?php defined('BASEPATH') or exit('No direct script access allowed '); ?>
<div id="downloadsView" class="option-service" style="display:none">
  <div class="flex mb-1 mx-4 flex-column">
    <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_BTN_DOWNLOADS') ?></span>
    <div class="px-5">
      <div class="container">
        <?php if (count(lang('SETTINGS_FILES_DOWNLOAD')) > 0): ?>
        <?php foreach(lang('SETTINGS_FILES_DOWNLOAD') as $title => $detail): ?>
        <div class="my-2 tertiary h4 semibold">
          <span><?= $title ?></span>
        </div>
        <div class="row">
          <?php foreach($detail as $index => $value): ?>
          <?php if ($value[3] == 'download'): ?>
          <div class="form-group col-auto mb-3 col-xl-5">
            <a href="<?= $this->asset->insertFile($value[0].'.'.$value[1], 'statics', $customerProgram) ?>" download>
              <div class="files btn-link flex items-center">
                <div class="file">
                  <?php switch ($value[1]): case 'xls': case 'xlsm': case 'xlsx':?>
                  <img src=<?= $this->asset->insertFile(lang('CONF_XLS_ICON'), 'images/icons');?> />
                  <?php break; case 'pdf': ?>
                  <img src=<?= $this->asset->insertFile(lang('CONF_PDF_ICON'), 'images/icons');?> />
                  <?php break; case 'rar': ?>
                  <img src=<?= $this->asset->insertFile(lang('CONF_RAR_ICON'), 'images/icons');?> />
                  <?php break; case 'zip': ?>
                  <img src=<?= $this->asset->insertFile(lang('CONF_ZIP_ICON'), 'images/icons');?> />
                  <?php break; endswitch; ?>
                </div>
                <span class="ml-2 flex justify-center"><?= $value[2]  ?></span>
              </div>
            </a>
          </div>
          <?php elseif ($value[3] == 'request'): ?>
          <div class="form-group col-auto mb-3 col-xl-5">
            <a href="<?= lang('CONF_NO_LINK'); ?>" class="<?= $disabled.' '.$value[0]; ?>" title="<?= $titleIniFile; ?>"
              download>
              <div class="files btn-link flex items-center">
                <div class="file download">
                  <img src="<?= $this->asset->insertFile(lang('CONF_SETT_ICON'), 'images/icons');?>" />
                </div>
                <span class="ml-2 flex justify-center"><?= $value[2] ?></span>
              </div>
            </a>
          </div>
          <?php elseif ($value[3] == 'video'): ?>
          <div class="col-sm-12 col-lg-11 col-xl-12 py-2">
            <div class="manual-video">
              <video controls preload>
                <source src="<?= $this->asset->insertFile($value[0].'.'.$value[1], 'statics', $customerProgram);?>" type="video/mp4">
              </video>
            </div>
          </div>
          <?php endif; ?>
          <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
