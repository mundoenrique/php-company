<?php defined('BASEPATH') OR exit('No direct script access allowed');

$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$ceo_name = $this->security->get_csrf_token_name();
$ceo_cook = $this->security->get_csrf_hash();
$acnomciaS = $this->session->userdata('acnomciaS');
$acdescS = $this->session->userdata('acdescS');
$acrifS = $this->session->userdata('acrifS');
$acrazonsocialS = $this->session->userdata('acrazonsocialS');

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

<h1>Productos</h1>

<ol class="breadcrumb">
	<li>
			<a href="<?php echo $urlBase; ?>/dashboard" rel="start"><?php echo lang('BREADCRUMB_INICIO'); ?></a>
	</li>
	/
	<li>
			<a href="<?php echo $urlBase; ?>/dashboard" rel="section"><?php echo lang('BREADCRUMB_EMPRESAS'); ?></a>
	</li>
	/
	<li class="breadcrumb-item-current">
			<a href="<?php echo $urlBase; ?>/dashboard/productos" rel="section"><?php echo lang('BREADCRUMB_PRODUCTOS'); ?></a>
	</li>
</ol>

<div class="filter" id="options">
	<ul class="filter-ul option-set" data-option-key="filter">
		<li class="filter-1">
			<a data-option-value="*"><? echo lang('FILTER_TODAS')?></a>
		</li>
		<select class="categories-products area" id="type-batch" name="batch">
			<option value="*" ><? echo lang('FILTER_SEL_CAT')?></option>
			<?php
				if(!array_key_exists("ERROR", $productos)){
					foreach ($listaCategorias as $lista) {
							echo '<option value=".'.url_title(to_ascii(mb_strtolower($lista->descripcion))).'">'.ucfirst(mb_strtolower($lista->descripcion)).'</option>';
					}
				}
			?>
		</select>
		<select class="categories-products tarjeta" id="type-batch" name="batch">
			<option value="*" ><? echo lang('FILTER_SEL_MARC')?></option>
			<?php
			if(!array_key_exists("ERROR", $productos)){
					foreach ($listaMarcas as $lista) {
							echo '<option value=".'.url_title(to_ascii(mb_strtolower($lista->nombre))).'">'.$lista->nombre.'</option>';
					}
			}
			?>
		</select>
		<li >
			<input id="search-filter" placeholder="<?php echo lang('BREADCRUMB_PH_BUSCAR') ?>">
		</li>
		<li class="filter-3">
			<a id="buscar" title="<?php echo lang('BREADCRUMB_TITL_BUSCAR') ?>">
				<span aria-hidden="true" class="icon" data-icon="&#xe07a;" ></span>
			</a>
		</li>
	</ul>
</div>

<form id="empresas" method="post" action="<?= str_replace('/'.$countryConf.'/','/'.$countryUri.'/',base_url('productos')); ?>">
	<input type='hidden' name='<?= $novoName ?>' value='<?= $novoCook ?>'>
</form>

<form id="productos" method="post" action="<?= str_replace('/'.$countryUri.'/','/'.$countryConf.'/', site_url('dashboard/productos/detalle')); ?>">
	<input type='hidden' name='<?php echo $ceo_name ?>' value='<?php echo $ceo_cook ?>'>
  <input type="hidden" name="data-idproducto"  />
  <input type="hidden" name="data-nombreProducto"  />
  <input type="hidden" name="data-marcaProducto" />
</form>

