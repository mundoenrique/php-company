<?php
$ceo_name = $this->security->get_csrf_token_name();
$ceo_cook = $this->security->get_csrf_hash();
?>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="response-serv" code="<?= $pageInfo['code']; ?>" title="<?= $pageInfo['title-modal'] ?>" msg="<?= $pageInfo['msg']; ?>"></div>
<div id="content-products">

	<h1><?php echo $action; ?></h1>
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
				<?php echo lang('BREADCRUMB_EMPRESAS'); ?>
			</a>
		</li>
		/
		<li>
			<a href="<?php echo base_url($pais . '/dashboard/productos'); ?>" rel="section">
				<?php echo lang('BREADCRUMB_PRODUCTOS'); ?>
			</a>
		</li>
		/
		<li>
			<a class="cursor-default">
				<?php echo lang('BREADCRUMB_CONSULTAS'); ?>
			</a>
		</li>
		/
		<li class="breadcrumb-item-current">
			<a class="cursor-default">
				<?php echo lang('BREADCRUMB_LOTES_POR_FACTURAR'); ?>
			</a>
		</li>

	</ol>

</div>

<div class="container">
	<div class="container-header">
		<span aria-hidden="true" class="icon icon-list" data-icon="&#xe046;"></span>
		<?= lang('TITULO_LOTES_POR_FACTURAR'); ?>
	</div>

	<div class="container-body">
		<div id="loading" style="text-align:center; margin-top: 90px">
			<?= insert_image_cdn("loading.gif"); ?>
		</div>
		<div id="display-table"style="display:none">
			<table id="novo-table" class="hover cell-border" style="width: 635px;">
				<thead>
					<tr>
						<th class="thead" style="width: 86px;">Tipo de lote</th>
						<th class="thead" style="width: 60px;">Cantidad de Lotes</th>
						<th class="thead" style="width: 60px;">Total de registros</th>
						<th class="thead" style="width: 84px;">Precio unitario Bs.</th>
						<th class="thead" style="width: 89px;">Total Bs.</th>
						<th class="thead" style="width: 50px;">Ver listado</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($dataResponse AS $key => $lotes): ?>
						<tr>
							<td><?= $lotes->descripcionpro; ?></td>
							<td><?= count($lotes->lista); ?></td>
							<td><?= $lotes->ncantregs; ?></td>
							<td><?= number_format($lotes->montoComision, 2, ',', '.'); ?></td>
							<td><?= number_format($lotes->montoNeto, 2, ',', '.'); ?></td>
							<td class="os-info-show">
								<a>
									<span aria-hidden="true" class="icon icon-list"></span>
								</a>
								<span class="show-table" style="display: none">
									<div class="table">
										<div class="heading">
											<div class="cell"><span>Lote</span></div>
											<div class="cell"><span>Fecha de autorización</span></div>
											<div class="cell"><span>Cant.</span></div>
											<div class="cell"><span>Estatus</span></div>
											<div class="cell"><span>Comisión Bs.</span></div>
											<div class="cell"><span>Total Bs.</span></div>
										</div>
										<?php foreach($lotes->lista AS $pos => $detail): ?>
											<?php $date = new DateTime($detail->dtfechorcarga); ?>
											<div class="row">
												<div class="cell">
													<span>
														<a id="<?= $detail->acidlote; ?>" class="batch-detail">
															<?= $detail->acnumlote; ?>
														</a>
													</span>
												</div>
												<div class="cell"><span><?= $date->format('d-m-Y'); ?></span></div>
												<div class="cell"><span><?= $detail->ncantregs; ?></span></div>
												<div class="cell">
													<span><?= ucfirst(strtolower($detail->status)); ?></span>
												</div>
												<div class="cell">
													<span><?= number_format($detail->montoComision, 2, ',', '.'); ?></span>
												</div>
												<div class="cell">
													<span><?= number_format($detail->montoNeto, 2, ',', '.'); ?></span>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								</span>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>

	<div class="contanier-footer">
	</div>
</div>

<form id='detalle_lote' method='post' action="<?= base_url($pais.'/lotes/autorizacion/detalle'); ?>/">
	<input type='hidden' name='<?php echo $ceo_name ?>' value='<?php echo $ceo_cook ?>'>
	<input type='hidden' id='data-LF' name='data-LF' value='LF' />
</form>
<form id="down-report" action="<?= base_url($pais . '/reportes/comisiones-recarga') ?>" method="post">
</form>

<div id="msg-system-report" style="display:none">
	<div id="msg-info" class="comb-content"></div>
	<div id="actions" class="comb-content actions-buttons">
		<button id="close-info" class="buttons-action"><?= lang('TAG_ACCEPT') ?></button>
	</div>
</div>
