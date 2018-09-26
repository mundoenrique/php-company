<?php
	$urlBase = $this->config->item('base_url');
	$urlOrigen = (isset($_SERVER['HTTP_REFERER']))?$_SERVER['HTTP_REFERER']:null;
	$rest = substr($urlOrigen, 0, strlen($urlBase));
?>

<div id="content-condiciones">
	<h1><?php echo lang("TITULO_TERMINOS") ?></h1>
	<?php echo lang("TERMINOS") ?>
	<?php if($rest == $urlBase) : ?>
		<div style="margin-top:25px; text-align: center;">
			<a href="<?php echo $urlOrigen;?>"><button style="float: none;">Volver atrás</button></a>
		</div>
	<?php endif; ?>
</div>
