<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pt-3 pb-5">
	<div class="logout-content max-width-4 mx-auto p-responsive py-4">
		<h1 class="primary h0"><?= lang('GEN_RECOVER_PASS_TITLE'); ?></h1>
		<section>
			<hr class="separador-one">
			<div id="pre-loader" class="mx-auto flex justify-center">
				<span class="spinner-border spinner-border-lg my-2" role="status" aria-hidden="true"></span>
			</div>
			<div class="pt-3 hide-out hide">
				<p><?= novoLang(lang('RECOVER_PASS_FORGOTTEN'), lang('GEN_SYSTEM_NAME')); ?></p>
				<div class="max-width-1 fit-lg mx-auto pt-3">
					<form id="form-access-recovery">
						<div class="row mb-2">
							<div class="form-group col-lg-4">
								<label for="email"><?= lang('GEN_EMAIL'); ?></label>
								<input id="email" name="email" class="form-control" type="text" maxlength="64" placeholder="<?= lang('GEN_PLACE_HOLDER_EMAIL') ?>"
									disabled autocomplete="off">
								<div class="help-block"></div>
							</div>
							<div class="form-group col-lg-3">
								<label for="documentType"><?= lang('GEN_DOCUMENT_TYPE'); ?></label>
								<select id="documentType" name="documentType" class="custom-select form-control" disabled autocomplete="off">
									<?php foreach (lang('GEN_RECOVER_DOC_TYPE') AS $key => $value): ?>
									<option value="<?= $key ?>" <?= $key == '' ? 'selected disabled' : '' ?>><?= $value ?></option>
									<?php endforeach; ?>
								</select>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-lg-4">
								<label for="documentId"><?= lang('GEN_DOCUMENT_ID'); ?></label>
								<input id="documentId" name="documentId" class="form-control" type="text" maxlength="15" disabled autocomplete="off">
								<div class="help-block"></div>
							</div>
						</div>
						<hr class="separador-one">
						<div class="flex items-center justify-end pt-3">
							<a class="btn btn-link btn-small big-modal" href="<?= base_url(lang('SETT_LINK_SIGNIN')) ?>"><?= lang('GEN_BTN_CANCEL'); ?></a>
							<button id="btn-pass-recover" class="btn btn-small btn-primary btn-loading" type="submit"
								disabled><?= lang('GEN_BTN_CONTINUE'); ?></button>
						</div>
					</form>
				</div>
			</div>
		</section>
	</div>
</div>
