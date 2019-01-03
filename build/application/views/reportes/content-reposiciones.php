<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$urlCdn = get_cdn();
?>
<div id="content-products">
	<h1><?php echo lang('TITULO_REPOSICIONES'); ?></h1>
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
			<a href="" rel="section">
				<?php echo lang('BREADCRUMB_REPORTES'); ?>
			</a>
		</li>
		<li class="breadcrumb-item-current">
			<a href="" rel="section">
				<?php echo lang('BREADCRUMB_REPORTES_REPOSICIONES'); ?>
			</a>
		</li>
	</ol>

	<div id="lotes-general">

		<div id="top-batchs"><span aria-hidden="true" class="icon" data-icon="&#xe07a;"></span> <?php echo lang('CRITERIOS_BUSQUEDA'); ?>
		</div>
		<div id="lotes-contenedor">
			<div id="lotes-2">
				<div id="search-1">
					<h5><?php echo lang('REPORTES_SELECCION_EMPRESA'); ?><img id ="cargando_empresa" style="display:none;width: 25px; margin-left:10px" src="<?php echo $urlCdn."media/img/loading.gif"?>"/></h5>
					<select  id="repReposiciones_empresa"class="required">
						<option value="" selected="selected"><?php echo lang('REPORTES_SELECCIONE_EMPRESA'); ?></option>
					</select>
				</div>
				<div id="search-1">
					<h5><?php echo lang('TITULO_REPORTES_RANGO'); ?></h5>
					<span>
						<p><?php echo lang('TITULO_REPORTES_FECHAINI'); ?></p>
						<input  id="repReposiciones_fechaInicial" class="required login fecha" type="text" placeholder="DD/MM/AA" value="" onFocus="javascript:this.value=''"/>
					</span>
					<span>
						<p><?php echo lang('TITULO_REPORTES_FECHAFIN'); ?></p>
						<input  id="repReposiciones_fechaFinal" class="required login fecha" type="text" placeholder="DD/MM/AA" value="" onFocus="javascript:this.value=''"/>
					</span>
					<span>
						<h5><?php echo lang('TITULO_REPORTES_TIPO'); ?></h5>
						<select class="required select" id="repReposiciones_tipoReposicion">
							<option value="" selected="selected">-</option>
							<option value="01"><?php echo lang('REPORTES_OPCION_TARJETA'); ?></option>
							<option value="02"><?php echo lang('REPORTES_OPCION_CLAVE'); ?></option>
						</select>
					</span>
				</div>
				<div id="search-2">
					<h5><?php echo lang('REPORTES_SELECCION_PRODUCTO'); ?><img id ="cargando_producto" style="display:none;width: 25px; margin-left:10px" src="<?php echo $urlCdn."media/img/loading.gif"?>"/></h5>
					<span>
						<select  class="required" id="repReposiciones_producto">
							<option value="" selected="selected"><?php echo lang('REPORTES_SELECCIONE_PRODUCTO'); ?></option>
						</select>
					</span>
					<h5><?php echo lang('TITULO_REPORTES_RESULTADOS'); ?></h5>
					<span>
						<input class="required radio" type="radio" name = "radio"  value="1"/>
						<p><?php echo lang('REPORTES_RADIO_TRIMESTRE'); ?></p>
					</span>
					<span>
						<input class="required radio" type="radio" name = "radio"  value="2"/>
						<p><?php echo lang('REPORTES_RADIO_SEMESTRE'); ?></p>
					</span>
					<span>
						<input class="required radio" type="radio" name = "radio"  value="3" checked/>
						<p><?php echo lang('REPORTES_RADIO_RANGO'); ?></p>
					</span>
					<span>
						<h5><?php echo lang('ID_PERSONA'); ?></h5> 
						<input class="required login nro" type="text" id="cedula" placeholder="<?php echo lang('ID_PERSONA'); ?>" value="" />
					</span>
				</div>



			</div>
		</div>
		<div id="batchs-last">
			<span id="mensajeError" style="float:left; display:none; color:red;"> <?php echo lang('REPORTE_MENSAJE_ERROR'); ?> </span>
			<button id="repReposiciones_btnBuscar" type="submit"><?php echo lang('REPORTE_BOTON_BUSCAR'); ?>
			</button>
		</div>
		<div id = "cargando" style = "display:none"><h2 style="text-align:center"><?php echo lang('CARGANDO'); ?></h2><img style="display:block; margin-left:auto; margin-right:auto" src="<?php echo $urlCdn."media/img/loading.gif"?>"/></div>
		<div id="div_tablaDetalle" style="display:none" class="div_tabla_detalle">
			<div id="top-batchs">
				<span aria-hidden="true" class="icon" data-icon="&#xe046;"></span> <?php echo lang('TITULO_REPORTES_RESULTADOS'); ?> 
			</div>
			
			<div id="view-results">
				<a id ="exportXLS_a">
					<span id="export_excel" title="Exportar Excel" aria-hidden="true" class="icon" data-icon="&#xe05a;"></span>
				</a>

			</div>
			<table id="tabla-datos-general" class = "tabla-reportes tbody-SC">
				<thead>
					<tr  id="datos-principales" >
						<th><?php echo lang('REPORTES_OPCION_TARJETA'); ?> </th>
						<th><?php echo lang('REPORTE_TARJETAHABIENTE'); ?></th>
						<th class='Ve'><?php echo lang('ID_PERSONA'); ?></th>
						<th class='Ve'><?php echo lang('REPORTE_FECHA_EXP'); ?></th>
						<th class='elem-hidden'>Orden de servicio</th>
						<th class='elem-hidden'>Num. factura</th>
						<tr>
						</thead>
						<tbody id="tbody-datos-general" class = "tbody-reportes">
						</tbody>
					</table>

					<!--
					<div class="Jpaginate">
                        <div id="paginacion">               
                        </div>
                    </div>
                	-->

					<div id="contend-pagination">
						<nav id="nav_left">
							<a href="#" id="anterior-22">Primera</a>
							&nbsp;
							<a href="#" id="anterior-2">&laquo;&laquo;</a>
							&nbsp;
							<a href="#" id="anterior-1">&laquo;</a>
						</nav>

							<div id="list_pagination"></div>

						<nav id="nav_right">
							<a href="#" id="siguiente-1">&raquo;</a>
							&nbsp;
							<a href="#" id="siguiente-2">&raquo;&raquo;</a>
							&nbsp;
							<a href="#" id="siguiente-22">&Uacute;ltima</a>
						</nav>
					</div>

				</div>
				<form id='formulario' method='post'></form>
			</div>
			

		</div>