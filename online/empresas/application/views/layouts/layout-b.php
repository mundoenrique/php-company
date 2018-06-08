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
								<div id="widget-info-2"  style="height: 305px; overflow-y: auto; text-align: justify">
										<b>Tarifas vigentes desde el 01-06-2018</b>
									<ul style="padding-left: 15px;">
										<li>Servicio Administrativo Mínimo:<br><b> Bs. 1.500.000 | Bs.S 1.500</b></li>
										(Aplica a facturas cuyo servicio administrativo sea inferior a <br><b>Bs. 1.500.000 | Bs.S 1.500</b>)

										<li>Servicios Operativos y de Logística:<br><b>Bs. 1.500.000 | Bs.S 1500</b> Cobro único mensual</li>
										(Quedan exceptuadas facturas con Servicio Administrativo Mínimo)

										<li>Emisión, reposición/renovación de tarjetas:<br><b>Bs. 200.000  | Bs.S 200</b> (c/u)</li>

										<li>Reposición y entrega de claves:<br><b>Bs. 150.000 | Bs.S 150</b> (c/u)</li>
									</ul>
									<p style="text-align: justify">Estas Tarifas se encuentran reexpresadas a fines referenciales, acorde con lo establecido en el Decreto N° 3.332, publicado en la Gaceta Oficial N° 41.366 del 22/3/2018,  dictado por la Presidencia de la República en el Marco del Estado de Excepción y de Emergencia Económica, mediante el cual se decretó la Reconversión Monetaria.</p>
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
