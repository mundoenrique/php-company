<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo $scriptCaptcha;?>

<div id="content">
	<div class="img-back">
		<h1 class="welcome-title"><?= lang('WELCOME_TITLE'); ?></h1>
	</div>
	<p><?= lang('WELCOME_MESSAGE') ?></p>

	<ul class='acordion kwicks kwicks-horizontal' >
		<li class="acordion-item" id="panel-1">
			<div class="acordion-item-content-1">
				<p>
					<span aria-hidden="true" class="icon" data-icon="&#xe028;"></span>

				<p class="titulo-login-desc">Operaciones seguras</p>
				<p class='desc-func'>
					Resguardamos la integridad y la privacidad de la información cumpliendo los más altos estándares internacionales de seguridad.
				</p>
			</div>
		</li>
		<li>
			<div class="acordion-item-content-2" id="panel-2">
				<p>
					<span aria-hidden="true" class="icon" data-icon="&#xe04f;"></span>
				</p>
				<p class="titulo-login-desc">Accesibilidad 7x24</p>
				<p class='desc-func'>
					La plataforma está disponible las 24 horas del día, los 7 días de la semana y le ofrece conexión directa vía Internet para realizar las operaciones de su empresa.
				</p>
			</div>
		</li>
		<li >
			<div class="acordion-item-content-3" id="panel-3">
				<p><span aria-hidden="true" class="icon" data-icon="&#xe023;"></span>
				<p class="titulo-login-desc">Actualización automática</p>
				<p class='desc-func'>
					Las actualizaciones y mejoras de la plataforma se ejecutarán de forma automática, permitiéndole a su empresa estar siempre al día con las nuevas funcionalidades que se desarrollen.
				</p>
			</div>
		</li>
		<li >
			<div class="acordion-item-content-4" id="panel-4">
				<p>
					<span aria-hidden="true" class="icon" data-icon="&#xe00b;"></span>
				</p>
				<p class="titulo-login-desc">Reportes Online</p>
				<p class='desc-func'>
					Obtenga información sobre las operaciones realizadas y gestione en línea la emisión de reportes y gráficos que facilitan auditorías y controles sobre los gastos realizados por su empresa.
				</p>
			</div>
		</li>
		<li>
			<div class="acordion-item-content-5" id="panel-5">
				<p>
					<span aria-hidden="true" class="icon" data-icon="&#xe089;"></span>
				</p>
				<p class="titulo-login-desc">Operaciones</p>
				<p class='desc-func'>
					Consulte, autorice o anule Lotes de Emisión y Recarga de tarjetas con mayor facilidad y obtenga la Orden de Servicio para facilitar su pago.
				</p>
			</div>
		</li>
	</ul>

	<div id="text-general">
		<div class="text-der" style="padding-left: 380px;">
			<p class="subtitulos-login">¿Necesita ayuda?</p>
			<p>
				Nuestros Ejecutivos del Centro de Soporte a  Empresas están a su orden para ofrecerle mayor información o aclararle cualquier duda.
			</p>
			<p class="subtitulos-login">
				<?php if($countryUri != "co") {echo  lang('LOGIN_INFO');} ?>
			</p>
			<p><?php echo lang('INFO-1'); ?></p>
			<p><?php if ($countryUri == "co") {echo "infocolombia@novopayment.com";} else { echo lang('INFO-2'); }?></p>
			<p><?php if ($countryUri != "co") { echo lang('INFO-3'); }  ?></p>
		</div>
	</div>

</div>
