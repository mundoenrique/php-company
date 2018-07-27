<?php
	$pais = $this->uri->segment(1);
	$urlBaseA = $this->config->item('base_url');

	$urlBase = $urlBaseA.$pais;
?>
<?php
	if (isset($header)) { ?> {header} <?php } ?>
	<div id="wrapper">
		<!-- Begin: Content Area -->
					{content}
		<!-- End: Content Area -->
		<div style="width: 230px;float: left;margin-top: 160px;">
		<?php
			if (isset($sidebarActive) && $sidebarActive) {
					if (isset($aviso) && $aviso && $pais == 'Ve') {
		?>
							<div class="aviso">
								<div id="widget-info" style="width: 200px;">
									<span data-icon="&#xe09d;" class="icon" aria-hidden="true"
											      style="font-size: 30px; padding-right: 25px;"></span>
									AVISO IMPORTANTE
								</div>
								<div id="widget-info-2"  style="height: 123px; overflow-y: auto; text-align: justify">

											Con la autorización del Lote, se confirma la  aceptación de las
											"<a href="<? echo $urlBase.'/'.'condiciones'?>">Condiciones generales</a>,
												<a href="<?php echo $urlBase; ?>/tarifas">	tarifas</a>,
											<a href="<? echo $urlBase.'/'.'condiciones'?>">términos de uso y confidencialidad</a>"
											de la plataforma Conexión Empresas Online y  de nuestros
											productos y servicios.

								</div>
							</div>
							<?php
					} ?>
					<!-- Begin: Sidebar -->
					<div id="sidebar-products">
						{sidebar}
					</div>
					<!-- End: Sidebar -->
				<?php
			}; ?>
		</div>
	</div>
	<?php
if (isset($footer)) { ?> {footer} <?php } ?>
