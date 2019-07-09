<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$urlCdn = get_cdn();
?>
<div id="content-products">
  <h1><?php echo lang('TITULO_RECARGAS_REALIZADAS'); ?></h1>
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
      <a href="<?php echo $urlBase; ?>/dashboard/productos"
        rel="section"><?php echo lang('BREADCRUMB_PRODUCTOS'); ?></a>
    </li>
    /
    <li>
      <a href="" rel="section">
        <?php echo lang('BREADCRUMB_REPORTES'); ?>
      </a>
    </li>
    <li class="breadcrumb-item-current">
      <a href="" rel="section">
        <?php echo lang('BREADCRUMB_REPORTES_RECARGAS'); ?>
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
            <h5><?php echo lang('REPORTES_SELECCION_EMPRESA'); ?><img id="cargando_empresa"
                style="display:none;width: 25px; margin-left:10px" src="<?php echo $urlCdn."media/img/loading.gif"?>" />
            </h5>
            <select id="RecargasRealizadas-Empresa" name="empresa-select" class="required">
              <option value="" selected="selected"><?php echo lang('REPORTES_SELECCIONE_EMPRESA'); ?></option>
            </select>
          </div>
          <div id="search-1">
            <!--<h5>Rango</h5>-->
            <span>
              <h5><?php echo lang('REPORTES_SELECCION_MESAÑO'); ?></h5>

              <input id="repRecargasRealizadas_anio" type="text" name="my-date" placeholder="MM-YYYY"
                class="required" />
          </div>
        </form>
      </div>
    </div>

    <div id="batchs-last">
      <span id="mensajeError" style="float:left; display:none; color:red;">
        <?php echo lang('REPORTE_MENSAJE_ERROR'); ?></span>
      <?php
					if($pais=='Ec-bp'){
						?>
      <center>
        <?php
					}
				?>
        <button id="repRecargasRealizadas_btnBuscar" type="submit"
          class="novo-btn-primary"><?php echo lang('REPORTE_BOTON_BUSCAR'); ?>
        </button>
        <?php
					if($pais=='Ec-bp'){
						?>
      </center>
      <?php
					}
				?>
    </div>
    <div id="cargando" style="display:none">
      <h2 style="text-align:center"><?php echo lang('CARGANDO'); ?></h2><img
        style="display:block; margin-left:auto; margin-right:auto" src="<?php echo $urlCdn."media/img/loading.gif"?>" />
    </div>
    <div id="chart" style="display:none"></div>

    <div id="div_tablaDetalle" style="display:none" class="div_tabla_detalle">
      <div id="top-batchs">
				<?php if($pais != 'Ec-bp'): ?>
        <span aria-hidden="true" class="icon" data-icon="&#xe046;"></span>
				<?php endif;?>
				 Recargas realizadas
      </div>
      <br>
      <div id="view-results">
        <a id="exportXLS_a">
          <span id="export_excel" title="Exportar Excel" aria-hidden="true" class="icon" data-icon="&#xe05a;"></span>
        </a>
        <a id="exportPDF_a">
          <span id="export_pdf" title="Exportar PDF" aria-hidden="true" class="icon" data-icon="&#xe02e;"></span>
        </a>
        <a aria-hidden="true" class="icon" data-icon="&#xe050;">
          <span id="grafica" title="Ver Gráfica" aria-hidden="true" class="icon" data-icon="&#xe050;"></span>
        </a>

      </div>
      <table id="tabla-datos-general" class="tabla-reportes">
        <thead id="thead-datos-principales">
          <tr id="datos-principales"></tr>
        </thead>
        <tbody id="tbody-datos-general" class="tbody-reportes">
        </tbody>
      </table>

    </div>
    <form id='formulario' method='post'></form>
  </div>

</div>
