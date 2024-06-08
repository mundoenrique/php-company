<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?= LANGUAGE; ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta http-equiv="cleartype" content="on">
  <link rel="icon" type="image/<?= $faviconExt ?>" href="<?= $this->asset->insertFile($favicon, 'images', $customerFiles, 'favicon') ?>">
  <?= $this->asset->insertCss(); ?>
  <?= (in_array($module, lang('SETT_VALIDATE_CAPTCHA')) && ACTIVE_RECAPTCHA) ?  $scriptCaptcha : ''; ?>
  <?= $this->asset->insertJs($wasMigrated); ?>
  <title><?= $titlePage; ?> - CEO</title>
</head>

<body>
  <?php $this->load->view('header_content') ?>
  <div id="wrapper">
    <?php foreach ($viewPage as $views) : ?>
      <?php $this->load->view($views . '_content'); ?>
    <?php endforeach; ?>
  </div>
  <?php $this->load->view('footer_content') ?>
  <?php $wasMigrated ? $this->load->view('insert_variables') : $this->load->view('insert_variables-legacy')  ?>
</body>

</html>