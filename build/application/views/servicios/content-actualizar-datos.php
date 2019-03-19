
<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
?>

<div id="content-products">
			<h1><?php echo lang('TITULO_ACTUALIZAR_DATOS'); ?></h1>

			<h2 class="title-marca">
				<?php echo ucwords(mb_strtolower($programa));?>
			</h2>

			<ol class="breadcrumb">
				<li>
					<a href="<?php echo $urlBase; ?>/dashboard" rel="start">
						<?php echo lang('BREADCRUMB_INICIO'); ?>
					</a>
				</li>
				/
				<li>
					<a href="<?php echo $urlBase; ?>/dashboard" rel="section">
						<?php echo lang('BREADCRUMB_EMPRESAS'); ?></a>
				</li>
				/
				<li>
			<a href="<?php echo $urlBase; ?>/dashboard/productos" rel="section">
				<?php echo lang('BREADCRUMB_PRODUCTOS'); ?></a>
		</li>
		/
				<li>
					<a rel="section">
						<?php echo lang('BREADCRUMB_SERVICIOS'); ?>
					</a>
				</li>
				/
				<li class="breadcrumb-item-current">
					<a href="<?php echo $urlBase; ?>/servicios/actualizar-datos" rel="section">
						<?php echo lang('BREADCRUMB_ACTUALIZAR_DATOS'); ?>
					</a>
				</li>
			</ol>



	<div id="lotes-general">

				<div id="top-batchs">
					<span aria-hidden="true" class="icon" data-icon="&#xe08c;"></span> <?php echo lang('TOP_CARGAR_ARCHIVO') ?>
				</div>
				<div id="lotes-contenedor">
					<input type="file" name="userfile" id="userfile" class='elem-hidden'/>
				 	<input id='archivo' placeholder='Click aquí para archivo seleccionar archivo.' readonly="readonly" size='70'/>
					<button id="cargarXLS" ><?php echo lang('BTN_CARGAR_ARCHIVO'); ?></button>
				</div>

				<div id="top-batchs">
					<span aria-hidden="true" class="icon" data-icon="&#xe07a;"></span> <?php echo lang('TOP_BUSCAR_ARCHIVO') ?>
				</div>

				<div id="lotes-contenedor">
					<div id="search-1">
						<h5>Nombre</h5>
						<input id="nombre" placeholder="Ingrese nombre del archivo" class="input-AD" style="width: 200px;"/>
					</div>

					<div id="search-3">
						<h5>Estatus</h5>
						<select name="estatus" id="estatus">
						<?
							if(array_key_exists("ERROR", $estatus)){
								echo "<option value=''>".$estatus["ERROR"]."</option>";
							}else{
								foreach ($estatus as $key => $value) {
									echo "<option value='".$value->estatus."' descargable='".$value->descarga."'>".$value->nombreStatus."</option>";
								}
							}
						?>
						</select>

					</div>

				</div>

				<div id="batchs-last">
					<button id='buscar-datos'><?php echo lang('BTN_BUSCAR_ARCHIVO'); ?></button>
				</div>

				<div id='resultado-busqueda' class='elem-hidden'>

					<div id="top-batchs">
						<span aria-hidden="true" class="icon" data-icon="&#xe056;"></span> <?php echo lang('TOP_RESULTADO') ?>
					</div>
					<div id='lotes-contenedor' >

						<table class="tabla-reportes" id="tabla-act-datos">
							<thead>
								<tr id="datos-principales">
									<th  >Archivo</th>
									<th class='td-medio'>Lote Nro.</th>
									<th >Estatus</th>
									<th class='td-medio'>Fecha reg.</th>
									<th id='td-nombre-2'>Observaciones</th>
								<!-- 	<th class='op-AD td-corto'>Opción</th> -->
								</tr>
							</thead>
							<tbody class="tbody-reportes">
								<!-- <tr>
									 						<td class='ampliar' >ZESJ-31184769-32012020102.xls</td>
									 						<td >123456</td>
									 						<td >en construcción</td>
									 						<td class='ampliar'>qLorem ipsum dolor sit amet, consectetur adipisicing elit. Quae, facere, nemo,</td>
									 						<td class='op-AD'>
									 							<a id='downXLS'>
									 								<span aria-hidden="true" class="icon" data-icon="&#xe05a;" title='Descargar'></span>
									 							</a>
									 						</td>
									 					</tr> -->
							</tbody>
						</table>

						<div id='paginado-'></div>

					</div>

				</div>
	</div>
</div>

<div id='loading' style='text-align:center' class='elem-hidden'><?php echo insert_image_cdn("loading.gif"); ?></div>
<form id='formulario' method='post' ></form>
