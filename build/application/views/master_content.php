<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?= LANGUAGE; ?>">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta http-equiv="cleartype" content="on">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="icon" type="image/<?= $ext ?>" href="<?= $this->asset->insertFile($favicon.'.'.$ext, 'images/favicon') ?>">
	<?= $this->asset->insertCss(); ?>
	<title><?= $titlePage; ?> - CEO</title>
</head>

<body>
	<?php $this->load->view('header_content') ?>
	<div id="wrapper">
		<?php foreach($viewPage as $views): ?>
		<?php $this->load->view($views.'_content'); ?>
		<?php endforeach; ?>
	</div>
	<?php $this->load->view('footer_content') ?>
	<?= (in_array($module, lang('SETT_VALIDATE_CAPTCHA')) && ACTIVE_RECAPTCHA) ?  $scriptCaptcha : ''; ?>
	<?= $this->asset->insertJs(); ?>
	<?php $this->load->view('insert_variables') ?>
</body>

</html>
