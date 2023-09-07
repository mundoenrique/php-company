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
	<meta name="googlebot" content="none" />
	<meta name="robots" content="noindex, nofollow" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="icon" type="image/<?= $ext ?>" href="<?php echo get_cdn(); ?>media/img/favicon.<?= $ext ?>" />
	<?php

	echo insert_css_cdn("jquery-ui.min.css");

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
		<?php if($pais == 'Ec-bp'): ?>
		<img class="img-header" src="<?= $this->asset->insertFile('logo-pichincha-azul.png', 'images', 'pb'); ?>" alt="Banco PICHINCHA">
		<?php endif; ?>
		<a id="branding" rel="start">
		</a>
		<?php if($menuHeaderActive){?>
			{menuHeader}
		<?php };?>
	</div>



</header>

<?php
	if($menuHeaderMainActive){
		$menuP =$this->session->userdata('menuArrayPorProducto');
		$menu = createMenu($menuP, TRUE);
		$settingsMenu = new stdClass();
		$settingsMenu->menu = $menu;
		$settingsMenu->pais = $this->config->item('countryUri');
		$settingsMenu->enterpriseList = $this->config->item('country').'/'.'dashboard';
		$this->load->view('widget/widget_menu-business_content', $settingsMenu);
	}
?>

<input type="hidden" id="path_JScdn" value="<? echo $urlBaseCDN;?>media/js/">

</body>
</html>
