<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$ceo_name = $this->security->get_csrf_token_name();
$ceo_cook = $this->security->get_csrf_hash();

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

    <h1>{titulo}</h1>

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

    <form id="productos" method="post" action="<?php echo site_url($pais.'/dashboard/productos/detalle'); ?>">
			<input type='hidden' name='<?php echo $ceo_name ?>' value='<?php echo $ceo_cook ?>'>
		</form>

    <?php

    if(!array_key_exists("ERROR", $productos)){
        echo "<div id='lotes-general'>
		<ul id='products-list'>";

        foreach ($productos as $producto) {

						$nombreMarca  =  url_title(to_ascii(mb_strtolower($producto->marca)));

						$nombreTarjeta = url_title(to_ascii(mb_strtolower($producto->descripcion)));
						if ($nombreMarca === 'cheque') {
							$nombreTarjeta = 'plata-guarderia';
						}

            $tipoCategoria = url_title(to_ascii(mb_strtolower($producto->categoria)));

            //VALIDACION EN LA VISTA PARA MOSTRAR EL ICONO Y LA CLASE QUE CORRESPONDE

            if($tipoCategoria=="recursos-humanos"){
                $tipoClass="color-product-rrhh";
                $tipoIcon="&#xe090;";

            }elseif ($tipoCategoria=='administracion-y-finanzas') {
                $tipoClass="color-product-admon";
                $tipoIcon="&#xe057;";

            }elseif ($tipoCategoria=='mercadeo-y-ventas' || $tipoCategoria=='marketing-y-ventas'){
                $tipoClass="color-product-mercadeo";
                $tipoIcon="&#xe051;";

            }else{
                $tipoClass="color-product-default";
                $tipoIcon="&#xe093;";
            }

            $tjta;
            if( file_exists( $this->config->item('CDN').'media/img/tarjetas/'.$nombreTarjeta.'.png') ){
                $tjta = insert_image_cdn("tarjetas/$nombreTarjeta.png");
            }else{
                $tjta = insert_image_cdn("tarjetas/default.png");
            }

            $marca;
            if( file_exists( $this->config->item('CDN').'media/img/marcas/'.$nombreMarca.'.png') ){
                $marca = insert_image_cdn("marcas/$nombreMarca.png");
            }else{
                $marca = insert_image_cdn("marcas/default.png");
            }

            echo "
			<li class='product-description ".$tipoCategoria." $nombreMarca ".url_title($producto->filial)."' id='$producto->idProducto'>";
						if($pais!='Ec-bp'){
			echo "
				<span class='".$tipoClass."'>
					<span aria-hidden='true' class='icon' data-icon='".$tipoIcon."'></span>
				</span>";
			}

			echo "<div id='img-1'>".$tjta."</div>
				<div id='img-2'>".$marca."</div>
				<div id='text-desc'>
					<p class='info-producto-1'> ".strtoupper($producto->descripcion)."</p>
					<p class='text-category'>$producto->filial / $producto->categoria</p>
				</div>
				<button id='sProducto' data-nombreProducto='$producto->descripcion' data-marcaProducto='$producto->marca' data-idProducto='$producto->idProducto' type='submit' class='btn-products-bp novo-btn-primary'>
					Seleccionar
				</button>
			</li>
			";
        }

        echo "
		</ul>
		<div id='products-general' class='results elem-hidden'>
		<h2 style='text-align:center;' >".lang('ERROR_(-150)')."</h2>
		</div>
		</div>
		";


    }else{
        echo "<ul hidden id='products-list'></ul> ";
        if($productos["ERROR"]=='-29'){
            echo "<script>alert('Usuario actualmente desconectado');</script>";
            redirect($urlBase.'/login');
        }else{
            ?>
            <div id='products-general'>
                <h2 style='text-align:center;' ><?php echo $productos["ERROR"]; ?></h2>
            </div>
            <?php
        }
    }
    ?>
</div>

<input type='hidden' id='cdn' value=<?php echo get_cdn(); ?> />


