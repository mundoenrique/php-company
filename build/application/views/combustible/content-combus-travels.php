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
            <a rel="section"><?= lang('BREADCRUMB_TRAVELS'); ?></a>
        </li>
    </ol>
</div>

<div class="container">
    <div class="container-header">
        <span aria-hidden="true" class="icon icon-list" data-icon="&#xe0a4;"></span>
        Opciones de búsqueda
    </div>
    <div id="filter-body" class="container-body whith-form">
        <nav class="filter-travel">
            <ul id="filter-selected">
                <li id="count" class="item-filter selected"><a rel="section"><?php echo lang('TRAVELS_RECENT'); ?></a></li>
                <li id="drivers" class="item-filter item-hover"><a rel="section"><?php echo lang('TRAVELS_DRIVERS'); ?></a></li>
                <li id="vehicles" class="item-filter item-hover"><a rel="section"><?php echo lang('TRAVELS_VEHICLES'); ?></a></li>
                <li id="statusId" class="item-filter item-hover"><a rel="section"><?php echo lang('TRAVELS_STATUS'); ?></a></li>
                <li id="date" class="item-filter item-hover"><a rel="section"><?php echo lang('TRAVELS_DATE'); ?></a></li>
            </ul>
        </nav>
        <div id="container-filter" class="container-filter "style="display: none;">
            <form id="form-filter" name="form-filter">
                <div id="filter-option" class="filters">
                    <label id="label-text" for></label>
                    <select id="search-option" name="search-option">
                        <option id="load" value=""><?php echo lang('TRAVELS_LOAD'); ?></option>
                    </select>
                    <input type="text" id="plate" name="plate" style="display:none">
                </div>
                <div class="filters">
                    <label for="first-date"><?php echo lang('TRAVEL_START_DATE'); ?></label>
                    <input id="first-date" name="first-date" placeholder="DD/MM/AA">
                </div>
                <div class="filters">
                    <label for="last-date"><?php echo lang('TRAVEL_END_DATE'); ?></label>
                    <input id="last-date" name="last-date" placeholder="DD/MM/AA">
                </div>
            </form>
            <div id="msg"></div>
        </div>
    </div>
    <div id="footer-filter" class="contanier-footer" style="display: none;">
        <button id="search" filterList disabled><?php echo lang("TAG_SEARCH"); ?></button>
        <button id="clear-form" class="button-cancel"><?php echo lang("TAG_CLEAR_FORM"); ?></button>
    </div>
    <div class="container-header">
        <span aria-hidden="true" class="icon icon-list" data-icon="&#xe0a4;"></span>
        <?= lang("BREADCRUMB_TRAVELS"); ?>
    </div>
    <div id="table-travels" class="container-body">
        <div id='loading' style='text-align:center'><?php echo insert_image_cdn("loading.gif"); ?></div>
        <table id="novo-table" class="hover cell-border" width="620px"></table>
    </div>
    <div id="footer-filter" class="contanier-footer">
        <button id="add" disabled><?php echo lang("TRAVELS_ADD"); ?></button>
    </div>
</div>

<form id='formulario' method='post'></form>

<div id="msg-system" style="display:none">
    <div id="msg-info" class="comb-content"></div>
    <div id="actions" class="comb-content actions-buttons">
        <button id="close-info" class="buttons-action"></button>
        <button id="send-info" class="buttons-action"></button>
    </div>
</div>
