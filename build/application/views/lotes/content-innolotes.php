<div id="content-products">
	<h1><?php echo lang('TITULO_CUENTAS_INNO');?></h1>

	<h2 class="title-marca">
		<?php echo ucwords(mb_strtolower($programa));?>
	</h2>

	<ol class="breadcrumb">
		<li>
			<a href="<?= base_url($pais.'/dashboard'); ?>" rel="start"><?= lang('BREADCRUMB_INICIO'); ?></a>
		</li>
		/
		<li>
			<a href="<?= base_url($pais.'/dashboard'); ?>" rel="section"><?= lang('BREADCRUMB_EMPRESAS'); ?></a>
		</li>
		/
		<li>
			<a href="<?= base_url($pais.'/dashboard/productos'); ?>" rel="section"><?= lang('BREADCRUMB_PRODUCTOS'); ?></a>
			</li>
			/
			<li>
				<a href="<?= base_url($pais.'/lotes'); ?>" rel="section"><?= lang('BREADCRUMB_LOTES'); ?></a>
			</li>
			/
			<li class="breadcrumb-item-current">
				<a><?= lang('POSITION_INNO'); ?></a>
			</li>
		</ol>

		<div style="display: block;" id="lotes-general" class="elem-hidden">

			<div id="filtroOS">

				<div id="top-batchs">
					<span aria-hidden="true" class="icon" data-icon="&#xe09e;"></span>
					Solicitud
				</div>

				<div id="lotes-contenedor">
					<span class="info-OD">
						<h5>Sucursal</h5>
						<span id="cargando" style="color:#0072C0">Cargando...</span>
						<select id="sucursal" name="batch" class="select_sucursales" disabled="disabled">
							<option value="">Selecciona</option>
						</select>
					</span>
					<div class="info-OD">
						<h5>Cantidad de tarjetas</h5>
						<input id="cant_tarjetas" class="required input4 nro" max-tjta="<?= $maxTarjetas ?>">
						<!-- onfocus="javascript:this.value=''" -->
					</div>
					<span class="info-OD">
						<h5>Fecha de expiraci&oacute;n</h5>
						<input id="fecha_expira" class="required input4" placeholder="MM/AA" value="<?php echo $mesesVencimiento; ?>" <?php if($pais=="Co"){echo"disabled";} ?> ><!-- onfocus="javascript:this.value=''" -->
					</span>
					<div class="info-OD">
						<h5>L&iacute;nea de embozo 1</h5>
						<input id="embozo_1" class="required input4" value=""><!--  onfocus="javascript:this.value=''" -->
					</div>
					<div class="info-OD">
						<h5>L&iacute;nea de embozo 2</h5>
						<input id="embozo_2" class="required input4" value=""><!-- onfocus="javascript:this.value=''" -->
					</div>
				</div>

				<?php
					if ( $pais == 'Ec-bp'):
				?>
				<div class="text-center">
					<input id="user-password" class="required input5" type="password" placeholder="Ingrese la Clave" autocomplete="off">
				</div>
				<?php endif; ?>

				<div id="batchs-last" class="button-process">
					<button id="procesar" class="novo-btn-primary">Procesar</button>
				</div>

			</div>

		</div>
	</div>
<div id='loading' style='text-align:center' class='elem-hidden'><?php echo insert_image_cdn("loading.gif"); ?></div>
