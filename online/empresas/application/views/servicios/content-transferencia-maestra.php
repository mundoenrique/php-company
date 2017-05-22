<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$monto = $this->input->get('monto');
$show_cl = (in_array("trapgo", $funciones)) ? '' : 'display:none';
?>

<div id="content-products">
    <h1><?php echo lang('TITULO_TRANSMAESTRA'); ?></h1>

    <h2 class="title-marca">
        <?php echo ucwords(mb_strtolower($programa));?>
    </h2>

    <ol class="breadcrumb">
        <li>
            <a href="<?php echo $urlBase; ?>/dashboard" rel="start">
                <?php echo lang('BREADCRUMB_INICIO'); ?>
            </a>
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
            <a rel="section">
                <?php echo lang('BREADCRUMB_SERVICIOS'); ?>
            </a>
        </li>
        /
        <li class="breadcrumb-item-current">
            <a href="<?php echo $urlBase; ?>/servicios/transferencia-maestra" rel="section">
                <?php echo lang('BREADCRUMB_TRANSMAESTRA'); ?>
            </a>
        </li>
    </ol>

    <div id="lotes-general">
        <div id="recarga_concetradora" style="<?php echo $show_cl ?>">
            <div id="top-batchs">
                <span aria-hidden="true" class="icon" data-icon="&#xe07a;"></span> <?php echo lang('REG_CTA_CONCEN'); ?>
            </div>

            <div id="lotes-contenedor">
                <div id="search-1">
                    <h5><span id="saldoEmpresa"></span></h5>
                    <!--<p id='saldoDisponible'></p>-->
                    <br>
                    <h5 style="float:left;"><?php echo "Monto" ?></h5>
                    <span>
					<input id="amount" placeholder="Ingrese <?php echo "ingrese Monto" ?>" disabled/>
                </span>
                </div>

                <div id="search-3" style="padding-top: 18px">
                    <h5><?php echo "Descripción" ?></h5>
                    <span>
					<input id="description" placeholder="<?php echo "Ingrese la Descripción" ?>" maxlength=16 disabled/>
				</span>
                </div>
            </div>

            <div id="batchs-last">
                <span id="mensajeError" style="float:left; display:none; color:red;"></span>
                <button id='recargar' disabled><?php echo "Recargar" ?></button>
            </div>
        </div>

        <div id="top-batchs">
            <span aria-hidden="true" class="icon" data-icon="&#xe07a;"></span> <?php echo lang('CRITERIOS_BUSQUEDA') ?>
        </div>

        <div id="lotes-contenedor">
            <div id="search-1">
                <h5><?php echo lang('ID_PERSONA'); ?></h5>
                <span>
						<input id="dni" placeholder="Ingrese <?php echo lang('ID_PERSONA'); ?>"/><!-- class='nro' onPaste="return false"-->
						</span>

            </div>
            <div id="search-3">
                <h5><?php echo lang('NRO_TARJETA'); ?></h5>
                <span>
						<input id="nroTjta" placeholder="<?php echo lang('INGRESE_NOTARJETA') ?>" maxlength=16/><!-- class='nro' onPaste="return false"-->
						</span>

            </div>

        </div>

        <div id="batchs-last">
            <span id="mensajeError" style="float:left; display:none; color:red;"></span>
            <button id='buscar'><?php echo lang('BUSCAR'); ?></button>
        </div>

        <div id='resultado-tarjetas' style='display:none'>
            <div id="top-batchs">
                <span aria-hidden="true" class="icon" data-icon="&#xe008;"></span> <?php echo lang('RESULTADOS') ?>
            </div>
            <div id='lotes-contenedor'>
                <div id="check-all">
                    <input id="select-allR" type='checkbox' /><em id='textS'> <?php echo lang("SEL_ALL"); ?></em>
                </div>
                <div class='montos-TM'>
                    <p id='saldoDisponible'></p>
                </div>
                <table class="table-text-aut">
                    <thead>
                    <th class="checkbox-select"><span aria-hidden="true" class="icon" data-icon="&#xe083;"></span></th>
                    <th id="td-nombre-2"><?php echo lang('NRO_TARJETA'); ?></th>
                    <th ><?php echo lang('ESTATUS'); ?></th>
                    <th id='td-nombre-2'><?php echo lang('NOMBRE') ?></th>
                    <th><?php echo lang('ID_PERSONA'); ?></th>
                    <th><?php echo lang('SALDO'); ?></th>
                    <th><?php echo lang('MONTO'); ?></th>
                    <th><?php echo lang('OPCIONES'); ?></th>
                    </thead>
                    <tbody>


                    </tbody>
                </table>

                <div id='paginado-TM'></div>

                <div class='montos-TM' >
                    <p id='comisionTrans'></p>
                    <p id='comisionCons'></p>
                </div>
            </div>

            <div id="batchs-last">
                <input id='clave' class='input-TM' type='password' placeholder="<?php echo lang('PLACEHOLDER_PASS'); ?>"/>
                <button id='cargo-tjta' class='elem-hidden'><?php echo lang('CARGO'); ?></button>
                <button id='abonar-tjta' class='elem-hidden'><?php echo lang('ABONO'); ?></button>
                <button id='consultar-tjta' class='elem-hidden'><?php echo lang('CONSULTA'); ?></button>
            </div>
        </div>
    </div>
</div>

<div id='loading' style='text-align:center' class='elem-hidden'><?php echo insert_image_cdn("loading.gif"); ?></div>