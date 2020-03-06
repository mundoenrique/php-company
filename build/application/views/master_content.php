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
	<?php $this->load->view('header_content'.$newViews) ?>

	<?php if($newViews != '' && $module != 'suggestion'): ?>
		<main class="content bg-content">
		<?php if($module != 'Login'): ?>
			<div id="product-info" class="pt-3 px-5 pb-5" prefix-prod="<?= $prefix ?>">
		<?php endif; ?>
	<?php elseif($newViews == ''): ?>
		<div id="wrapper">
	<?php endif; ?>
		<?php
			foreach($viewPage as $views) {
				$this->load->view($views.'_content'.$newViews);
			}
		?>
	<?php if($newViews != '' && $module != 'suggestion'): ?>
		<?php if($module != 'Login'): ?>
			</div>
		<?php endif; ?>
		</main>
	<?php elseif($newViews == ''): ?>
		</div>
	<?php endif; ?>
	<a id="download-file" href="javascript:" download></a>
	<?php $this->load->view('footer_content'.$newViews) ?>

	<?= ($module == lang('GEN_LOGIN') && $activeRecaptcha) ?  $scriptCaptcha : ''; ?>
	<?= $this->asset->insertJs(); ?>
	<script>
		var lang = <?php print_r(json_encode($this->lang->language)); ?>;
		var baseURL = '<?= base_url(); ?>';
		var assetUrl = '<?= assetUrl(); ?>';
		var country = '<?= $countryUri; ?>';
		var newViews = '<?= $this->config->item('new-views'); ?>';
		var code = <?= isset($code) ? $code : 0; ?>;
		var title = '<?= isset($title) ? $title: ''; ?>';
		var msg = '<?= isset($msg) ? $msg : ''; ?>';
		var icon = '<?= isset($icon) ? $icon : ''; ?>';
		var data = <?= isset($data) ? $data : 0; ?>;
		var logged = <?= json_encode($this->session->has_userdata('logged')); ?>;
		var sessionTime = <?= $sessionTime; ?>;
		var callModal = <?= $callModal; ?>;
		var callServer = <?= $callServer; ?>;
	</script>
</body>
</html>
