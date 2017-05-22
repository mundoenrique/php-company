<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');

$urlBase = $urlBaseA.$pais;
$logged_in = $this->session->userdata('logged_in');
?>
<footer id="foot">
		<div id="foot-wrapper">
			<nav id="extra-nav">
				<ul class="menu">

					<?
						if(!$logged_in){?>
						<li class="menu-item signup">
							<a href="<? echo $urlBase?>/login" rel="section">
								<? echo lang('BREADCRUMB_INICIO');?>
							</a>
						</li>
					<?	} ?>

					<!--<li class="menu-item signup">
						<a href="#" rel="section">
							Afiliación
						</a>
					</li>-->
					<li class="menu-item benefits">
						<a href="<? echo $urlBase.'/'.'beneficios'?>" rel="section">
							<? echo lang('BREADCRUMB_BENEFICIOS');?>
						</a>
					</li></li>
					<!--<li class="menu-item mobile">
						<a href="#" rel="section">
							Móvil
						</a>
					</li>
					<li class="menu-item support">
						<a href="#" rel="section">
							Soporte
						</a>
					</li>-->
					<li class="menu-item terms">
						<a href="<? echo $urlBase.'/'.'condiciones'?>" rel="section">
							<? echo lang('BREADCRUMB_CONDICIONES');?>
						</a>
					</li>
					<!-- <li class="menu-item privacy">
						<a href="<? //echo $urlBase.'/'.'privacidad'?>" rel="section">
							<? //echo lang('BREADCRUMB_PRIVACIDAD');?>
						</a>
					</li> -->
					<?php
					if($logged_in){
					?>

					<li class="menu-item privacy">
						<a id='exit' href="<?php echo $urlBase; ?>/logout" rel="section">
							<? echo lang('SUBMENU_LOGOUT'); ?>
						</a>
					</li>
					<?php } ?>
				</ul>
			</nav>
			<a id="ownership" href="http://www.novopayment.com/" rel="me">Powered by NovoPayment, Inc.</a>
			<div class="separator"></div>
			<div id="credits">
				<!--<p>© <?php echo date('Y')?> <?php echo lang('FOOTER') ?> </p>-->
				<p>© <?php echo date('Y'); ?> NovoPayment Inc. All rights reserved.</p>
			</div>
		</div>
	</footer>
<?php if($FooterCustomInsertJSActive){?>
	<?php
	foreach ($FooterCustomInsertJS as $key) {
		echo insert_js_cdn($key);
	}

	 ?>
<?php };?>
<?php if($FooterCustomJSActive){?>
	<script>
		{FooterCustomJS}
	</script>
<?php };?>
