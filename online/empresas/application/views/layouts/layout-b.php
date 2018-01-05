<?php if(isset($header)){ ?>
{header}
<?php } ?>

	<div id="wrapper">
		<!-- Begin: Content Area -->

					{content}

		<!-- End: Content Area -->
		<?php if(isset($sidebarActive) && $sidebarActive){?>

			<?php if (isset($aviso) && $aviso && $pais=='Ve') { ?>
			<div class="aviso elem-hidden">
				<div id="widget-info" style="width: 200px;">
					<span data-icon="&#xe09d;" class="icon" aria-hidden="true" style="font-size: 30px; padding-right: 25px;"></span>
					AVISO IMPORTANTE
					<!-- <img src="https://cdn.novopayment.dev/empresas/Ve/media/img/alerta.png" style="margin: -10px 34px"> -->

				</div>
				<div id="widget-info-2">
					A partir del <strong>08 de enero del 2018</strong> se ajustará a Bs. <strong>50.000,00</strong> el cobro mínimo mensual a aquellas facturas cuyo Servicio Administrativo sea inferior a esa cantidad y la tarifa mensual por concepto de Servicios Operativos y de Logística será de Bs. <strong>30.000,00</strong> quedando exceptuadas aquellas facturas con Servicio Administrativo Mínimo.
				</div>
			</div>
			<?php } ?>

			<!-- Begin: Sidebar -->
			<div id="sidebar-products">
				{sidebar}

			</div>
			<!-- End: Sidebar -->




		<?php };?>
	</div>
	<?php if(isset($footer)){ ?>
{footer}
<?php } ?>
