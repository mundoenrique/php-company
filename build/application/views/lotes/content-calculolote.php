<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA . $pais;
$ceo_name = $this->security->get_csrf_token_name();
$ceo_cook = $this->security->get_csrf_hash();

$data = unserialize($data);

if (isset($data->tokenOTP->authToken)) {
  $this->session->set_userdata('authToken', $data->tokenOTP->authToken);
}
//Verifica si existen lotes sin retenciones asociadas si aplica
$reten = NULL;
for ($i = 0; $i < count($data->lista); $i++) {
  if (($data->lista[$i]->aplicaReten == 'S') && (empty($data->lista[$i]->retenciones->retenciones))) {
    $reten .= $data->lista[$i]->lotes[0]->acnumlote . ", ";
  }
}
$reten = ($reten == NULL) ? "nonEmpty" : trim($reten, ', ');

?>
<div id="content-products">
  <h1><?php echo lang('TITULO_PRELIMINAR'); ?></h1>
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
      <a href="<?php echo $urlBase; ?>/lotes/autorizacion" rel="section">
        <?php echo lang('BREADCRUMB_AUTORIZACION'); ?></a>
    </li>
    /
    <li>
      <a rel="section">
        <?php echo lang('BREADCRUMB_CALCULO_ORDEN_SERVICIO'); ?></a>
    </li>
  </ol>

  <div id="lotes-general">

    <?php
    if (!property_exists($data, 'ERROR')) {
      if ($data->lista) {
    ?>
        <div id="top-batchs">
          <?php if ($pais != 'Ec-bp') : ?>
            <span aria-hidden="true" class="icon" data-icon="&#xe035;"></span>
          <?php endif; ?>
          <?php echo lang('TITULO_ORDEN_SERVICIO'); ?>
        </div>
        <div id="lotes-contenedor" class="b-rs">
          <table id="tabla-datos-general" class="tabla-reportes-OS">
            <thead>
              <?php
              $pruebaIva = $data->lista[0]->nuevoIva;
              if ($pruebaIva == '1') {
              ?>
                <tr id="datos-principales">
                  <th style='display:none'></th>
                  <th style='display:none'></th>
                  <th><?php echo lang('TABLA_OS_MONTO'); ?></th>
                  <th><?php echo lang('TABLA_OS_MONTO_IVA'); ?></th>
                  <th>Medio de pago</th>
                  <th class="th-empresa"><?php echo lang('TABLA_OS'); ?></th>
                  <th><?php echo lang('TABLA_OS_MONTO_TOTAL'); ?></th>
                  <th><?php echo lang('TABLA_OS_MONTO_DEPOSITO'); ?></th>
                </tr>
              <?php } else { ?>
                <tr id="datos-principales">
                  <th style='display:none'></th>
                  <th style='display:none'></th>
                  <?php if ($pais != 'Ec-bp') : ?>
                    <th><?php echo lang('TABLA_OS_MONTO'); ?></th>
                  <? endif; ?>
                  <th><?php echo lang('TABLA_OS_MONTO_IVA'); ?></th>
                  <th class="th-empresa"><?php echo lang('TABLA_OS'); ?></th>
                  <th><?php echo lang('TABLA_OS_MONTO_TOTAL'); ?></th>
                  <th><?php echo lang('TABLA_OS_MONTO_DEPOSITO'); ?></th>

                </tr>
              <?php } ?>

            </thead>
            <tbody id="tbody-datos-general" class="tbody-reportes-OS">
              <?php
              $lc = 0;
              $tempidOrdenLotes = array();

              foreach ($data->lista as $value) {

                array_push($tempidOrdenLotes, $value->idOrdenTemp);
                $comision = "<td>" . lang('TABLA_OS_COMISION') . "</td>";
                if ($pais == 'Ec-bp') {
                  $comision = '';
                }
                $ltr = "<tr class='OShead-2 OSinfo $value->idOrdenTemp elem-hidden'>
							<td>" . lang('TABLA_OS_NROLOTE') . "</td>
							<td>" . lang('TABLA_OS_FECHA') . "</td>
							<td>" . lang('TABLA_OS_TIPO') . "</td>
							<td>" . lang('TABLA_OS_CANT') . "</td>
							<td>" . lang('TABLA_OS_STATUS') . "</td>
							<td>" . lang('TABLA_OS_MONTO_RECARGA') . "</td>" .
                  $comision .
                  "<td>" . lang('TABLA_OS_MONTO_TOTAL') . "</td>
						</tr>";
                foreach ($value->lotes as $l) {
                  $montoComision = "<td>$l->montoComision</td>";
                  if ($pais == 'Ec-bp') {
                    $montoComision = '';
                  }

                  $montoNeto = floatval($l->montoRecarga) + floatval($l->montoComision);
                  $ltr  .= "<tr class='OSinfo $value->idOrdenTemp elem-hidden'>
							<td><a id='$l->acidlote' class='viewLo' title='Detalle lote'>$l->acnumlote</a></td>
							<td>$l->dtfechorcarga</td>
							<td>" . ucfirst(mb_strtolower($l->acnombre)) . "</td>
							<td>$l->ncantregs</td>
							<td>" . ucfirst(mb_strtolower($l->status)) . "</td>
							<td>$l->montoRecarga</td>
							$montoComision
							<td>$montoNeto</td>
						</tr>";
                }
                if ($pruebaIva == '1') {

                  $oripais = $value->nuevoIva;

                  $medio = $value->medioPago;
                  echo "
								<tr id='$value->idOrdenTemp'>
									<td class='OS-icon'>
										<a id='ver_lotes' title='Ver lotes'>
											<span aria-hidden='true' class='icon' data-icon='&#xe003;'></span>
										</a>
									</td>
									<td>$value->montoComision</td>
									<td>$value->montoIVA</td>
									<td>$medio->descripcion</td>
									<td class='th-empresa'>$value->montoOS</td>
									<td>" . amount_format($value->montoTotal) . "</td>
									<td>" . amount_format($value->montoDeposito) . "</td>
									<td style='float:left; padding:0; '><table><tbody>$ltr</tbody></table></td>
								</tr>";
                } else {
                  $monComision = "<td>$value->montoComision</td>";
                  if ($pais == 'Ec-bp') {
                    $monComision = '';
                  }
                  echo "

								<tr class='tr-calculo' id='$value->idOrdenTemp'>
									<td class='OS-icon'>
										<a id='ver_lotes' title='Ver lotes'>
											<span aria-hidden='true' class='icon' data-icon='&#xe003;'></span>
										</a>
									</td>
									$monComision
									<td>" . amount_format($value->montoIVA) . "</td>
									<td class='th-empresa bueno'>" . amount_format($value->montoOS) . "</td>
									<td>" . amount_format($value->montoTotal) . "</td>
									<td>" . amount_format($value->montoDeposito) . "</td>
									<td style='float:left; padding:0; '><table><tbody>$ltr</tbody></table></td>
								</tr>";
                }
              }
              $tempIdOrdenL = serialize($tempidOrdenLotes);
              ?>

            </tbody>
          </table>
          <?php
          if ($pais == 'Ec-bp') {
          ?>
            <div class="recepcion-tcs">
              <p class="t-center">Ingresa el código de seguridad enviado a tu correo</p>
              <input type="text" id="passOtp" name="passOtp" value="">
            </div>
            <div class="botones-OS">
              <button id="confirmarPreOSL" style="display: none" class="novo-btn-primary">
                <?php echo lang('BTN_CONFIRMAR_OS') ?></button>
              <button id='cancelar-OS' style="display: none" class="novo-btn-secondary">
                <?php echo lang('BTN_CANCELAR_OS') ?></button>
            </div>
          <?php
          }
          ?>

        </div>
      <?php echo "<input type='hidden' id='tempIdOrdenL' name='tempIdOrdenL' value='$tempIdOrdenL' />";
      }
      if ($data->lotesNF) {
      ?>
        <div id="top-batchs">
          <span aria-hidden="true" class="icon" data-icon="&#xe072;"></span>
          Lotes no facturables
        </div>
        <div id="lotes-contenedor">
          <table id="tablelotesNF" class="tabla-lotesNF">
            <thead>
              <tr id="datos-principales" class="tabla-lotesNF">
                <th><?php echo lang('ID_FISCAL'); ?></th>
                <th class="th-empresa"><?php echo lang('TABLA_OS_EMPRESA'); ?></th>
                <th><?php echo lang('TABLA_OS_NROLOTE') ?></th>
                <th><?php echo lang('TABLA_OS_FECHA_CARGA'); ?></th>
                <th><?php echo lang('TABLA_OS_LOTE_REG'); ?></th>
                <th><?php echo lang('TABLA_OS_STATUS'); ?></th>
              </tr>
            </thead>
            <tbody id="tbody-datos-general" class="tbody-reportes-OS jslistaNF">
              <?php
              $tempidOrdenLotesNF = array();
              foreach ($data->lotesNF as $valueNF) {
                array_push($tempidOrdenLotesNF, $valueNF->acidlote);
                echo "
							<tr>
								<td>$valueNF->acrif</td>
								<td class='th-empresa'>" . ucwords(mb_strtolower($valueNF->acnomcia)) . "</td>
								<td>$valueNF->acnumlote</td>
								<td>$valueNF->dtfechorcarga</td>
								<td>" . ucfirst(mb_strtolower($valueNF->acnombre . '/' . $valueNF->ncantregs)) . "</td>
								<td>" . ucfirst(mb_strtolower($valueNF->status)) . "</td>
							</tr>
							";
              }
              $tempIdOrdenLNF = serialize($tempidOrdenLotesNF);

              ?>
            </tbody>
          </table>

        </div>
      <?php echo "<input type='hidden' id='tempIdOrdenLNF' name='tempIdOrdenLNF' value='$tempIdOrdenLNF' />";
      }
      ?>
      <div class="botones-OS">
        <button id="confirmarPreOSL" style="display: none">
          <?php echo lang('BTN_CONFIRMAR_OS') ?></button>
        <button id='cancelar-OS' style="display: none">
          <?php echo lang('BTN_CANCELAR_OS') ?></button>
      </div>
    <?php } else {
      if ($data["ERROR"] == '-29') {
        echo "<script>alert('Usuario actualmente desconectado');  location.reload();</script>";
      } else {
        echo '
				<div id="products-general" style="margin-top: 10px;">
				<h2 style="text-align:center">' . $data['ERROR'] . '</h2>
				</div>';
      }
    } ?>


  </div>
</div>

<form id='toOS' action="<?php echo $urlBase ?>/consulta/ordenes-de-servicio " method="post">

  <input type="hidden" name="data-confirm" value="" id="data-confirm" />
</form>

<form id='detalle_lote' method='post' action="<?php echo $urlBase ?>/lotes/autorizacion/detalle">
  <input type='hidden' name='<?php echo $ceo_name ?>' value='<?php echo $ceo_cook ?>'>
  <input type='hidden' name='data-COS' value='<?php echo serialize($data) ?>' />
</form>
<form id='viewAutorizar' action="<?php echo $urlBase ?>/lotes/autorizacion " method="POST">

</form>
<div id='loading' style='text-align:center' class='elem-hidden'><?php echo insert_image_cdn("loading.gif"); ?></div>
<input type='hidden' id='empty' value='<?= $reten ?>' />