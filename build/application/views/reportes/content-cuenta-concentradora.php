<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$urlCdn = get_cdn();
?>

<div id="content-products">
	<h1><?php echo lang('TITULO_DEPOSITOS_GARANTIA'); ?></h1>
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
		<li href="" class="breadcrumb-item-current">
			<a  rel="section">
				<?php echo lang('BREADCRUMB_REPORTES_DEPOSITOS'); ?>
			</a>
		</li>
	</ol>
	<div id = "cargando2" style = "display:none"><h2 style="text-align:center"><?php echo lang('CARGANDO'); ?></h2><img style="display:block; margin-left:auto; margin-right:auto" src="<?php echo $urlCdn."media/img/loading.gif"?>"/></div>

	<div id="lotes-general">

		<div id="top-batchs"><span aria-hidden="true" class="icon" data-icon="&#xe07a;"></span> <?php echo lang('CRITERIOS_BUSQUEDA'); ?>
		</div>
		<div id="lotes-contenedor">
			<div id="lotes-2">
			<form id="form-criterio-busqueda" onsubmit="return false">
				<div id="search-1">
					<h5><?php echo lang('REPORTES_SELECCION_EMPRESA'); ?><img id ="cargando_empresa" style="display:none;width: 25px; margin-left:10px" src="<?php echo $urlCdn."media/img/loading.gif"?>" /></h5>
					<select id="repUsuario_empresa" name="empresa-select" class="required">
						<option value="" selected="selected"><?php echo lang('REPORTES_SELECCIONE_EMPRESA'); ?></option>
					</select>
				</div>
				<div id="search-1">
					<h5><?php echo lang('TITULO_REPORTES_RANGO'); ?></h5>
					<span>
						<p><?php echo lang('TITULO_REPORTES_FECHAINI'); ?></p>
						<input  id="repUsuario_fechaInicial" class=" required fecha login" type="text" name="start-dmy-date" placeholder="DD/MM/AA" value="" onFocus="javascript:this.value=''"/>
					</span>
					<span>
						<p><?php echo lang('TITULO_REPORTES_FECHAFIN'); ?></p>
						<input  id="repUsuario_fechaFinal" class=" required fecha login" type="text" name="end-dmy-date"  placeholder="DD/MM/AA" value="" onFocus="javascript:this.value=''"/>
					</span>
				</div>
				<div id="search-2">



					<table>
						<tr>

							<td style="vertical-align: top; width: 152px;">
								<h5 style="width:85px "><?php echo lang('TITULO_REPORTES_RESULTADOS'); ?></h5>
							<?php if($pais !== 'Co'): ?>
								<span>
									<input id = "trimestre" type="radio" name = "radio" value = "3" class="radio"/>
									<p><?php echo lang('REPORTES_RADIO_TRIMESTRE'); ?></p>
								</span>
								<span>
									<input id = "semestre" type="radio" name = "radio" value = "6" class="radio"/>
									<p><?php echo lang('REPORTES_RADIO_SEMESTRE'); ?></p>
								</span>
							<?php endif; ?>
								<span>
									<input id = "rango" type="radio" class="radio" name = "radio" value = "0" checked ="true"/>
									<p><?php echo lang('TITULO_REPORTES_RANGO'); ?></p>
								</span>
							</td>
							<td style="vertical-align: top; width: 152px;">
								<h5 style="width:95px "><?php echo lang('TITULO_REPORTES_PROCEDIMIENTO'); ?></h5>

								<span>
									<input id = "cargo" type="checkbox" name = "ca" value = "D" class=""/>
									<p><?php echo lang('REPORTES_RADIO_CARGO'); ?></p>
								</span>

								<span>
									<input id = "abono" type="checkbox" name = "ca" value = "C" class=""/>
									<p><?php echo lang('REPORTES_RADIO_ABONO'); ?></p>
								</span>
							</td>
						</td>
					</tr>
				</table>
			</div>


			</form>
		</div>
	</div>

	<div id="batchs-last">
		<span id="mensajeError" style="float:left; display:none; color:red;"> <?php echo lang('REPORTE_MENSAJE_ERROR'); ?></span>
		<?php
					if($pais=='Ec-bp'){
						?>
							<center>
						<?php
					}
				?>
		<button id="repUsuario_btnBuscar" type="submit" class="novo-btn-primary"><?php echo lang('REPORTE_BOTON_BUSCAR'); ?>
		</button>
		<?php
					if($pais=='Ec-bp'){
						?>
							</center>
						<?php
					}
				?>
	</div>
	<div id = "cargando" style = "display:none"><h2 style="text-align:center"><?php echo lang('CARGANDO'); ?></h2><img style="display:block; margin-left:auto; margin-right:auto" src="<?php echo $urlCdn."media/img/loading.gif"?>" /></div>
	<div id = "grafica" style = "display:none"></div>
	<div id="div_tablaDetalle" style="display:none" class="div_tabla_detalle">

		<div id="top-batchs">
			<span aria-hidden="true" class="icon" data-icon="&#xe046;"></span> Cuenta concentradora
		</div>
		<br>

		<div id="view-results">
			<a id = "exportXLS_a">
				<span id="export_excel" title = "Exportar Excel" aria-hidden="true" class="icon" data-icon="&#xe05a;"></span>
			</a>
			<a id = "exportPDF_a">
				<span id="export_pdf" title = "Exportar PDF" aria-hidden="true" class="icon" data-icon="&#xe02e;"></span>
			</a>
			<a id = "grafica_a">
				<span id="grafic" title = "Ver gráfico" aria-hidden="true" class="icon" data-icon="&#xe050;"></span>
			</a>
			<a id = "exportCon_XLS_a">
				<span id="export_excel" title = "Exportar Excel de Consolidado" aria-hidden="true" class="icon consolidado xls" data-icon="&#xe05a;"></span>
			</a>
			<a id = "exportCon_PDF_a">
				<span id="export_pdf" title = "Exportar PDF de Consolidado" aria-hidden="true" class="icon consolidado pdf" data-icon="&#xe02e;"></span>
			</a>
		</div>
		<table id="tabla-datos-general" class = "tabla-reportes  tbody-CC">
			<thead id= "thead-datos-principales">
				<tr  id="datos-principales" >
					<th class="ccf"><?php echo lang('DEPOSITOS_FECHA'); ?></th>
					<th><?php echo lang('DEPOSITOS_DESCRIPCION'); ?></th>
					<th class="ccR"><?php echo lang('DEPOSITOS_REF'); ?></th>
					<th><?php echo lang('DEPOSITOS_DEBITOS'); ?></th>
					<th><?php echo lang('DEPOSITOS_CREDITOS'); ?></th>
					<th><?php echo lang('DEPOSITOS_SALDO'); ?></th>
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
				<div id="consolid" style="display:none">
					<select id="anio">
						<option value ="">Seleccione Año</option>>
					</select>
				</div>
			<form id='formulario' method='post'></form>
		</div>
	</div>

<div id='loading' style='text-align:center' class='elem-hidden'><?php echo insert_image_cdn("loading.gif"); ?></div>
