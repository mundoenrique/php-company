<?php
	$pais = $this->uri->segment(1);
	$urlBaseA = $this->config->item('base_url');
	$urlBase = $urlBaseA.$pais;
	$monto = $this->input->get('monto');
	$show_cl = (in_array("trapgo", $funciones)) ? '' : 'display:none';
	$ctas = $pais == 'Ec-bp' ? $dataCtas['data'] : $dataCtas;
?>

<div id="content-products">
	<h1><?= lang('TITULO_TRANSMAESTRA'); ?></h1>
	<h2 class="title-marca">
		<?= ucwords(mb_strtolower($programa));?>
	</h2>
	<ol class="breadcrumb">
		<li>
			<a href="<?= $urlBase; ?>/dashboard" rel="start">
				<?= lang('BREADCRUMB_INICIO'); ?>
			</a>
		</li>
		/
		<li>
			<a href="<?= $urlBase; ?>/dashboard" rel="section">
				<?= lang('BREADCRUMB_EMPRESAS'); ?>
			</a>
		</li>
		/
		<li>
			<a href="<?= $urlBase; ?>/dashboard/productos" rel="section">
				<?= lang('BREADCRUMB_PRODUCTOS'); ?>
			</a>
		</li>
		/
		<li>
			<a rel="section">
				<?= lang('BREADCRUMB_SERVICIOS'); ?>
			</a>
		</li>
		/
		<li class="breadcrumb-item-current">
			<a href="<?= $urlBase; ?>/servicios/transferencia-maestra" rel="section">
				<?= lang('BREADCRUMB_TRANSMAESTRA'); ?>
			</a>
		</li>
	</ol>
	<div id="lotes-general">
		<div id="recarga_concetradora" style="<?= $show_cl ?>">
			<div id="top-batchs">
				<span aria-hidden="true" class="icon" data-icon="&#xe07a;"></span> <?= lang('REG_CTA_CONCEN'); ?>
			</div>
			<div id="lotes-contenedor">
				<form id="form-recarga-cuenta" onsubmit="return false">
					<div id="search-1">
						<h5><span id="saldoEmpresa"></span></h5>
							<br>
						<h5 style="float:left;"><?= "Monto" ?></h5>
						<span>
							<input id="amount" name="amount" placeholder="Ingrese monto" disabled/>
						</span>
					</div>
					<div id="search-3" style="padding-top: 18px">
						<h5><?= "Descripción" ?></h5>
						<span>
							<input id="description" name="text" placeholder="Ingrese descripción" maxlength=16 disabled/>
						</span>
					</div>
					<?php if($pais == 'Ec-bp'): ?>
					<div id="search-1">
						<br>
						<h5 style="float:left;"><?= "Cuenta" ?></h5>
						<span>
							<select id="account" name="account" code="<?= $dataCtas['code'] ?>" title="<?= $dataCtas['title'] ?>" msg="<?= $dataCtas['msg'] ?>"
								disabled>
								<option value="0" selected>
									<?= $dataCtas['code'] == 0 ? 'Seleccione una Cuenta' : $dataCtas['data'] ?>
								</option>
								<?php if($dataCtas['code'] == 0): foreach($ctas as $pos => $cta): ?>
								<option value="<?= $cta['value'] ?>"><?= $cta['descrip'].'   --Saldo: '.$cta['saldo'] ?></option>
								<?php endforeach; endif; ?>
							</select>
						</span>
					</div>
					<div id="charge-or-credit" class="panel-right">
						<span class="selected-option">
							<input type="radio" id="charge" class="control-radio" class="radio" name="type" value="cargo" disabled>
							<label for="charge">cargo</label>
						</span>
						<span class="selected-option">
							<input type="radio" id="credit" class="control-radio" class="radio" name="type" value="abono"  disabled>
							<label for="credit">abono</label>
						</span>
					</div>
					<?php endif; ?>
				</form>
			</div>
			<div id="batchs-last">
				<span id="mensajeError" style="float:left; display:none; color:red;"></span>
				<button id='recargar' disabled><?= "Recargar" ?></button>
			</div>
		</div>
		<div id="top-batchs">
			<span aria-hidden="true" class="icon" data-icon="&#xe07a;"></span> <?= lang('CRITERIOS_BUSQUEDA') ?>
		</div>
		<div id="lotes-contenedor">
			<form name="trasn-master">
				<div id="search-1">
					<h5><?= lang('ID_PERSONA'); ?></h5>
						<span>
							<input type="text" id="dni" placeholder="<?= lang('ID_PERSONA'); ?>"/>
					</span>
				</div>
				<div id="search-3">
					<h5><?= lang('NRO_TARJETA'); ?></h5>
					<span>
						<input type="text" id="nroTjta" placeholder="<?= lang('INGRESE_NOTARJETA') ?>" maxlength="16"/>
					</span>
				</div>
			</form>
		</div>
		<div id="batchs-last">
			<span id="mensajeError" style="float:left; display:none; color:red;"></span>
			<button id='buscar'><?= lang('BUSCAR'); ?></button>
		</div>
		<div id='resultado-tarjetas' style='display:none'>
			<div id="top-batchs">
				<span aria-hidden="true" class="icon" data-icon="&#xe008;"></span> <?= lang('RESULTADOS') ?>
			</div>
			<div id='lotes-contenedor'>
				<div id="check-all">
					<input id="select-allR" type='checkbox' /><em id='textS'> <?= lang("SEL_ALL"); ?></em>
				</div>
				<div class='montos-TM'>
					<p id='saldoDisponible'></p>
				</div>
				<table class="table-text-aut">
					<thead>
						<th class="checkbox-select"><span aria-hidden="true" class="icon" data-icon="&#xe083;"></span></th>
						<th id="td-nombre-2"><?= lang('NRO_TARJETA'); ?></th>
						<th ><?= lang('ESTATUS'); ?></th>
						<th id='td-nombre-2'><?= lang('NOMBRE') ?></th>
						<th><?= lang('ID_PERSONA'); ?></th>
						<th><?= lang('SALDO'); ?></th>
						<?php if($pais != 'Ec-bp'): ?>
						<th><?= lang('MONTO'); ?></th>
						<?php endif; ?>
						<th><?= lang('OPCIONES'); ?></th>
					</thead>
					<tbody></tbody>
				</table>
				<div id='paginado-TM'></div>
				<div class='montos-TM' >
					<p id='comisionTrans'></p>
					<p id='comisionCons'></p>
				</div>
			</div>
			<div id="batchs-last">
				<form name="no-form" onsubmit="return false">
					<input id='clave' class='input-TM' type='password' name='user_pass' placeholder="<?= lang('PLACEHOLDER_PASS'); ?>"/>
					<button id='cargo-tjta' class='elem-hidden'><?= lang('CARGO'); ?></button>
					<button id='abonar-tjta' class='elem-hidden'><?= lang('ABONO'); ?></button>
					<button id='consultar-tjta' class='elem-hidden'><?= lang('CONSULTA'); ?></button>
				</form>
			</div>
		</div>
	</div>
</div>
<div id='loading' style='text-align:center' class='elem-hidden'>
	<?= insert_image_cdn("loading.gif"); ?>
</div>
