<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;


$info;
log_message('info',json_encode($data));
?>
<div id="content-products">
	<h1><?php echo lang('TITULO_LOTES_CONFIRMACION'); ?></h1>
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
			<a rel="section"><?php echo lang('POSITION_CONFIRM') ?></a>
		</li>
	</ol>




	<div id="lotes-general">


			<?php
				if( !array_key_exists('ERROR', $data[0]) ){
					$info = $data[0]->lotesTO;
			?>

			<div id="top-batchs">
				<span aria-hidden="true" class="icon" data-icon="&#xe03c;"></span>
				<?php echo lang('TITULO_LOTES_CONFIRMACIONT'); ?>
			</div>
			<div id="lotes-contenedor">

				<div id="lotesConfirmacion-1">
					<div id="lotesConfirmacion-long">
						<h5><?php echo lang('ID_FISCAL') ?></h5>
						<p><?php echo $info->idEmpresa ?></p>
					</div>
					<div id="lotesConfirmacion-long">
						<h5><?php echo lang('TABLA_LOTESPA_NOMBREEMPRESA') ?></h5>
						<p><?php echo $info->nombreEmpresa ?></p>
					</div>
					<div id="lotesConfirmacion-short">
						<h5><?php echo lang('TABLA_LOTESPA_TIPOLOTE') ?></h5>
						<p id="type-1"><?php echo $info->tipoLote ?></p>
					</div>

				</div>
				<div id="lotesConfirmacion-2">
					<div id="lotesConfirmacion-long">
						<h5><?php echo lang('TABLA_LOTESPA_CANTIDADREGISTROS') ?></h5>
						<p><?php echo $info->cantRegistros ?></p>
					</div>
					<div id="lotesConfirmacion-long">
						<h5><?php echo lang('TABLA_LOTESPA_MONTOTOTAL') ?></h5>
						<p><?php echo $info->monto ?></p>
					</div>
					<div id="lotesConfirmacion-short">
						<h5><?php echo lang('TABLA_LOTESPA_NROLOTE') ?></h5>
						<p id='numLote'><?php echo $info->numLote ?></p>
					</div>
				</div>
				<div id="lotesConfirmacion-3">
					<div id="lotesConfirmacion-long">
						<h5><?php echo lang('TABLA_LOTESPA_OBSERVACIONES') ?></h5>
						<?php
							if( count($info->mensajes) > 0 ){
								foreach ($info->mensajes as $errores) {
									echo "<p>Linea: $errores->linea, $errores->mensaje ($errores->detalle)</p>";
								}
							}else{
								echo "<p>".$data[0]->msg."</p>";
							}
						?>

					</div>
					<?
					if( count($info->conceptosDinamicos) > 0 ){
					?>

						<h5 class="conceptDinam-h5"><?php echo "Concepto dinámico: " ?></h5>
						<select id="conceptoDinamico" name="conceptoDinamico">

							<?php
								foreach ($info->conceptosDinamicos as $conceptos) {
									echo "<option value='$conceptos->idConcepto'>$conceptos->concepto</option>";
								}
							?>

						</select>

					<?php } ?>

				</div>
				<?php
						if( count($info->lineasEmbozo1) > 0 ){
					?>
				<div id="lotesConfirmacion-1">



					<div >
						<h5><?php echo lang('TABLA_LOTESPA_LINEAEMBOZO1') ?></h5>
						<select id="embozo1" name="embozo1">

							<?php
								foreach ($info->lineasEmbozo1 as $embozo1) {
									echo "<option value='$embozo1->idEmbozo'>".ucwords(mb_strtolower($embozo1->textoEmbozo))."</option>";
								}
							?>

						</select>
					</div>
					<div id="lotesConfirmacion-short">
						<h5><?php echo lang('TABLA_LOTESPA_LINEAEMBOZO2') ?></h5>
						<select id="embozo2" name="embozo2">

							<?php
								foreach ($info->lineasEmbozo2 as $embozo2) {
									echo "<option value='$embozo2->idEmbozo'>".ucwords(mb_strtolower($embozo2->textoEmbozo))."</option>";
								}
							?>

						</select>
					</div>


				</div>
				<?php
						}
					?>
			</div>
			<div id="batchs-last">
				<input id="clave" class="input-clave" type="password" placeholder="<?php echo lang('MSG_INGRESE_CLAVE'); ?>" value="">
				<button onclick="location.href='<?php echo $urlBase; ?>/lotes'"><?php echo lang('BOTON_LOTES_CANCELAR') ?></button>
				<button id="confirma" ><?php echo lang('BOTON_LOTES_CONFIRMAR') ?></button>

				<input id="tipo" type='hidden' data-tipo='<?php echo $info->tipoLote ?>'/>

				<input id="info" type='hidden' name='info' value='<?php echo serialize($info) ?>'/>

				<input id="idTipoLote" type="hidden" name="idTipoLote" value='<?php echo $info->idTipoLote ?>'/>


			</div>

			<?php
				}else{
					if($data[0]['ERROR']=='-29'){
						echo "<script>alert('Usuario actualmente desconectado'); location.reload();</script>";
					}
					echo '
				<div id="products-general" style="margin-top: 10px;">
				<h2 style="text-align:center">'.$data[0]['ERROR'].'</h2>
				</div>';
				}
			?>

	</div>

</div>

<form id='toOS' action="<?php echo $urlBase ?>/consulta/ordenes-de-servicio " method="post">
	<input type="hidden" name="data-confirm" value="" id="data-confirm" />
</form>
