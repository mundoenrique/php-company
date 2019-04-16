<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?= $lang; ?>">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta http-equiv="cleartype" content="on">
	<meta http-equiv="pragma" content="no-cache">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="icon" type="image/<?= $ext ?>" href="<?= $this->asset->insertFile($favicon.'.'.$ext, 'images/favicon') ?>">
	<?= $this->asset->insertCss(); ?>
	<title><?= $titlePage; ?> - CEO</title>
</head>
<body base-url="<?= base_url(); ?>" asset-url="<?= assetUrl(); ?>" country="<?= $countryUri; ?>"
	pais="<?= $this->session->userdata('countryConf') ?>">
	<header id="head">
		<div id="head-wrapper">
			<a id="branding" rel="start"></a>
		</div>
	</header>

	<div id="wrapper">
	<?php
		foreach($viewPage as $views) {
			$this->load->view($views . '_content');
		}
	?>
	</div>

	<footer id="foot" class="foot">
		<div id="foot-wrapper">
			<nav id="extra-nav">
				<ul class="menu">
					<?php if(!$logged && $module !== 'login'): ?>
					<li class="menu-item signup">
						<a href="<?= base_url('home'); ?>" rel="section">
							<?= lang('BREADCRUMB_INICIO'); ?>
						</a>
					</li>
					<?php endif; ?>
					<li class="menu-item benefits">
						<a href="<?= base_url('inf-beneficios') ?>" rel="section">
							<?= lang('BREADCRUMB_BENEFICIOS') ?>
						</a>
					</li>
					<li class="menu-item terms">
						<a href="<?= base_url('inf-condiciones'); ?>" rel="section">
							<?= lang('BREADCRUMB_CONDICIONES') ?>
						</a>
					</li>
					<?php if(!$logged && $countryUri == 've'): ?>
					<li class="menu-item privacy">
						<a id='tarifas' href="<?= base_url('inf-tarifas'); ?>" rel="section">
							<? echo lang('SUBMENU_TARIFAS'); ?>
						</a>
					</li>
					<?php endif; ?>
					<?php if($logged):	?>
					<li class="menu-item privacy">
						<a id='exit' href="<?= base_url('logout'); ?>" rel="section">
							<? echo lang('SUBMENU_LOGOUT'); ?>
						</a>
					</li>
					<?php endif; ?>
				</ul>
			</nav>
			<a id="ownership" href="http://www.novopayment.com/" rel="me">
				Powered by NovoPayment, Inc.
			</a>
			<div class="separator"></div>
			<div id="credits">
				<p>© <?= date('Y'); ?> NovoPayment Inc. All rights reserved.</p>
			</div>
		</div>
	</footer>
	<?= $this->asset->insertJs(); ?>
</body>
</html>
