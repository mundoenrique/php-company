<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$urlCdn = get_cdn();
?>
<div id="content-products">
			<h1><?php echo lang('TITULO_GASTOS_POR_CATEGORIA'); ?></h1>
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
					<a  href="" rel="section">
						<?php echo lang('BREADCRUMB_REPORTES_GASTOS'); ?>
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
						<span>
						<select id = "repGastosPorCategoria_empresa" class="required">
							<option  value="" selected="selected" ><?php echo lang('REPORTES_SELECCIONE_EMPRESA'); ?></option>
						</select>
						</span>

					</div>
					<div id="search-1">
						<h5><?php echo lang('TITULO_REPORTES_RANGO'); ?></h5>
						<span>
						<p><?php echo lang('TITULO_REPORTES_FECHAINI'); ?></p>
						<input id = "repGastosPorCategoria_fecha_ini" class="required login fecha" type="text" placeholder="DD/MM/AA" value="" onFocus="javascript:this.value=''"/>
						</span>
						<span>
						<p><?php echo lang('TITULO_REPORTES_FECHAFIN'); ?></p>
						<input id = "repGastosPorCategoria_fecha_fin" class="required login fecha" type="text" placeholder="DD/MM/AA" value="" onFocus="javascript:this.value=''"/>
						</span>
						<span>
						    <h5><?php echo lang('TITULO_REPORTES_TARJETA'); ?></h5>
							<input id = "repGastosPorCategoria_tarjeta" class="required login nro" type="text" name="tarjeta" placeholder="<?php echo lang('PLACEHOLDER_TARJETA'); ?>" value="" maxlength=16/>
						</span>
						<h5><?php echo lang('TITULO_REPORTES_ANIO'); ?></h5>
						<span>
						<select id="repGastosPorCategoria_anio" style="width:75px;" class="required">
								<option selected="selected"value=""><?php echo lang('TITULO_REPORTES_ANIO'); ?></option>
						</select>
						</span>
					</div>
					<div id="search-2">
						<h5><?php echo lang('REPORTES_SELECCION_PRODUCTO'); ?><img id ="cargando_producto" style="display:none;width: 25px; margin-left:10px" src="<?php echo $urlCdn."media/img/loading.gif"?>"/></h5>
						<span>
						<select id = "repGastosPorCategoria_producto" class = "required">
							<option  value =""   selected="selected"><?php echo lang('REPORTES_SELECCIONE_PRODUCTO'); ?></option>
							<!--<option>Plata</option>-->
						</select>
						</span>
						<h5><?php echo lang('TITULO_REPORTES_RESULTADOS'); ?></h5>
						<span>
							<input id ="anual" class="radio required" name = "radio" type="radio" value = "0"/>
							<p><?php echo lang('REPORTES_RADIO_ANUAL'); ?></p>
							</span>
						<span>
							<input id ="mensual" class="radio required" name="radio" type="radio" value = "1"/>
							<p><?php echo lang('REPORTES_RADIO_MENSUAL'); ?></p>
						</span>
						<span>
						    <h5><?php echo lang('ID_PERSONA'); ?></h5>
							<input id = "repGastosPorCategoria_dni" class="required login nro" type="text" name="DNI" placeholder="<?php echo lang('ID_PERSONA'); ?>" value="" />
						</span>
					</div>
					

					
				</div>
				</div>
				<div id="batchs-last">
					<span id="mensajeError" style="float:left; display:none; color:red;"><?php echo lang('REPORTE_MENSAJE_ERROR'); ?></span>
						<button id = "repGastosPorCategoria_btnBuscar" type="submit"><?php echo lang('REPORTE_BOTON_BUSCAR'); ?>
						</button>
				</div>

				<div id = "cargando" style = "display:none"><h2 style="text-align:center">Cargando Reporte</h2><img style="display:block; margin-left:auto; margin-right:auto" src="<?php echo $urlCdn."media/img/loading.gif"?>"/></div>
				<div id="div_tablaDetalle" style="display:none" class="div_tabla_detalle"></div>
			<div id ="div-anio" class="full-width" style = "display:none">
				<div class="tabs-contenedor">
					<div id="top-batchs-CG"><span aria-hidden="true" class="icon" data-icon="&#xe046;"></span></span> <?php echo lang('TITULO_RESULTADO_GC'); ?>
					</div>
					<div id="view-results">
						<a id = "exportXLS_a" class = "exportXLS_a">
							<span id="export_excel" title="Exportar Excel" aria-hidden="true" class="icon" data-icon="&#xe05a;"></span>
						</a>
						<a id="exportPDF_a" class = "exportPDF_a">
							<span id="export_pdf" title="Exportar PDF" aria-hidden="true" class="icon" data-icon="&#xe02e;"></span>
						</a>
						<a>
						<span id = "grafica" title="Ver Grafica" aria-hidden="true" class="icon grafica" data-icon="&#xe050;"></span>
					</a>
					</div>
					<table id ="tabla-anio" class="table-GC" >
					<thead>
						<tr id = "datos-cliente" class="GC-first">
						</tr>
						<tr>
							<th class="GC-long">Fecha</th>
							<th class="GC-6"><span aria-hidden="true" class="icon" data-icon="&#xe04d;" title="Hoteles"></span></th>
							<th class="GC-9"><span aria-hidden="true" class="icon" data-icon="&#xe079;" title="Cajeros automáticos"></span></th>
							<th class="GC-2"><span aria-hidden="true" class="icon" data-icon="&#xe09a;" title="Comercio y tiendas por departamento"></span></th>
							<th class="GC-1"><span aria-hidden="true" class="icon" data-icon="&#xe0a4;" title="Alquiler de vehículos"></span></th>
							<th class="GC-3"><span aria-hidden="true" class="icon" data-icon="&#xe086;" title="Comida, despensa y restaurantes"></span></th>
							<th class="GC-7"><span aria-hidden="true" class="icon" data-icon="&#xe047;" title="Líneas aéreas y transporte"></span></th>
							<th class="GC-5"><span aria-hidden="true" class="icon" data-icon="&#xe058;" title="Farmacias"></span></th>
							<th class="GC-4"><span aria-hidden="true" class="icon" data-icon="&#xe05f;" title="Diversión y entretenimiento"></span></th>
							<th class="GC-8"><span aria-hidden="true" class="icon" data-icon="&#xe018;" title="Servicios médicos"></span></th>
							<th class="GC-10"><span aria-hidden="true" class="icon" data-icon="&#xe034;" title="Otros"></span></th>
							<th class="GC-Medium"><?php echo lang('TOTAL_GASTOS_POR_CATEGORIA'); ?></th>

						</tr>
					</thead>
					<tfoot>
						<tr id="totales">
							<th class="GC-long"><?php echo lang('TOTAL_GASTOS_POR_CATEGORIA'); ?></th>
						</tr>
					</tfoot>
					<tbody>
						<tr id="enero">
							<td class="GC-long"><?php echo lang('ENERO'); ?></td>
						</tr>
						<tr id= "febrero">
							<td class="GC-long"><?php echo lang('FEBRERO'); ?></td>
						</tr>
						<tr id= "marzo">
							<td class="GC-long"><?php echo lang('MARZO'); ?></td>
						</tr>
						<tr id= "abril">
							<td class="GC-long"><?php echo lang('ABRIL'); ?></td>
						</tr>
						<tr id= "mayo">
							<td class="GC-long"><?php echo lang('MAYO'); ?></td>
						</tr>
						<tr id= "junio">
							<td class="GC-long"><?php echo lang('JUNIO'); ?></td>
						</tr>
						<tr id= "julio">
							<td class="GC-long"><?php echo lang('JULIO'); ?></td>
						</tr>
						<tr id= "agosto">
							<td class="GC-long"><?php echo lang('AGOSTO'); ?></td>
						</tr>
						<tr id= "septiembre">
							<td class="GC-long"><?php echo lang('SEPTIEMBRE'); ?></td>
						</tr>
						<tr id= "octubre">
							<td class="GC-long"><?php echo lang('OCTUBRE'); ?></td>
						</tr>
						<tr id= "noviembre"> 
							<td class="GC-long"><?php echo lang('NOVIEMBRE'); ?></td>
						</tr>
						<tr id= "diciembre">
							<td class="GC-long"><?php echo lang('DICIEMBRE'); ?></td>
						</tr>	
					</tbody>
				</table>
				
				<!--div id="batchs-last">
					
				</div>-->
				
				
			</div></div>
			<div id = "chart" style="display:none">
				<th class="GC-6"><span aria-hidden="true" class="icon" data-icon="&#xe04d;"></span></th>
				<th class="GC-9"><span aria-hidden="true" class="icon" data-icon="&#xe079;"></span></th>
				<th class="GC-2"><span aria-hidden="true" class="icon" data-icon="&#xe09a;"></span></th>
				<th class="GC-1"><span aria-hidden="true" class="icon" data-icon="&#xe0a4;"></span></th>
				<th class="GC-3"><span aria-hidden="true" class="icon" data-icon="&#xe086;"></span></th>
				<th class="GC-7"><span aria-hidden="true" class="icon" data-icon="&#xe047;"></span></th>
				<th class="GC-5"><span aria-hidden="true" class="icon" data-icon="&#xe058;"></span></th>
				<th class="GC-4"><span aria-hidden="true" class="icon" data-icon="&#xe05f;"></span></th>
				<th class="GC-8"><span aria-hidden="true" class="icon" data-icon="&#xe018;"></span></th>
				<th class="GC-10"><span aria-hidden="true" class="icon" data-icon="&#xe034;"></span></th>
			</div>
			<div id = "div-mes" class="full-width" style ="display:none">
				
					<div id="top-batchs-CG"><span aria-hidden="true" class="icon" data-icon="&#xe03c;"></span></span> <?php echo lang('TITULO_REPORTES_RESULTADOS'); ?>
					</div>
					<div id="view-results">
						<a id = "exportXLS_a" class="exportXLS_a">
							<span id="export_excel" title="Exportar Excel" aria-hidden="true" class="icon" data-icon="&#xe05a;"></span>
						</a>
						<a id="exportPDF_a" class="exportPDF_a">
							<span id="export_pdf" title="Exportar PDF" aria-hidden="true" class="icon" data-icon="&#xe02e;"></span>
						</a>
						<a>
						<span id = "grafica"  title="Ver Grafica" aria-hidden="true" class="icon grafica" data-icon="&#xe050;"></span>
						</a>
					</div>
					<table id ="tabla-anio" class="table-GC">
					<thead>
						<tr id = "datos-cliente-mes" class="GC-first" style="margin-bottom:10px;">
						</tr>
						<tr>
							<th class="GC-long"><?php echo lang('FECHA_GASTOS_POR_CATEGORIA'); ?></th>
							<th class="GC-6"><span aria-hidden="true" class="icon" data-icon="&#xe04d;" title="Hoteles"></span></th>
							<th class="GC-9"><span aria-hidden="true" class="icon" data-icon="&#xe079;" title="Cajeros automáticos"></span></th>
							<th class="GC-2"><span aria-hidden="true" class="icon" data-icon="&#xe09a;" title="Comercio y tiendas por departamento"></span></th>
							<th class="GC-1"><span aria-hidden="true" class="icon" data-icon="&#xe0a4;" title="Alquiler de vehículos"></span></th>
							<th class="GC-3"><span aria-hidden="true" class="icon" data-icon="&#xe086;" title="Comida, despensa y restaurantes"></span></th>
							<th class="GC-7"><span aria-hidden="true" class="icon" data-icon="&#xe047;" title="Líneas aéreas y transporte"></span></th>
							<th class="GC-5"><span aria-hidden="true" class="icon" data-icon="&#xe058;" title="Farmacias"></span></th>
							<th class="GC-4"><span aria-hidden="true" class="icon" data-icon="&#xe05f;" title="Diversión y entretenimiento"></span></th>
							<th class="GC-8"><span aria-hidden="true" class="icon" data-icon="&#xe018;" title="Servicios médicos"></span></th>
							<th class="GC-10"><span aria-hidden="true" class="icon" data-icon="&#xe034;" title="Otros"></span></th>
							<th class="GC-Medium"><?php echo lang('TOTAL_GASTOS_POR_CATEGORIA'); ?></th>

						</tr>
					</thead>
					<tfoot>
						<tr id="totales-mes">
							<th class="GC-long"><?php echo lang('TOTAL_GASTOS_POR_CATEGORIA'); ?></th>
						</tr>
					</tfoot>
					<tbody id="tbody-datos-mes">
					</tbody>
				</table>
				
				<!--div id="batchs-last">
					
				</div>-->
				
				
			</div>
			<form id='formulario' method='post'></form>
	</div>
			<input id="cedula" type='hidden' data='<?php echo lang('ID_PERSONA'); ?>: '/> 
			<input id="cuenta" type='hidden' data='<?php echo lang('CUENTA_GASTOS_POR_CATEGORIA'); ?>'/> 
			<input id="rango" type='hidden' data='<?php echo lang('RANGO_GASTOS_POR_CATEGORIA'); ?>'/> 
			<input id="titulograficogc" type='hidden' data='<?php echo lang('TITULO_GRAFICO_GC'); ?>'/> 
			<input id="moneda" type='hidden' data='<?php echo lang('MONEDA'); ?>'/> 

	</div>