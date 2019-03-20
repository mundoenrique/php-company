<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$urlCdn = get_cdn();
?>
<div id="config-empresas">

<div id = "cargando" style = "display:none"><h2 style="text-align:center"><?php echo lang('CARGANDO'); ?></h2><img style="display:block; margin-left:auto; margin-right:auto" src="<?php echo $urlCdn."media/img/loading.gif"?>"/></div>

	<h1><?php echo lang('EMP_TITLE_CONFIG'); ?></h1>

	<select class="select-empresa" id="listaEmpresas" name="batch">
		<? if(array_key_exists('ERROR', $listaEmpr[0])){
			if($listaEmpr[0]['ERROR']=='-29'){
				echo "<script>alert('".$listaEmpr[0]['msg']."'); location.reload();</script>";
			}
		}else{
			echo "<option value=''>Seleccione una empresa</option>";
			foreach ($listaEmpr[0]->lista as $listado) {
				echo "<option data-rif='$listado->acrif' data-nombre='$listado->acnomcia' data-accodcia='$listado->accodcia'>$listado->acnomcia</option>";
			}
		}
		?>
	</select>

	<div id="campos-config" class='elem-hidden'>

		<div id="campos-1">

			<span>
				<p id="first"><?php echo lang('EMP_IDENTIFICADOR'); ?></p>
				<p id="rif"></p>
			</span>
			<span>
				<p id="first"><?php echo lang('EMP_NOMBRE'); ?></p>
				<p id="nombre"></p>
			</span>
		</div>
		<div id="campos-1">
			<span>
				<p id="first"><?php echo lang('EMP_RAZON_SOCIAL'); ?></p>
				<p id="razon"></p>
			</span>
			<span>
				<p id="first"><?php echo lang('EMP_CONTACTO'); ?></p>
				<p id="contacto"></p>
			</span>
		</div>
		<div id="campos-dir">
			<span>
				<p id="first"><?php echo lang('EMP_DIR_UBICACION'); ?></p>
				<p id = "ubicacion">
				</p>
			</span>

		</div>
		<div id="campos-dir">
			<span>
				<p id="first"><?php echo lang('EMP_DIR_FACTURACION'); ?></p>
				<p id="facturacion">
				</p>
			</span>

		</div>
		<div id="campos-1">
			<span>
				<p id="first"><?php echo lang('TITLE_TELEFONO1') ?></p>
				<input id="tlf1" class='nro' type="text" disabled="disabled" value="" style="float:left;" />
					<a title=<?php echo lang('TITLE_MODIFICAR'); ?>>
						<span id="tlf1Input" class="icon lapiz-mod" data-icon="" ></span>
					</a>
			</span>
			<span>
				<p id="first"><?php echo lang('TITLE_TELEFONO2') ?></p>
				<input id="tlf2" class='nro' type="text" disabled="disabled" value="" style="float:left;" />
					<a title=<?php echo lang('TITLE_MODIFICAR'); ?>>
						<span id="tlf2Input" class="icon lapiz-mod" data-icon="" ></span>
					</a>
			</span>

		</div>
		<div id="campos-1">
			<span>
				<p id="first"><?php echo lang('TITLE_TELEFONO3') ?></p>
				<input id="tlf3" class='nro' type="text" disabled="disabled" value="" style="float:left;" />
					<a title=<?php echo lang('TITLE_MODIFICAR'); ?>>
						<span id="tlf3Input" class="icon lapiz-mod" data-icon="" ></span>
					</a>
			</span>

		</div>
		<div id="opciones-btn">
			<button id='modif' type="submit"><?php echo lang('EMP_BTN_MODIF'); ?></button>
			<button id='mostrarContact' type="submit" ><?php echo lang('EMP_BTN_CONTACT'); ?></button>
			<button id='agregarContact' class='agregar-contact' ><?php echo lang('EMP_BTN_AGREGAR_CONTACT_EMPRESA'); ?></button>
		</div>
		<div id='contactos'>

	<div id="datos-1">
		<p id="user-name">Listado de contactos asociados</p>
	</div>
	<div id ="contenedor_contacts">

	</div>
	<div id="contact-paginacion"></div>
	<div id="opciones-btn">
		<button id='eliminar_contact'><?php echo lang('EMP_BTN_ELIM_CONTACT'); ?></button>
		<button id='modificar_contact'><?php echo lang('EMP_BTN_MODIF_CONTACT'); ?></button>
		<!-- <button id='anadir' class='agregar-contact'><?php //echo lang('EMP_BTN_AGREGAR_CONTACT'); ?></button> -->
		<input id="pass" type="password" class="pass" style="margin-left:170px" placeholder="Ingrese su Contraseña">
	</div>
</div>

<div id="agregarContacto">

			<div id="campos-1">
			<span>
				<p id="first"><?php echo lang('INFO_USER_NAME'); ?></p>
				<input id='contact-nomb' type="text" class="required" size='24' maxlength='100'>
			</span>
			<span>
				<p id="first"><?php echo lang('INFO_USER_APELLIDO'); ?></p>
				<input id='contact-apell' type="text" class="required" maxlength='100'>
			</span>


		</div>
		<div id="campos-1">

			<span>
				<p id="first"><?php echo lang('INFO_USER_CARGO'); ?></p>
				<input id='contact-carg' type="text" class="required"  size='26' maxlength='50'>
			</span>
			<span>
				<p id="first"><?php echo lang('ID_PERSONA'); ?></p>
				<input id='contact-id' type="text" class="required nro" >
			</span>

		</div>
		<div id="campos-1">

			<span>
				<p id="first"><?php echo lang('INFO_USER_EMAIL'); ?></p>
				<input id='contact-email' type="text" class="required" size='26' maxlength='45'>
			</span>
			<span>
				<p id="first"><?php echo lang('EMP_CONTACT_TIPO'); ?></p>
				<select class="config-empresas required" id="tipo_contact" name="batch" required>
					<option value="" selected="selected">Seleccionar tipo</option>
				</select>
			</span>


		</div>

	<div id="opciones-btn">
		<button id='agregar' type"submit"><?php echo lang('EMP_BTN_ADD_CONTACT'); ?></button>
		<button id='limpiar'><?php echo lang('EMP_BTN_LIMPIAR_CONTACT'); ?></button>
		<input id="passAgregar" type="password" class="pass" style="margin-left:170px" placeholder="Ingrese su Contraseña">
	</div>
		</div>

</div>

<input type='hidden' id='info_user_name' value='<?php echo lang('INFO_USER_NAME'); ?>'>
<input type='hidden' id='title_modificar' value='<?php echo lang('TITLE_MODIFICAR'); ?>'>
<input type='hidden' id='info_user_apellido' value='<?php echo lang('INFO_USER_APELLIDO'); ?>'>
<input type='hidden' id='info_user_cargo' value='<?php echo lang('INFO_USER_CARGO'); ?>'>
<input type='hidden' id='id_persona' value='<?php echo lang('ID_PERSONA'); ?>'>
<input type='hidden' id='info_user_mail' value='<?php echo lang('INFO_USER_EMAIL'); ?>'>
<input type='hidden' id='emp_contact_tipo' value='<?php echo lang('EMP_CONTACT_TIPO'); ?>'>


