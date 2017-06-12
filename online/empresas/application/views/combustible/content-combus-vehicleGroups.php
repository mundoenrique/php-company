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
            <a rel="section"><?= lang('BREADCRUMB_GROUP'); ?></a>
        </li>
    </ol>
</div>
<div class="container">
    <div class="container-header">
        <span aria-hidden="true" class="icon icon-list" data-icon="&#xe0a4;"></span>
        <?= lang("BREADCRUMB_GROUP"); ?>
    </div>
    <div class="container-body">
        <div id='loading' style='text-align:center'><?php echo insert_image_cdn("loading.gif"); ?></div>
        <table id="novo-table" class="hover cell-border" width="620px"></table>
    </div>
    <div class="contanier-footer">
        <button id="add" disabled><?php echo lang("GROUP_ADD"); ?></button>
    </div>
</div>

<div id="add-edit" title="" style='display:none'>
    <div id="content-holder">
        <?= form_open(
            '',
            array(
                'id' => 'formAddEdit',
            )
        ); ?>
        <input type="hidden" id="func" name="func">
        <input type="hidden" id="idFlota" name="idFlota">
        <div class="alert-success" id="message">
            <div class="content-user novo-content">
                <span aria-hidden="true" class="icon icon-list" data-icon="&#xe0a4;"></span>
                <p><?= lang('GROUP_DATA'); ?></p>
            </div>
            <div id="" class="novo-campo">
                    <span>
                        <p class="novo-campo-name"><?= lang('TAG_NAME') ?>:</p>
                        <?= form_input( array(
                            'name' => 'nameGroup',
                            'id' => 'nameGroup',
                            'maxlength' => '25',
                        )); ?>
                    </span>
            </div>
            <div class="novo-campo">
                    <span>
                        <p class="novo-campo-name"><?= lang('TAG_DESCRIPTION') ?>:</p>
                        <?= form_textarea( array(
                            'name' => 'desc',
                            'id' => 'descGroup',
                            'maxlength' => '50',
                            'rows' => '5'
                        )); ?>
                    </span>
            </div>
        </div>
        <div class="form-actions">
            <?= form_button( array(
                'id' => 'send-save',
                'type' => 'button',
                'function' => '',
                'content' => ''
            ));?>
            <?= form_button( array(
                'id' => 'cancel',
                'type' => 'reset',
                'class' => 'button-cancel',
                'content' => lang('TAG_CANCEL')
            )); ?>
        </div>
        <?= form_close(); ?>
        <div id="msg"></div>
    </div>
</div>
<form id='formulario' method='post'></form>


<div id="msg-system" style='display:none'>
    <div id="msg-info" class="comb-content"></div>
    <div id="actions" class="comb-content actions-buttons">
        <button id="close-info" class="buttons-action"></button>
        <button id="send-info" class="buttons-action"></button>
    </div>
</div>
