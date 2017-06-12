<?php
$dataResponse = json_decode($dataResponse);
//print_r($dataResponse);
$dataAccount = json_decode($dataResponse,true);

$card = $dataAccount['tarjeta'];
$saldo = (isset($dataAccount['saldos']))?$dataAccount['saldos']:'';
$dataNotAvailable = 'No disponible';
//print_r($card['idAsignacion']);
?>


<div class="content-products">
    <h1><?php echo $action['title']; ?></h1>
    <h2 class="title-marca"><?php echo ucwords(mb_strtolower($programa));?></h2>
    <ol class="breadcrumb">
        <li>
            <a rel="start" href="<?php echo base_url().$pais; ?>/dashboard"><?php echo lang('BREADCRUMB_INICIO'); ?></a>
        </li>
        /
        <li>
            <a rel="section" href="<?php echo base_url().$pais; ?>/dashboard"><?php echo lang('BREADCRUMB_EMPRESAS'); ?></a>
        </li>
        /
        <li>
            <a rel="section" href="<?php echo base_url().$pais; ?>/dashboard/productos"><?php echo lang('BREADCRUMB_PRODUCTOS'); ?></a>
        </li>
        /
        <li>
            <a rel="section" href="<?php echo base_url().$pais; ?>/trayectos"><?php echo lang('BREADCRUMB_COMBUSTIBLE'); ?></a>
        </li>
        /
        <li>
            <a rel="section" href="<?php echo base_url().$pais; ?>/trayectos/cuentas"><?php echo lang('BREADCRUMB_ACCOUNTS'); ?></a>
        </li>
        /
        <li class="breadcrumb-item-current">
            <a rel="section"><?php echo lang('BREADCRUMB_PROFILE_ACCOUNT_DETAIL'); ?></a>
        </li>
    </ol>
</div>
<div class="container">
    <div class="container-header">
        <span aria-hidden="true" class="icon icon-list" data-icon="&#xe020;"></span>
        <?php echo lang("MENU_ACCOUNTS_DETAILS"); ?>
    </div>
    <div class="container-body">
               <div class="field-set">
            <span class="field-area">
            <label for="dniDriver" class="label">Número de tarjeta:</label>
                <?php echo form_input( array(
                    'name' => 'cardNumber',
                    'id' => 'cardNumber',
                    'class' => 'field',
                    'readonly' => true,
                    'value' => (isset($dataAccount) && $card['noTarjeta'] != '') ?$card['noTarjeta'] : $dataNotAvailable
                )); ?>
            </span>
            <span class="field-area">
            <label class="label">RUC:</label>
                <?php echo form_input( array(
                    'name' => 'rucNumber',
                    'id' => 'rucNumber',
                    'class' => 'field',
                    'readonly' => true,
                    'value' => (isset($dataAccount) && $card['rif'] != '') ?$card['rif'] : $dataNotAvailable
                )); ?>
            </span>
        </div>
        <div class="field-set">
            <span class="field-area">
            <label class="label">Fecha de asignación:</label>
                <?php echo form_input( array(
                    'name' => 'asignacionNumber',
                    'id' => 'asignacionNumber',
                    'class' => 'field',
                    'readonly' => true,
                    'value' => (isset($dataAccount) && $card['fechaAsignacion'] != '') ?$card['fechaAsignacion'] : $dataNotAvailable
                )); ?>
            </span>
            <span class="field-area">
            <label  class="label">Fecha de devolución:</label>
                <?php echo form_input( array(
                    'name' => 'devolucionNumber',
                    'id' => 'devolucionNumber',
                    'class' => 'field',
                    'readonly' => true,
                    'value' => (isset($card['fechaDevolucion'])) ? $card['fechaDevolucion']: $dataNotAvailable
                )); ?>
            </span>
        </div>
        <div class="field-set">
            <span class="field-area">
            <label class="label">Usuario de registro:</label>
                <?php echo form_input( array(
                    'name' => 'registerUser',
                    'id' => 'registerUser',
                    'class' => 'field',
                    'readonly' => true,
                    'value' => (isset($dataAccount) && $card['usuarioRegistro'] != '' ) ?$card['usuarioRegistro'] : $dataNotAvailable
                )); ?>
            </span>
            <span class="field-area">
            <label for="dniDriver" class="label">Saldo:</label>
                <?php echo form_input( array(
                    'name' => 'balance',
                    'id' => 'balance',
                    'class' => 'field',
                    'readonly' => true,
                    'value' => ($saldo != '') ?$saldo['actual'] : $dataNotAvailable
                )); ?>
            </span>
            <input type="hidden" name="idAccount" id="idAccount" value="<?php echo $card['idAsignacion'] ?>">
            <input type="hidden" name="urlAccount" id="urlAccount" value="<?php echo  base_url().$pais; ?>/trayectos/cuentas">
        </div>
    </div>
    <div class="contanier-footer">
        <button id="accountOff" hidden>
            Devolver cuenta
        </button>
    </div>

    <div class="container" id="assignContainer" hidden>
        <div class="container-header">
            <span aria-hidden="true" class="icon icon-list" data-icon="&#xe020;"></span>
            Asignar cuenta
        </div>
        <div class="container-body">
            <div id="driverAvailable" hidden>
            <span >
                <select style="float: left;" id="selectDriver">

                </select>
            </span>

            </div>

            <div id="driverNotAvailable" hidden>
            <span >
                No posee conductores disponibles
            </span>

            </div>

            <button id="assignAccount">
                Asignar
            </button>

        </div>
    </div>


    <div id="msg-system" style='display:none'>
        <div id="msg-info" class="comb-content"></div>
        <div id="msg">
            <p>Será devuelta la cuenta y se cancelaran los viajes asociados</p>
        </div>
        <div id="actions" class="comb-content actions-buttons">
            <button id="send-info" class="buttons-action ">Aceptar</button>
            <button id="close-info" class="buttons-action button-cancel">Cancelar</button>
        </div>
    </div>


    <div id="msg-system-assing" style='display:none'>
        <div id="msg-info" class="comb-content"></div>
        <div id="msg">
            <p>¿Desea asignar la cuenta <?php echo  $card['noTarjeta'] ?> al conductor seleccionado?</p>
        </div>
        <div id="actions" class="comb-content actions-buttons">
            <button id="send-info" class="buttons-action">Aceptar</button>
            <button id="close-info" class="buttons-action  button-cancel">Cancelar</button>
        </div>
    </div>



</div>