<?php
	$paisBase = $pais == 'bp'? $pais : null;
	if(!array_key_exists("ERROR", $productos)){
		echo "<div id='lotes-general' >
			<ul id='products-list'>";
				foreach ($productos as $producto) {

					$nombreMarca  =  url_title(to_ascii(mb_strtolower($producto->marca)));
					$nombreTarjeta = url_title(to_ascii(mb_strtolower($producto->descripcion)));
					$tipoCategoria = url_title(to_ascii(mb_strtolower($producto->categoria)));

					if ($nombreMarca === 'cheque') {
						$nombreTarjeta = 'plata-guarderia';
					}
					if($tipoCategoria=="recursos-humanos")
					{
						$tipoClass="color-product-rrhh";
						$tipoIcon="&#xe090;";
					}
					elseif ($tipoCategoria=='administracion-y-finanzas') {
						$tipoClass="color-product-admon";
						$tipoIcon="&#xe057;";

					}elseif ($tipoCategoria=='mercadeo-y-ventas' || $tipoCategoria=='marketing-y-ventas'){
						$tipoClass="color-product-mercadeo";
						$tipoIcon="&#xe051;";
					}else{
						$tipoClass="color-product-default";
						$tipoIcon="&#xe093;";
					}
					@$tjta = $this->asset->insertFile( "$nombreTarjeta.png", "images/tarjetas", $paisBase);
					if( !$this->asset->verifyFileUrl($tjta) ){
						$tjta = $this->asset->insertFile( "default.png", "images/tarjetas", $paisBase);
					}
					@$marca = $this->asset->insertFile( "$nombreMarca.png", "images/marcas", $paisBase);
					if( !$this->asset->verifyFileUrl($marca) ){
						$marca = $this->asset->insertFile( "default.png", "images/marcas", $paisBase);
					}
					echo "
					<li class='product-description ".$tipoCategoria." $nombreMarca ".url_title($producto->filial)
					."' id='$producto->idProducto'>";
//						if($pais!='Ec-bp'){
						if($pais!='bp'){

							echo "
								<span class='".$tipoClass."'>
									<span aria-hidden='true' class='icon' data-icon='".$tipoIcon."'></span>
								</span>";
						}
						echo "<div id='img-1'><img src='".$tjta."'></div>";
						echo "<div id='img-2'><img src='".$marca."'></div>";
						echo "<div id='text-desc'>
									<p class='info-producto-1'> ".strtoupper($producto->descripcion)."</p>
									<p class='text-category'>$producto->filial / $producto->categoria</p>
								</div>
								<button id='sProducto' data-nombreProducto='$producto->descripcion' data-marcaProducto='$producto->marca' data-idProducto='$producto->idProducto' type='submit' class='btn-products-bp novo-btn-primary'>
									Seleccionar
								</button>";
					echo "</li>";
				}
			echo "</ul>";
			echo "
			<div id='products-general' class='results elem-hidden'>
				<h2 style='text-align:center;' >".lang('ERROR_(-150)')."</h2>
			</div>
		</div>";
	}else{
		echo "<ul hidden id='products-list'></ul> ";
		if($productos["ERROR"]=='-29'){
				echo "<script>alert('Usuario actualmente desconectado');</script>";
				redirect($urlBase.'/login');
		}else{
			echo "<div id='products-general'>
							<h2 style='text-align:center;' ><?php echo". $productos["ERROR"] ."</h2>
						</div>";
		}
	}
?>

</div>

<div id="sidebar-products">
	<div id="widget-info">
		<?php echo $acrazonsocialS;?> /
		<?php echo lang('ID_FISCAL')." ". $acrifS;?> /
		<?php echo $acdescS;?>
	</div>
	<div id="widget-info-2">

			<?php if($pais!=='bp'){ ?>
			<button id="sEmpresa" type="submit"><?php echo lang('WIDGET_EMPRESAS_BTNSELECCIONAR') ?></button>
			<div id="sEmpresaS" style='display:none'>
		<?}else{?>
			<div id="sEmpresaS" style='display:block'>
		<?php }?>

				<select style='width: 200px;' id='empresasS'>
					<option value="0" id='seleccionar_empresaS'><?php echo lang('WIDGET_EMPRESAS_OPC_SEL_EMPRESAS') ?></option>
				</select>

				<select style='width: 200px; display:none' id='productosS'>
					<option value="0"></option>
				</select>

				<center>
					<button id='aplicar' class="novo-btn-secondary"><?php echo lang('WIDGET_EMPRESAS_BTNAPLICAR') ?></button>
				</center>
			</div>
			</div>
		<?php if ($pais !== 'bp'): ?>
		<div id="widget-info-2">
			<button id="sPrograms" ><?php echo lang('WIDGET_EMPRESAS_BTNOTROSPROGRAMAS') ?></button>
		</div>
		<?php endif; ?>
	</div>

	<input type='hidden' id='cdn' value=<?php echo get_cdn(); ?> />

