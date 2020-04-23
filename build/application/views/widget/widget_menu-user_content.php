<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
	$urlRedirect = str_replace('/'.$countryUri.'/', '/'.$this->config->item('country').'/', base_url('usuario/config?tab='));
?>
<nav id="account-nav">
	<ul class="menu">
		<?php if(verifyDisplay('header', 'menu', lang('GEN_TAG_LINK_UNIC'))): ?>
		<li class="menu-item settings">
			<a rel="section" title="Mi Perfil" id="config">
				<span aria-hidden="true" class="icon" data-icon="&#xe090;"></span>
				<?php echo $fullName; ?>
			</a>
			<ul class="submenu">
				<li class="menu-item setting">
					<a id='subm-user' rel="subsection" href="<?= $urlRedirect.'0' ?>">
						<?php echo lang('CONFIGURACION') ?>
					</a>
				</li>
				<li class="menu-item privacy">
					<a id='subm-emp' rel="subsection" href="<?= $urlRedirect.'1' ?>">
						<?php echo lang('SUBMENU_EMPRESAS'); ?>
					</a>
				</li>
				<li class="menu-item signout">
					<a id='subm-desc' rel="subsection" href="<?=  $urlRedirect.'2' ?>">
						<?php echo lang('SUBMENU_DESCARGAS') ?>
					</a>
				</li>
				<li class="menu-item signout">
					<a href="<?= base_url('cerrar-sesion'); ?>" rel="subsection">
						<?php echo lang('SUBMENU_LOGOUT') ?>
					</a>
				</li>
			</ul>
		</li>
		<?php else: ?>
		<li class="menu-item profile">
			<a href="<?= $urlRedirect.'0'; ?>" rel="section" title="Mi Perfil">
				<span aria-hidden="true" class="icon" data-icon="&#xe090;"></span>
				<?= $fullName ?>
			</a>
		</li>
		<li class="menu-item settings">
			<a id="config" rel="section" title="Configuración">
				<span aria-hidden="true" class="icon" data-icon="&#xe074;"></span>
				<?php echo lang('CONFIGURACION'); ?>
			</a>

			<ul class="submenu">
				<li class="menu-item account">
					<span aria-hidden="true" class="icon" data-icon="&#xe090;"></span>
					<a id='subm-user' rel="subsection" href="<?= $urlRedirect.'0'; ?>">
						<?php echo lang('SUBMENU_USUARIO') ?>
					</a>
				</li>
				<li class="menu-item privacy">
					<span aria-hidden="true" class="icon" data-icon="&#xe064;"></span>
					<a id='subm-emp' rel="subsection" href="<?= $urlRedirect.'1'; ?>">
						<?php echo lang('SUBMENU_EMPRESAS'); ?>
					</a>
				</li>
				<li class="menu-item security">
					<span aria-hidden="true" class="icon" data-icon="&#xe013;"></span>
					<a id='subm-suc' rel="subsection" href="<?= $urlRedirect.'2'; ?>">
						<?php echo lang('SUBMENU_SUCURSALES') ?>
					</a>
				</li>
				<li class="menu-item signout">
					<span aria-hidden="true" class="icon" data-icon="&#xe06e;"></span>
					<a id='subm-desc' rel="subsection" href="<?= $urlRedirect.'3'; ?>">
						<?php echo lang('SUBMENU_DESCARGAS') ?>
					</a>
				</li>
				<li class="menu-item signout">
					<span aria-hidden="true" class="icon" data-icon="&#xe03e;"></span>
					<a href="<?= base_url('cerrar-sesion') ?>" rel="subsection">
						<?php echo lang('SUBMENU_LOGOUT') ?>
					</a>
				</li>
			</ul>
		</li>
			<?php if(verifyDisplay('header', 'menu', lang('GEN_TAG_HELPER'))): ?>
			<li class="menu-item profile">
				<a href="<?=  base_url('guias') ?>" rel="section" title="Ayuda">
					<span aria-hidden="true" class="icon" data-icon="&#xe04b;"></span>
					<?= lang('AYUDA') ?>
				</a>
			</li>
			<?php endif; ?>
		<?php endif; ?>
	</ul>
</nav>
