<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$urlCdn = get_cdn();
?>
<h1 class="first-title">{titulo}</h1>

<div id='lotes-general'>


    <ul class="tipo-lote">
        <li>
            <a id='usuario' href="#config-user"><?php echo lang('SUBMENU_USUARIO'); ?></a>
        </li>
        <li>
            <a id='empresas' href="<?php echo $urlBase.'/empresas/config' ?>"><?php echo lang('SUBMENU_EMPRESAS'); ?></a>
        </li>
        <li>
            <a id='sucursales' href="<?php echo $urlBase.'/empresas/configsuc' ?>"><?php echo lang('SUBMENU_SUCURSALES'); ?></a>
        </li>
        <li>
            <a id='descargas'  href="<?php echo $urlBase.'/empresas/configdesc' ?>"><?php echo lang('SUBMENU_DESCARGAS'); ?></a>
        </li>
    </ul>

    <div id="config-user">
        <h1><?php echo lang('CONFIG_USER'); ?></h1>

        <div id="campos-config">
            <div class='content-user'>
                <span aria-hidden="true" class="icon" data-icon="&#xe090;"></span>
                <p id="user-name">{user}</p>
            </div>
            <div id="campos-1">

					<span>
						<p id="first"><?php echo lang('INFO_USER_NAME'); ?></p>
						<p id ="nom_user"></p>
					</span>
                <span>
						<p id="first"><?php echo lang('INFO_USER_APELLIDO'); ?></p>
						<p id ="ape_user"></p>
					</span>
            </div>
            <div id="campos-1">
					<span>
						<p id="first"><?php echo lang('INFO_USER_CARGO'); ?></p>
						<p id="cargo_user"></p>

					</span>
                <span>
						<p id="first"><?php echo lang('INFO_USER_AREA'); ?></p>
						<p id="area_user"></p>

					</span>
            </div>
            <div id="campos-1">
					<span class="input-email">
						<p id="first"><?php echo lang('INFO_USER_EMAIL'); ?></p>
						<input id="email_user" type="text" disabled="disabled" value="" style="float:left;" maxlength='45'/>
						<a title=<?php echo lang('TITLE_MODIFICAR'); ?>>
							<span id="email_userInput" class="icon lapiz-mod" data-icon="" ></span>
						</a>
					</span>

            </div>

            <div id="opciones-btn">
                <button id='btn-modificar' type="submit"><?php echo lang('BOTON_MOD_USER'); ?></button>
                <button id='btn-cambioC' type="submit"><?php echo lang('BOTON_CAMBIO_CLAVE'); ?></button>
            </div>
        </div>
    </div>

</div>

<div id="psw_info" style="display: none;">
    <h5>Requerimientos de contraseña:</h5>
    <ul style="list-style-type: none; padding: 0px; margin: 0px; font-size: 11px;">
        <li id="letter" class="invalid">Debe tener al menos <strong>una letra</strong></li>
        <li id="capital" class="invalid">Debe tener al menos una <strong>letra mayúscula</strong></li>
        <li id="number" class="invalid">Debe tener de <strong>1 a 3 números</strong></li>
        <li id="length" class="invalid">Debe tener <strong>mínimo 8 y máximo 15 caracteres</strong></li>
        <li id="consecutivo" class="invalid">No puede tener más de  <strong>3 caracteres</strong> iguales consecutivos</li>
        <li id="especial" class="invalid">Tener al menos <strong>un caracter especial</strong> (ej: ! @ ? + - . , #)</li>
    </ul>
</div>

<input type='hidden' id='tab' value='<?php echo $_GET["tab"]?>' >

<div id='loading' style='text-align:center' class='elem-hidden'><?php echo insert_image_cdn("loading.gif"); ?></div>
