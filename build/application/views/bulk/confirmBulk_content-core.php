<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pt-3 px-5 pb-5">
	<h1 class="primary h3 regular inline">Confirmación de Lotes</h1>
	<span class="ml-2 regular tertiary"><?= $productName ?></span>
	<div class="mb-2 flex items-center">
		<div class="flex tertiary">
			<nav class="main-nav nav-inferior">
				<ul class="mb-0 h6 light tertiary list-style-none list-inline">
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>">Empresas</a></li> /
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>">Productos</a></li> /
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>">Detalle del
							producto</a>
					</li> /
					<li class="inline"><a class="tertiary big-modal" href="<?= base_url('cargar-lotes') ?>">Cargar lotes</a></li>
					/
					<li class="inline"><a class="tertiary" href="javascript:">Confirmar lote</a></li>
				</ul>
			</nav>
		</div>
	</div>
	<div class="flex mt-1 mb-5 bg-color flex-nowrap justify-between">
		<div class="flex flex-auto flex-column">
			<div class="flex flex-column">
				<span class="line-text mb-2 h4 semibold primary">Confirmación</span>
				<div class="row px-5">
					<div class="form-group mb-3 col-4">
						<label for="confirmNIT" id="confirmNIT"><?= lang('GEN_FISCAL_REGISTRY') ?></label>
						<span id="confirmNIT" class="form-control px-1" readonly="readonly"><?= $detailBulk->idFiscal ?></span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="confirmName" id="confirmName">Nombre de la empresa</label>
						<span id="confirmName" class="form-control px-1"
							readonly="readonly"><?= $detailBulk->enterpriseName ?></span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="typeLot" id="typeLot">Tipo de lote</label>
						<span id="typeLotName" class="form-control px-1 bold pink-salmon"
							readonly="readonly"><?= $detailBulk->bulkType ?></span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="regNumber" id="regNumber">Cantidad de registros</label>
						<span id="amountNumber" class="form-control px-1 "
							readonly="readonly"><?= $detailBulk->totaRecords ?></span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="amount" id="amount">Monto total</label>
						<span id="totalAmount" class="form-control px-1" readonly="readonly"><?= $detailBulk->amount ?></span>
					</div>

					<div class="form-group mb-3 col-4">
						<label for="lot" id="lot">Lote nro.</label>
						<span id="numLot" class="form-control px-1" readonly="readonly"><?= $detailBulk->bulkNumber ?></span>
					</div>

					<div class="form-group mb-3 col-12">
						<label for="obsConfirm" id="obsConfirm">Observaciones</label>
						<?php if(!empty($detailBulk->errors)): ?>
						<?php foreach($detailBulk->errors AS $pos => $error): ?>
						<span id="comment" class="form-control px-1" readonly="readonly">
							<?= $error->line; ?>, <?= $error->msg; ?> <?= $error->detail; ?>
						</span>
						<?php endforeach; ?>
						<?php else: ?>
						<span id="comment" class="form-control px-1" readonly="readonly">
							<?= $detailBulk->success; ?>
						</span>
						<?php endif; ?>
					</div>
				</div>

				<div class="line mb-2"></div>

				<form id="confirm-bulk-btn" method="post">
					<div class="flex flex-column mb-4 mt-4 px-5 justify-center items-center">
						<div class="form-group mb-3 mb-3 col-4 col-lg-3">
							<input id="bulkTicked" name="bulkTicked"  type="hidden"  value="<?= $detailBulk->bulkTicked ?>">
							<input id="password" name="password" class="form-control" type="password" placeholder="Ingresa tu contraseña">
							<div class="help-block"></div>
						</div>
						<div class="flex flex-row">
							<div class="mb-3 mr-4">
								<a href="<?= base_url('cargar-lotes') ?>" class="btn btn-link btn-small big-modal">Cancelar</a>
							</div>
							<div class="mb-3 mr-1">
								<button id="confirm-bulk" class="btn btn-primary  btn-loading btn-small">Confirmar</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php if($widget): ?>
		<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
		<?php endif; ?>
	</div>
</div>
