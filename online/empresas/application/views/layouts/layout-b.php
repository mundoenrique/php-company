<?php
	if (isset($header)) { ?> {header} <?php } ?>
	<div id="wrapper">
		<!-- Begin: Content Area -->
					{content}
		<!-- End: Content Area -->
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
								<div id="widget-info-2">
									<b>Tarifas vigentes desde el 08-01-2018</b>
									<ul style="padding-left: 15px;">
										<li>Servicio Administrativo Mínimo:<br><b>Bs. 50.000</b></li>
										(Aplica a facturas cuyo servicio administrativo sea inferior a<br><b>Bs. 50.000</b>)
										<li>Servicios Operativos y de Logística: <b>Bs. 30.000.</b> Cobro único mensual</li>
										(Quedan exceptuadas facturas con Servicio Administrativo Mínimo)
									  <li>Emisión de tarjetas: <b>Bs. 20.000</b> (c/u)</li>
										<li>Reposición/renovación de tarjetas: <b>Bs.25.000</b> (c/u)</li>
										<li>Reposición y entrega de claves: <b>Bs.12.500</b> (c/u)</li>
									</ul>
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
	<?php
if (isset($footer)) { ?> {footer} <?php } ?>
