<?php
$dataResponse = json_decode($dataResponse);
?>
<div class="content-products">
    <h1><?= $action['title']; ?></h1>
    <h2 class="title-marca"><?= ucwords(mb_strtolower($programa));?></h2>
    <ol class="breadcrumb">
        <li>
            <a rel="start" href="<?= base_url().$pais; ?>/dashboard"><?= lang('BREADCRUMB_INICIO'); ?></a>
        </li>
        /
        <li>
            <a rel="section" href="<?= base_url().$pais; ?>/dashboard"><?= lang('BREADCRUMB_EMPRESAS'); ?></a>
        </li>
        /
        <li>
            <a rel="section" href="<?= base_url().$pais; ?>/dashboard/productos"><?= lang('BREADCRUMB_PRODUCTOS'); ?></a>
        </li>
        /
        <li>
            <a rel="section" href="<?= base_url().$pais; ?>/trayectos"><?php echo lang('BREADCRUMB_COMBUSTIBLE'); ?></a>
        </li>
        /
        <li>
            <a href="<?= base_url().$pais; ?>/trayectos/gruposVehiculos" rel="section"><?= lang('BREADCRUMB_GROUP'); ?></a>
        </li>
        /
        <li class="breadcrumb-item-current">
            <a rel="section"><?= lang('BREADCRUMB_VEHICLES'); ?></a>
        </li>
    </ol>
</div>

<div id="vehicle-gruop" class="container" group-id="<?php echo $action['groupID']; ?>" group-name="<?php echo $action['groupName']; ?>">
    <div class="container-header">
        <span aria-hidden="true" class="icon icon-list" data-icon="&#xe0a4;"></span>
        <?php echo lang("BREADCRUMB_VEHICLES").' '.lang("VEHI_MEMBERS").' '.$action['groupName']; ?>
    </div>
    <div class="container-body">
        <div id='loading' style='text-align:center'><?php echo insert_image_cdn("loading.gif"); ?></div>
        <table id="novo-table" class="hover cell-border" width="620px"></table>
    </div>
    <div class="contanier-footer">
        <button id="add" disabled><?= lang("VEHI_ADD"); ?></button>
    </div>
</div>

<div id="add-edit" title="" style='display:none'>
    <div id="content-holder">
        <form id="formAddEdit">
            <input type="hidden" id="func" name="func">
            <input type="hidden" id="idFlota" name="idFlota">
            <input type="hidden" id="idVehicle" name="idVehicle">
            <div class="alert-success" id="message">
                <div class="content-user novo-content">
                    <span aria-hidden="true" class="icon" data-icon="&#xe0a4;"></span>
                    <p><?= lang('VEHI_DAT'); ?></p>
                </div>
                <div class="novo-campo">
                    <span>
                        <p class="novo-campo-name"><?= lang('VEHI_PLATE') ?>:</p>
                        <input type="text" name="plate" id="plate" maxlength="7">
                    </span>
                </div>
                <div class="novo-campo">
                    <span>
                        <p class="novo-campo-name"><?= lang('VEHI_BRAND') ?>:</p>
                        <input type="text" name="brand" id="brand" maxlength="25">
                    </span>
                </div>
                <div class="novo-campo">
                    <span>
                        <p class="novo-campo-name"><?= lang('VEHI_MODEL') ?>:</p>
                        <input type="text" name="model" id="model" maxlength="25">
                    </span>
                </div>
                <div class="novo-campo">
                    <span>
                        <p class="novo-campo-name"><?= lang('VEHI_YEAR') ?>:</p>
                        <input type="text" name="year" id="year" maxlength="4">
                    </span>
                </div>
                <div class="novo-campo">
                    <span>
                        <p class="novo-campo-name"><?= lang('VEHI_CAPACITY') ?>:</p>
                        <input type="text" name="capacity" id="capacity" maxlength="3">
                    </span>
                </div>
                <div id="edit-veh" class="novo-campo">
                    <span>
                        <p class="novo-campo-name"><?= lang('VEHI_ODOMETRO') ?>:</p>
                        <input type="text" name="odometer" id="odometer" maxlength="7">
                    </span>
                </div>
            </div>
            <div id="statusDriver" class="novo-campo">
                <span>
                    <p class="novo-campo-name"><?= lang('TAG_STATUS') ?>:</p>
                    <select name="status" id="status">
                        <?php
                        foreach ($dataResponse as $status):
                        ?>
                        <option value="<?php echo $status->id ?>"><?php echo $status->value ?></option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                </span>
            </div>
            <div class="form-actions">
                <button type="button" id="send-save"></button>
                <button type="reset" id="cancel" class="button-cancel"><?php echo lang('TAG_CANCEL') ?></button>
            </div>
        </form>
        <div id="msg"></div>
    </div>
</div>

<div id="msg-system" style='display:none'>
    <div id="msg-info" class="comb-content"></div>
    <div id="actions" class="comb-content actions-buttons">
        <button id="close-info" class="buttons-action"></button>
        <button id="send-info" class="buttons-action"></button>
    </div>
</div>

<form id='formulario' method='post'></form>
