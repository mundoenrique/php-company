<?php
	if( ! $this->session->userdata('logged_in') ){redirect($urlBase);}
	$pais = $this->uri->segment(1);
?>
<div id="config-empresas">
	<div id = "cargando" style = "display:none">
		<h2 style="text-align:center">
		<?php echo lang('CARGANDO'); ?></h2>
		<img style="display:block; margin-left:auto; margin-right:auto"
		 		src="<?php echo $urlCdn."media/img/loading.gif"?>"/>
	</div>
	<h1><?php echo lang('NOTI_TITLE_CONFIG'); ?></h1>
	<select class="select-empresa" id="listaEmpresas" name="batch">
		<?php
			if(array_key_exists('ERROR', $listaEmpr[0])){
					if($listaEmpr[0]['ERROR']=='-29'){
						echo "<script>alert('".$listaEmpr[0]['msg']."'); location.reload();</script>";
					}
			}else{
					echo "<option value=''>Selecciona una Empresa</option>";
					foreach ($listaEmpr[0]->lista as $listado) {
						echo "<option data-rif='$listado->acrif' data-nombre='$listado->acnomcia'
										 data-accodcia='$listado->accodcia'>$listado->acnomcia</option>";
					}
			}
		?>
	</select>
</div>
