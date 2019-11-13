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
<body base-url="<?= base_url(); ?>" asset-url="<?= assetUrl(); ?>" country="<?= $countryUri; ?>">
	<?php $this->load->view('header_content'.$newViews) ?>

	<?php if($newViews != ''): ?>
		<main class="content bg-content pt-3 px-5 h3 regular">
	<?php else: ?>
		<div id="wrapper">
	<?php endif; ?>
		<?php
			foreach($viewPage as $views) {
				$this->load->view($views.'_content'.$newViews);
			}
		?>
	<?php if($newViews != ''): ?>
		</main>
	<?php else: ?>
		</div>
	<?php endif; ?>

	<?php $this->load->view('footer_content'.$newViews) ?>

	<?= ($module == 'login' && $activeRecaptcha) ?  $scriptCaptcha : ''; ?>
	<?= $this->asset->insertJs(); ?>
	<script>
		var lang = <? print_r(json_encode($this->lang->language)); ?>
	</script>
</body>
</html>
