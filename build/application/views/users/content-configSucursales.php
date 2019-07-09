
<div id="config-sucursales">
	<h1><?php echo lang('SUC_TITLE'); ?></h1>

	<select class="select-empresa" id="listaEmpresasSuc" name="batch">
		<? if(array_key_exists('ERROR', $listaEmpr[0])){
			if($listaEmpr[0]['ERROR']=='-29'){
				echo "<script>alert('".$listaEmpr[0]['msg']."'); location.reload();</script>";
			}
		}else{
			echo "<option value=''>Selecciona una empresa</option>";
			foreach ($listaEmpr[0]->lista as $listado) {
				echo "<option data-rif='$listado->acrif' data-nombre='$listado->acnomcia' data-accodcia='$listado->accodcia'>$listado->acnomcia</option>";
			}
		}
		?>

	</select>

	<div id="campos-config">
		<div id='contenido_sucursales'>
			<table id="tabla-datos-general" class="tabla-sucursales elem-hidden">
				<thead>
					<tr id="datos-principales">
						<th>Nombre</th>
						<th>Código</th>
						<th>Contacto</th>
						<th>Teléfono</th>
						<th style="display: none"></th>
					</tr>
				</thead>
				<tbody id="tbody-datos-general" class = "tbody-sucursales">

				</tbody>
			</table>
			<div id="sucursales-paginacion"></div>

		</div>

<div id="opciones-btn" class="suc elem-hidden" style="height: 90px;">

				<button id='btn-new-suc'><?php echo lang('SUC_BTN_NEW_SUC'); ?></button>
				<button id='btn-new-mas'><?php echo lang('SUC_BTN_NEW_MASV'); ?></button>

				<input type="file" name="userfile" id="userfile" class='elem-hidden'/>
				<input id='archivo' placeholder='Seleccione archivo de sucursales.' readonly="readonly" size='35'/>

			</div>

			<div id='form-new-suc'>
				<div id="datos-1"><p id="user-name"><?php echo lang('SUC_TITLE_NEW_SUC'); ?></p>
				</div>
				<div id="campos-dir">

					<span>
						<p id="first"><?php echo lang('SUC_NOMB'); ?>*</p>
						<input type = "text" id ="suc_nom" placeholder ="Introduzca el nombre de la empresa" maxlength='150'>
					</span>
				</div>
				<div id="campos-dir">
					<span>
						<p id="first"><?php echo lang('SUC_ZONA'); ?>*</p>
						<input type = "text" id = "suc_zona" placeholder = "Punto de referencia" maxlength='100'>
					</span>

				</div>
				<div id="campos-dir">
					<span>
						<p id="first"><?php echo lang('SUC_DIRECCION_1'); ?>*</p>
						<input type = "text" id ="suc_dir1" placeholder = "Dirección principal" maxlength='250'>
					</span>

				</div>
				<div id="campos-dir">
					<span>
						<p id="first"><?php echo lang('SUC_DIRECCION_2'); ?></p>
						<input type = "text" id ="suc_dir2" placeholder = "Dirección alternativa" maxlength='250' >
					</span>

				</div>
				<div id="campos-dir">
					<span>
						<p id="first"><?php echo lang('SUC_DIRECCION_3'); ?></p>
						<input type = "text" id ="suc_dir3" placeholder = "Dirección alternativa" maxlength='250'>
					</span>

				</div>
				<div id="campos-1">
					<span>
						<p id="first"><?php echo lang('SUC_PAIS'); ?>*</p>
						<select id="suc_pais" disabled>
						</select>
					</span>
					<span>
						<p id="first" class='refestado'><?php echo lang('SUC_ESTADO'); ?>*</p>
						<select id="suc_estado">
							<option value="">Seleccione <?php echo lang('SUC_ESTADO'); ?></option>
						</select>
					</span>

				</div>
				<div id="campos-1">
					<span>
						<p id="first" class='refciudad'><?php echo lang('SUC_CIUDAD'); ?>*</p>
						<select id="suc_ciudad">
							<option value="">Seleccione <?php echo lang('SUC_CIUDAD'); ?></option>
						</select>
					</span>

				</div>
				<div id="campos-1">
					<span>
						<p id="first"><?php echo lang('SUC_AREA'); ?></p>
						<input type="text" id="suc_area" class='nro' placeholder= "Código de área">
					</span>
					<span>
						<p id="first"><?php echo lang('SUC_TELEFONO'); ?></p>
						<input type = "text" id = "suc_tlf" class='nro' placeholder = "Teléfono">
					</span>

				</div>
				<div id="campos-1">
					<span>
						<p id="first"><?php echo lang('SUC_CONTACTO'); ?></p>
						<input type="text" id="suc_contacto" placeholder= "Nombre del contacto" size="26" maxlength='100'>
					</span>
					<span>
						<p id="first"><?php echo lang('SUC_CODIGO'); ?>*</p>
						<input type = "text" disabled="disabled" id="suc_cod" placeholder ="Código de la sucursal" maxlength='15'>
					</span>

				</div>


				<div id="opciones-btn" style='margin-top:20px'>
					<button id='agregarSuc'><?php echo lang('EMP_BTN_ADD_CONTACT'); ?></button>
					<button id='btn-modif-suc'><?php echo lang('SUC_BTN_MODIFICAR'); ?></button>
					<input id="pass_suc" type="password" class="pass" style="margin-left:245px; margin-top: 4px" placeholder="Ingrese su contraseña">

			</div>
		</div>
	</div>
</div>
