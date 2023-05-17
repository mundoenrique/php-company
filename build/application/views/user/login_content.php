<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="content">
	<div class="img-back">
		<h1 class="welcome-title"><?= lang('USER_WELCOME_TITLE'); ?></h1>
	</div>

	<p><?= lang('LOGIN_WELCOME_MESSAGE') ?></p>
	<ul class='acordion kwicks kwicks-horizontal'>
		<li class="acordion-item" id="panel-1">
			<div class="acordion-item-content-1">
				<p><span aria-hidden="true" class="icon" data-icon="&#xe028;"></span></p>
				<p class="titulo-login-desc"><?= lang('LOGIN_SECURE_OPER_TITLE'); ?></p>
				<p class='desc-func'><?= lang('LOGIN_SECURE_OPER_MSG'); ?></p>
			</div>
		</li>
		<li>
			<div class="acordion-item-content-2" id="panel-2">
				<p><span aria-hidden="true" class="icon" data-icon="&#xe04f;"></span></p>
				<p class="titulo-login-desc"><?= lang('LOGIN_ACCESS_TITLE'); ?></p>
				<p class='desc-func'><?= lang('LOGIN_ACCESS_MSG'); ?></p>
			</div>
		</li>
		<li>
			<div class="acordion-item-content-3" id="panel-3">
				<p><span aria-hidden="true" class="icon" data-icon="&#xe023;"></span>
					<p class="titulo-login-desc"><?= lang('LOGIN_AUTOMATIC_UPGRADE_TITLE'); ?></p>
					<p class='desc-func'>
						<?= lang('LOGIN_AUTOMATIC_UPGRADE_MSG'); ?>
					</p>
			</div>
		</li>
		<li>
			<div class="acordion-item-content-4" id="panel-4">
				<p><span aria-hidden="true" class="icon" data-icon="&#xe00b;"></span></p>
				<p class="titulo-login-desc"><?= lang('LOGIN_ONLINE_REPORTS_TITLE'); ?></p>
				<p class='desc-func'><?= lang('LOGIN_ONLINE_REPORTS_MSG'); ?></p>
			</div>
		</li>
		<li>
			<div class="acordion-item-content-5" id="panel-5">
				<p><span aria-hidden="true" class="icon" data-icon="&#xe089;"></span></p>
				<p class="titulo-login-desc"><?= lang('LOGIN_OPERATIONS_TITLE'); ?></p>
				<p class='desc-func'><?= lang('LOGIN_OPERATIONS_MSG'); ?></p>
			</div>
		</li>
	</ul>

	<div id="text-general">
	<?php if(lang('SETT_SIGNIN_IMG') === 'ON'): ?>
		<div class="text-brand">
			<img src="<?= $this->asset->insertFile(lang('GEN_IMAGE_LOGIN'), 'images', $customerFiles); ?>"
			alt="Tebca" />
			<p>Emitido por Servitebca Péru, Servicio de Transferencia Electrónica de Beneficios y Pagos S.A.</p>
		</div>
		<?php endif;?>
		<div class="text-der">
			<p class="subtitulos-login"><?= lang('LOGIN_NEED_HELP_TITLE'); ?></p>
			<p><?= lang('LOGIN_NEED_HELP_MSG'); ?></p>
			<p class="subtitulos-login"><?= lang('LOGIN_INFO'); ?></p>
			<p><?= lang('LOGIN_INFO-1'); ?></p>
			<p><?= lang('LOGIN_INFO-2'); ?></p>
			<p><?= lang('LOGIN_INFO-3'); ?></p>
		</div>
	</div>

</div>
