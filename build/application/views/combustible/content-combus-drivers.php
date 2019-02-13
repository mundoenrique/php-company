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
        <li class="breadcrumb-item-current">
            <a rel="section"><?= lang('BREADCRUMB_DRIVERS'); ?></a>
        </li>
    </ol>
</div>
<div class="container">
    <div class="container-header">
        <span aria-hidden="true" class="icon icon-list" data-icon="&#xe020;"></span>
        <?= lang("BREADCRUMB_DRIVERS"); ?>
    </div>
    <div id="table-drivers" class="container-body">
        <div id='loading' style='text-align:center'><?php echo insert_image_cdn("loading.gif"); ?></div>
        <table id="novo-table" class="hover cell-border" width="620px"></table>
    </div>
    <div id="footer-filter" class="contanier-footer">
        <button id="add" disabled><?php echo lang("DRIVER_ADD"); ?></button>
    </div>
</div>

<form id='formulario' method='post'></form>
<input type="hidden" id="logUrl" value="<?php echo  base_url().$pais; ?>">

<div id="msg-system" style='display:none'>
    <div id="msg-info" class="comb-content"></div>
    <div id="actions" class="comb-content actions-buttons">
        <button id="close-info" class="buttons-action"></button>
        <button id="send-info" class="buttons-action"></button>
    </div>
</div>
