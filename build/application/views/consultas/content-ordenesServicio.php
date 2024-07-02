<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA . $pais;
$ceo_name = $this->security->get_csrf_token_name();
$ceo_cook = $this->security->get_csrf_hash();
$statusAth = '';
$datos = null;


if ($osConfirmV) {

  $datos = unserialize($osConfirmV);
  $acrifS = $this->session->userdata('acrifS');
  $acrazonsocialS = $this->session->userdata('acrazonsocialS');
  $acnomciaS = $this->session->userdata('acnomciaS');

  if (!$datos) {
    $datos = array('ERROR' => lang('ERROR_NO_DATA'));
  } elseif (isset($datos->lista)) {
    foreach ($datos->lista as $lista) {
      foreach ($lista->lotes as $lotes) {
        $statusAth = $lotes->status;
        if ($statusAth !== '') {
          break;
        }
      }
    }
  }
}
?>
<div id="content-products">
  <h1><?php echo lang('TITULO_CONSULTA_OS'); ?></h1>

  <h2 class="title-marca">
    <?php echo ucwords(mb_strtolower($programa)); ?>
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
      <a href="<?php echo $urlBase; ?>/dashboard/productos" rel="section"><?php echo lang('BREADCRUMB_PRODUCTOS'); ?></a>
    </li>
    /
    <li>
      <a href="<?php echo $urlBase; ?>/lotes/autorizacion" rel="section"><?php echo lang('BREADCRUMB_AUTORIZACION'); ?></a>
    </li>
    /
    <li>
      <a rel="section"><?php echo lang('BREADCRUMB_ORDEN_SERVICIO'); ?></a>
    </li>
  </ol>

  <div id="lotes-general" class='elem-hidden'>

    <div id='filtroOS'>

      <div id="top-batchs">
        <?php if ($pais != 'Ec-bp') : ?>
          <span aria-hidden="true" class="icon" data-icon="&#xe07a;"></span>
        <?php endif; ?>
        <?php echo lang('TITULO_CRITERIOSBUSQ'); ?>
      </div>

      <div id="lotes-contenedor">
        <form id="form-criterio-busqueda" onsubmit="return false">
          <span class="info-OD">
            <h5>5 días</h5>
            <input class="required login" type="radio" name="dias" value="5" />
          </span>
          <span class="info-OD">
            <h5>10 días</h5>
            <input class="required login" type="radio" name="dias" value="10" />
          </span>
          <span class="info-OD">
            <h5>Fecha inicial</h5>
            <input id='fecha_inicial' name="start-dmy-date" class="required login" placeholder="DD/MM/AA" value="" onfocus="javascript:this.value=''" />
          </span>
          <span class="info-OD">
            <h5>Fecha final</h5>
            <input id='fecha_final' name="end-dmy-date" class="required login" placeholder="DD/MM/AA" value="" onfocus="javascript:this.value=''" />
          </span>
          <div class="info-OD">
            <h5>Estatus de Lote</h5>
            <select id="status_lote" name="batch" class="required">
              <?
              if (array_key_exists("ERROR", $tipoStatus[0])) {
                if ($tipoStatus['ERROR'] == '-29') {
                  echo "<script>alert('Usuario actualmente desconectado'); location.reload();</script>";
                } else {
                  echo "<option value='' selected='selected'>No disponible</option>";
                }
              } else {
                echo '<option value="" selected="selected">Selección</option>';
                foreach ($tipoStatus[0]->lista as $tipos) {
                  echo '<option value="' . $tipos->codEstatus . '">' . ucfirst(mb_strtolower($tipos->descEstatus)) . '</option>';
                }
              } ?>
            </select>
          </div>
        </form>
      </div>

      <div id="batchs-last">
        <?php
        if ($pais == 'Ec-bp') {
        ?>
          <center>
          <?php
        }
          ?>
          <button id='buscarOS' class="novo-btn-primary">Buscar</button>
          <?php
          if ($pais == 'Ec-bp') {
          ?>
          </center>
        <?php
          }
        ?>
      </div>

    </div>

    <?php
    if (isset($datos) && $datos != false) {

      if (array_key_exists("ERROR", $datos)) {

        if ($datos['ERROR'] == '-29') {
          echo "<script>alert('Usuario actualmente desconectado'); location.reload();</script>";
        }
    ?>
        <div id="top-batchs" class='top-listOS'>
          <?php if ($pais != 'Ec-bp') : ?>
            <span aria-hidden="true" class="icon" data-icon="&#xe035;"></span>
          <?php endif; ?>
          <?php echo lang('TITULO_CONSULTA_OSE'); ?>
        </div>
        <div id="lotes-contenedor" class="ordenes_ser">
          <h2><?php echo $datos['ERROR'] ?></h2>
        </div>
        <?php  }

      if (array_key_exists('lista', $datos)) {
        if (count($datos->lista) > 0) {
        ?>
          <div id="top-batchs" class='top-listOS'>
            <?php if ($pais != 'Ec-bp') : ?>
              <span aria-hidden="true" class="icon" data-icon="&#xe035;"></span>
            <?php endif; ?>
            <?php echo lang('TITULO_CONSULTA_OSE'); ?>
          </div>

          <div id="lotes-contenedor" class="ordenes_ser">
            <table id="tabla-datos-general" class="tabla-reportes-OS">
              <thead>
                <tr id="datos-principales-OS">
                  <th style='display:none'></th>
                  <th style='display:none'></th>
                  <th><?php echo lang('TABLA_COS_ID_ORDEN') ?></th>
                  <th><?php echo lang('TABLA_COS_FECHA') ?></th>
                  <?= !(in_array("tebpgo", $funciones) && $statusAth === 'AUTORIZADO') ? '<th>' . lang('ID_FISCAL') . '</th>' : '' ?>
                  <th class="th-empresa"><?php echo lang('TABLA_COS_EMPRESA') ?></th>
                  <th><?php echo lang('TABLA_COS_MONTO_OS') ?></th>
                  <th><?php echo lang('TABLA_COS_MONTO_DEPOSITADO') ?></th>
                  <?= (in_array("tebpgo", $funciones) && $statusAth === 'AUTORIZADO') ? "<th></th>" : '' ?>
                </tr>
              </thead>
              <tbody id="tbody-datos-general" class="tbody-reportes-OS jslista">
                <?php
                $lc = 0;
                $tempidOrdenLotes = array();

                foreach ($datos->lista as $value) {

                  array_push($tempidOrdenLotes, $value->idOrdenTemp);
                  $ltr = "<tr class='OShead-2 OSinfo $value->idOrden'>
																<td>" . lang('TABLA_COS_NRO_LOTE') . "</td>
																<td>" . lang('TABLA_COS_FECHA') . "</td>
																<td>" . lang('TABLA_COS_TIPO') . "</td>
																<td>" . lang('TABLA_COS_CANT') . "</td>
																<td>" . lang('TABLA_COS_STATUS') . "</td>
																<td>" . lang('TABLA_COS_MONTO_RECA') . "</td>
																<td>" . lang('TABLA_COS_MONTO_COMI') . "</td>
																<td>" . lang('TABLA_COS_MONTO_TOTAL') . "</td>
																</tr>";

                  foreach ($value->lotes as $l) {


                    $ltr .= "<tr class='OSinfo $value->idOrden'>";
                    $ltr .= "<td><a id='$l->acidlote' class='viewLo' title='Detalle lote'>$l->acnumlote</a></td>";
                    $ltr .= "<td>$l->dtfechorcarga</td>";
                    $ltr .= ($pais === 'Ec-bp'  && $l->ctipolote === '1'  && $l->cestatus === '4') ? "<td id='res' data-id='$value->idOrden' title='Recepcion de tarjeta'>" . ucfirst(mb_strtolower($l->acnombre)) . "</td>" : "<td>" . ucfirst(mb_strtolower($l->acnombre)) . "</td>";
                    //$ltr.=($pais === 'Ec-bp'  && $l->ctipolote === '1'  && $l->cestatus === '4' )?"<td><a id='res' data-id='$value->idOrden' title='Recepcion de tarjeta'>".ucfirst(mb_strtolower($l->acnombre))."</a></td>":"<td>".ucfirst(mb_strtolower($l->acnombre))."</td>";
                    $ltr .= "<td>$l->ncantregs</td>
																		<td>" . ucfirst(mb_strtolower($l->status)) . "</td>
																		<td>$l->montoRecarga</td>
																		<td>$l->montoComision</td>
																		<td>$l->montoNeto</td>
																		</tr>";
                  }
                  echo "
									<tr id='$value->idOrden' aplica-costo='$value->aplicaCostD'>
                  <td class='OS-icon'>";
                  if ($pais == 'Ec-bp' && $l->ctipolote === 'Z') {
                    echo "<a id='dwnPDF' title='Descargar como PDF'>
                   <span aria-hidden='true' class='icon' data-icon='&#xe02e;'></span>
                   </a>";
                  } else {
                    echo "<a id='ver_lotes' title='Ver lotes de detalles'>
                    <span aria-hidden='true' class='icon' data-icon='&#xe003;'></span>
                    </a>
                    <a id='dwnPDF' title='Descargar como PDF'>
                    <span aria-hidden='true' class='icon' data-icon='&#xe02e;'></span>
                    </a>";
                  }

                  if (($value->nofactura != '' && $value->fechafactura != '') && ($pais == 'Ve')) {

                    if ($datos->facturacion) {

                      echo "<a id='facturaOS' title='Ver factura' data-dw='$datos->facturacion' >
										<span aria-hidden='true' class='icon' data-icon='&#xe009;'></span>
										</a>";
                    } else {

                      echo "<a id='factura' title='Ver factura' data-dw='$datos->facturacion' >
										<span aria-hidden='true' class='icon' data-icon='&#xe009;'></span>
										</a>";
                    }
                  }
                  if (in_array('tebanu', $funciones) && $value->estatus == '0' && ($value->nofactura == '' && $value->fechafactura == '')) {
                    echo "
									<a id='anular' title='Anular Orden'>
									<span aria-hidden='true' class='icon' data-icon='&#xe06f;'></span>
									</a>
									";
                  }
                  if (in_array("tebpgo", $funciones) && $statusAth === 'AUTORIZADO') {
                    $pagoCo = "<td style='color:#0072c0'><strong><a id='pagoCo' value='$value->idOrden'>" . "Pagar OS" . "</a></strong></td>";
                    $thEmpresa = "<td class='th-empresa'>" . ucwords(mb_strtolower($acnomciaS) . " <br> " . lang('ID_FISCAL') . " : " . $acrifS) . "</td>";
                    $idFiscal = "";
                  } else {
                    $pagoCo = "";
                    $thEmpresa = "<td class='th-empresa'>" . ucwords(mb_strtolower($acnomciaS)) . "</td>";
                    $idFiscal = "<td>$acrifS</td>";
                  }
                  echo "
								</td>
								<td>$value->idOrden</td>
								<td>$value->fechaGeneracion</td>" .
                    $idFiscal .
                    $thEmpresa
                    . "<td>$value->montoOS</td>
								<td id='montoDeposito'>$value->montoDeposito</td>" .
                    $pagoCo
                    . "<td style='float:left; padding:0; '><table><tbody>$ltr</tbody></table></td>
								</tr>
								";
                }
                $tempIdOrdenL = serialize($tempidOrdenLotes);

                echo "<input type='hidden' id='tempIdOrdenL' name='tempIdOrdenL' value='$tempIdOrdenL' />";

                ?>
              </tbody>
            </table>
          </div>
        <?php }
      }

      if (array_key_exists('lotesNF', $datos)) {
        if (count($datos->lotesNF) > 0) {
        ?>
          <div id="top-batchs">
            <span aria-hidden="true" class="icon" data-icon="&#xe046;"></span>
            <?php echo lang('TITULO_CONSULTA_OS_LOTESNF') ?>
          </div>
          <div id="lotes-contenedor">
            <table id='tablelotesNF' class="tabla-lotesNF">
              <thead>
                <tr id="datos-principales" class="tabla-lotesNF">
                  <th><?php echo lang('ID_FISCAL') ?></th>
                  <th class="th-empresa"><?php echo lang('TABLA_COS_EMPRESA') ?></th>
                  <th><?php echo lang('TABLA_COS_NRO_LOTE') ?></th>
                  <th><?php echo lang('TABLA_COS_FECHA_CARGA') ?></th>
                  <th><?php echo lang('TABLA_COS_TIPO_LOTE') . ' / ' . lang('TABLA_CANT_REG') ?></th>
                  <th><?php echo lang('TABLA_COS_STATUS') ?></th>
                </tr>
              </thead>
              <tbody id="tbody-datos-general" class="tbody-reportes-OS jslistaNF">
                <?php
                $tempidOrdenLotesNF = array();
                foreach ($datos->lotesNF as $valueNF) {
                  array_push($tempidOrdenLotesNF, $valueNF->acidlote);
                  echo "
									<tr>
									<td>$acrifS</td>
									<td class='th-empresa'>" . ucwords(mb_strtolower($acnomciaS)) . "</td>
									<td><a id='$valueNF->acidlote' class='viewLo' title='Detalle lote'>$valueNF->acnumlote</a></td>
									<td>$valueNF->dtfechorcarga</td>
									<td>" . ucwords(mb_strtolower($valueNF->acnombre)) . " / $valueNF->ncantregs</td>
									<td>" . ucfirst(mb_strtolower($valueNF->status)) . "</td>
									</tr>
									";
                }
                $tempIdOrdenLNF = serialize($tempidOrdenLotesNF);
                echo "<input type='hidden' id='tempIdOrdenLNF' name='tempIdOrdenLNF' value='$tempIdOrdenLNF' />";
                ?>
              </tbody>
            </table>

          </div>
    <?php }
      }

      if (!array_key_exists("ERROR", $datos) && $datos->rc == -88) {
        echo "<input type='hidden' id='msg' value='$datos->msg' />";
      }
    } ?>

  </div>

</div>
<form id='formulario' method='post'></form>

<form id='detalle_lote' method='post' action="<?php echo $urlBase ?>/lotes/autorizacion/detalle">
  <input type='hidden' name='<?php echo $ceo_name ?>' value='<?php echo $ceo_cook ?>'>
  <input type='hidden' id='data-OS' name='data-OS' value='<?php echo serialize($datos) ?>' />
</form>

<div id='loading' style='text-align:center; padding-top:30px;' class='elem-hidden'>
  <?php echo insert_image_cdn("loading.gif"); ?></div>


<div id="msg-certificate-notifi" style="display:none">
  <div id="msg-info" class="comb-content"></div>
  <div id="actions" class="comb-content actions-buttons">
    <button id="close-info" class="buttons-action">Aceptar</button>
  </div>
</div>