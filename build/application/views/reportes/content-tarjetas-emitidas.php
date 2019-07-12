<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$urlCdn = get_cdn();
?>
<div id="content-products">
	<h1><?php echo lang('TITULO_TARJETAS_EMITIDAS'); ?></h1>
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
				<?php echo lang('BREADCRUMB_REPORTES_TARJETAS'); ?>
			</a>
		</li>
	</ol>

	<div id="lotes-general">

		<div id="top-batchs">
			<?php if($pais != 'Ec-bp'): ?>
			<span aria-hidden="true" class="icon" data-icon="&#xe07a;"></span>
			<?php endif;?>
				<?php echo lang('CRITERIOS_BUSQUEDA'); ?>
		</div>
		<div id="lotes-contenedor">
			<div id="lotes-2">
				<form id="form-criterio-busqueda" onsubmit="return false">
					<div id="search-1">
						<h5><?php echo lang('REPORTES_SELECCION_EMPRESA'); ?><img id ="cargando_empresa" style="display:none;width: 25px; margin-left:10px" src="<?php echo $urlCdn."media/img/loading.gif"?>"/>
						</h5>

						<select id="repTarjetasEmitidas_empresa" name="empresa-select" class="required">
							<option value="" selected="selected"><?php echo lang('REPORTES_SELECCIONE_EMPRESA'); ?></option>
						</select>
					</div>
					<div id="search-1">
						<h5><?php echo lang('TITULO_REPORTES_RANGO'); ?></h5>
						<span>
							<p><?php echo lang('TITULO_REPORTES_FECHAINI'); ?></p>
							<input id = "repTarjetasEmitidas_fecha_in"  class="required login fecha" type="text" name="start-dmy-date" placeholder="DD/MM/AA" value="" onFocus="javascript:this.value=''"/>
						</span>
						<span>
							<p><?php echo lang('TITULO_REPORTES_FECHAFIN'); ?></p>
							<input id = "repTarjetasEmitidas_fecha_fin" class="required login fecha" type="text" name="end-dmy-date" placeholder="DD/MM/AA" value="" onFocus="javascript:this.value=''"/>
						</span>
					</div>
					<div id="search-2">
						<h5><?php echo lang('TITULO_REPORTES_RESULTADOS'); ?></h5>
						<span>
							<input type="radio" name="radio" id='radio-general' class="required" value = "0"/>
							<p><?php echo lang('REPORTES_RADIO_GENERAL'); ?></p>
						</span>
						<span>
							<input type="radio" name="radio" id='radio-producto' class="required" value = "1"/>
							<p><?php echo lang('REPORTES_RADIO_PRODUCTO'); ?></p>
						</span>
					</div>


				</form>
			</div>
		</div>
		<div id="batchs-last">
			<span id="mensajeError" style="float:left; display:none; color:red;"> <?php echo lang('REPORTE_MENSAJE_ERROR'); ?> </span>
			<?php
					if($pais=='Ec-bp'){
						?>
							<center>
						<?php
					}
				?>
			<button id="repTarjetasEmitidas_btnBuscar" type="submit" class="novo-btn-primary"><?php echo lang('REPORTE_BOTON_BUSCAR'); ?>
			</button>
			<?php
					if($pais=='Ec-bp'){
						?>
							</center>
						<?php
					}
				?>
		</div>

		<div id = "chart" style="display:none"></div>
		<div id = "cargando" style = "display:none"><h2 style="text-align:center"><?php echo lang('CARGANDO'); ?></h2><img style="display:block; margin-left:auto; margin-right:auto" src="<?php echo $urlCdn."media/img/loading.gif"?>"/></div>
		 <div id="div_tablaDetalle" class="div_tabla_detalle elem-hidden">
			<!-- <div id="top-batchs">
				<span aria-hidden="true" class="icon" data-icon="&#xe035;"></span> Resultados
			</div> -->
		<!--	<div id="view-results">
				<a>
					<span id="grafica" aria-hidden="true" class="icon" data-icon="&#xe05a;"></span>
				</a>
				<a>
					<span id="export_excel" aria-hidden="true" class="icon" data-icon="&#xe05a;"></span>
				</a>
				<a>
					<span id="export_pdf" aria-hidden="true" class="icon" data-icon="&#xe02e;"></span>
				</a>
			</div>
			<table id="tabla-datos-general">
				<thead>
					<tr  id="datos-principales">
						<tr>
						</thead>
						<tbody id="tbody-datos-general">
						</tbody>
					</table>-->

			</div>
			<form id='formulario' method='post'></form>
			<input id="producto" type='hidden' data='<?php echo lang('PRODUCTO_TARJETAS'); ?>'/>
			<input id="emision" type='hidden' data='<?php echo lang('EMISION_TARJETAS'); ?>'/>
			<input id="reptarjeta" type='hidden' data='<?php echo lang('REPTARJETA_TARJETAS'); ?>'/>
			<input id="repclave" type='hidden' data='<?php echo lang('REPCLAVE_TARJETAS'); ?>'/>
			<input id="total" type='hidden' data='<?php echo lang('TOTAL_TARJETAS'); ?>'/>
			<input id="categoria_uno" type='hidden' data='<?php echo lang('CATEGORIA_UNO'); ?>'/>
			<input id="categoria_dos" type='hidden' data='<?php echo lang('CATEGORIAL_DOS'); ?>'/>
			<input id="titulografico" type='hidden' data='<?php echo lang('GRAFICO_TITULO_TARJETAS'); ?>'/>
			<input id="titulograficotext" type='hidden' data='<?php echo lang('GRAFICO_TITULO_TEXT_TARJETAS'); ?>'/>

		</div>

	</div>
