<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
?>
<div id="content-products">
	<h1>{titulo}</h1>

	<ol class="breadcrumb">

		<li>
			<a href="<?php echo $urlBase; ?>/dashboard" rel="start"><?php echo lang('BREADCRUMB_INICIO'); ?></a>
		</li>
		/
		<li>
			<a href="<?php echo $urlBase; ?>/dashboard" rel="section"><?php echo lang('BREADCRUMB_EMPRESAS'); ?></a>
		</li>
		/
		<li class="breadcrumb-item-current">
			<a href="#" rel="section"><?php echo lang('BREADCRUMB_OTROS_PROGRAMS'); ?></a>
		</li>

	</ol>




	<div id="lotes-general">
		<div id="rrhh">
			<div class="widget-rrhh">
				<span class="icon" data-icon="&#xe090;" aria-hidden="true"></span>
				Recursos Humanos
			</div>
			<div class='acordion-program'>
				<h3 class="info-programa">Alimentación</h3>
				<div>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true"></span>Pago del beneficio legal a los trabajadores</h6>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true"></span>Mejoras del paquete de compensación</h6>
				</div>
				<h3 class="info-programa">Nómina</h3>
				<div>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true"></span>Sueldos y salarios</h6>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true"></span>Pensiones y jubilaciones</h6>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true"></span>Fideicomisos</h6>
				</div>
				<h3 class="info-programa">Incentivos</h3>
				<div>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true"></span>Bonos de desempeño</h6>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true"></span>Asignaciones</h6>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true"></span>Incentivos Plus: pago de beneficios a ejecutivos de nómina de alto valor</h6>
				</div>
				<h3 class="info-programa">Guardería</h3>
				<div>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true"></span>Beneficio legal para hijos de los trabajadores</h6>
				</div>
				<h3 class="info-programa">Salud</h3>
				<div>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true"></span>Beneficios adicionales para el pago de servicios médicos y medicinas</h6>
				</div>
				<h3 class="info-programa">Juguetes y Útiles Escolares</h3>
				<div>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true"></span>Juguetes de navidad</h6>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true"></span>Bonificaciones especiales</h6>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true"></span>Dotación de útiles escolares</h6>
				</div>
				<h3 class="info-programa">Cesta</h3>
				<div>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true"></span>Beneficios puntuales de alimentación</h6>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true"></span>Bonificaciones navideñas</h6>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true"></span>Aguinaldos</h6>
				</div>
			</div>
		</div>
		<div id="admon">
			<div class="widget-admon">
				<span class="icon" data-icon="&#xe057;" aria-hidden="true"></span>
				Administración y Finanzas
			</div>
			<div class='acordion-program'>
				<h3 class="info-programa">Viáticos</h3>
				<div >
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true">Gastos de viaje y pasajes</h6>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true">Gastos de gasolina</h6>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true">Acceso a efectivo</h6>
				</div>
				<h3 class="info-programa">Procura</h3>
				<div >
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true">Caja chica</h6>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true">Insumos de oficina/proveeduría</h6>
				</div>
				<h3 class="info-programa">Gastos de Representación</h3>
				<div >
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true">Gastos vinculados al desarrollo comercial y de negocios de la empresa</h6>
				</div>
				<h3 class="info-programa">Fletes y Encomiendas</h3>
				<div >
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true">Gastos relacionados con transporte de carga y entrega de mercancías realizadas por flotas de transporte propias o tercerizadas</h6>
				</div>
			</div>
		</div>
		<div id="mercadeo">
			<div class="widget-mercadeo">
				<span class="icon" data-icon="&#xe051;" aria-hidden="true"></span>
				Marketing y Ventas
			</div>
			<div class="acordion-program">
				<h3 class="info-programa">Promociones</h3>
				<div >
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true">Campañas publicitarias o promocionales</h6>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true">Programas de lealtad</h6>
				</div>
				<h3 class="info-programa">Incentivos</h3>
				<div >
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true">Canales y fuerza de venta</h6>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true">Consumidor</h6>
				</div>
				<h3 class="info-programa">Regalo</h3>
				<div >
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true">Asignaciones especiales</h6>
					<h6><span class="icon" data-icon="&#xe036;" aria-hidden="true">Obsequios</h6>
				</div>
			</div>
		</div>
	</div>


</div>