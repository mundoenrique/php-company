<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_BULK_AUTH') ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>"><?= lang('PRODUCTS_DETAIL_TITLE') ?></a></li> /
				<li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_MENU_LOTS') ?></a></li>
			</ul>
		</nav>
	</div>
</div>
<div class="flex mt-1 bg-color flex-nowrap justify-between">
	<div id="pre-loader" class="mt-2 mx-auto">
		<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
	</div>
	<div class="w-100 hide-out hide">
		<div class="flex flex-auto flex-column <?= $widget ? '' : 'max-width-6'; ?>">
			<?php if($signBulk != new stdClass()): ?>
			<div class="flex pb-5 flex-column">
				<span class="line-text mb-2 h4 semibold primary"><?= lang('BULK_PENDING_SIGN') ?></span>
				<div class="center mx-1">
					<table id="sign-bulk" class="cell-border h6 display" sign="<?= $authorizeAttr->sign; ?>">
						<thead class="regular secondary bg-primary">
							<tr id="headerRow">
								<th class="toggle-all"></th>
								<th><?= lang('GEN_TABLE_BULK_NUMBER'); ?></th>
								<th><?= lang('GEN_TABLE_BULK_ID'); ?></th>
								<th><?= lang('GEN_TABLE_BULK_DATE'); ?></th>
								<th><?= lang('GEN_TABLE_TYPE'); ?></th>
								<th><?= lang('GEN_TABLE_TYPE_ID'); ?></th>
								<th><?= lang('GEN_TABLE_RECORDS'); ?></th>
								<th><?= lang('GEN_TABLE_AMOUNT'); ?></th>
								<th><?= lang('GEN_TABLE_OPTIONS'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($signBulk AS $bulk): ?>
							<tr>
								<td></td>
								<td><?= $bulk->bulkNumber; ?></td>
								<td><?= $bulk->idBulk; ?></td>
								<td><?= $bulk->loadDate; ?></td>
								<td class="tool-ellipsis"><?= $bulk->type; ?></td>
								<td><?= $bulk->idType; ?></td>
								<td><?= $bulk->records; ?></td>
								<td><?= $bulk->amount; ?></td>
								<td class="p-0 flex justify-center items-center">
									<form id="id-<?= $bulk->idBulk; ?>" action="<?= base_url('consulta-lote') ?>" method="post">
										<input type="hidden" name="bulkId" value="<?= $bulk->idBulk; ?>">
										<input type="hidden" name="bulkfunction" value="Autorización de lotes">
									</form>
									<button class="btn mx-1 px-0 big-modal" title="<?= lang('GEN_BTN_SEE'); ?>" data-toggle="tooltip">
										<i class="icon icon-find" aria-hidden="true"></i>
									</button>
									<?php if($this->verify_access->verifyAuthorization('TEBAUT', 'TEBELI')): ?>
									<button class="btn mx-1 px-0" title="<?= lang('GEN_BTN_DELETE'); ?>" data-toggle="tooltip">
										<i class="icon icon-remove mr-1" aria-hidden="true"></i>
									</button>
									<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<form id="sign-bulk-form" method="post">
						<div class="flex row mt-3 mb-2 mx-2 justify-end">
							<div class="col-5 col-lg-3 col-xl-3 form-group">
								<div class="input-group">
									<input id="password-sign" name="password" class="form-control pwd-input pr-0" type="password"
										placeholder="<?= lang('GEN_PLACE_PASSWORD'); ?>">
									<div class="input-group-append">
										<span id="pwd_action" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i
												class="icon-view mr-0"></i></span>
									</div>
								</div>
								<div class="help-block bulk-select text-left"></div>
							</div>
							<div class="col-auto">
								<button id="sign-bulk-btn" class="btn btn-primary btn-small btn-loading flex mx-auto">
									<?= lang('GEN_BTN_SIGN'); ?>
								</button>
							</div>
							<?php if($this->verify_access->verifyAuthorization('TEBAUT', 'TEBELI')): ?>
							<div class="col-auto">
								<button id="del-sign-bulk-btn" class="btn btn-primary btn-small btn-loading flex mx-auto">
									<?= lang('GEN_BTN_DELETE'); ?>
								</button>
							</div>
							<?php endif; ?>
						</div>
					</form>
					<div class="line mb-2"></div>
				</div>
			</div>
			<?php endif; ?>
			<div class="flex pb-5 flex-column">
				<span class="line-text mb-2 h4 semibold primary"><?= lang('BULK_PENDING_AUTH'); ?></span>
				<div class="center mx-1">
					<table id="authorize-bulk" class="cell-border h6 display" auth="<?= $authorizeAttr->auth; ?>" order-to-pay="<?= $authorizeAttr->toPAy; ?>">
						<thead class="bg-primary secondary regular">
							<tr>
								<th class="<?= $authorizeAttr->allBulk; ?>"></th>
								<th><?= lang('GEN_TABLE_BULK_NUMBER'); ?></th>
								<th><?= lang('GEN_TABLE_BULK_ID'); ?></th>
								<th><?= lang('GEN_TABLE_BULK_DATE'); ?></th>
								<th><?= lang('GEN_TABLE_TYPE'); ?></th>
								<th><?= lang('GEN_TABLE_TYPE_ID'); ?></th>
								<th><?= lang('GEN_TABLE_RECORDS'); ?></th>
								<th><?= lang('GEN_TABLE_AMOUNT'); ?></th>
								<th><?= lang('GEN_TABLE_OPTIONS'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($authorizeBulk AS $bulk): ?>
							<tr class="<?= $bulk->selectRow; ?>">
								<td class="p-0 <?= $bulk->selectRow; ?>"><?= $bulk->selectRowContent; ?></td>
								<td><?= $bulk->bulkNumber; ?></td>
								<td><?= $bulk->idBulk; ?></td>
								<td><?= $bulk->loadDate; ?></td>
								<td><?= $bulk->type; ?></td>
								<td><?= $bulk->idType; ?></td>
								<td><?= $bulk->records; ?></td>
								<td><?= $bulk->amount; ?></td>
								<td class="p-0 flex justify-center items-center">
									<form id="id-<?= $bulk->idBulk; ?>" action="<?= base_url('consulta-lote') ?>" method="post">
										<input type="hidden" name="bulkId" value="<?= $bulk->idBulk; ?>">
										<input type="hidden" name="bulkfunction" value="Autorización de lotes">
									</form>
									<?php if($bulk->seeDetail): ?>
									<button class="btn mx-1 px-0 big-modal" title="<?= lang('GEN_BTN_SEE') ?>" data-toggle="tooltip">
										<i class="icon icon-find" aria-hidden="true"></i>
									</button>
									<?php endif; ?>
									<?php if($this->verify_access->verifyAuthorization('TEBAUT', 'TEBELI')): ?>
									<button class="btn mx-1 px-0" title="<?= lang('GEN_BTN_DELETE') ?>" data-toggle="tooltip">
										<i class="icon icon-remove mr-1" aria-hidden="true"></i>
									</button>
									<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<?php if($authorizeBulk != new stdClass()): ?>
					<form id="auth-bulk-form" method="post">
						<div class="flex row mt-3 mb-2 mx-2 justify-end">
							<div class="col-4 col-lg-3 h6 regular form-group">
								<?php if(verifyDisplay('body', $module,  lang('GEN_TAG_ORDER_TYPE'))): ?>
								<select id="type-order" name="type-order" class="select-box custom-select h6">
									<option value="0"><?= lang('BULK_PROCESS_BY_BULK'); ?></option>
									<option value="1" ><?= lang('BULK_PROCESS_TYPE_BULK') ?></option>
								</select>
								<?php else: ?>
								<input type="hidden" id="type-order" name="type-order" value="0">
								<?php endif; ?>
								<div class="help-block"></div>
							</div>
							<div class="col-5 col-lg-3 col-xl-3 form-group">
								<div class="input-group">
									<input id="password-auth" name="password" class="form-control pwd-input pr-0" type="password"
										placeholder="<?= lang('GEN_PLACE_PASSWORD'); ?>">
									<div class="input-group-append">
										<span id="pwd_action" class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i
												class="icon-view mr-0"></i></span>
									</div>
								</div>
								<div class="help-block bulk-select text-left"></div>
							</div>
							<div class="col-3 col-lg-auto">
								<button id="auth-bulk-btn" class="btn btn-primary btn-small btn-loading flex mx-auto">
									<?= lang('GEN_BTN_AUTHORIZE'); ?>
								</button>
							</div>
							<?php if($this->verify_access->verifyAuthorization('TEBAUT', 'TEBELI') && $authorizeAttr->allBulk == 'toggle-all'): ?>
							<div class="col-3 col-lg-auto">
								<button id="del-auth-bulk-btn" class="btn btn-primary btn-small btn-loading flex mx-auto">
									<?= lang('GEN_BTN_DELETE'); ?>
								</button>
							</div>
							<?php endif; ?>
						</div>
					</form>
					<?php endif; ?>
					<div class="line mb-2"></div>
				</div>

				<div class="my-5 py-4 center none">
					<span class="h4"><?= lang('RESP_NO_LIST'); ?></span>
				</div>

			</div>
		</div>
	</div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
	<?php endif; ?>
</div>
