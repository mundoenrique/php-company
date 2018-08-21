<div class="content-products">
    <h1><?php echo $action['title']; ?></h1>
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
            <a rel="section" href="<?= base_url().$pais; ?>/trayectos/viajes"><?php echo lang('BREADCRUMB_TRAVELS'); ?></a>
        </li>
        /
        <li class="breadcrumb-item-current">
            <a rel="section"><?= lang('BREADCRUMB_ADD_TRAVELS'); ?></a>
        </li>
    </ol>
</div>
<div id="travel" class="container" id-travel="<?php echo $action['travelID']; ?>" func="<?php echo $action['function']; ?>">
    <div class="container-header">
        <span aria-hidden="true" class="icon" data-icon="&#xe006;"></span>
        <?php echo lang('BREADCRUMB_ADD_TRAVELS'); ?>
    </div>
    <div class="container-body">
        <div id='loading' style='text-align:center'><?php echo insert_image_cdn("loading.gif"); ?></div>

        <div id="get-date" class="container-filter elem-hidden">
            <form id="formDate" name="formDate">
                <div class="filters">
                    <span class="date"><label for="first-date"><?php echo lang('TRAVEL_START_DATE'); ?></label></span>
                    <input id="first-date" name="first-date" placeholder="DD/MM/AAAA" disabled>
                    <input id="first-hour" name="first-hour" class="hour-min" placeholder="HH" maxlength="2" disabled>
                    <input id="first-minute" name="first-minute" class="hour-min" placeholder="MM" maxlength="2" disabled>
                </div>

                <div class="filters">
                    <span class="date"><label for="first-date"><?php echo lang('TRAVEL_END_DATE'); ?></label></span>
                    <input id="last-date" name="last-date" placeholder="DD/MM/AAAA" disabled>
                    <input id="last-hour" name="last-hour" class="hour-min" placeholder="HH" maxlength="2" disabled>
                    <input id="last-minute" name="last-minute" class="hour-min" placeholder="MM" maxlength="2" disabled>
                </div>
            </form>
            <div id="msg-date"></div>
        </div>

        <div id="get-route" class="elem-hidden">
            <form id="formAdd" method="post">
                <div id="dateTrip" class="elem-hidden">
                    <div class="field-set">
                    <span class="field-area">
                        <label for="start-date" class="label"><?php echo lang('TRAVEL_START_DATE'); ?></label>
                        <input type="text" id="start-date" name="start-date" class="field date-field" readonly>
                    </span>
                        <span class="field-area">
                        <label for="final-date" class="label"><?php echo lang('TRAVEL_END_DATE'); ?></label>
                        <input type="text" id="final-date" name="final-date" class="field date-field" readonly>
                    </span>
                    </div>
                    <div class="field-set">
                    <span class="field-area">
                        <label for="driver" class="label"><?php echo lang('TRAVELS_DRIVER'); ?></label>
                        <select id="driver" name="driver" class="field elem-hidden">
                            <option id="list-driver" value=""><?php echo lang('TRAVELS_LOAD'); ?></option>
                        </select>
                        <input type="text" id="driverD" name="driverD" class="field elem-hidden" readonly>
                    </span>
                        <span class="field-area">
                        <label for="vehicle" class="label"><?php echo lang('TRAVELS_VEHICLE'); ?></label>
                        <select id="vehicle" name="vehicle" class="field elem-hidden">
                            <option id="list-vehicle" value=""><?php echo lang('TRAVELS_LOAD'); ?></option>
                        </select>
                        <input type="text" id="vehicleD" name="vehicleD" class="field elem-hidden" readonly>

                    </span>
                    </div>
                </div>

                <div id="pointStart" class="elem-hidden">
                    <div class="field-set">
                    <span class="field-area coor-map">
                        <label for="origin" class="label"><?php echo lang('TRAVEL_ORIGIN'); ?></label>
                        <input type="text" id="origin" name="origin" class="coordinate" placeholder="Seleccione punto de partida">
                        <input type="hidden" id="orgL" name="orgL">
                    </span>
                    </div>
                    <div class="field-set map">
                        <div id="map-direction" map="<?= $action['map'] ?>"></div>
                        <section id="def-route" class="bg-map">
                            <div class="map-route" id="map-route">
                                <div id="map-content" class="map-content"></div>
                                <div class="actions">
                                    <button name="" type="button" id="search"><?php echo lang('TRAVELS_CREATE_ROUTE') ?></button>
                                    <button name="" type="reset" id="cancel"><?php echo lang('TAG_CANCEL') ?></button>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                <div id="pointEnd" class="elem-hidden">
                    <div class="field-set">
                    <span class="field-area coor-map">
                        <label for="destination" class="label"><?php echo lang('TRAVEL_DESTINATION'); ?></label>
                        <input type="text" name="destination" id="destination" class="coordinate" placeholder="Seleccione punto de llegada">
                        <input type="hidden" id="desL" name="desL">
                    </span>
                    </div>

                    <div class="field-set map">
                        <div id="map-direction2" map="<?= $action['map'] ?>"></div>
                        <section id="def-route" class="bg-map">
                            <div class="map-route" id="map-route">
                                <div id="map-content" class="map-content"></div>
                                <div class="actions">
                                    <button name="" type="button" id="search"><?php echo lang('TRAVELS_CREATE_ROUTE') ?></button>
                                    <button name="" type="reset" id="cancel"><?php echo lang('TAG_CANCEL') ?></button>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

            </form>
            <div id="msg-route"></div>
        </div>

        <div id="resume" class="elem-hidden">
            <div class="field-set">
                <span class="field-area">
                    <label for="start" class="label"><?php echo lang('TRAVEL_START_DATE'); ?></label>
                    <input type="text" id="start" class="field" readonly>
                </span>
                <span class="field-area">
                    <label for="final" class="label"><?php echo lang('TRAVEL_END_DATE'); ?></label>
                    <input type="text" id="final" class="field" readonly>
                </span>
            </div>
            <div class="field-set">
                <span class="field-area">
                    <label for="driv" class="label"><?php echo lang('TRAVELS_DRIVER'); ?></label>
                    <input type="text" id="driv" class="field" readonly>
                </span>
                <span class="field-area">
                    <label for="vehi" class="label"><?php echo lang('TRAVELS_VEHICLE'); ?></label>
                    <input type="text" id="vehi" class="field" readonly>
                </span>
            </div>
            <div class="field-set">
                <span class="field-area coor-map">
                    <label for="org" class="label"><?php echo lang('TRAVEL_ORIGIN'); ?></label>
                    <input type="text" id="org" class="coordinate" readonly>
                    <input type="text" id="pStart" class="coordinate" readonly hidden>
                </span>
            </div>
            <div class="field-set">
                <span class="field-area coor-map">
                    <label for="dest" class="label"><?php echo lang('TRAVEL_DESTINATION'); ?></label>
                    <input type="text" id="dest" class="coordinate" readonly>
                    <input type="text" id="pEnd" class="coordinate" readonly hidden>
                </span>
            </div>

            <div class="field-set map">
                <div id="map-resume" map="<?= $action['map'] ?>"></div>
                <section id="def-route" class="bg-map">
                    <div class="map-route" id="map-route">
                        <div id="map-content" class="map-content"></div>
                    </div>
                </section>
            </div>
        </div>

        <div id="datailTravel" class="elem-hidden">
						<div class="field-set border-zero">
							<a class="down-report" title="<?php echo lang('TAG_DWN_PDF'); ?>">
								<span id="down-pdf" aria-hidden="true" class="icon" data-icon="&#xe02e"></span>
							</a>
						</div>
            <div class="field-set">
                <span class="field-area">
                    <label for="start" class="label"><?php echo lang('TRAVEL_START_DATE'); ?></label>
                    <input type="text" id="startDetail" class="field" readonly>
                </span>
                <span class="field-area">
                    <label for="final" class="label"><?php echo lang('TRAVEL_END_DATE'); ?></label>
                    <input type="text" id="finalDetail" class="field" readonly>
                </span>
            </div>
            <div class="field-set">
                <span class="field-area">
                    <label for="driv" class="label"><?php echo lang('TRAVELS_DRIVER'); ?></label>
                    <input type="text" id="drivDetail" class="field" readonly>
                </span>
                <span class="field-area">
                    <label for="vehi" class="label"><?php echo lang('TRAVELS_VEHICLE'); ?></label>
                    <input type="text" id="vehiDetail" class="field" readonly>
                </span>
            </div>
            <div class="field-set">
                <span class="field-area coor-map">
                    <label for="org" class="label"><?php echo lang('TRAVEL_ORIGIN'); ?></label>
                    <input type="text" id="pStartDetail" class="coordinate" readonly>
                    <input type="text" id="coordStart" class="coordinate" readonly hidden>
                </span>
            </div>
            <div class="field-set">
                <span class="field-area coor-map">
                    <label for="dest" class="label"><?php echo lang('TRAVEL_DESTINATION'); ?></label>
                    <input type="text" id="pEndDetail" class="coordinate" readonly>
                    <input type="text" id="coordEnd" class="coordinate" readonly hidden>
                </span>
            </div>

            <div class="field-set map">
                <div id="map-detail"></div>
            </div>
        </div>
    </div>
    <div class="contanier-footer">
        <button id="travelAdd"  step="first" disabled><?php echo $action['action']; ?></button>
        <button id="clear-form" class="button-cancel" step="first"><?php echo $action['info']; ?></button>
    </div>
    <div id="routes-alter" class="elem-hidden">
        <div class="container-header">
            <span aria-hidden="true" class="icon" data-icon="&#xe006;"></span>
            <?php echo lang('TRAVELS_ROUTES_ALTER'); ?>
        </div>
        <div class="container-body">
            <div id="panel_ruta" class="content-route"></div>
        </div>
    </div>
</div>
<div id="msg-system" style="display:none">
    <div id="msg-info" class="comb-content"></div>
    <div id="actions" class="comb-content actions-buttons">
        <button id="close-info" class="buttons-action"></button>
        <button id="send-info" class="buttons-action"></button>
    </div>
</div>

<div id="msj-map" style='display:none'>
    <div id="msg-info" class="comb-content"></div>
    <div id="msg">
        <p>No existen rutas entre ambos puntos</p>
    </div>
    <div id="actions" class="comb-content actions-buttons">
        <button id="accept-info" class="buttons-action ">Aceptar</button>
    </div>
</div>

<form id="formulario" method="post"></form>

<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDc-lvekbTTsJpJbbU7P1rfkIw0cRQ_bt8&libraries=places,visualization">
</script>
