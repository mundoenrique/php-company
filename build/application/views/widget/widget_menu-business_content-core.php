<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<nav class="navbar-secondary line-main-nav flex bg-secondary items-center">
	<ul class="main-nav-user flex my-0 list-style-none">
		<li class="nav-item mr-1 inline"><a class="nav-link pr-2 semibold primary" href="ceo_dashboard.php">Inicio</a>
		</li>
		<li class="nav-item mr-1 inline">
			<a class="nav-link px-2 semibold primary">Lotes</a>
			<ul class="dropdown-user pl-0 regular tertiary bg-secondary list-style-none list-inline">
				<li><a href="ceo_load_lots.php">Carga de lotes</a></li>
				<li><a href="ceo_authorization_lots.php">Autorización de Lotes</a></li>
				<li><a href="#">Cuentas innominadas</a></li>
			</ul>
		</li>
		<li class="nav-item mr-1 inline">
			<a class="nav-link px-2 semibold primary">Consulta</a>
			<ul class="dropdown-user pl-0 regular tertiary bg-secondary list-style-none list-inline">
				<li><a href="ceo_service_orders.php">Órdenes de servicio</a></li>
			</ul>
		</li>
		<li class="nav-item mr-1 inline">
			<a class="nav-link px-2 semibold primary">Reportes</a>
			<ul class="dropdown-user pl-0 regular tertiary bg-secondary list-style-none list-inline">
				<li><a href="#">Reposiciones</a></li>
				<li><a href="#">Saldos al cierre</a></li>
				<li><a href="ceo_reports_account_statement.php">Estado de cuenta</a></li>
				<li><a href="#">Actividad por usuario</a></li>
				<li><a href="#">Recargas relizadas</a></li>
				<li><a href="#">Tarjetas emitidas</a></li>
				<li><a href="#">Gastos por categoría</a></li>
				<li><a href="#">Cuenta concentradora</a></li>
			</ul>
		</li>
	</ul>
</nav>
