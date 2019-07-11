
<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
?>

<div id="content-products">
			<h1>{titulo}</h1>
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
			<span aria-hidden="true" class="icon" data-icon="&#xe089;"></span> <?php echo lang('TITULO_CREAR_BENEFICIARIO') ?>
		</div>
		<div id="lotes-contenedor">

			<div class='select-tipo-guarderia'>
				Tipo de lote:
				<select id="tipoChequeCrear">
					<option value="E">cheque guardería-elect</option>
					<option value="G">cheque guardería</option>
				</select>
			</div>

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
					<p>Apellido infante: *</p><input type="text" id='apellInfant' />
				</div>
				<div class="campos-reproceso">
					<p>Nombre infante: *</p><input type="text" id='nombInfant'/>
				</div>
				<div class="campos-reproceso">
					<p>Nombre guardería: *</p><input type="text" id='nombGuard' />
				</div>
				<div class="campos-reproceso chq-elect">
					<p>RIF guardería: *</p><input type="text" id='idfiscalGuard' />
				</div>
				<div class="campos-reproceso chq-elect">
					<p>Nro. Cuenta guardería: *</p><input type="text"  id='nroCuentaGuard' class='nro' maxlength='20'/>
				</div>
				<div class="campos-reproceso chq-elect">
					<p>Email guardería: *</p><input type="text" id='emailGuard'/>
				</div>
				<div class="campos-reproceso">
					<p>Monto cheque: *</p><input type="text" id='monto' />
				</div>
				<div class="campos-reproceso">
					<p>Concepto pago: *</p><input type="text" id='concepto'/>
				</div>


		</div>
		<div id="batchs-last">
			<input id="passCrear" class="input-pass-reproceso" placeholder="Ingresa tu contraseña" type='password'/>
			<button id='btnCrearBenf'>Crear</button>
		</div>

		<div id="top-batchs">
			<span aria-hidden="true" class="icon" data-icon="&#xe089;"></span> <?php echo lang('TITULO_MASIVO_BENEFICIARIO') ?>
		</div>
		<div id="lotes-contenedor">
			<input type="file" name="userfile" id="userfile" class='elem-hidden'/>
			<input id='archivo' placeholder='Click aquí para archivo seleccionar archivo.' readonly="readonly" size='70'/>
			<button id="cargarXLS" ><?php echo "Cargar" ?></button>
		</div>


		<div id="top-batchs">
			<span aria-hidden="true" class="icon" data-icon="&#xe07a;"></span> <?php echo lang('TITULO_LISTA_BENEFICIARIOS') ?>
		</div>

		<div id="lotes-contenedor">
			<div class="div-buscar-reproceso">
				Tipo de lote:
				<select id="tipoChequeBuscar">
					<option value="E">cheque guardería-elect</option>
					<option value="G">cheque guardería</option>
				</select>

				<button id='buscar'><?php echo "Buscar"; ?></button>
			</div>
			<table id='lista-reproceso' class="tabla-reportes elem-hidden" >
				<thead>
					<tr id="datos-principales">
						<th class='td-corto'><?echo lang('ID_PERSONA');?></th>
						<th >Empleado</th>
						<th >Beneficiario</th>
						<th >Nro. Cuenta</th>
						<th>Monto</th>
						<th class='td-corto'>Opción</th>
					</tr>
				</thead>
				<tbody class="tbody-reportes">

				</tbody>
			</table>

		</div>

		<div id="batchs-last" class='elem-hidden'>
			<button id='reprocesar'><?php echo "Reprocesar" ?></button>
		</div>

	</div>


	<div id="camposBenef">
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
			<p>Apellido infante: *</p><input type="text" id='apellInfant' />
		</div>
		<div class="campos-reproceso">
			<p>Nombre infante: *</p><input type="text" id='nombInfant'/>
		</div>
		<div class="campos-reproceso">
			<p>Nombre guardería: *</p><input type="text" id='nombGuard' />
		</div>
		<div class="campos-reproceso chq-elect">
			<p>RIF guardería: *</p><input type="text" id='idfiscalGuard' />
		</div>
		<div class="campos-reproceso chq-elect">
			<p>Nro. Cuenta guardería: *</p><input type="text"  id='nroCuentaGuard' class='nro' maxlength='20'/>
		</div>
		<div class="campos-reproceso chq-elect">
			<p>Email guardería: *</p><input type="text" id='emailGuard'/>
		</div>
		<div class="campos-reproceso">
			<p>Monto cheque: *</p><input type="text" id='monto' />
		</div>
		<div class="campos-reproceso">
			<p>Concepto pago: *</p><input type="text" id='concepto'/>
		</div>
	</div>


</div>

<div id='loading' style='text-align:center' class='elem-hidden'><?php echo insert_image_cdn("loading.gif"); ?></div>
<form id='formulario' method='post'></form>
