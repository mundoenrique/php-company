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
        <li class="breadcrumb-item-current">
            <a rel="section"><?= lang('BREADCRUMB_COMBUSTIBLE'); ?></a>
        </li>
    </ol>
</div>
<div class="container">
    <a href="<?= base_url().$pais; ?>/trayectos/conductores">
        <div class="home-item">
            <div class="item-img">
                <span aria-hidden="true" class="icon icon-list" data-icon="&#xe020;"></span>
            </div>
            <div class="item-detail">
                <p><strong><?= lang('DRIVER_TITLES') ?></strong></p>
            </div>
        </div>
    </a>
    <a href="<?= base_url().$pais; ?>/trayectos/cuentas">
        <div class="home-item">
            <div class="item-img">
                <span aria-hidden="true" class="icon icon-list" data-icon="&#xe027;"></span>
            </div>
            <div class="item-detail">
                <p><strong><?= lang('MENU_INVENTORIES') ?></strong></p>
            </div>
        </div>
    </a>
    <a href="<?= base_url().$pais; ?>/trayectos/gruposVehiculos">
        <div class="home-item">
            <div class="item-img">
                <span aria-hidden="true" class="icon icon-list" data-icon="&#xe0a4;"></span>
            </div>
            <div class="item-detail">
                <p><strong><?= lang('MENU_VEHICLES') ?></strong></p>
            </div>
        </div>
    </a>
    <a href="<?= base_url().$pais; ?>/trayectos/viajes">
        <div class="home-item">
            <div class="item-img">
                <span aria-hidden="true" class="icon icon-list" data-icon="&#xe006;"></span>
            </div>
            <div class="item-detail">
                <p><strong><?= lang('MENU_TRAVELS') ?></strong></p>
            </div>
        </div>
    </a>
</div>
