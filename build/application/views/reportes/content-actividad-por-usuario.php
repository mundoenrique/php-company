
<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$urlCdn = get_cdn();
?>

<div id="content-products">
			<h1><?php echo lang('TITULO_ACTIVIDAD_USUARIO'); ?></h1>
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
					<a rel="section">
						<?php echo lang('BREADCRUMB_REPORTES'); ?>
					</a>
				</li>
				<li href="" class="breadcrumb-item-current">
					<a  rel="section">
						<?php echo lang('BREADCRUMB_REPORTES_ACTIVIDAD'); ?>
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
						<select id = "empresa" class = "required">
							<option value = "" selected="selected"><?php echo lang('REPORTES_SELECCIONE_EMPRESA'); ?></option>

						</select>
					</div>
					<div id="search-1">
						<h5><?php echo lang('TITULO_REPORTES_RANGO'); ?></h5>
						<span>
						<p><?php echo lang('TITULO_REPORTES_FECHAINI'); ?></p>
						<input id = "fecha_ini" name= "fech_ini" class="required login" type="text"  placeholder="DD/MM/AA" value="" />
						</span>
						<span>
						<p><?php echo lang('TITULO_REPORTES_FECHAFIN'); ?></p>
						<input id = "fecha_fin" name= "fech_fin" class="required login" type="text"  placeholder="DD/MM/AA" value="" />
						</span>
					</div>
				
				</div>
				</div>
				<div id="batchs-last">
					<span id="mensajeError" style="float:left; display:none; color:red;"><?php echo lang('REPORTE_MENSAJE_ERROR'); ?></span>
						<button type="submit" id = "btnBuscar"><?php echo lang('REPORTE_BOTON_BUSCAR'); ?>
						</button>
				</div>

				<div id = "cargando" style = "display:none">
			<h2 style="text-align:center"><?php echo lang('CARGANDO'); ?></h2>
			<img style="display:block; margin-left:auto; margin-right:auto" src="<?php echo $urlCdn."media/img/loading.gif"?>" />
		</div>

				<div id="lotes-2" style='display:none' class='resultadosAU'>
					<div id="top-batchs">
						<span aria-hidden="true" class="icon" data-icon="&#xe046;"></span> Actividad por usuario
					</div>
					<div id="view-results">
						<a id='downXLS'>
							<span aria-hidden="true" class="icon" data-icon="&#xe05a;" title='<?echo lang('DWL_XLS')?>'></span>
						</a>
						<a id='downPDF'>
							<span aria-hidden="true" class="icon" data-icon="&#xe02e;" title='<?echo lang('DWL_PDF')?>'></span>
						</a>
					</div>
					<table id="table-activ-user" class = "table-activ-user">
						<thead>
							<tr id="datos-principales-AU">
								<td>USUARIO</td>
								<td>ESTATUS</td>
								<td>ÚLTIMA CONEXIÓN</td>															
								<td >OPCIONES</td>
								<td class='elem-hidden'></td>
								
							</tr>
						</thead>
						<tbody class = "tbody-reportes-AU" >							
							
						</tbody>
					</table>

					<div id='funciones-user'>
						
					</div>
				</div>
	</div>
						
		</div>


<div id = "loadImg" style = "display:none">
	<img style="display:block; margin-left:auto; margin-right:auto" src="<?php echo $urlCdn."media/img/loading.gif"?>" />
</div>

<form id='exportTo' method='post'>
 <input type='hidden' id='data-fechaIni' name='data-fechaIni'/>
 <input type='hidden' id='data-fechaFin' name='data-fechaFin'/>
 <input type='hidden' id='data-acodcia' name='data-acodcia'/>
 <input type='hidden' id='data-acrif' name='data-acrif'/>
</form>

		