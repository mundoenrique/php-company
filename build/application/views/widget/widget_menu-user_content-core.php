<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<nav class="main-nav main-nav-dropdown">
	<ul class="flex my-0 items-center list-style-none list-inline">
		<li>
			<a class="mt-1 mx-1 regular text-decoration-none white" href="#"><?= $fullName ?>
				<i class="ml-5 icon icon-chevron-down" aria-hidden="true"></i>
			</a>
			<ul class="dropdown regular tertiary bg-secondary">
				<?php if(lang('CONF_SETT_CONFIG') == 'ON'): ?>
				<li>
				<a class="pl-2 pr-1 h6 big-modal" href="<?= base_url('configuracion') ?>"><?= lang('GEN_SETTINGS_TITLE'); ?></a>
				</li>
				<?php endif; ?>
				<li>
				<a class="pl-2 pr-1 h6 big-modal" href="<?= base_url('cerrar-sesion/inicio') ?>"><?= lang('GEN_MENU_SIGN_OFF'); ?></a>
				</li>
			</ul>
			<span></span>
		</li>
	</ul>
</nav>