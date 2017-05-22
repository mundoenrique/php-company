<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;

?>

<div class='content-endSession'>
	<p>:(</p>
	<h1>Ruta no encontrada</h1>

		<a href="<?php echo $urlBase ?>"><button><?php echo lang('LOGOUT_BTN_BACK') ?></button></a>
	
</div>