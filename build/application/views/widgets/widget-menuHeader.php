<?php
$CI =& get_instance();
$pais = $CI->config->item('country');
$urlBase= $CI->config->item('base_url').$pais.'/';
$urlBaseCDN = $CI->config->item('base_url_cdn');
$nombreCompleto = $this->session->userdata('nombreCompleto');
$tab = $pais == 'Ec-bp' ? '2' : '3';
?>
<nav id="account-nav">
  <ul class="menu">
  <?php if($pais != 'Ec-bp'): ?>
    <li class="menu-item profile">
      <a href="<?php echo $urlBase.'usuario/config?tab=0' ?>" rel="section" title="Mi Perfil">
        <span aria-hidden="true" class="icon" data-icon="&#xe090;"></span>
        <?php echo $nombreCompleto; ?>
      </a>
    </li>
    <li class="menu-item settings">
      <a rel="section" title="Configuración" id='config'>
        <span aria-hidden="true" class="icon" data-icon="&#xe074;"></span>
        <?php echo lang('CONFIGURACION'); ?>
      </a>
      <ul class="submenu">
        <li class="menu-item account">
          <span aria-hidden="true" class="icon" data-icon="&#xe090;"></span>
          <a id='subm-user' rel="subsection" href="<?php echo $urlBase.'usuario/config?tab=0' ?>">
            <?php echo lang('SUBMENU_USUARIO') ?>
          </a>
        </li>
        <li class="menu-item privacy">
          <span aria-hidden="true" class="icon" data-icon="&#xe064;"></span>
          <a id='subm-emp' rel="subsection" href="<?php echo $urlBase.'usuario/config?tab=1' ?>">
            <?php echo lang('SUBMENU_EMPRESAS'); ?>
          </a>
        </li>
        <li class="menu-item security">
          <span aria-hidden="true" class="icon" data-icon="&#xe013;"></span>
          <a id='subm-suc' rel="subsection" href="<?php echo $urlBase.'usuario/config?tab=2' ?>">
            <?php echo lang('SUBMENU_SUCURSALES') ?>
          </a>
        </li>
        <li class="menu-item signout">
          <span aria-hidden="true" class="icon" data-icon="&#xe06e;"></span>
          <a id='subm-desc' rel="subsection" href="<?php echo $urlBase.'usuario/config?tab='.$tab ?>">
            <?php echo lang('SUBMENU_DESCARGAS') ?>
          </a>
        </li>
        <li class="menu-item signout">
          <span aria-hidden="true" class="icon" data-icon="&#xe03e;"></span>
          <a href="<?php echo $urlBase.'logout' ?>" rel="subsection">
            <?php echo lang('SUBMENU_LOGOUT') ?>
          </a>
        </li>
      </ul>
    </li>
  <?php else: ?>
    <li class="menu-item profile">
      <a rel="section" title="Mi Perfil" id="profileMenu">
        <span aria-hidden="true" class="icon" data-icon="&#xe090;"></span>
        <?php echo $nombreCompleto; ?>
      </a>
      <ul class="submenu">
        <li class="menu-item settings">
					<a id='subm-user' rel="subsection" id='config' href="<?php echo $urlBase.'usuario/config?tab=0' ?>">
            <?php echo lang('CONFIGURACION') ?>
					</a>
				</li>
        <li class="menu-item privacy">
          <a id='subm-emp' rel="subsection" href="<?php echo $urlBase.'usuario/config?tab=1' ?>">
            <?php echo lang('SUBMENU_EMPRESAS'); ?>
          </a>
        </li>
        <li class="menu-item signout">
          <a id='subm-desc' rel="subsection" href="<?php echo $urlBase.'usuario/config?tab='.$tab ?>">
            <?php echo lang('SUBMENU_DESCARGAS') ?>
          </a>
        </li>
        <li class="menu-item signout">
          <a href="<?php echo $urlBase.'logout' ?>" rel="subsection">
            <?php echo lang('SUBMENU_LOGOUT') ?>
          </a>
        </li>
      </ul>
    </li>
  <?php endif; ?>

    <?php if ($pais == 'Ve') {
			echo '<li class="menu-item profile">
				<a href="' . $urlBase . '/guias' . '" rel="section" title="Ayuda">
					<span aria-hidden="true" class="icon" data-icon="&#xe04b;"></span>' .
						 lang('AYUDA') .
				'</a>
			</li>';
		}
		?>
  </ul>
</nav>

<form id='logout' action="<?php echo $urlBase ?>logout" method='post'>
  <input type='hidden' name='data-caducada' value='true'>
</form>

<input type='hidden' id='ruta-cdn' value='<?php echo $this->config->item('base_url_cdn') ?>' />
