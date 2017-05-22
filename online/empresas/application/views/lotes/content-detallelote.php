<?php
	$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;

$info;
?>

<div id="content-products">
	<h1><?php echo lang('TITULO_LOTES_DETALLE'); ?></h1>
	<ol class="breadcrumb">
		<li>
			<a href="<?php echo $urlBase; ?>/dashboard" rel="start"><?php echo lang('BREADCRUMB_INICIO'); ?></a>
		</li>
		/
		<li>
			<a href="<?php echo $urlBase; ?>/dashboard" rel="section"><?php echo lang('BREADCRUMB_EMPRESAS'); ?></a>
		</li>
		/
		<li>
			<a href="<?php echo $urlBase; ?>/dashboard/productos" rel="section">
				<?php echo lang('BREADCRUMB_PRODUCTOS'); ?></a>
		</li>
		/
		<li>
			<a href="<?php echo $urlBase; ?>/lotes" rel="section"><?php echo lang('BREADCRUMB_LOTES'); ?></a>
		</li>
		/
		<li class="breadcrumb-item-current">
			<a ><?php echo lang('POSITION_DETAIL'); ?></a>
		</li>
	</ol>
	



<div id="lotes-general">

	<?php
		if(!array_key_exists('ERROR', $data[0])){
			$info = $data[0]->lotesTO;
	?>
	<div id="top-batchs">
		<span aria-hidden="true" class="icon" data-icon="&#xe03c;"></span>
		<?php echo lang('TITULO_LOTES_DETALLE'); ?>
	</div>
	<div id="lotes-contenedor">
		<div id="detalleLote-1">
			<div id="detalleLote-1-RUC">
				<h5><?php echo lang('ID_FISCAL') ?></h5>
				<p><?php echo $info->idEmpresa ?></p>
			</div>
			<div >
				<h5><?php echo lang('TABLA_LOTESPA_NOMBREEMPRESA') ?></h5>
				<p><?php echo $info->nombreEmpresa ?></p>
			</div>
			
		</div>
		<div id="detalleLote-3">			
			<div >
				<h5><?php echo lang('TABLA_LOTESPA_OBSERVACIONES') ?></h5>
				<?php 
					foreach ($info->mensajes as $errores) {
						echo "<p>Linea: $errores->linea, $errores->mensaje ($errores->detalle)</p>";
					}
				?>
			</div>
			
		</div>
		

	</div>
	<div id="batchs-last">
		<button onclick="location.href='<?php echo $urlBase; ?>/lotes'"><?php echo lang('DETALLE_LOTES_VOLVER') ?></button>
	</div>
<?php
	}else{
		if($data[0]["ERROR"]=='-29'){
                          echo "<script>alert('Usuario actualmente desconectado');  location.reload();</script>";
                          } 
		echo '
				<div id="products-general" style="margin-top: 10px;">
				<h2 style="text-align:center">'.$data[0]['ERROR'].'</h2>
				</div>';
	}
?>
</div>

</div>