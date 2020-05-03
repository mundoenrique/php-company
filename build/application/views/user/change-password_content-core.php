<?php defined('BASEPATH') or exit('No direct script access allowed '); ?>
<div class="logout-content max-width-4 mx-auto p-responsive py-4">
	<h1 class="primary h0"><?= $greeting.' '.$fullName ?></h1>
	<section>
		<hr class="separador-one">
		<div class="pt-3">
			<p><?= $message ?></p>
			<form id="change-pass-form" class="mt-4" method="post">
				<input type="hidden" id="userType" name="user-type" value="<?= $userType ?>">
				<div class="row">
					<div class="col-6 col-lg-8 col-xl-6">
						<div class="row">
							<div class="form-group col-12 col-lg-6">
								<label for="currentPass"><?= lang('PASSWORD_CURRENT');?></label>
								<div class="input-group">
									<input id="currentPass" class="form-control pwd-input" type="password" name="current-pass">
									<div class="input-group-append">
										<span class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i class="icon-view mr-0"></i></span>
									</div>
								</div>
								<div class="help-block"></div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-12 col-lg-6">
								<label for="newPass"><?= lang('PASSWORD_NEW'); ?></label>
								<div class="input-group">
									<input id="newPass" class="form-control pwd-input" type="password" name="new-pass">
									<div class="input-group-append">
										<span class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i class="icon-view mr-0"></i></span>
									</div>
								</div>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-12 col-lg-6">
								<label for="confirmPass"><?= lang('PASSWORD_CONFIRM'); ?></label>
								<div class="input-group">
									<input id="confirmPass" class="form-control pwd-input" type="password" name="confirm-pass">
									<div class="input-group-append">
										<span class="input-group-text pwd-action" title="<?= lang('GEN_SHOW_PASS') ?>"><i class="icon-view mr-0"></i></span>
									</div>
								</div>
								<div class="help-block"></div>
							</div>
						</div>
					</div>

					<div class="col-6 col-lg-4 col-xl-6">
						<div class="field-meter" id="password-strength-meter">
							<h4><?= lang('PASSWORD_INFO_TITLE'); ?></h4>
							<ul class="pwd-rules">
								<li id="length" class="pwd-rules-item rule-invalid"><?= lang('PASSWORD_INFO_1'); ?></li>
								<li id="letter" class="pwd-rules-item rule-invalid"><?= lang('PASSWORD_INFO_2'); ?></li>
								<li id="capital" class="pwd-rules-item rule-invalid"><?= lang('PASSWORD_INFO_3'); ?></li>
								<li id="number" class="pwd-rules-item rule-invalid"><?= lang('PASSWORD_INFO_4'); ?></li>
								<li id="special" class="pwd-rules-item rule-invalid"><?= lang('PASSWORD_INFO_5'); ?></li>
								<li id="consecutive" class="pwd-rules-item rule-invalid"><?= lang('PASSWORD_INFO_6'); ?></li>
							</ul>
						</div>
					</div>
				</div>

				<hr class="separador-one mt-2 mb-4">
				<div class="flex items-center justify-end">
					<a class="btn btn-link btn-small big-modal" href="<?= base_url($goOut); ?>"><?= lang('GEN_BTN_CANCEL'); ?></a>
					<button id="change-pass-btn" class="btn btn-small btn-loading btn-primary" type="submit">
						<?= lang('GEN_BTN_ACCEPT'); ?>
					</button>
				</div>
			</form>
		</div>
	</section>
</div>
