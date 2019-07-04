<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$urlCdn = get_cdn();
?>
<div id="content-products">
			<h1><?php echo lang('TITULO_ESTATUS_LOTES'); ?></h1>
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
						<?php echo lang('BREADCRUMB_REPORTES_ESTATUS'); ?>
					</a>
				</li>
			</ol>

	<div id="lotes-general">

				<div id="top-batchs"><span aria-hidden="true" class="icon" data-icon="&#xe07a;"></span><?php echo lang('CRITERIOS_BUSQUEDA'); ?>
				</div>
				<div id="lotes-contenedor">
				<div id="lotes-2">
					<form id="form-criterio-busqueda" onsubmit="return false">
						<div id="search-1">
							<h5><?php echo lang('REPORTES_SELECCION_EMPRESA'); ?><img id ="cargando_empresa" style="display:none;width: 25px; margin-left:10px" src="<?php echo $urlCdn."media/img/loading.gif"?>"/></h5>
							<select  id= "EstatusLotes-empresa" name="empresa-select" class="required">
								<option value="" selected="selected"><?php echo lang('REPORTES_SELECCIONE_EMPRESA'); ?></option>
							</select>
						</div>
						<div id="search-1">
							<h5><?php echo lang('TITULO_REPORTES_RANGO'); ?></h5>
							<span>
							<p><?php echo lang('TITULO_REPORTES_FECHAINI'); ?></p>
							<input  id = "EstatusLotes-fecha-in" class="required login fecha" type="text" name="start-dmy-date" placeholder="DD/MM/AA" value="" onFocus="javascript:this.value=''"/>
							</span>
							<span>
							<p><?php echo lang('TITULO_REPORTES_FECHAFIN'); ?></p>
							<input  id = "EstatusLotes-fecha-fin" class="required login fecha" type="text" name="end-dmy-date" placeholder="DD/MM/AA" value="" onFocus="javascript:this.value=''"/>
							</span>
						</div>
						<div id="search-2">
							<h5><?php echo lang('REPORTES_SELECCION_PRODUCTO'); ?><img id ="cargando_producto" style="display:none;width: 25px; margin-left:10px" src="<?php echo $urlCdn."media/img/loading.gif"?>"/></h5>
							<span>
								<select  class="required" id="EstatusLotes-producto" name="producto-select">
									<option value="" selected="selected"><?php echo lang('REPORTES_SELECCIONE_PRODUCTO'); ?></option>
								</select>
							</span>
						</div>


					</form>
				</div>
				</div>
				<div id="batchs-last">
					<span id="mensajeError" style="float:left; display:none; color:red;"><?php echo lang('REPORTE_MENSAJE_ERROR'); ?></span>
					<?php
					if($pais=='Ec-bp'){
						?>
							<center>
						<?php
					}
				?>
						<button id= "EstatusLotes-btnBuscar" type="submit" class="novo-btn-primary"><?php echo lang('REPORTE_BOTON_BUSCAR'); ?>
						</button>
						<?php
					if($pais=='Ec-bp'){
						?>
							</center>
						<?php
					}
				?>
				</div>
				<div id = "cargando" style = "display:none"><h2 style="text-align:center">Cargando Reporte</h2><img style="display:block; margin-left:auto; margin-right:auto" src="<?php echo $urlCdn."media/img/loading.gif"?>"/></div>
				<div id="div_tablaDetalle" class="div_tabla_detalle elem-hidden" >
					<div id="top-batchs">
						<span aria-hidden="true" class="icon" data-icon="&#xe046;"></span> Estatus de lotes
					</div>
					<br>
					<div id="view-results" class="view_results_help">
					<a id = "exportXLS_a" >
					<span id="export_excel" title="Exportar Excel" aria-hidden="true" class="icon" target="_blank" data-icon="&#xe05a;"></span>
				</a>
				<a id="exportPDF_a" >
					<span id="export_pdf" title="Exportar PDF" aria-hidden="true" class="icon" data-icon="&#xe02e;"></span>
				</a>
					</div>
					<table id="tabla-estatus-lotes" class = "tabla-reportes tbody-statuslotes">
						<thead>
							<tr  id="datos-principales">
								<th>Tipo de lote</th>
								<th>Num. lote</th>
								<th>Estatus</th>
								<th>Fecha carga</th>
								<th>Fecha valor</th>
								<th>Registros</th>
								<th>Monto</th>
							</tr>
						</thead>
						<tbody id="tbody-datos-general" class = "tbody-reportes">
						</tbody>
					</table>

				</div>
				<form id='formulario' method='post'></form>
	</div>


	</div>
