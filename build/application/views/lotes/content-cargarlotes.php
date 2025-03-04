<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$ceo_name = $this->security->get_csrf_token_name();
$ceo_cook = $this->security->get_csrf_hash();
$margB = $pais == 'Ec-bp' ? 'style="margin-bottom: 10px;"' : '';
?>

<div id="content-products" style="    width: 720px;padding: 20px 2px;float: left;">

  <h1><?php echo lang('TITULO_LOTES_CARGA'); ?></h1>

  <h2 class="title-marca">
    <?php echo ucwords(mb_strtolower($programa));?>
  </h2>

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
      <a href="<?php echo $urlBase; ?>/dashboard/productos" rel="section">
        <?php echo lang('BREADCRUMB_PRODUCTOS'); ?></a>
    </li>
    /
    <li>
      <a href="<?php echo $urlBase; ?>/lotes" rel="section"><?php echo lang('BREADCRUMB_LOTES'); ?></a>
    </li>
    /
    <li class="breadcrumb-item-current">
      <a href="#" rel="section"><?php echo lang('BREADCRUMB_LOTES_CARGA'); ?></a>
    </li>
  </ol>


  <div id="lotes-general">

    <div id="tabs-1">
      <div id="lotes-2" style='display:none'>
        <div id="top-batchs">
          <?php if($pais != 'Ec-bp'): ?>
          <span aria-hidden="true" class="icon" data-icon="&#xe09e;"></span>
          <?php endif; ?>
          <?php echo lang('TITULO_NUEVOS_LOTES'); ?>
        </div>
        <div id="lotes-contenedor">
          <div id="selection-1">
            <h5><?php echo lang('TITULO_LOTES_TIPOLOTES'); ?></h5>
            <select id="tipoLote" name="batch">
              <option value="">Selecciona</option>
              <?php
								foreach ($selectTiposLotes[0]->lista as $tipol) {
									$tipoLS = ucfirst(mb_strtolower($tipol->tipoLote));
									echo "<option value='$tipol->idTipoLote' rel='$tipol->formato'>$tipoLS</option>";
								}
								?>
            </select>
          </div>
          <div id="selection-2">
            <h5>&nbsp;</h5>
            <input type="file" name="userfile" id="userfile" class='elem-hidden' />
            <input id='archivo' placeholder='Clic aquí para seleccionar archivo de Lote.' readonly="readonly"
              style='margin-left:0;width:459px' />
          </div>

        </div>
        <div id="batchs-last">
          <?php
							if($pais =='Ec-bp'){
								?>
          <center>
            <button id="cargaLote" class="novo-btn-primary"><?php echo lang('TITULO_LOTES_BTNCARGAR'); ?></button>
          </center>
          <?php
							}else{
								?>
          <button id="cargaLote"><?php echo lang('TITULO_LOTES_BTNCARGAR'); ?></button>
          <?php
							}
						?>
        </div>

        <div id="lotes-2">
          <div id="top-batchs">
            <?php if($pais != 'Ec-bp'): ?>
            <span aria-hidden="true" class="icon" data-icon="&#xe03c;"></span>
            <?php endif; ?>
            <?php echo lang('TITULO_LOTES_PENDIENTES'); ?>
          </div>
          <div id="lotes-contenedor-2" <?= $margB ?>>
            <table id="table-text-lotes" class='table-bandeja'>
              <thead style='display:none'>
                <th style="display:none"></th>
                <th><?php echo lang('TABLA_LOTESP_NROLOTE'); ?></th>
                <th id="td-nombre"><?php echo lang('TABLA_LOTESP_NOMBRE'); ?></th>
                <th><?php echo lang('TABLA_LOTESP_FECHACARGA'); ?></th>
                <th><?php echo lang('TABLA_LOTESP_ESTATUS'); ?></th>
                <th><?php echo lang('TABLA_LOTESP_OPCIONES'); ?></th>
              </thead>
              <tbody>
                <h3 id='actualizador' style='display:none'>
                  <br><br>
                  <?echo lang('CARGANDO')?><br><br><br><br></h3>
              </tbody>
            </table>
          </div>
          <input type='hidden' id='boton' value='<?php echo lang('TITULO_LOTES_BTNCARGAR'); ?>' />
          <form id='confirmar' method='post' action="<?php echo $urlBase ?>/lotes/confirmacion">
          </form>
          <form id='detalle' method='post' action="<?php echo $urlBase ?>/lotes/detalle">
            <input type='hidden' id="ceo_detalle" name='<?php echo $ceo_name ?>' value='<?php echo $ceo_cook ?>'>
          </form>
					<?php if($pais == 'Ec-bp'): ?>
					<div class="legend">
						<p>
							<span class="square done"></span><label for="">Todos los registros serán procesados</label>
						</p>
						<p>
							<span class="square war"></span><label for="">Existen registros que no serán procesados</label>
						</span>
						<p>
							<span class="square err"></span><label for="">Ningún registro será procesado</label>
						</p>
					</div>
					<?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<div id='loading' style='text-align:center' class='elem-hidden'><?php echo insert_image_cdn("loading.gif"); ?></div>
