
<!--<div id="errorDriver" code="--><?php //echo  $code ?><!--" title="--><?php //echo $title ?><!--" msg="--><?php //echo $msg ?><!--"></div>-->
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
            <a rel="section"><?= lang('BREADCRUMB_ACCOUNTS'); ?></a>
        </li>
    </ol>
</div>
<div class="container">
    <div class="container-header" id="filter-title" >
        <span aria-hidden="true" class="icon icon-list" data-icon="&#xe020;"></span>
        Busqueda
    </div>
    <div class="container-body" id="filter" >
        <nav class="filter-travel">
            <ul id="filter-selected">
                <li id="accountAllocated" class="item-filter selected"><a rel="section">Cuentas asignadas</a></li>
                <li id="accountAvailable" class="item-filter item-hover"><a rel="section">Cuentas disponibles</a></li>
            </ul>
        </nav>
    </div>

    <div class="container-header">
        <span aria-hidden="true" class="icon icon-list" data-icon="&#xe020;"></span>
        <?= lang("BREADCRUMB_ACCOUNTS"); ?>
    </div>
    <div class="container-body" id="novo-container-body">
        <div id='loading' style='text-align:center'><?php echo insert_image_cdn("loading.gif"); ?></div>
        <table id="novo-table" class="hover cell-border" width="620px"></table>
    </div>

</div>


<form id='formularioAccount' method='post' name="form1"></form>
<input type="hidden" id="logUrl" value="<?php echo  base_url().$pais; ?>">

<div id="msg-system" style='display:none' >
    <div id="msg-info" class="comb-content"></div>
    <div id="actions" class="comb-content actions-buttons">
<!--      <button id="send-info" class="buttons-action2">--><?php //echo lang('TAG_ACCEPT') ?><!--</button>-->
<!--      <button id="close-info" class="buttons-action2">--><?php //echo lang('TAG_CANCEL') ?><!--</button>-->
    </div>
</div>

<form id='formulario' method='post'></form>
