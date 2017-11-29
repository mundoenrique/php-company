<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA . $pais;
$urlCdn = get_cdn();
?>
<div id="content-products">
    <h1><?php echo lang('TITULO_GUARDERIA'); ?></h1>
    <ol class="breadcrumb">
        <li>
            <a href="<?php echo $urlBase; ?>/dashboard" rel="start">
							<?php echo lang('BREADCRUMB_INICIO'); ?></a>
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
            <a href="" rel="section">
                <?php echo lang('BREADCRUMB_REPORTES'); ?>
            </a>
        </li>
        <li class="breadcrumb-item-current">
            <a href="" rel="section">
                <?php echo lang('BREADCRUMB_REPORTES_GUARDERIA'); ?>
            </a>
        </li>
    </ol>
    <div id="lotes-general">
        <div id="top-batchs"><span aria-hidden="true" class="icon" data-icon="&#xe07a;">
        	</span><?php echo lang('CRITERIOS_BUSQUEDA'); ?>
        </div>
        <div id="lotes-contenedor">
            <div id="lotes-2">
                <div id="search-1">
									<input id="Guarderia-riff" type="hidden" value="<?php echo $riffGuarderia;?>" />
									<input id="Empresa-nombre" type="hidden" value="" />
                    <h5><?php echo lang('TITULO_REPORTES_RANGO'); ?></h5>
                    <span>
                        <p><?php echo lang('TITULO_REPORTES_FECHAINI'); ?></p>
                        <input  id = "Guarderia-fecha-in" class="required login fecha"
																type="text" placeholder="DD/MM/AA" value="" onFocus="javascript:this.value = ''"/>
                    </span>
                    <span>
                        <p><?php echo lang('TITULO_REPORTES_FECHAFIN'); ?></p>
                        <input  id = "Guarderia-fecha-fin" class="required login fecha"
																type="text" placeholder="DD/MM/AA" value="" onFocus="javascript:this.value = ''"/>
                    </span>
                </div>
            </div>
        </div>
        <div id="batchs-last">
            <span id="mensajeError" style="float:left; display:none; color:red;">
								<?php echo lang('REPORTE_MENSAJE_ERROR'); ?></span>
            <button id= "EstatusLotes-btnBuscar" type="submit">
								<?php echo lang('REPORTE_BOTON_BUSCAR'); ?>
            </button>
        </div>
        <div id = "cargando" style = "display:none">
					<h2 style="text-align:center">Cargando Reporte</h2>
						<img style="display:block; margin-left:auto; margin-right:auto"
							src="<?php echo $urlCdn . "media/img/loading.gif" ?>"/>
				</div>
        <div id="div_tablaDetalle" class="div_tabla_detalle elem-hidden" >
            <div id="top-batchs">
                <span aria-hidden="true" class="icon" data-icon="&#xe046;"></span> Resumen de Guardería
            </div>
            <br>
            <div id="view-results">
                <a id = "exportXLS_a" >
                    <span id="export_excel" title="Exportar Excel" aria-hidden="true"
                     class="icon" target="_blank" data-icon="&#xe05a;"></span>
                </a>
                <a id="exportPDF_a" >
                    <span id="export_pdf" title="Exportar PDF"
                    aria-hidden="true" class="icon" data-icon="&#xe02e;"></span>
                </a>
            </div>
            <table id="tabla-estatus-lotes" class = "tabla-reportes tbody-statuslotes">
                <thead>
                    <tr id="datos-principales">
                        <th >Fecha de emisión OS</th>
                        <th >Número OS</th>
												<th style="max-width: 180px !important; min-width: 180px !important;">Guardería</th>
												<th >Nombre del empleado</th>
												<th >Monto</th>
												<th >Estatus (aceptado o rechazado)</th>
                    </tr>
                </thead>
                <tbody id="tbody-datos-general" class = "tbody-reportes">
                </tbody>
            </table>
						<!---
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
					-->
        </div>
        <form id='formulario' method='post'></form>
    </div>
</div>
