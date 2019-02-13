<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;

$datos=null;

if($osConfirmV){

	$datos = unserialize($osConfirmV);
	$acrifS = $this->session->userdata('acrifS');
	$acrazonsocialS = $this->session->userdata('acrazonsocialS');
	$acnomciaS = $this->session->userdata('acnomciaS');

	if(!$datos){
		$datos= array('ERROR'=>lang('ERROR_NO_DATA'));
	}

}

?>
<div id="content-products">
	<h1 ><?php echo lang('TITULO_CONSULTA_OS'); ?></h1>
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
			<a href="<?php echo $urlBase; ?>/dashboard/productos" rel="section"><?php echo lang('BREADCRUMB_PRODUCTOS'); ?></a>
		</li>
		/
		<li>
			<a href="<?php echo $urlBase; ?>/lotes/autorizacion" rel="section"><?php echo lang('BREADCRUMB_AUTORIZACION'); ?></a>
		</li>
		/
		<li>
			<a rel="section"><?php echo lang('BREADCRUMB_ORDEN_SERVICIO'); ?></a>
		</li>
	</ol>

	<div id="lotes-general" class='elem-hidden'>				

		<div id='filtroOS' >

			<div id="top-batchs">
				<span aria-hidden="true" class="icon" data-icon="&#xe07a;"></span>
				<?php echo lang('TITULO_CRITERIOSBUSQ'); ?>
			</div>

			<div id="lotes-contenedor" >
				<span class="info-OD">
					<h5>5 días</h5>
					<input  class="required login" type="radio" name="dias" value="5" />
				</span>
				<span class="info-OD">
					<h5>10 días</h5>
					<input  class="required login" type="radio" name="dias" value="10" />
				</span>	
				<span class="info-OD">
					<h5>Fecha inicial</h5>
					<input id='fecha_inicial' class="required login" placeholder="DD/MM/AA" value="" onfocus="javascript:this.value=''"/>
				</span>
				<span class="info-OD">
					<h5>Fecha final</h5>
					<input id='fecha_final' class="required login" placeholder="DD/MM/AA" value="" onfocus="javascript:this.value=''"/>
				</span>
				<div class="info-OD">
					<h5>Estatus de Lote</h5>
					<select id="status_lote" name="batch">
						<?
						if( array_key_exists("ERROR", $tipoStatus[0]) ){
							if($tipoStatus['ERROR']=='-29'){
								echo "<script>alert('Usuario actualmente desconectado'); location.reload();</script>";
							}else{
								echo "<option value='' selected='selected'>No disponible</option>";
							}
						}else{
							echo '<option value="" selected="selected">Selección</option>';
							foreach ($tipoStatus[0]->lista as $tipos) {
								echo '<option value="'.$tipos->codEstatus.'">'.ucfirst(mb_strtolower($tipos->descEstatus)).'</option>';
							}
						}?>
					</select>
				</div>
			</div>
			
			<div id="batchs-last">
				<button id='buscarOS'>Buscar</button>
			</div>

		</div>
		
		<?php 
		if(isset($datos) && $datos!=false){

			if( array_key_exists("ERROR", $datos) ) {
				
				if($datos['ERROR']=='-29'){
					echo "<script>alert('Usuario actualmente desconectado'); location.reload();</script>";
				}
		?>
				<div id="top-batchs" class='top-listOS'>
					<span aria-hidden="true" class="icon" data-icon="&#xe035;"></span>
					<?php echo lang('TITULO_CONSULTA_OSE'); ?>
				</div>
				<div id="lotes-contenedor">
					<h2><?php echo $datos['ERROR'] ?></h2>
				</div>
	<?php	}

			if(array_key_exists('lista',$datos) ) { 
				if(count($datos->lista)>0){
	?>
					<div id="top-batchs" class='top-listOS'>
						<span aria-hidden="true" class="icon" data-icon="&#xe035;"></span>
						<?php echo lang('TITULO_CONSULTA_OSE'); ?>
					</div>
					<div id="lotes-contenedor">
						<table id="tabla-datos-general" class="tabla-reportes-OS">
							<thead>
								<tr id="datos-principales-OS">
									<th style='display:none'></th>
									<th style='display:none'></th>
									<th><?php echo lang('TABLA_COS_ID_ORDEN') ?></th>
									<th><?php echo lang('TABLA_COS_FECHA') ?></th>
									<th><?php echo lang('ID_FISCAL') ?></th>
									<th class="th-empresa"><?php echo lang('TABLA_COS_EMPRESA') ?></th>
									<th><?php echo lang('TABLA_COS_MONTO_OS') ?></th>
									<th><?php echo lang('TABLA_COS_MONTO_DEPOSITADO') ?></th>
									<th>Opt</th>
								</tr>
							</thead>
							<tbody id="tbody-datos-general" class="tbody-reportes-OS jslista">
								<?php 
								$lc=0;
								$tempidOrdenLotes=array();

								foreach ($datos->lista as $value) {
									array_push($tempidOrdenLotes, $value->idOrdenTemp);
									$ltr="<tr class='OShead-2 OSinfo $value->idOrden'>
									<td>".lang('TABLA_COS_NRO_LOTE')."</td>
									<td>".lang('TABLA_COS_FECHA')."</td>
									<td>".lang('TABLA_COS_TIPO')."</td>
									<td>".lang('TABLA_COS_CANT')."</td>
									<td>".lang('TABLA_COS_STATUS')."</td>
									<td>".lang('TABLA_COS_MONTO_RECA')."</td>
									<td>".lang('TABLA_COS_MONTO_COMI')."</td>
									<td>".lang('TABLA_COS_MONTO_TOTAL')."</td>
									</tr>";

									foreach ($value->lotes as $l) {

										$ltr .= "<tr class='OSinfo $value->idOrden'>
										<td><a id='$l->acidlote' class='viewLo' title='Detalle lote'>$l->acnumlote</a></td>
										<td>$l->dtfechorcarga</td>
										<td>".ucfirst(mb_strtolower($l->acnombre))."</td>
										<td>$l->ncantregs</td>
										<td>".ucfirst(mb_strtolower($l->status))."</td>
										<td>$l->montoRecarga</td>
										<td>$l->montoComision</td>
										<td>$l->montoNeto</td>
										</tr>";
									} 
									echo "
									<tr id='$value->idOrden'>
									<td class='OS-icon' style='display:none;'>
									<a id='ver_lotes' title='Ver lotes'>
									<span aria-hidden='true' class='icon' data-icon='&#xe003;'></span> 
									</a>
									<a id='dwnPDF' title='Descargar como PDF'>
									<span aria-hidden='true' class='icon' data-icon='&#xe02e;'></span>
									</a>"; 
									//if( ($pais=='Ve' && $value->nofactura!='' && $value->fechafactura!='') || ($pais=="Co" && $value->estatus=='1') ){
									if(($value->nofactura!=''&&$value->fechafactura!='')&&($pais=='Ve'||$pais=="Co")){
										echo "<a id='factura' title='Ver factura' data-dw='$datos->facturacion' >
										<span aria-hidden='true' class='icon' data-icon='&#xe009;'></span>
										</a>";
									}
								/*	if($value->lotes[0]->cestatus=='2'){
								echo	"<a id='pagoOS' title='Registar pago'>
										<span aria-hidden='true' class='icon' data-icon='&#xe004;'></span>
									</a>";
								}*/ //requirimiento anulado
								if(in_array('tebanu', $funciones)){
									echo "
									<a id='anular' title='Anular Orden'>
									<span aria-hidden='true' class='icon' data-icon='&#xe06f;'></span>
									</a>
									";
								}
								echo "								
								</td>
								<td>$value->idOrden</td>
								<td>$value->fechaGeneracion</td>
								<td>$acrifS</td>
								<td class='th-empresa'>".ucwords(mb_strtolower($acnomciaS))."</td>
								<td>$value->montoOS</td>
								<td>$value->montoDeposito</td>
								<td style='float:left; padding:0; '><table><tbody>$ltr</tbody></table></td>
								</tr>

								";

								}
								$tempIdOrdenL=serialize($tempidOrdenLotes);
								
								echo "<input type='hidden' id='tempIdOrdenL' name='tempIdOrdenL' value='$tempIdOrdenL' />";							
								
								?>					
							</tbody>
						</table>					
					</div>
		  <?php } 
			}

			if(array_key_exists('lotesNF',$datos) ){ 
				if(count($datos->lotesNF)>0){
					?>
					<div id="top-batchs" >
						<span aria-hidden="true" class="icon" data-icon="&#xe046;"></span>
						<?php echo lang('TITULO_CONSULTA_OS_LOTESNF') ?>
					</div>
					<div id="lotes-contenedor">
						<table id='tablelotesNF' class="tabla-lotesNF">
							<thead>
								<tr id="datos-principales" class="tabla-lotesNF">
									<th><?php echo lang('ID_FISCAL') ?></th>
									<th class="th-empresa"><?php echo lang('TABLA_COS_EMPRESA') ?></th>
									<th><?php echo lang('TABLA_COS_NRO_LOTE')?></th>
									<th><?php echo lang('TABLA_COS_FECHA_CARGA') ?></th>
									<th><?php echo lang('TABLA_COS_TIPO_LOTE').' / '.lang('TABLA_CANT_REG') ?></th>
									<th><?php echo lang('TABLA_COS_STATUS') ?></th>
								</tr>
							</thead>
							<tbody id="tbody-datos-general" class="tbody-reportes-OS jslistaNF">
								<?php
								$tempidOrdenLotesNF=array();
								foreach ($datos->lotesNF as $valueNF) {
									array_push($tempidOrdenLotesNF, $valueNF->acidlote);
									echo "
									<tr>
									<td>$acrifS</td>
									<td class='th-empresa'>".ucwords(mb_strtolower($acnomciaS))."</td>
									<td><a id='$valueNF->acidlote' class='viewLo' title='Detalle lote'>$valueNF->acnumlote</a></td>
									<td>$valueNF->dtfechorcarga</td>
									<td>".ucwords(mb_strtolower($valueNF->acnombre))." / $valueNF->ncantregs</td>
									<td>".ucfirst(mb_strtolower($valueNF->status))."</td>
									</tr>
									";
								}
								$tempIdOrdenLNF=serialize($tempidOrdenLotesNF);
								echo "<input type='hidden' id='tempIdOrdenLNF' name='tempIdOrdenLNF' value='$tempIdOrdenLNF' />";
								?>
							</tbody>
						</table>

					</div>
		  <?php }
			}

			if( !array_key_exists("ERROR", $datos) && $datos->rc==-88 ){
				echo "<input type='hidden' id='msg' value='$datos->msg' />";
			}

		} ?>

	</div>
				
</div>

<form id='formulario' method='post'></form>

<form id='detalle_lote' method='post' action="<?php echo $urlBase ?>/lotes/autorizacion/detalle">
	<input type='hidden' id='data-OS' name='data-OS' value='<?php echo serialize($datos) ?>' />
</form>

<div id='loading' style='text-align:center' class='elem-hidden'><?php echo insert_image_cdn("loading.gif"); ?></div>


