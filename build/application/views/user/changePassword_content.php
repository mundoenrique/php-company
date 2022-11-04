<?php defined('BASEPATH') or exit('No direct script access allowed '); ?>
<div id="content-condiciones">
	<h1><?= lang('GEN_WELCOME_TEXT'); ?> <span class='first-title'> <?= $fullName ?></span></h1>
	<p id="text-alerta">
		<?= $message ?>
	</p>
	<div id="sidebar-cambioclave">
		<div id="widget-area">
			<div class="widget tooltip" id="widget-signin">
				<h2 class="widget-title">
					<?php if($this->config->item('customer') != 'Ec-bp'): ?>
					<span aria-hidden="true" class="icon" data-icon="&#xe03f;"></span>
					<?php endif; ?>
					<?= lang('GEN_CHANGE_PASS'); ?>
				</h2>
				<div class="widget-content">
					<form id="form-change-pass" name="form-change-pass" accept-charset="utf-8">
						<input type="hidden" id="status-user" name="user-type" value="<?= $userType ?>">
						<fieldset>
							<div class="field-input">
								<label for="current-pass"><?= lang('PASSWORD_CURRENT'); ?></label>
								<input type="password" id="current-pass" name="current-pass" class="input-middle" required disabled>
							</div>
							<div class="field-input">
								<label for="new-pass"><?= lang('PASSWORD_NEW'); ?></label>
								<input type="password" id="new-pass" name="new-pass" class="input-middle" required disabled>
							</div>
							<div class="field-input">
								<label for="confirm-pass"><?= lang('PASSWORD_CONFIRM'); ?></label>
								<input type="password" id="confirm-pass" name="confirm-pass" class="input-middle" required disabled>
							</div>
						</fieldset>
						<button id="passwordChangeBtn" name="passwordChangeBtn" class="btn-middle btn-sidebar"><?= lang('GEN_BTN_ACCEPT') ?></button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="psw_info" style="display: none">
		<h5><?= lang('PASSWORD_INFO_TITLE'); ?></h5>
		<ul>
			<li id="length" class="invalid"><?= lang('PASSWORD_INFO_1'); ?></li>
			<li id="letter" class="invalid"><?= lang('PASSWORD_INFO_2'); ?></li>
			<li id="capital" class="invalid"><?= lang('PASSWORD_INFO_3'); ?></li>
			<li id="number" class="invalid"><?= lang('PASSWORD_INFO_4'); ?></li>
			<li id="especial" class="invalid"><?= lang('PASSWORD_INFO_5'); ?></li>
			<li id="consecutivo" class="invalid"><?= lang('PASSWORD_INFO_6'); ?></li>
		</ul>
	</div>
</div>
