<?php
	if( ! $this->session->userdata('logged_in') ){redirect($urlBase);}
	$pais = $this->uri->segment(1);
	$urlCdn = get_cdn();
?>
<div id="config-empresas" style="width: 670px;">
	<div id = "cargando" style = "display:none">
		<h2 style="text-align:center">
		<?php echo lang('CARGANDO'); ?></h2>
		<img style="display:block; margin-left:auto; margin-right:auto"
		 		src="<?php echo $urlCdn."media/img/loading.gif"?>"/>
	</div>
	<h1><?php echo lang('NOTI_TITLE_CONFIG'); ?></h1>

		<select class="selectorNotificacion" id="listaEmpresas" name="batch"  onchange="selectorEmpresa()">
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
		<!-- <label  class="checkNotiAdj">Desea Aceptar Notificaciones?</label>
		<input type="checkbox" id="checkNoti"name="checkNoti" value="" > -->

	<!--<div id="campos-1" class="input-email">
		<span class="input-email">
			<p id="first"><?php echo lang('INFO_USER_EMAIL'); ?></p>
			<input id="email_user_noti" type="text" value="" style="float:left;" maxlength='45'/>
		</span>
	</div>
	<div id="opciones-btn">
			<button id='btn-modificar-noti' type="submit">Guardar</button>
	</div> -->
	<br>
	<div id="notificacionesRequest" style="margin-left: 10px;"></div>
</div>
