<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$urlCdn = get_cdn();

?>
<div id="content-products">
  <h1><?php echo lang('TITULO_ESTADOS_DE_CUENTA'); ?></h1>
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
        <?php echo lang('BREADCRUMB_REPORTES_ESTADOS'); ?>
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
      <form id="form-criterio-busqueda" onsubmit="return false">
        <div id="lotes-2">
          <div id="search-1">
            <h5><?php echo lang('REPORTES_SELECCION_EMPRESA'); ?><img id="cargando_empresa"
                style="display:none;width: 25px; margin-left:10px" src="<?php echo $urlCdn."media/img/loading.gif"?>" />
            </h5>
            <select id="repEstadosDeCuenta_empresa" name="empresa-select" class="required ">
              <option value="" selected="selected"><?php echo lang('REPORTES_SELECCIONE_EMPRESA'); ?></option>
            </select>
          </div>
          <div id="search-1">
            <h5><?php echo lang('DEPOSITOS_FECHA'); ?></h5>
            <span>
              <p><?php echo lang('REPORTES_SELECCION_MESAÑO'); ?></p>
              <input id="fecha_ini" class="required login fecha" type="text" name="my-date" placeholder="MM/AAAA"
                value="" onFocus="javascript:this.value=''" />
              <input id="repEstadosDeCuenta_fecha_ini" class="required login fecha" type="hidden" name="start-dmy-date"
                placeholder="MM/AAAA" value="" onFocus="javascript:this.value=''" />
            </span>
            <span>
              <!-- <p><?php echo lang('TITULO_REPORTES_FECHAFIN'); ?></p> -->
              <input id="repEstadosDeCuenta_fecha_fin" class="required login fecha" type="hidden" name="end-dmy-date"
                placeholder="DD/MM/AA" value="" onFocus="javascript:this.value=''" />
            </span>
          </div>
          <div id="search-2">
            <h5><?php echo lang('REPORTES_SELECCION_PRODUCTO'); ?><img id="cargando_producto"
                style="display:none;width: 25px; margin-left:10px" src="<?php echo $urlCdn."media/img/loading.gif"?>" />
            </h5>
            <span>
              <select class="required" id="repEstadosDeCuenta_producto" name="select-producto">
                <option value="" selected="selected"><?php echo lang('REPORTES_SELECCIONE_PRODUCTO'); ?></option>
              </select>
            </span>
            <h5><?php echo lang('TITULO_REPORTES_RESULTADOS'); ?></h5>
            <span>
              <input checked class="required radio" type="radio" name="radio" value="0" />
              <p><?php echo lang('REPORTES_RADIO_TODOS'); ?></p>
            </span>
            <span>
              <input class="required radio" type="radio" name="radio" value="1" />
              <p><?php echo lang('ID_PERSONA'); ?></p>
              <input disabled id="repEstadosDeCuenta_dni" class="bloqued cedula required nro" type="text"
                name="Ingrese ID">
            </span>
          </div>


      </form>
    </div>
  </div>
  <div id="batchs-last">
    <span id="mensajeError"
      style="float:left; display:none; color:red;"><?php echo lang('REPORTE_MENSAJE_ERROR'); ?></span>
    <?php
					if($pais=='Ec-bp'){
						?>
    <center>
      <?php
					}
				?>
      <button id="repEstadosDeCuenta_btnBuscar" type="submit"
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
  <input id="titulografico" type='hidden' data='<?php echo lang('TITULO_ESTADOS_DE_CUENTA'); ?>' />
  <input id="modeda" type='hidden' data='<?php echo lang('MONEDA'); ?>' />
  <input id="abono" type='hidden' data='<?php echo lang('ABONO'); ?>' />
  <input id="cargo" type='hidden' data='<?php echo lang('CARGO'); ?>' />
  <div id="chart" style="display:none"></div>

  <div id="div_tablaDetalle" style="display:none">

    <!-- <div id="top-batchs">
					<span aria-hidden="true" class="icon" data-icon="&#xe035;"></span><?php echo lang('ESTADOS_CUENTA_RESULT'); ?>
					</div>
				<br>
				 -->

  </div>
  <!--
			<div class='Jpaginate'><div id='paginacion'></div></div>
			-->

  <div id="contend-pagination" style="display:none">
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

  <input id="titulografico" type='hidden' data='<?php echo lang('TITULO_GRAFICO_EC'); ?>' />
  <input id="moneda" type='hidden' data='<?php echo lang('MONEDA'); ?>' />
  <input id="abono" type='hidden' data='<?php echo lang('ABONO'); ?>' />
  <input id="cargo" type='hidden' data='<?php echo lang('CARGO'); ?>' />
  <input id="cedula" type='hidden' data='<?php echo lang('ID_PERSONA'); ?>: ' />
  <input id="cuenta" type='hidden' data='<?php echo lang('CUENTA_ESTADOS_DE_CUENTA'); ?>' />
  <input id="cliente" type='hidden' data='' />
  <input id="tarjeta" type='hidden' data='<?php echo lang('TARJETA_ESTADO_DE_CUENTA'); ?>' />
  <input id="fecha" type='hidden' data='<?php echo lang('FECHA_ESTADO_DE_CUENTA'); ?>' />
  <input id="referencia" type='hidden' data='<?php echo lang('REFERENCIA_ESTADO_DE_CUENTA'); ?>' />
  <input id="descripcion" type='hidden' data='<?php echo lang('DESCRIPCION_ESTADO_DE_CUENTA'); ?>' />
  <form id='formulario' method='post'></form>


</div>
</div>
