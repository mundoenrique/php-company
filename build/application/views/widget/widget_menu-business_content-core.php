<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<nav class="line-main-nav flex bg-secondary items-center">
	<ul class="main-nav-user flex my-0 list-style-none">
		<li class="inline mr-1 pr-2"><a class="primary" href="ceo_dashboard.html">Inicio</a></li>
		<li class="inline mr-1 px-2">
			<a class="primary">Lotes</a>
			<ul class="dropdown-user pl-0 regular bg-secondary tertiary list-style-none list-inline">
				<li><a class="px-2" href="ceo_load_lots.html">Carga de lotes</a></li>
				<li><a class="px-2" href="ceo_authorization_lots.html">Autorización de Lotes</a></li>
				<li><a class="px-2" href="#">Cuentas innominadas</a></li>
			</ul>
		</li>
		<li class="inline mr-1 px-2">
			<a class="primary">Consulta</a>
			<ul class="dropdown-user pl-0 regular bg-secondary tertiary list-style-none list-inline">
				<li><a class="px-2" href="ceo_service_orders.html">Órdenes de servicio</a></li>
			</ul>
		</li>
		<li class="inline mr-1 px-2">
			<a class="primary">Reportes</a>
			<ul class="dropdown-user pl-0 regular bg-secondary tertiary list-style-none list-inline">
				<li><a class="px-2" href="#">Reposiciones</a></li>
				<li><a class="px-2" href="#">Saldos al cierre</a></li>
				<li><a class="px-2" href="#">Estado de cuenta</a></li>
				<li><a class="px-2" href="#">Actividad por usuario</a></li>
				<li><a class="px-2" href="#">Recargas relizadas</a></li>
				<li><a class="px-2" href="#">Tarjetas emitidas</a></li>
				<li><a class="px-2" href="#">Gastos por categoría</a></li>
				<li><a class="px-2" href="#">Cuenta concentradora</a></li>
			</ul>
		</li>
	</ul>
</nav>
