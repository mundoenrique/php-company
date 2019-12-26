<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pt-3 px-5 pb-5">
	<h1 class="primary h3 regular inline"><?= lang('BULK_TITLE_PAGE'); ?></h1>
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
					<li class="inline"><a class="tertiary" href="#"><?= lang('BULK_TITLE'); ?></a></li>
				</ul>
			</nav>
		</div>
	</div>
	<div class="flex mt-1 mb-5 bg-color flex-nowrap justify-between">
		<div class="flex flex-auto flex-column <?= $widget ? '' : 'max-width-6';  ?>">
			<div class="flex flex-column">
				<span class="line-text mb-2 h4 semibold primary"><?= lang('BULK_NEW'); ?></span>
				<form id="upload-file-form">
					<div class="flex px-5 pb-4 items-center row">
						<div class="form-group col-3">
							<label class="mt-1 h6" for="type-bulk"><?= lang('BULK_TYPE'); ?></label>
							<select id="type-bulk" name="type-bulk" class="select-box custom-select h6 w-100">
								<?php foreach($typesLot AS $pos => $type): ?>
								<option value="<?= $type->key; ?>" format="<?= $type->format; ?>"
									<?= $pos != 0 ? '' : 'selected disabled' ?>><?= $type->text; ?></option>
								<?php endforeach; ?>
							</select>
							<div class="help-block"></div>
						</div>
						<?php if(verifyDisplay('body', $module,  lang('GEN_TAG_BRANCHOFFICE'))): ?>
						<div class="form-group col-3 hide">
							<label class="mt-1 h6" for="branch-office"><?= lang('BULK_BRANCH_OFFICE'); ?></label>
							<select id="branch-office" name="branch-office" class="select-box custom-select h6 w-100">
								<?php foreach($branchOffices AS $pos => $branchOffice): ?>
								<option value="<?= $branchOffice->key; ?>" <?= $pos != 0 ? '' : 'selected disabled' ?>>
									<?= $branchOffice->text; ?></option>
								<?php endforeach; ?>
							</select>
							<div class="help-block"></div>
						</div>
						<?php endif; ?>
						<div class="form-group col-6 bg-color">
							<input type="file" name="file-bulk" id="file-bulk" class="input-file">
							<label for="file-bulk" class="label-file js-label-file mb-0">
								<i class="icon icon-upload mr-1 pr-3 right"></i>
								<span class="js-file-name h6 regular"><?= lang('BULK_SELECT_BULK_FILE'); ?></span>
							</label>
							<div class="help-block"></div>
						</div>
						<div class="col mt-1">
							<button id="upload-file-btn" class="btn btn-primary btn-small btn-loading flex ml-auto">
								<?= lang('GEN_BTN_SEND'); ?>
							</button>
						</div>
					</div>
				</form>
			</div>
			<div class="flex flex-column">
				<span class="line-text mb-2 h4 semibold primary"><?= lang('BULK_PENDING'); ?></span>
				<div id="pre-loader" class="mt-2 mx-auto">
					<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
				</div>
				<div id="content-datatable" class="center mx-1 hide">
					<table id="pending-bulk" class="cell-border h6 display">
						<thead class="regular secondary bg-primary">
							<tr>
								<th><?= lang('BULK_NUMBER'); ?></th>
								<th><?= lang('BULK_FILE_NAME'); ?></th>
								<th><?= lang('BULK_LOAD_DATE'); ?></th>
								<th><?= lang('BULK_STATUS'); ?></th>
								<th><?= lang('BULK_OPTIONS'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($pendingBulk AS $bulk): ?>
							<tr ticket-id="<?= $bulk->ticketId ?>" bulk-id="<?= $bulk->bulkId ?>">
								<td><?= $bulk->lotNum ?></td>
								<td><?= $bulk->fileName ?></td>
								<td><?= $bulk->loadDate ?></td>
								<td>
									<div class="<?= $bulk->statusPr ?> flex items-center justify-center">
										<div class="icon-circle <?= $bulk->statusColor ?>" alt=""></div>
										<span class="pl-1 uppercase"><?= $bulk->statusText ?></span>
									</div>
								</td>
								<td>
									<?php if($bulk->status == 1 || $bulk->status == 6): ?>
									<button class="btn mx-1 px-0 big-modal" title="Confirmar" data-toggle="tooltip">
										<i class="icon icon-ok" aria-hidden="true"></i>
									</button>
									<?php endif; ?>
									<?php if($bulk->status == 5 || $bulk->status == 6): ?>
									<button class="btn mx-1 px-0 big-modal" title="Ver" data-toggle="tooltip">
										<i class="icon icon-find" aria-hidden="true"></i>
									</button>
									<?php endif; ?>
									<button class="btn mx-1 px-0" title="Eliminar" data-toggle="tooltip">
										<i class="icon icon-remove" aria-hidden="true"></i>
									</button>
									<form id="bulk-<?= $bulk->ticketId; ?>" method="POST">
										<input type="hidden" name="bulkStatus" value="<?= $bulk->status; ?>">
										<input type="hidden" name="bulkId" value="<?= $bulk->bulkId; ?>">
										<input type="hidden" name="bulkTicked" value="<?= $bulk->ticketId; ?>">
										<input type="hidden" name="bulkFile" value="<?= $bulk->fileName; ?>">
									</form>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<div class="mx-3 h3">
						<div class="flex mt-4 items-center">
							<div class="icon-square bg-vista-blue" alt=""></div>
							<span class="pl-1 h6"><?= lang('BULK_ALL_RECORDS'); ?></span>
						</div>
						<div class="flex mt-2 items-center">
							<div class="icon-square bg-trikemaster" alt=""></div>
							<span class="pl-1 h6"><?= lang('BULK_SOME_RECORDS'); ?></span>
						</div>
						<div class="flex mt-2 items-center">
							<div class="icon-square bg-pink-salmon" alt=""></div>
							<span class="pl-1 h6"><?= lang('BULK_SOME_RECORDS'); ?></span>
						</div>
					</div>
				</div>
				<div id="no-bulk" class="my-5 py-4 center none">
					<span class="h4"><?= lang('BULK_NO_RENDER'); ?></span>
				</div>
			</div>
		</div>
		<?php if($widget): ?>
		<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
		<?php endif; ?>
	</div>
</div>
