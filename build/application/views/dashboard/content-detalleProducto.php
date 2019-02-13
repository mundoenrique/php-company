<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;

$showVisa='';
$showVisaEle='none';
$showMaestro='';
$showMaster='';

$marginLeft='78px';

if($producto!=FALSE){

	$nombreProducto = ucwords(mb_strtolower($producto[0]->producto->descripcion));
	if($pais=='Pe' || $pais=='Usd' || $pais=='Co'){
		$showMaestro='none';
		$marginLeft='156px';
	}
}else{
	$nombreProducto="";
	if($msgError=='-29'){
		echo "<script>alert('Usuario actualmente desconectado'); location.href = '$urlBase/login';</script>";
	}

}


function to_ascii($word){

	$word=str_replace("á", 'a', $word);
	$word=str_replace("é", 'e', $word);
	$word=str_replace("í", 'i', $word);
	$word=str_replace("ó", 'o', $word);
	$word=str_replace("ú", 'u', $word);
	$word=str_replace("ñ", 'n', $word);
	$word=str_replace("/", '-', $word);
	return $word;
}


?>

<div id="content-products">
	<h1><?php echo $nombreProducto; ?></h1>
	<ol class="breadcrumb">
		<li>
			<a href="<?php echo $urlBase; ?>/dashboard" rel="start">Inicio </a>
		</li>
		/
		<li>
			<a href="<?php echo $urlBase; ?>/dashboard" rel="section">Empresas </a>
		</li>
		/
		<li class="breadcrumb-item-current">
			<a href="<?php echo $urlBase; ?>/dashboard/productos" rel="section">Productos</a>
		</li>
	</ol>

	<?php
	if(isset($producto) && $producto!=FALSE){

		$marcaProducto = mb_strtolower($producto[0]->producto->marca);

		$nombreTarjeta = url_title(to_ascii(mb_strtolower($producto[0]->producto->descripcion)));
		if ($marcaProducto === 'cheque') {
			$nombreTarjeta = 'plata-guarderia';
		}

		//Tarjetas
		if (isset($producto[0]->listadoTarjeta->numeroTarjetas)) {
			$tarjetas['total'] = $producto[0]->listadoTarjeta->numeroTarjetas;
			$tarjetas['activas'] = $producto[0]->listadoTarjeta->numTarjetasActivas;
			$tarjetas['inactivas'] = $producto[0]->listadoTarjeta->numTarjetasInactivas;
		}
		//Lotes
		$lotes['total'] = $producto[0]->lote->total;
		$lotes['xautorizar'] = $producto[0]->lote->numPorAutorizar;
		$lotes['xfirmar'] = $producto[0]->lote->numPorFirmar;

		//Ordenes de Servicios
		if (isset($producto[0]->ordenServicio->Total)) {
			$ordenesS['total']=$producto[0]->ordenServicio->Total;
			$ordenesS['pendientes']=$producto[0]->ordenServicio->numNoConciliada;
			$ordenesS['conciliadas']=$producto[0]->ordenServicio->numConciliada;
			$ordenesS['anuladas']=$producto[0]->ordenServicio->Total;
		}

		$css;
		if( file_exists( $this->config->item('CDN')."media/img/dashboard/productos/detalle/".$nombreTarjeta.".png") ){
			$css = insert_image_cdn("dashboard/productos/detalle/$nombreTarjeta.png");
		}else{
			$css = insert_image_cdn("dashboard/productos/detalle/default.png");
		}
	?>

		<div id="content-product-detail">
			<div id="img-product">
				<p><?php echo $css; ?></p>
			</div>
				<?php
				echo "<ul id='brands' style='margin-left:$marginLeft'>";
				if($marcaProducto=='maestro'){
					echo "
					<li style='display:$showMaestro'><p>".insert_image_cdn('marcas/maestro.png')."</p></li>
					<li style='display:$showMaster'><p class='unselected'>".insert_image_cdn('marcas/mastercard.png')."</p></li>
					<li style='display:$showVisa'><p class='unselected'>".insert_image_cdn('marcas/visa.png')."</p></li>
					<li style='display:$showVisaEle'><p class='unselected'>".insert_image_cdn('marcas/visa-electron.png')."</p></li>
					";
				}elseif ($marcaProducto=='mastercard') {
					echo "
					<li style='display:$showMaestro'><p class='unselected'>".insert_image_cdn('marcas/maestro.png')."</p></li>
					<li style='display:$showMaster'><p>".insert_image_cdn('marcas/mastercard.png')."</p></li>
					<li style='display:$showVisa'><p class='unselected'>".insert_image_cdn('marcas/visa.png')."</p></li>
					<li style='display:$showVisaEle'><p class='unselected'>".insert_image_cdn('marcas/visa-electron.png')."</p></li>
					";
				}elseif ($marcaProducto=='visa') {
					echo "
					<li style='display:$showMaestro'><p class='unselected'>".insert_image_cdn('marcas/maestro.png')."</p></li>
					<li style='display:$showMaster'><p class='unselected'>".insert_image_cdn('marcas/mastercard.png')."</p></li>
					<li style='display:$showVisa'><p>".insert_image_cdn('marcas/visa.png')."</p></li>
					<li style='display:$showVisaEle'><p class='unselected'>".insert_image_cdn('marcas/visa-electron.png')."</p></li>
					";
				}elseif ($marcaProducto=='visa-electron') {
					echo "
					<li style='display:$showMaestro'><p class='unselected'>".insert_image_cdn('marcas/maestro.png')."</p></li>
					<li style='display:$showMaster'><p class='unselected'>".insert_image_cdn('marcas/mastercard.png')."</p></li>
					<li style='display:$showVisa'><p class='unselected'>".insert_image_cdn('marcas/visa.png')."</p></li>
					<li style='display:$showVisaEle'><p>".insert_image_cdn('marcas/visa-electron.png')."</p></li>
					";
				}
				echo "</ul>";
				?>

			<?php
			$menuP =$this->session->userdata('menuArrayPorProducto');

			$nombreMarca = ' - '.ucwords($marcaProducto);
			if ($marcaProducto === 'cheque') {
				$nombreMarca = '';
			}

			echo' <div id="text-product-detail">
					<h2>Producto</h2>
					<p>'.$nombreProducto.$nombreMarca.'</p>';

			$moduloAct = np_hoplite_existeLink($menuP,"TEBCAR");
			if($moduloAct!==false){
				echo '
				<p><a href="'.$urlBase.'/lotes">
				<span aria-hidden="true" class="icon" data-icon=""></span>
				Cargar Lotes</a>
				</p>';
			}

			$moduloAct = np_hoplite_existeLink($menuP,"TEBAUT");
			if ($moduloAct!==false) {
				if ($moduloAct!==false) {
					echo '<p><a href="'.$urlBase.'/lotes/autorizacion">';
				}else{
					echo '<p> <a title="'.lang('SIN_FUNCION').'">';
				}

				echo '
				<span aria-hidden="true" class="icon" data-icon="&#xe03C;"></span>
				Lotes:
				<span class="num-product-detail">'.$lotes['total'].'</span>
				'.$lotes['xfirmar'].' Por firmar / '.$lotes['xautorizar'].' Por autorizar
				</a>
				</p>';
			}

			$moduloAct = np_hoplite_existeLink($menuP,"TEBORS");
			if (isset($ordenesS)) {
				if ($moduloAct!==false) {
					echo '<p><a href="'.$urlBase.'/consulta/ordenes-de-servicio">';
				}else{
					echo '<p> <a title="'.lang('SIN_FUNCION').'">';
				}
				echo '
				<span aria-hidden="true" class="icon" data-icon="&#xe035;"></span>
				Órdenes de Servicio:
				<span class="num-product-detail">'.$ordenesS['total'].'</span>
				'.$ordenesS['pendientes'].' No conciliadas / '.$ordenesS['conciliadas'].' Conciliadas
				</a>
				</p>';
			}

			$moduloAct = np_hoplite_existeLink($menuP,"TRAMAE");
			if (isset($tarjetas)) {
				echo'<p> <a style="cursor:default"><span aria-hidden="true" class="icon" data-icon="&#xe027;"></span>
				Tarjetas:
				<span class="num-product-detail">'.$tarjetas['total'].'</span>
				'.$tarjetas['activas'].' Activas / '.$tarjetas['inactivas'].' Inactivas
				</a><p>';
			}

			echo '
			</div>
		</div>
			';

	}else{
		echo "
		<div id='products-general' style='margin-top:20px'>
		<h2 style='text-align:center;'>$msgError</h2>
		</div>
		";

	} ?>
</div>
