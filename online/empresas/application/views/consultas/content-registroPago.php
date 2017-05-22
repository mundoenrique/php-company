<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$urlCdn = base_cdn();
?>
	<div id="content-products">

		<h1><?php echo lang('TITULO_REGISTRO_PAGO'); ?></h1>
		<ol class="breadcrumb">
			<li>
				<a href="#" rel="start">
					Inicio /
				</a>
			</li>
			<li>
				<a href="$urlBase/consulta/ordenes-de-servicio" rel="section">
					Ordenes de Servicio /
				</a>
			</li>
			<li class="breadcrumb-item-current">
				<a href="#" rel="section">
					Registro de Pago
				</a>
			</li>
		</ol>
		<input id ="idOrden" type="text" value ="{idOrden}" style="display:none"/>

		<div id = "cargando" style = "display:none"><h2 style="text-align:center"><?php echo lang('CARGANDO'); ?></h2><img style="display:block; margin-left:auto; margin-right:auto" src="<?php echo $urlCdn."media/img/loading.gif"?>"/></div>
		<div id="lotes-general">

			<div id="top-batchs"><span aria-hidden="true" class="icon" data-icon="&#xe035;"></span><?php echo lang('ORDENES_DE_SERVICIO'); ?>
			</div>
			<div id="lotes-contenedor">
				
				<table id="tabla-datos-general" class="tabla-reportes-consulta">
					<thead>
						<tr id="datos-principales">
							<th class="th-empresa"><?php echo lang('TABLA_EMPRESA'); ?></th>
							<th><?php echo lang('TABLA_ORDEN_SERVICIO'); ?></th>
							<th><?php echo lang('TABLA_RUC'); ?></th>
							<th><?php echo lang('TABLA_FECHA'); ?></th>
							<th><?php echo lang('TABLA_CANT_LOTES'); ?></th>
							<th><?php echo lang('TABLA_CANT_REG'); ?></th>
							<th><?php echo lang('TABLA_TOTAL_DEPOSITO'); ?></th>
						</tr>
					</thead>
					<tbody id="tbody-datos-OS" class="tbody-reportes-consulta">

					</tbody>
				</table>
			</div>

			<div id="lotes-2">
				<div id="top-batchs">
					<span aria-hidden="true" class="icon" data-icon="&#xe031;"></span><?php echo lang('TITULO_NUEVO_PAGO'); ?>
				</div>
				<div id="lotes-contenedor">
					<div id="search-1">
						<h5><?php echo lang('FORM_BANCO'); ?></h5>
						<select id="listaBanco">
							<option selected="selected">Empresa</option>
						</select>
					</div>
					<h5><?php echo lang('FORM_TIPO'); ?></h5>
					<select id="tipo">
						<option value = "" selected="selected">Empresa</option>
						<option value="1">Disponibilidad Inmediata</option>
						<option value="2">Transferencia Otros Bancos</option>
						<option value="3">Cheques Otros Bancos</option>
					</select>
					<div id="search-1">
						<h5><?php echo lang('FORM_FECHA'); ?></h5>
						<input  id="fecha_pago" class="required login" type="datetime" name="Ingrese clave de confirmación" placeholder="<?php echo lang('PLACEHOLDER_FECHA'); ?>" value="" />
					</div>
					<div id="search-1">
						<h5><?php echo lang('FORM_REFERENCIA'); ?></h5>
						<input id="referencia" class="required login" type="datetime" name="Ingrese clave de confirmación" placeholder="<?php echo lang('PLACEHOLDER_REFERENCIA'); ?>" value="" />
					</div>
					<h5><?php echo lang('FORM_MONTO'); ?></h5>
					<input id ="monto" class="required login" type="datetime" name="Ingrese clave de confirmación" placeholder="<?php echo lang('PLACEHOLDER_MONTO'); ?>" value="" />
				</div>
			</div>

			<div id="batchs-last">
				<input id="pass" class="required login" type="password" name="Ingrese clave de confirmación" placeholder="<?php echo lang('PLACEHOLDER_CLAVE'); ?>" value="" />
				<button id="registrar" type="submit"><?php echo lang('BTN_REGISTRAR_PAGO'); ?>
				</button>
			</div>
			<div id="lotes-2" class="listapagos">
				<div id="top-batchs">
					<span aria-hidden="true" class="icon" data-icon="&#xe045;"></span><?php echo lang('TITULO_LISTA_PAGOS'); ?>
				</div>
				<div id="lotes-contenedor">
					<table id="tabla-datos-general" class="tabla-reportes-consulta">
						<thead>
							<tr id="datos-principales">
								<th class="th-empresa"><?php echo lang('TABLA_TIPO'); ?></th>
								<th><?php echo lang('TABLA_FECHA'); ?></th>
								<th><?php echo lang('TABLA_REFERENCIA'); ?></th>
								<th><?php echo lang('TABLA_BANCO'); ?></th>
								<th><?php echo lang('TABLA_MONTO'); ?></th>
								<th><?php echo lang('TABLA_ELIMINAR'); ?></th>
							</tr>
						</thead>
						<tbody id="tbody-datos-pagos" class="tbody-reportes-consulta">
						</tbody>
					</table>
				</div>
				
			</div>
			<form  id='lotes-contenedor' method='post' action="<?php echo $urlBase ?>/consulta/ordenes-de-servicio">
				<input type='hidden' id='data-OS' name='data-OS' value='<?php echo $_POST['data-OS'] ?>'/>
				<button id="regresar" type="submit">Regresar</button>
			</form>
			
		</div>
	</div>





