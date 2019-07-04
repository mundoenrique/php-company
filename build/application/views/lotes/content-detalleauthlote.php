
<?php
	$pais = $this->uri->segment(1);
	$urlBaseA = $this->config->item('base_url');
	$urlBase = $urlBaseA.$pais;

		if($dataCOS!='') {
			$bread = "BREADCRUMB_CALCULO_ORDEN_SERVICIO";
			$breadcrumb_back = $urlBase."/lotes/calculo";
		} else if($dataOS!='') {
			$bread = "BREADCRUMB_ORDEN_SERVICIO";
			$breadcrumb_back = $urlBase."/consulta/ordenes-de-servicio";
		} else if($dataLF != '') {
			$bread = 'BREADCRUMB_LOTES_POR_FACTURAR';
		} else {
			$bread = "BREADCRUMB_AUTORIZACION";
			$breadcrumb_back = $urlBase."/lotes/autorizacion";
		}
		$breadcrumb_back = $_SERVER['HTTP_REFERER'];
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
			<a href="<?php echo $breadcrumb_back; ?>" rel="section"><?php echo lang($bread); ?></a>
		</li>
	</ol>


<div id="lotes-general">

	<?php
		if(!array_key_exists('ERROR', $data[0])){
	?>

	<div id="top-batchs">
		<span aria-hidden="true" class="icon" data-icon="&#xe03c;"></span>
		<?php echo lang('TITULO_LOTES_DETALLE'); ?>
	</div>

	<div id="lotes-contenedor">

		<div id='detalle-gral'>

		<div id="detalleLote-1">
			<div id="detalleLote-1-RUC">
				<h5><?php echo lang('ID_FISCAL') ?></h5>
				<p><?php echo $data[0]->acrif ?></p>
			</div>
			<div id="detalleLote-1-nombre">
				<h5><?php echo lang('TABLA_LOTESPA_NOMBREEMPRESA') ?>:</h5>
				<p><?php echo $data[0]->acnomcia ?></p>
			</div>
			<div id="detalleLote-1-short">
				<h5><?php echo lang('TABLA_LOTESPA_TIPOLOTE') ?></h5>
				<p><?php echo $data[0]->acnombre ?></p>
			</div>
		</div>

		<div id="detalleLote-2">
			<div id="detalleLote-2-tipo">
				<h5><?php echo lang('TABLA_LOTESPA_NROLOTE') ?></h5>
				<p><?php echo $data[0]->acnumlote ?></p>
			</div>
			<div id="detalleLote-2-nro">
				<h5><?php echo lang('TABLA_LOTESPA_CANTIDADREGISTROS') ?></h5>
				<p><?php echo $data[0]->ncantregs ?></p>
			</div>
			<div id="detalleLote-2-ord">
				<h5><?php echo lang('USUARIO_CARGA') ?></h5>
				<p><?php echo $data[0]->accodusuarioc ?></p>
			</div>
		</div>

		<div id="detalleLote-3">
			<div id="detalleLote-3-asu">
				<h5><?php echo lang('TABLA_LOTESP_FECHACARGA') ?></h5>
				<p><?php echo $data[0]->dtfechorcarga ?></p>
			</div>
			<div id="detalleLote-3-fecha">
				<h5><?php echo lang('TABLA_LOTESP_ESTATUS') ?></h5>
				<p><?php echo $data[0]->status ?></p>
			</div>
			<div id="detalleLote-3-fecha" style="border:none;">
				<h5><?php echo lang('MONTO') ?></h5>
				<p><?php echo amount_format($data[0]->nmonto) ?></p>
			</div>
		</div>

		</div>

	</div>
	<div id='detalle-reg'>
		<div id="top-batchs">
			<span aria-hidden="true" class="icon" data-icon="&#xe03c;"></span><?php echo lang('TITLE_REG_LOTES'); ?>
		</div>
		<div id="lotes-contenedor">
		<?php
				$html_view_results = "<div id=\"view-results\">
					<a id='downXLS'>
						<span aria-hidden=\"true\" class=\"icon\" data-icon=\"&#xe05a;\" title='".lang('DWL_XLS')."'></span>
					</a>
					<a id='downPDF'>
						<span aria-hidden=\"true\" class=\"icon\" data-icon=\"&#xe02e;\" title='".lang('DWL_PDF')."'></span>
					</a>
				</div>";
			if( $data[0]->ctipolote=='1' && count($data[0]->registrosLoteEmision) > 0 ){ //LOTES DE EMISION
				echo $html_view_results;
				echo '
					<table id="table-lote-detail">
						<thead>
							<th>'.lang('ID_PERSONA').'</th>
							<th class="width-td">'.lang('TABLA_REG_EMISION_NOMB').'</th>
							<th class="width-td">'.lang('TABLA_REG_EMISION_APELL').'</th>
							<th class="width-td">'.lang('TABLA_REG_EMISION_UBIC').'</th>
							<th class="width-td">'.lang('TABLA_REG_RECARGA_STATUS').'</th>
						</thead>
						<tbody>';
				foreach ($data[0]->registrosLoteEmision as $registros) {
					$statusEmision = ['0', '1', '7'];
					$statusEmisionText = lang('STATUS_EMISION_'.$registros->status);
					if(!in_array($registros->status, $statusEmision)) {
						$statusEmisionText = 'N/A';
					}
					echo '
						<tr>
							<td>'.$registros->idExtPer.'</td>
							<td class="width-td">'.$registros->nombres.'</td>
							<td class="width-td">'.$registros->apellidos.'</td>
							<td class="width-td">'.$registros->ubicacion.'</td>
							<td class="width-td">'.$statusEmisionText.'</td>
						</tr>
					';
				}
				echo '</table></tbody>';
			}elseif ($data[0]->ctipolote=='1') {
				echo "<h2>".lang('TABLA_REG_MSJ')."</h2>";
			}

			if( ($data[0]->ctipolote=='2' || $data[0]->ctipolote=='5' || $data[0]->ctipolote=='L') && count($data[0]->registrosLoteRecarga) > 0 ){ //LOTES DE RECARGA
				echo $html_view_results;
				echo '
					<table id="table-lote-detail">
						<thead>
							<th id="td-nombre-2">'.lang('ID_PERSONA').'</th>
							<th id="td-nombre-2">'.lang('TABLA_REG_RECARGA_CTA').'</th>
							<th >'.lang('TABLA_REG_RECARGA_MONTO').'</th>
							<th '.(($data[0]->ctipolote=='2') ? 'style="display:none"' : '').'>'.lang('TABLA_REG_RECARGA_STATUS').'</th>
						</thead>
						<tbody>';
				foreach ($data[0]->registrosLoteRecarga as $registros) {

					if($data[0]->ctipolote=='5') {//Lote Recarga en Línea
						$estatus = ($registros->status == '3') ? 'En Proceso' : (($registros->status == '6') ? 'Procesada' : 'Rechazada');
					}elseif($data[0]->ctipolote=='L') {//Lote Cargos en Línea
						$estatus = ($registros->status == '1') ? 'Procesada' : (($registros->status == '2') ? 'Invalida' : (($registros->status == '0') ? 'Pendiente' : 'Rechazada'));
					}

					echo '
						<tr>
							<td id="td-nombre-2">'.$registros->id_ext_per.'</td>
							<td id="td-nombre-2">'.substr_replace($registros->nro_cuenta,'*************',0,-4).'</td>
							<td >'.$registros->monto.'</td>
							<td '.(($data[0]->ctipolote=='2') ? 'style="display:none"' : '').'>'.@$estatus.'</td>
						</tr>
					';
				}
				echo '</table></tbody>';
			}elseif ($data[0]->ctipolote=='2' || $data[0]->ctipolote=='5' || $data[0]->ctipolote=='L') {
				echo "<h2>".lang('TABLA_REG_MSJ')."</h2>";
			}

			if( ($data[0]->ctipolote=='E' || $data[0]->ctipolote=='G') && count($data[0]->registrosLoteGuarderia) > 0 ){ //LOTES DE GUARDERIA  lang('TABLA_REG_GUARDERIA_NOMB') lang('TABLA_REG_GUARDERIA_APEL') lang('TABLA_REG_GUARDERIA_UBIC')
				echo $html_view_results;
				echo '
					<table id="table-lote-detail">
						<thead>
							<th >'.lang('ID_PERSONA').'</th>
							<th id="td-nombre-2">Empleado</th>
							<th id="td-nombre-2">Beneficiario</th>
							';
							if($data[0]->ctipolote=='E'){
								echo '<th id="td-nombre-2">'.lang('TABLA_REG_GUARDERIA_CTA').' beneficiario</th>';
							}
							echo '<th >Monto</th>
						</thead>
						<tbody>';
				foreach ($data[0]->registrosLoteGuarderia as $registros) {
					echo '
						<tr>
							<td>'.$registros->id_per.'</td>
							<td id="td-nombre-2">'.$registros->nombre.' '.$registros->apellido.'</td>
							<td id="td-nombre-2">'.$registros->beneficiario.'</td>';
							if($data[0]->ctipolote=='E'){
								echo '<td id="td-nombre-2">'.$registros->nro_cuenta.'</td>';
							}
							echo '
							<td >'.$registros->monto_total.'</td>
						</tr>
					';
				}
				echo '</table></tbody>';
			}elseif ($data[0]->ctipolote=='E' || $data[0]->ctipolote=='G') {
				echo "<h2>".lang('TABLA_REG_MSJ')."</h2>";
			}


			if( ($data[0]->ctipolote=='R'||$data[0]->ctipolote=='C')  && count($data[0]->registrosLoteReposicion) > 0 ){ //LOTES DE REPOSICION
				echo $html_view_results;
				echo '
					<table id="table-lote-detail">
						<thead>
							<th id="td-full">'.lang('ID_PERSONA').'</th>
							<th id="td-full">'.lang('TABLA_REG_REPOS_CTA').'</th>
						</thead>
						<tbody>';
				foreach ($data[0]->registrosLoteReposicion as $registros) {
					echo '
						<tr>';
						if($registros->nocuenta==""){
							echo '<td id="td-full">'.$registros->aced_rif.'</td>
								  <td id="td-full">No aplica</td>';
						}else{
							echo '<td id="td-full">'.$registros->aced_rif.'</td>
							<td id="td-full">'.substr_replace($registros->nocuenta,'*************',0,-4).'</td>';
						}
						echo '</tr>';
				}
				echo '</table></tbody>';
			}elseif ( $data[0]->ctipolote=='R'||$data[0]->ctipolote=='C' ) {
				echo "<h2>".lang('TABLA_REG_MSJ')."</h2>";
			}

			if( $data[0]->ctipolote=='3' && count($data[0]->registrosLoteEmision) > 0 ){ //LOTES INNOMINADA
				echo $html_view_results;
				echo '
					<table id="table-lote-detail">
						<thead>
							<th>'.lang('ID_PERSONA').'</th>
							<th id="td-nombre-2">'.lang('TITULO_TARJETA').'</th>
							<th id="td-nombre-2">'.lang('TABLA_REG_EMISION_NOMB').'</th>
							<th id="td-nombre-2">'.lang('TABLA_REG_EMISION_APELL').'</th>
						</thead>
						<tbody>';
				foreach ($data[0]->registrosLoteEmision as $registros) {
					echo '
						<tr>
							<td>'.$registros->idExtPer.'</td>
							<td id="td-nombre-2">'.$registros->nroTarjeta.'</td>
							<td id="td-nombre-2">'.$registros->nombres.'</td>
							<td id="td-nombre-2">'.$registros->apellidos.'</td>
						</tr>
					';
				}
				echo '</table></tbody>';
			}elseif ($data[0]->ctipolote=='3') {
				echo "<h2>".lang('TABLA_REG_MSJ')."</h2>";
			}

			if( ($data[0]->ctipolote=='A' || $data[0]->ctipolote=='6') && count($data[0]->registrosLoteEmision) > 0 ){ //LOTES AFILIACIÓN Y DESAFILIACIÓN
				echo $html_view_results;
				echo '
					<table id="table-lote-detail">
						<thead>
							<th>'.lang('ID_PERSONA').'</th>
							<th id="td-nombre-2">'.lang('TITULO_TARJETA').'</th>
							<th id="td-nombre-2">'.lang('TABLA_REG_EMISION_NOMB').'</th>
							<th id="td-nombre-2">'.lang('TABLA_REG_EMISION_APELL').'</th>
						</thead>
						<tbody>';
				foreach ($data[0]->registrosLoteEmision as $registros) {
					echo '
						<tr>
							<td>'.$registros->idExtPer.'</td>
							<td id="td-nombre-2">'.$registros->nroTarjeta.'</td>
							<td id="td-nombre-2">'.$registros->nombres.'</td>
							<td id="td-nombre-2">'.$registros->apellidos.'</td>
						</tr>
					';
				}
				echo '</table></tbody>';
			}elseif ($data[0]->ctipolote=='A' || $data[0]->ctipolote=='6') {
				echo "<h2>".lang('TABLA_REG_MSJ')."</h2>";
			}

			if($data[0]->ctipolote=='N' && count($data[0]->registrosLoteReposicion) > 0 ){
				//LOTES RENOVACIÓN
				echo $html_view_results;
				echo '
					<table id="table-lote-detail">
						<thead>
							<th id="td-full">'.lang('ID_PERSONA').'</th>
							<th id="td-full">'.lang('TABLA_REG_REPOS_CTA').'</th>
						</thead>
						<tbody>';
				foreach ($data[0]->registrosLoteReposicion as $registros) {
					echo '
						<tr>
							<td id="td-full">'.$registros->aced_rif.'</td>
							<td id="td-full">'.substr_replace($registros->nocuenta,'*************',0,-4).'</td>
						</tr>
					';
				}
				echo '</table></tbody>';
			}elseif($data[0]->ctipolote=='N' && count($data[0]->registrosLoteReposicion) == 0 ) {
				echo "<h2>".lang('TABLA_REG_MSJ')."</h2>";
			}
		?>
	</div>

	</div>

	<div id="batchs-last">
		<?php
	if($dataCOS!=''){
			echo "<form id='go-back' action='".$urlBase."/lotes/calculo' method='POST'>
								<input type='hidden' name='data-COS' value='".$dataCOS."' />";

		}else if($dataOS!=''){
			echo "<form id='go-back' action='".$urlBase."/consulta/ordenes-de-servicio' method='POST'>
								<input type='hidden' name='data-OS' value='".$dataOS."' />";
		}else{
			echo "<form id='go-back' action='$breadcrumb_back' method='GET'>";
		}

		echo '<button id="btn-goback" class="novo-btn-secondary">'.lang("DETALLE_LOTES_VOLVER").'</button> </form>';
		?>

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

<form id='exportTo' method='post'>
 <input type='hidden' id='data-lote' name='data-lote' value="<? echo $acidlote; ?>"/>
</form>
