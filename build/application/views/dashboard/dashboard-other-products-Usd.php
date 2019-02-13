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
					
					<h3 class="info-programa">No disponible</h3>
						
				</div>
				<div id="admon">
					<div class="widget-admon">
						<span class="icon" data-icon="&#xe057;" aria-hidden="true"></span>
						Administración y Finanzas
					</div>
					<div class='acordion-program'>
					<h3 class="info-programa">Gestión de efectivo</h3>
					<div>
						<h6><span class="icon" data-icon="&#xe027;" aria-hidden="true">Plata clásica incentivos</h6>
						<h6><span class="icon" data-icon="&#xe027;" aria-hidden="true">Plata clásica viáticos</h6>
						<h6><span class="icon" data-icon="&#xe027;" aria-hidden="true">Plata clásica viajes</h6>
						<h6><span class="icon" data-icon="&#xe027;" aria-hidden="true">Plata compras incentivos</h6>
					</div>
					</div>
				</div>
				<div id="mercadeo">
					<div class="widget-mercadeo">
						<span class="icon" data-icon="&#xe051;" aria-hidden="true"></span>
						Marketing y Ventas
					</div>
					
					<h3 class="info-programa">No disponible</h3>
					
				</div>
			</div>
			
		
		</div>
