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
								<div id="widget-info-2">
										<b>Tarifas vigentes desde el 01-05-2018</b>
									<ul style="padding-left: 15px;">
										<li>Servicio Administrativo Mínimo:<br><b>Bs. 600.000 | Bs.S 600</b></li>
										(Aplica a facturas cuyo servicio administrativo sea inferior a <br><b>Bs. 600.000 | Bs.S 600</b>)
										<li>Servicios Operativos y de Logística: <b>Bs. 600.000 | Bs.S 600</b> Cobro único mensual</li>
										(Quedan exceptuadas facturas con Servicio Administrativo Mínimo)
										<li>Emisión, reposición/renovación de tarjetas: <b>Bs. 45.000  | Bs.S 45</b> (c/u)</li>
										<li>Reposición y entrega de claves: <b>Bs. 30.000 | Bs.S 30</b> (c/u)</li>
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
	</div>
	<?php
if (isset($footer)) { ?> {footer} <?php } ?>
