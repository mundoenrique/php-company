<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$urlCdn = get_cdn();
?>
<div id="content-products">
    <h1><?php echo lang('TITULO_SALDOS_AMANECIDOS'); ?></h1>
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
                <?php echo lang('BREADCRUMB_REPORTES_SALDOS'); ?>
            </a>
        </li>
    </ol>

    <div id="lotes-general">
        
        <div id="top-batchs"><span aria-hidden="true" class="icon" data-icon="&#xe07a;"></span><?php echo lang('CRITERIOS_BUSQUEDA'); ?>
        </div>
        <div id="lotes-contenedor">
            <div id="lotes-2">
                <div id="search-1">
                    <h5><?php echo lang('REPORTES_SELECCION_EMPRESA'); ?><img id ="cargando_empresa" style="display:none;width: 25px; margin-left:10px" src="<?php echo $urlCdn."media/img/loading.gif"?>"/></h5>
                    <select class="required" id = "SaldosAmanecidos-empresa">
                        <option selected="selected" value=""><?php echo lang('REPORTES_SELECCIONE_EMPRESA'); ?></option>
                    </select>
                </div>
                <div id="search-1">
                    <h5><?php echo lang('ID_PERSONA'); ?></h5>
                    <span>
                        <input id = "SaldosAmanecidos-TH"  class="required login nro" type="text" placeholder="<?php echo lang('ID_PERSONA'); ?>" value="" />
                    </span>
                </div>
                <div id="search-2">
                        <h5><?php echo lang('REPORTES_SELECCION_PRODUCTO'); ?><img id ="cargando_producto" style="display:none;width: 25px; margin-left:10px" src="<?php echo $urlCdn."media/img/loading.gif"?>"/></h5>
                        <span>
                            <select  class="required" id="SaldosAmanecidos-producto">
                                <option value="" selected="selected"><?php echo lang('REPORTES_SELECCIONE_PRODUCTO'); ?></option>
                            </select>
                        </span>
                </div>
                
            </div>
        </div>
        <div id="batchs-last">
            <span id="mensajeError" style="float:left; display:none; color:red;"> <?php echo lang('REPORTE_MENSAJE_ERROR'); ?> </span>
            <button id = "SaldosAmanecidos-btnBuscar" type="submit"><?php echo lang('REPORTE_BOTON_BUSCAR'); ?>
            </button>
        </div>
        <div id = "cargando" style = "display:none"><h2 style="text-align:center"><?php echo lang('CARGANDO'); ?></h2><img style="display:block; margin-left:auto; margin-right:auto" src="<?php echo $urlCdn."media/img/loading.gif"?>"/></div>
        <div id="div_tablaDetalle" style="display:none" class="div_tabla_detalle">
            <div id="top-batchs">
                <span aria-hidden="true" class="icon" data-icon="&#xe046;"></span> Saldos al cierre 
            </div>
            <br>
            <div id="view-results">
                <a id = "exportXLS_a">
                    <span id="export_excel" title="Exportar Excel" aria-hidden="true" class="icon" data-icon="&#xe05a;"></span>
                </a>
            </div>
            <table id="tabla-datos-general" class = "tabla-reportes">
                <thead>
                    <tr  id="datos-principales" >
                        <th class='td-largo'><?php echo lang('SALDOS_HABIENTE'); ?></th>
                        <th><?php echo lang('ID_PERSONA'); ?></th>
                        <th><?php echo lang('SALDOS_TARJETA'); ?></th>                                                
                        <th><?php echo lang('SALDOS_SALDO'); ?></th>
                        <th class='td-medio'><?php echo lang('SALDOS_ULT_ACTIVIDAD'); ?></th>
                        </tr>
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