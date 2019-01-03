<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

$data = json_decode($dataResponse);
$dni = '';
$card = '';
$startDate = '';
$endDate = '';
$controllersList = [];
if($data->code === 0) {
	$controllersList = $data->data->listaControles;
	$dni = $data->data->dni;
	$card = $data->data->card;
	$date = isset($data->data->startDate) ? new DateTime($data->data->startDate) : '';
	$startDate = $date !== '' ? $date->format('d/m/Y') : '';
	$date = isset($data->data->startDate) ? new DateTime($data->data->endDate) : '';
	$endDate = $date !== '' ? $date->format('d/m/Y') : '';
}
?>
<div id="content-products" code="<?php echo $data->code; ?>" <?php echo $data->code !== 0 ?
	'title="' . $data->title . '" msg="' . $data->msg . '"' : ""; ?>>

	<h1><?php echo lang('TITLE_VISA_DETALLE'); ?></h1>
	<h2 class="title-marca">
		<?php echo ucwords(mb_strtolower($programa)); ?>
	</h2>
	<ol class="breadcrumb">
		<li>
			<a href="<?php echo base_url($pais . '/dashboard'); ?>" rel="start">
				<?php echo lang('BREADCRUMB_INICIO'); ?>
			</a>
		</li>
		/
		<li>
			<a href="<?php echo base_url($pais . '/dashboard'); ?>" rel="section">
				<?php echo lang('BREADCRUMB_EMPRESAS'); ?></a>
		</li>
		/
		<li>
			<a href="<?php echo base_url($pais . '/dashboard/productos'); ?>" rel="section">
				<?php echo lang('BREADCRUMB_PRODUCTOS'); ?></a>
		</li>
		/
		<li>
			<a rel="section">
				<?php echo lang('BREADCRUMB_SERVICIOS'); ?>
			</a>
		</li>
		/
		<li>
			<a href="<?php echo base_url($pais . '/controles/visa'); ?>" rel="section">
				<?php echo lang('BREADCRUMB_CONTROLES'); ?>
			</a>
		</li>
		/
		<li class="breadcrumb-item-current">
			<a rel="section">
				<?php echo lang('BREADCRUMB_DETALLE'); ?>
			</a>
		</li>
	</ol>
</div>
<div class="container">
	<div class="container-header">
		<span aria-hidden="true" class="icon icon-list" data-icon="&#xe02c;"></span>
		<?php echo lang('SECTION_TITLE_CONTROLES'); ?>
	</div>
	<div class="container-body">
		<div class="body-center">
			<div class="filters">
				<label class="label-input" for="dni">DNI</label>
				<input id="dni" name="dni" value="<?php echo $dni; ?>" readonly>
			</div>
			<div class="filters">
				<label class="label-input" for="card">Número de Tarjeta</label>
				<input id="card" name="card" value="<?php echo $card; ?>" readonly>
			</div>
		</div>
	</div>
	<div class="container-body">
		<div class="body-center">
			<form id="contols-dates">
				<div class="filters">
					<label class="label-input" for="first-date">Fecha Inicio</label>
					<input id="first-date" name="first-date" placeholder="DD/MM/AA" value="<?php echo $startDate; ?>" readonly disabled>
				</div>
				<div class="filters">
					<label class="label-input" for="last-date">Fecha Fin</label>
					<input id="last-date" name="last-date" placeholder="DD/MM/AA" value="<?php echo $endDate; ?>" readonly disabled>
				</div>
			</form>
			<div id="validate-list"></div>
		</div>
	</div>
	<div class="container-body">
		<div id='loading' style='text-align:center'><?php echo insert_image_cdn("loading.gif"); ?></div>
		<div id="controls-list" style="display: none">
			<?php foreach($controllersList AS $pos => $controllers): ?>
				<?php $idControl = $controllers->rule_code; ?>
				<div class="visa-controls">
					<div class="control-check">
						<input type="checkbox" id="<?php echo $idControl; ?>" <?php echo $controllers->status_descripcion === 'active' ? 'checked' : ''; ?> plus="<?php echo $controllers->override === 'y' ? 'y' : 'n'; ?>" status="<?php echo $controllers->status_descripcion === 'active' ? 'a' : 'i'; ?>">
						<label for="<?php echo $idControl; ?>">
							<span><?php echo $controllers->status_descripcion === 'active'? '&#x2714;' : '&#x2716;'; ?></span>
						</label>
					</div>
					<div class="control-label">
						<label for="<?php echo $idControl; ?>"><?php echo strtoupper($controllers->rule_code) ?></label><br>
						<label for="<?php echo $idControl; ?>"><?php echo lang('VISA_CTRL_' . strtoupper($idControl)); ?></label>
					</div>
					<?php if($controllers->override === 'y'): ?>
						<div class="control-override" show="<?php echo $controllers->status_descripcion === 'active' ? 'y' : 'n'; ?>">
							<span> <?php echo $controllers->status_descripcion === 'active' ? '-' : '+'; ?> </span>
						</div>
						<div class="control-override-value" style="display: <?php echo $controllers->status_descripcion === 'active' ? 'block;' : 'none;'; ?>">
							<?php foreach($controllers->ListaOverrides AS $item => $overrides): ?>
								<?php if($item === 0 && $overrides->overrideCode === 'amount'): ?>
									<input type="text" name="<?php echo $controllers->rule_code; ?>" overrride="<?php echo $overrides->overrideCode; ?>" value="<?php echo $overrides->overrideValue !== 'amount' ? $overrides->overrideValue : ''; ?>" placeholder="Indique el monto">
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="contanier-footer">
		<button id="update">Actualizar</button>
	</div>
</div>


<form id='formulario' method='post'></form>

<div id="msg-system" style="display:none">
	<div id="msg-info" class="comb-content">
		<p></p>
	</div>
	<div id="actions" class="comb-content actions-buttons">
		<button id="close-info" class="buttons-action">Aceptar</button>
	</div>
</div>
