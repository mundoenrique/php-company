<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<nav id="account-nav">
	<ul class="menu">
		<li class="menu-item profile">
			<?php 
				$urlRedirect = str_replace($countryUri, $this->config->item('country'), base_url('usuario/config?tab=0'));
			 ?>
			<a href="<?= $urlRedirect; ?>" rel="section" title="Mi Perfil">
				<span aria-hidden="true" class="icon" data-icon="&#xe090;"></span>
				<?= $fullName; ?>
			</a>
		</li>
		<li class="menu-item settings">
			<a id="config" rel="section" title="Configuración">
				<span aria-hidden="true" class="icon" data-icon="&#xe074;"></span>
				<?php echo lang('CONFIGURACION'); ?>
			</a>

			<ul class="submenu" >
				<li class="menu-item account">
					<span aria-hidden="true" class="icon" data-icon="&#xe090;" ></span>
					<?php 
						$urlRedirect = str_replace($countryUri, $this->config->item('country'), base_url('usuario/config?tab=0'));
					?>
					<a id='subm-user'  rel="subsection" href="<?= $urlRedirect; ?>">
						<?php echo lang('SUBMENU_USUARIO') ?>
					</a>
				</li>
				<li class="menu-item privacy">
					<span aria-hidden="true" class="icon" data-icon="&#xe064;" ></span>
					<?php 
						$urlRedirect = str_replace($countryUri, $this->config->item('country'), base_url('usuario/config?tab=1'));
					?>
					<a id='subm-emp' rel="subsection" href="<?= $urlRedirect; ?>">
						<?php echo lang('SUBMENU_EMPRESAS'); ?>
					</a>
				</li>
				<li class="menu-item security">
					<span aria-hidden="true" class="icon" data-icon="&#xe013;" ></span>
					<?php 
						$urlRedirect = str_replace($countryUri, $this->config->item('country'), base_url('usuario/config?tab=2'));
					?>
					<a id='subm-suc' rel="subsection" href="<?= $urlRedirect; ?>">
						<?php echo lang('SUBMENU_SUCURSALES') ?>
					</a>
				</li>
				<li class="menu-item signout">
					<span aria-hidden="true" class="icon" data-icon="&#xe06e;" ></span>
					<?php 
						$urlRedirect = str_replace($countryUri, $this->config->item('country'), base_url('usuario/config?tab=3'));
					?>
					<a id='subm-desc' rel="subsection" href="<?= $urlRedirect; ?>">
						<?php echo lang('SUBMENU_DESCARGAS') ?>
					</a>
				</li>
				<li class="menu-item signout">
					<span aria-hidden="true" class="icon" data-icon="&#xe03e;" ></span>
					<a href="<?= base_url('cerrar-sesion') ?>" rel="subsection">
						<?php echo lang('SUBMENU_LOGOUT') ?>
					</a>
				</li>
			</ul>
		</li>
		<?php if($countryUri === 've'): ?>
		<li class="menu-item profile">
		<?php 
			$urlRedirect = str_replace($countryUri, $this->config->item('country'), base_url('guias'));
		?>
			<a href="<?= $urlRedirect; ?>" rel="section" title="Ayuda">
			<span aria-hidden="true" class="icon" data-icon="&#xe04b;"></span>
				<?= lang('AYUDA') ?>
			</a>
		</li>
		<?php endif; ?>
	</ul>
</nav>