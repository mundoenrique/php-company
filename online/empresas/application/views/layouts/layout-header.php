<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$nombreCompleto = $this->session->userdata('nombreCompleto');

$style_css = $this->uri->segment(3); 

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>{titlePage}</title>
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width" />
<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<?php echo insert_css_cdn("default.css");?>
	<?php 

		if ($style_css==="pass_recovery") 
		{
				
			echo insert_css_cdn("signin.css");
		
		}
		
	?>
	<?php echo insert_js_cdn("html5.js");?>
</head>
<body <?php if(isset($bodyclass)){echo 'class="'.$bodyclass.'"'; }?> >	
	<header id="head">
		<div id="head-wrapper">
			<a id="branding" rel="start">
			</a>
			<?php if($menuHeaderActive){?>
			{menuHeader}
		<?php };?>
		</div>

	
			
	</header>
	
	<?php if($menuHeaderMainActive){
	$menuP =$this->session->userdata('menuArrayPorProducto');

	?>

	<div id="nav-bar2">
		<?php echo np_hoplite_crearMenu($menuP,$pais,$urlBaseA);?>
	</div>
			<?php };?>

	<input type="hidden" id="path_JScdn" value="<? echo $this->config->item('base_url_cdn')?>media/js/">

</body>
</html>
