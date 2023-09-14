<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?= LANGUAGE; ?>">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta http-equiv="cleartype" content="on">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="icon" type="image/<?= $faviconExt ?>" href="<?= $this->asset->insertFile($favicon, 'images', $customerFiles, 'favicon') ?>">
	<?= $this->asset->insertCss(); ?>
	<?= (in_array($module, lang('SETT_VALIDATE_CAPTCHA')) && ACTIVE_RECAPTCHA) ?  $scriptCaptcha : ''; ?>
	<?= $this->asset->insertJs(); ?>
	<title><?= $titlePage; ?> - CEO</title>
</head>

<body>
	<?php $this->load->view('header_content-core') ?>
	<main class="content bg-content">
		<?php if (!isset($skipProductInf)) : ?>
			<div id="product-info" class="pt-3 px-5 pb-5 mt-ie11" prefix-prod="<?= $prefix ?>">
			<?php endif; ?>
			<?php foreach ($viewPage as $views) : ?>
				<?php $this->load->view($views . '_content-core'); ?>
			<?php endforeach; ?>
			<?php if (!isset($skipProductInf)) : ?>
			</div>
		<?php endif; ?>
	</main>
	<a id="download-file" href="javascript:" download></a>
	<?php $this->load->view('footer_content-core') ?>
	<?php $this->load->view('insert_variables') ?>
</body>

</html>