<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="response-serv" class="content-products" code="<?= $pageInfo['code']; ?>" title="<?= $pageInfo['title-modal'] ?>" msg="<?= $pageInfo['msg']; ?>">
	<h1><?= $pageInfo['title']; ?></h1>

	<ol class="breadcrumb">
		<li>
			<a rel="start" href="<?= base_url().$pais; ?>/dashboard">
				<?= lang('BREADCRUMB_INICIO'); ?>/
			</a>
		</li>

		<li>
			<a rel="section" href="<?= base_url().$pais; ?>/dashboard">
				<?= lang('BREADCRUMB_EMPRESAS'); ?>/
			</a>
		</li>

		<li>
			<a rel="section" href="<?= base_url().$pais; ?>/dashboard/productos">
				<?= lang('BREADCRUMB_PRODUCTOS'); ?>/
			</a>
		</li>

		<li>
			<a rel="section">
				<?php echo lang('BREADCRUMB_REPORTES'); ?>
			</a>
		</li>

		<li class="breadcrumb-item-current">
			<a rel="section">
				<?= lang('BREADCRUMB_REPORTES_COMISION'); ?>
			</a>
		</li>
	</ol>
</div>

<div class="container">
	<div class="container-header">
		<span aria-hidden="true" class="icon" data-icon="&#xe07a;"></span>
			<?= lang("CRITERIOS_BUSQUEDA"); ?>
	</div>

	<div class="container-body">
		<div class="campo">
			<label for="company" class="item"><?= lang('REPORTES_SELECCION_EMPRESA'); ?></label>
			<select id="company" name="company" <?= $pageInfo['select-companies']; ?> required>
				<option value=""><?= $pageInfo['option-companies']; ?></option>
				<?php foreach($pageInfo['companies'] as $pos => $value): ?>
				<option value="<?= $pos; ?>" <?= $pageInfo['current-company'] == $pos ? 'selected' : '' ?>><?= $value; ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="campo">
			<label for="date" class="item"><?= lang('TITULO_REPORTES_RANGO'); ?></label>
			<span class="range">
				<label class="item date-range">Fecha Inicial</label>
				<input type="text" id="first-date" class="date-rep" placeholder="DD/MM/AA" disabled required>
			</span>
			<span  class="range">
				<label class="item date-range">Fecha Final</label>
				<input type="text" id="last-date" class="date-rep" placeholder="DD/MM/AA" disabled required>
			</span>
		</div>
	</div>

	<div class="contanier-footer">
		<span id="mensajeError" class="mensajeError"></span>
		<button id="search" disabled><?= lang("REPORTE_BOTON_BUSCAR"); ?></button>
	</div>

	<div class="container-header">
		<span aria-hidden="true" class="icon icon-list" data-icon="&#xe046;"></span>
			<?= lang("BREADCRUMB_REPORTES_COMISION"); ?>
	</div>

	<div class="container-body border-radius">
		<div id="loading" style="text-align:center">
			<?php echo insert_image_cdn("loading.gif"); ?>
		</div>

		<div id="detail-report" style="display: none;">
			<span id="info-date" class="info"><?= $pageInfo['date']; ?></span>
			<div id="downloads" class="download" first-date last-date company>
				<div id="loading-report" class="loader-report" style="display:none">
					<?php echo insert_image_cdn("loading.gif"); ?>
				</div>
				<a id="comisiones-xls" class="<?= $pageInfo['css']; ?>">
					<span title="Exportar Excel" aria-hidden="true" class="icon" data-icon="&#xe05a;"></span>
				</a>
				<a id="comisiones-pdf" class="<?= $pageInfo['css']; ?>">
					<span title="Exportar PDF" aria-hidden="true" class="icon" data-icon="&#xe02e;"></span>
				</a>
			</div>

			<table id="novo-table" class="hover cell-border" width="630px">
				<thead>
					<th>Fecha</th>
					<th>Cédula</th>
					<th>Tarjeta</th>
					<th>Recarga</th>
					<th>Comisión TH</th>
					<th>Comisión Empresa</th>
					<th>Monto</th>
				</thead>

				<tbody>
					<?php foreach ($recharges as $key => $value): ?>
					<tr>
						<td><?= $value->fecha; ?></td>
						<td><?= $value->idPersona; ?></td>
						<td><?= $value->tarjeta; ?></td>
						<td><?= $value->montoRecarga; ?></td>
						<td><?= $value->montoComisionTh; ?></td>
						<td><?= $value->montoComisionEmpresa; ?></td>
						<td><?= $value->montoTotalOS; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<form id="down-report" action="<?= base_url($pais . '/reportes/comisiones-recarga') ?>" method="post">
</form>

<div id="msg-system-report" style="display:none">
	<div id="msg-info" class="comb-content"></div>
	<div id="actions" class="comb-content actions-buttons">
		<button id="close-info" class="buttons-action"><?= lang('TAG_ACCEPT') ?></button>
	</div>
</div>
