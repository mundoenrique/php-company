<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
?>
<div id="content-products">
			<h1>{titulo}</h1>
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
						<li class="breadcrumb-item-current">
							<a href="<?php echo $urlBase; ?>/lotes/reproceso" rel="section">
								<?php echo lang('BREADCRUMB_REPROCESO'); ?>
							</a>
						</li>
				</ol>
	<div id="lotes-general">
		<div id="top-batchs">
			<span aria-hidden="true" class="icon" data-icon="&#xe02c;"></span> Selección
		</div>
		<div id="lotes-contenedor">
			<div class='select-tipo-guarderia'>
				Tipo de lote:
				<select id="tipoCheque">
					<?
					if(array_key_exists("ERROR", $selectTiposLotes[0])){
						if($selectTiposLotes[0]["ERROR"]=='-29'){
							redirect($urlBase.'/login');
						}else{
							echo "<option value=''>".$selectTiposLotes[0]["ERROR"]."</option>";
						}

					}else{
						foreach ($selectTiposLotes[0]->lista as $tipol) {
							$tipoLS = ucfirst(mb_strtolower($tipol->tipoLote));
							echo "<option value='$tipol->idTipoLote'>$tipoLS</option>";
						}
					}
					?>
				</select>
			</div>
			<button id='crear'>Crear Guardería</button>
			<button id='buscar'>Buscar Guardería</button>
		</div>
		<div id="top-batchs" class='crear elem-hidden'>
			<span aria-hidden="true" class="icon" data-icon="&#xe08a;"></span> <?php echo lang('TITULO_CREAR_BENEFICIARIO') ?>
		</div>

		<div id="lotes-contenedor" class='crear elem-hidden'>
				<div class="campos-reproceso">
					<p>Cédula empleado: *</p><input type="text" id='idPersona' class='nro' maxlength='8'/>
				</div>
				<div class="campos-reproceso">
					<p>Apellido empleado: *</p><input type="text" id='apellEmpl'/>
				</div>
				<div class="campos-reproceso">
					<p>Nombre empleado: *</p><input type="text" id='nombEmpl'/>
				</div>
				<div class="campos-reproceso chq-elect">
					<p>Email empleado: *</p><input type="text" id='emailEmpl' />
				</div>
				<div class="campos-reproceso">
					<p>Apellido niño(a): *</p><input type="text" id='apellInfant' />
				</div>
				<div class="campos-reproceso">
					<p>Nombre niño(a): *</p><input type="text" id='nombInfant'/>
				</div>
				<div class="campos-reproceso">
					<p>Nombre guardería: *</p><input type="text" id='nombGuard' />
				</div>
				<div class="campos-reproceso chq-elect">
					<p>RIF guardería: *</p><input type="text" id='idfiscalGuard' placeholder='x-xxxxxxxx-x'/>
				</div>
				<div class="campos-reproceso chq-elect">
					<p>Nro. Cuenta guardería: *</p><input type="text"  id='nroCuentaGuard' class='nro' maxlength='20'/>
				</div>
				<div class="campos-reproceso chq-elect">
					<p>Email guardería: *</p><input type="text" id='emailGuard'/>
				</div>
				<div class="campos-reproceso">
					<p>Monto: *</p><input type="text" id='monto' />
				</div>
				<div class="campos-reproceso">
					<p>Concepto pago: *</p><input type="text" id='concepto'/>
				</div>
		</div>
		<div id="batchs-last" class='crear elem-hidden'>
			<button id='btnCrearBenf'>Crear</button>
			<input id="passcrear" class="input-pass-reproceso" placeholder="Ingrese su contraseña" type='password'/>
		</div>
		<div id="top-batchs" class='cargar elem-hidden'>
			<span aria-hidden="true" class="icon" data-icon="&#xe08a;"></span> <?php echo lang('TITULO_MASIVO_BENEFICIARIO') ?>
		</div>
		<div id="lotes-contenedor" class='cargar elem-hidden'>
			<input type="file" name="userfile" id="userfile" class='elem-hidden'/>
			<input id='archivo' placeholder='Click aquí para archivo seleccionar archivo.' readonly="readonly" size='70'/>
			<button id="cargarXLS" ><?php echo "Cargar" ?></button>
		</div>
		<div id="top-batchs" class='buscar elem-hidden'>
			<span aria-hidden="true" class="icon" data-icon="&#xe046;"></span> <?php echo lang('TITULO_LISTA_BENEFICIARIOS') ?>
		</div>
		<div id="lotes-contenedor" class='buscar elem-hidden'>
			<table id='lista-reproceso' class="tabla-reportes" >
				<thead>
					<tr id="datos-principales">
						<th class='checkbox-select'><input id="selectAll" type='checkbox' title="seleccionar todos"/> </th>
						<th class='td-medio'><?echo lang('ID_PERSONA');?></th>
						<th id="th-empleado">Empleado</th>
						<th class='td-elect'>Guardería</th>
						<th id='td-nombre-2'>Cuenta Guardería</th>
						<th class='td-medio'>Monto</th>
						<th class='td-corto'>Opción</th>
					</tr>
				</thead>
				<tbody class="tbody-reportes"></tbody>
			</table>
			<div id='paginado'></div>
		</div>
		<div id="batchs-last" class='elem-hidden buscar'>
			<button id='btn-eliminar-benf'>Eliminar</button>
			<button id='modificacionMasiva'>Modificar y Reprocesar</button>
			<button id='reprocesar'>Reprocesar</button>
			<input id="passreprocesar" class="input-pass-reproceso" placeholder="Ingrese su contraseña" type='password'/>
		</div>
	</div>

	<div id="camposReprocesoMasivo">
		<br>
		<div id="MensajeRegistros"></div><br>
		<table id="Estadistica"></table>
	</div>

	<div id="camposBenef">
		<div class="campos-reproceso">
			<p>Cédula empleado: *</p><input type="text" id='idPersona' class='nro' maxlength='8' disabled/>
		</div>
		<div class="campos-reproceso">
			<p>Apellido empleado: *</p><input type="text" id='apellEmpl' disabled/>
		</div>
		<div class="campos-reproceso">
			<p>Nombre empleado: *</p><input type="text" id='nombEmpl' disabled/>
		</div>
		<div class="campos-reproceso chq-elect">
			<p>Email empleado: *</p><input type="text" id='emailEmpl' />
		</div>
		<div class="campos-reproceso">
			<p>Apellido niño(a): *</p><input type="text" id='apellInfant'disabled/>
		</div>
		<div class="campos-reproceso">
			<p>Nombre niño(a): *</p><input type="text" id='nombInfant' disabled/>
		</div>
		<div class="campos-reproceso">
			<p>Nombre guardería: *</p><input type="text" id='nombGuard' />
		</div>
		<div class="campos-reproceso chq-elect">
			<p>RIF guardería: *</p><input type="text" id='idfiscalGuard' placeholder='x-xxxxxxxx-x'/>
		</div>
		<div class="campos-reproceso chq-elect">
			<p>Nro. Cuenta guardería: *</p><input type="text"  id='nroCuentaGuard' class='nro' maxlength='20'/>
		</div>
		<div class="campos-reproceso chq-elect">
			<p>Email guardería: *</p><input type="text" id='emailGuard'/>
		</div>
		<div class="campos-reproceso">
			<p>Monto: *</p><input type="text" id='monto' />
		</div>
		<div class="campos-reproceso">
			<p>Concepto pago: *</p><input type="text" id='concepto'/>
		</div>
	</div>

</div>
<div id="modal_modalidad_pago"  class='elem-hidden'>
	<?php
	  $html = '';
		foreach ($selectTiposLotes[0]->mediosPago as $medio) {
			echo '<input type="radio" id="methodChoice'.$medio->idPago.'"
									name="methodChoice" value="'.$medio->descripcion.$medio->idPago.'">
									<label for="methodChoice'.$medio->idPago.'">'.$medio->descripcion.'</label><br>';

		}
	?>
</div>
<div id='loading' style='text-align:center' class='elem-hidden'><?php echo insert_image_cdn("loading.gif"); ?></div>
<form id='toOS' action="<?php echo $urlBase ?>/consulta/ordenes-de-servicio " method="post">
	<input type="hidden" name="data-OS" value="" id="data-OS" />
</form>
