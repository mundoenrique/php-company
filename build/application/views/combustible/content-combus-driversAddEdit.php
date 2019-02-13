<?php
$dataResponse = json_decode($dataResponse);
$dataDriver = NULL;
$code = $dataResponse->code;
$title = '';
$msg = '';
$dni = '';

if ($dataResponse->code === 0) {
    $dni = $dataResponse->msg->id_ext_per;
    $dataDriver = $action['function'] === 'update' ? $dataResponse->msg : NULL;

} else {
    $title = $dataResponse->title;
    $msg = $dataResponse->msg;
}
?>
<div id="errorDriver" code="<?php echo  $code ?>" title="<?php echo $title ?>" msg="<?php echo $msg ?>"></div>
<div class="content-products">
    <h1 id="reg-upt"><?php echo $action['title']; ?></h1>
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
            <a rel="section" href="<?php echo base_url().$pais; ?>/trayectos/conductores"><?php echo lang('BREADCRUMB_DRIVERS'); ?></a>
        </li>
        /
        <li class="breadcrumb-item-current">
            <a rel="section"><?php echo lang('BREADCRUMB_PROFILE_DRIVERS'); ?></a>
        </li>
    </ol>
</div>
<div class="container">
    <div class="container-header">
        <span aria-hidden="true" class="icon icon-list" data-icon="&#xe020;"></span>
        <?php echo lang("BREADCRUMB_PROFILE_DRIVERS"); ?>
    </div>
    <div class="container-body">
        <div class="field-set title">
            <span aria-hidden="true" class="icon icon-list" data-icon=""></span>
            <span><?php echo lang('MENU_DAT_DRIVER'); ?></span>
            <button id="disabled" style=" font-size: small;" data-status="<?php echo (isset($dataDriver)) ? $dataDriver->estatusConductor : ''; ?>">
                <?php echo (isset($dataDriver)) ? $action['status'] : ''; ?>
            </button>
        </div>
        <?php echo form_open(' ',
            array('id' => 'formAddEdit')
        ); ?>
        <input type="hidden" name="function" id="function" value="<?php echo $action['function']; ?>">
        <div class="field-set">
            <span class="field-area">
                <label for="dniDriver" class="label"><?php echo lang('DRIVER_DNI') ?></label>
                <?php echo form_input( array(
                    'name' => 'dniDriver',
                    'id' => 'dniDriver',
                    'class' => 'field',
                    'maxlength' => '9',
                    'readonly' => false,
                    'value' => (isset($dataDriver)) ? $dataDriver->id_ext_per : $dni
                )); ?>
            </span>
            <span class="field-area">
                <label for="user" class="label"><?php echo lang('DRIVER_USER') ?></label>
                <?php echo form_input(array(
                    'name' => 'user',
                    'id' => 'user',
                    'class' => 'field',
                    'maxlength' => '16',
                    ($action['function'] === 'update') ? 'readonly' : 'read' => false,
                    'value' => (isset($dataDriver)) ? strtolower($dataDriver->userName) : ''
                ));?>
            </span>
        </div>
        <div class="field-set">
            <span class="field-area">
                <label for="mail" class="label"><?php echo lang('DRIVER_MAIL') ?></label>
                <?php echo form_input(array(
                    'name' => 'mail',
                    'id' => 'mail',
                    'class' => 'field',
                    'maxlength' => '100',
                    'value' => (isset($dataDriver)) ? $dataDriver->email : ''
                ));?>
            </span>
            <span class="field-area">
                <label for="telf_mov" class="label"><?php echo lang('DRIVER_MOV') ?></label>
                <?php echo form_input(array(
                    'name' => 'telf_mov',
                    'id' => 'telf_mov',
                    'class' => 'field',
                    'maxlength' => '20',
                    'value' => (isset($dataDriver)) ? $dataDriver->numero : ''
                ));?>
            </span>
        </div>
        <div class="field-set">
            <span class="field-area">
                <label for="name1" class="label"><?php echo lang('DRIVER_NAME1') ?></label>
                <?php echo form_input(array(
                    'name' => 'name1',
                    'id' => 'name1',
                    'class' => 'field',
                    'maxlength' => '20',
                    'value' => (isset($dataDriver)) ? $dataDriver->primerNombre : ''
                ));?>
            </span>
            <span class="field-area">
                <label for="name2" class="label"><?php echo lang('DRIVER_NAME2') ?></label>
                <?php echo form_input(array(
                    'name' => 'name2',
                    'id' => 'name2',
                    'class' => 'field',
                    'maxlength' => '20',
                    'value' => (isset($dataDriver)) ? $dataDriver->segundoNombre : ''
                ));?>
            </span>
        </div>
        <div class="field-set">
            <span class="field-area">
                <label for="ape1" class="label"><?php echo lang('DRIVER_LAST1') ?></label>
                <?php echo form_input(array(
                    'name' => 'ape1',
                    'id' => 'ape1',
                    'class' => 'field',
                    'maxlength' => '20',
                    'value' => (isset($dataDriver)) ? $dataDriver->primerApellido : ''
                ));?>
            </span>
            <span class="field-area">
                <label for="ape2" class="label"><?php echo lang('DRIVER_LAST2') ?></label>
                <?php echo form_input(array(
                    'name' => 'ape2',
                    'id' => 'ape2',
                    'class' => 'field',
                    'maxlength' => '20',
                    'value' => (isset($dataDriver)) ? $dataDriver->segundoApellido : ''
                ));?>
            </span>
        </div>
        <div class="field-set">
            <span class="field-area">
                <label for="birthDay" class="label"><?php echo lang('DRIVER_BIRTHDATE') ?></label>
                <div class="date-content">
                    <?php echo form_input( array(
                        'name' => 'birthDay',
                        'id' => 'birthDay',
                        'class' => 'field',
                        'maxlength' => '',
                        'placeholder' => 'dd/mm/aaaa',
                        'value' => (isset($dataDriver)) ? $dataDriver->fechaNacimiento : ''
                    )); ?>
                </div>
            </span>
            <span class="field-area">
                <label for="sex" class="label"><?php echo lang('DRIVER_SEX') ?></label>
                M<?php echo form_radio(array(
                    'name' => 'sex',
                    'id' => 'sex',
                    'class' => 'gender',
                    'checked' => (isset($dataDriver) && $dataDriver->sexo == 'M') ? true : false,
                    'value' => 'M'
                ));?>
                F<?php echo form_radio(array(
                    'name' => 'sex',
                    'id' => 'sex',
                    'class' => 'gender',
                    'checked' => (isset($dataDriver) && $dataDriver->sexo == 'F') ? true : false,
                    'value' => 'F'
                ));?>
            </span>
        </div>
        <?php echo form_close(); ?>
        <div id="msg"></div>
    </div>
    <div class="contanier-footer">
        <button id="add-edit" class="<?php echo $action['function'] === 'update' ? 'withoutChanges' : ''; ?>" changes="<?php echo isset($action['changes']) ? $action['changes'] : ''; ?>" function="<?php echo $action['function']; ?>" <?php echo $action['function'] === 'update' ? 'disabled' : ''; ?>><?php echo $action['action']; ?></button>
    </div>
</div>

<div id="msg-system" style='display:none'>
    <div id="msg-info" class="comb-content"></div>
    <div id="actions" class="comb-content actions-buttons">
        <button id="close-info" class="buttons-action"><?php echo lang('TAG_ACCEPT') ?></button>
        <button id="send-info" class="buttons-action"></button>
    </div>
</div>
<form id='formulario' method='post'></form>