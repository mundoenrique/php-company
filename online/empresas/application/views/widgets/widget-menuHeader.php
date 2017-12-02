<?php
$nombreCompleto = $this->session->userdata('nombreCompleto');
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
?>
<nav id="account-nav">
	<ul class="menu">
		<li class="menu-item profile">
			<a href="<?php echo $urlBase.'/usuario/config?tab=0' ?>" rel="section" title="Mi Perfil">
				<span aria-hidden="true" class="icon" data-icon="&#xe090;"></span>
				<?php echo $nombreCompleto; ?>
			</a>
		</li>
		<li class="menu-item settings">
			<a rel="section" title="Configuración" id='config'>
				<span aria-hidden="true" class="icon" data-icon="&#xe074;"></span>
				<?php echo lang('CONFIGURACION'); ?>
			</a>

			<ul class="submenu" >
				<li class="menu-item account">
					<span aria-hidden="true" class="icon" data-icon="&#xe090;" ></span>
					<a id='subm-user'  rel="subsection" href="<?php echo $urlBase.'/usuario/config?tab=0' ?>">
						<?php echo lang('SUBMENU_USUARIO') ?>
					</a>
				</li>
				<li class="menu-item privacy">
					<span aria-hidden="true" class="icon" data-icon="&#xe064;" ></span>
					<a id='subm-emp' rel="subsection" href="<?php echo $urlBase.'/usuario/config?tab=1' ?>">
						<?php echo lang('SUBMENU_EMPRESAS'); ?>
					</a>
				</li>
				<li class="menu-item security">
					<span aria-hidden="true" class="icon" data-icon="&#xe013;" ></span>
					<a id='subm-suc' rel="subsection" href="<?php echo $urlBase.'/usuario/config?tab=2' ?>">
						<?php echo lang('SUBMENU_SUCURSALES') ?>
					</a>
				</li>
				<li class="menu-item signout">
					<span aria-hidden="true" class="icon" data-icon="&#xe06e;" ></span>
					<a id='subm-desc' rel="subsection" href="<?php echo $urlBase.'/usuario/config?tab=3' ?>">
						<?php echo lang('SUBMENU_DESCARGAS') ?>
					</a>
				</li>

					<?php if ($pais == 'Ve'): ?>
					<li class="menu-item signout">
						<span aria-hidden="true" class="icon" data-icon="&#xe06e;" ></span>
						<a id='subm-desc' rel="subsection" href="<?php echo $urlBase.'/usuario/config?tab=4' ?>">
							<?php echo lang('SUBMENU_NOTIFICACIONES') ?>
						</a>
					</li>
				<?php endif; ?>
				<li class="menu-item signout">
					<span aria-hidden="true" class="icon" data-icon="&#xe03e;" ></span>
					<a href="<?php echo $urlBase.'/logout' ?>" rel="subsection">
						<?php echo lang('SUBMENU_LOGOUT') ?>
					</a>
				</li>
			</ul>

		</li>
	</ul>
</nav>

<form id='logout' action="<?php echo $urlBase ?>/logout" method='post' >
	<input type='hidden' name='data-caducada' value='true'>
</form>

<input type='hidden' id='ruta-cdn' value='<?php echo $this->config->item('base_url_cdn') ?>'/>
