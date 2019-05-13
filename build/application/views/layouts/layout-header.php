<?php
$CI =& get_instance();
$pais = $CI->config->item('country');
$urlBase= $CI->config->item('base_url');
$urlBaseCDN = $CI->config->item('base_url_cdn');
$nombreCompleto = $this->session->userdata('nombreCompleto');
$ext = "png";

switch($pais) {
	case 'Ec-bp':
		$ext = "ico";
		break;
}

$style_css = $this->uri->segment(3);

?>

<!DOCTYPE html>
<html>
<head lang="es">
	<meta charset="utf-8" />
	<title>{titlePage}</title>
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="cleartype" content="on" />
	<meta http-equiv="pragma" content="no-cache" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="icon" type="image/<?= $ext ?>" href="<?php echo get_cdn(); ?>media/img/favicon.<?= $ext ?>" />
	<?php

	echo insert_css_cdn("default.css");

	if ($style_css==="pass_recovery") {
		echo insert_css_cdn("signin.css");
	}

	if (isset($css)){
		foreach ($css as $styleCss) {
			echo insert_css_cdn($styleCss);
		}
	}

	echo insert_js_cdn("html5.js");
	?>
</head>
<body <?php if(isset($bodyclass)){echo 'class="'.$bodyclass.'"'; }?> data-country="<?php echo $pais; ?>" data-app-base="<?php echo $urlBase;?>" data-app-base-cdn="<?php echo $urlBaseCDN;?>">
<header id="head">
	<div id="head-wrapper">
		<a id="branding" rel="start">
		</a>
		<?php if($menuHeaderActive){?>
			{menuHeader}
		<?php };?>
	</div>



</header>

<?php
	if($menuHeaderMainActive):
		$menuP =$this->session->userdata('menuArrayPorProducto');
		$menu = createMenu($menuP,$pais);
?>
	<div id="nav-bar2">
		<nav id="nav2">
			<ul style="margin:0">
				<li>
					<a href="<?=base_url($pais.'/dashboard')?>" rel="start" >
						<span aria-hidden="true" class="icon" data-icon="&#xe097;"></span>
						<?=lang('MENU_INICIO')?>
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
										<a href="<?=$lvlTwoOpt['route']?>">
											<?=$lvlTwoOpt['text']?>
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
										<? endif; ?>
									</li>
								<?php endforeach; ?>
								<div id="scrolldown" style="display:none">
									<span class="ui-icon ui-icon-triangle-1-s"></span>
								</div>
							</ul>
						<? endif; ?>
					</li>
				<?php endforeach; ?>
				<li>
					<a href="<?=base_url($pais.'/logout')?>" rel="subsection">
						<span aria-hidden="true" class="icon" data-icon="&#xe03e;"></span>
						<?=lang("SUBMENU_LOGOUT")?>
					</a>
				</li>
			</ul>
		</nav>
	</div>
<?php
	endif;
?>

<input type="hidden" id="path_JScdn" value="<? echo $urlBaseCDN;?>media/js/">

</body>
</html>
