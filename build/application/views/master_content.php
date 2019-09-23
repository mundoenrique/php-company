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

<body base-url="<?= base_url(); ?>" asset-url="<?= assetUrl(); ?>" country="<?= $countryUri; ?>"
	pais="<?= $countryConf; ?>"
	type-over-detail-companies="<?= $settingContents['enterprise_content']['typeOverDetailCompanies'];?>"
	show-razon-social-detail-companies="<?= $settingContents['enterprise_content']['showRazonSocialDetailCompanies'];?>"
	>
	<?php if($settingContents['master_content']['logo'] || $module != 'login'): ?>
		<header id="head">
			<div id="head-wrapper">
				<?php if( lang('LOGO-HEAD') ): ?>
					<img class="img-header" src="<?= $this->asset->insertFile( lang('LOGO-HEAD'), 'images'); ?>"
						alt="Banco PICHINCHA">
				<?php endif; ?>
				<a id="branding" rel="start"></a>
				<?php
					if($logged) {
						$this->load->view('widget/widget_menu-user_content');
					}
				?>
			</div>
		</header>
	<?php endif; ?>

	<?php
		if($logged) {
			$this->load->view('widget/widget_menu-business_content', $settingsMenu);
		}
	?>

	<div id="wrapper">
		<?php
			foreach($viewPage as $views) {
			$this->load->view($views . '_content');
			}
		?>
	</div>

	<footer id="foot" class="foot">
		<div id="foot-wrapper">
			<?php if ($settingContents['master_content']['menuFooter']): ?>
				<nav id="extra-nav">
					<ul class="menu">
						<?php if(!$logged && $module !== 'login'): ?>
						<li class="menu-item signup">
							<a id="signup" href="<?= base_url($goOut); ?>" rel="section">
								<?= lang('BREADCRUMB_INICIO'); ?>
							</a>
						</li>
						<?php endif; ?>
						<?php if($module !== 'benefits' && $module !== 'change-password' && $module !== 'terms'): ?>
						<li class="menu-item benefits">
							<a href="<?= base_url('inf-beneficios') ?>" rel="section">
								<?= lang('BREADCRUMB_BENEFICIOS') ?>
							</a>
						</li>
						<?php endif; ?>
						<?php if($module !== 'terms' && $module !== 'change-password'): ?>
						<li class="menu-item terms">
							<a href="<?= base_url('inf-condiciones'); ?>" rel="section">
								<?= lang('BREADCRUMB_CONDICIONES') ?>
							</a>
						</li>
						<?php endif; ?>
						<?php if($logged && $settingContents['master_content']['showRates'] && $module !== 'rates'): ?>
						<li class="menu-item privacy">
							<a id='tarifas' href="<?= base_url('inf-tarifas'); ?>" rel="section">
								<?= lang('SUBMENU_TARIFAS'); ?>
							</a>
						</li>
						<?php endif; ?>
						<?php if($logged):	?>
						<li class="menu-item privacy">
							<a id='exit' href="<?= base_url('cerrar-sesion'); ?>" rel="section">
								<?= lang('SUBMENU_LOGOUT'); ?>
							</a>
						</li>
						<?php endif; ?>
					</ul>
				</nav>
			<?php endif; ?>

			<?php if($settingContents['master_content']['ownerShip'] !== FALSE): ?>
				<a id="ownership" href="<?= $settingContents['master_content']['ownerShip'] ?>" rel="me">
					<?= lang('OWNERSHIP'); ?>
				</a>
				<div class="separator"></div>
				<div id="credits">
					<p>© <?= date('Y').' '.lang('CREDITS'); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</footer>

	<div id="loader" class="hidden">
		<img src="<?= $this->asset->insertFile($loader, 'images/loading-gif') ?>" class="requesting" alt="<?= lang('ALT_LOADER'); ?>">
	</div>

	<div id="system-info" class="hidden">
		<p>
			<span id="system-msg" class="system-msg"><?= lang('MESSAGE_SYSTEM'); ?></span>
		</p>
		<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
			<div class="ui-dialog-buttonset novo-dialog-buttonset">
				<button type="button" id="cancel" class="cancel-button novo-btn-secondary-modal dialog-buttons"><?= lang('BUTTON_CANCEL'); ?></button>
				<button type="button" id="accept" class="novo-btn-primary-modal dialog-buttons"><?= lang('BUTTON_ACCEPT'); ?></button>
			</div>
		</div>
	</div>
	<?php
		if($module == 'login') {
		echo 	$scriptCaptcha;
		}
		echo $this->asset->insertJs();
	?>
</body>

</html>
