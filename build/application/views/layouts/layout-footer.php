<?php
$urlBaseA = $this->config->item('base_url');
$pais = $this->uri->segment(1);
$urlBase = $urlBaseA.$pais;
$logged_in = $this->session->userdata('logged_in');
?>
<footer id="foot">
		<div id="foot-wrapper">
			<nav id="extra-nav">
				<ul class="menu">

					<?
						if(!$logged_in && $pais != 'Ec-bp'){?>
						<li class="menu-item signup">
							<a href="<? echo $urlBase?>/login" rel="section">
								<? echo lang('BREADCRUMB_INICIO');?>
							</a>
						</li>
					<?	} ?>
					<?php if($pais != 'Ec-bp'): ?>
					<li class="menu-item benefits">
						<a href="<? echo $urlBase.'/'.'beneficios'?>" rel="section">
							<? echo lang('BREADCRUMB_BENEFICIOS');?>
						</a>
					</li>
					<li class="menu-item terms">
						<a href="<? echo $urlBase.'/'.'condiciones'?>" rel="section">
							<? echo lang('BREADCRUMB_CONDICIONES');?>
						</a>
					</li>
					<?php
					endif;
					if($logged_in){
					?>
						<?php
							if($pais=='Ve'){
						?>
							<li class="menu-item privacy">
								<a id='tarifas' href="<?php echo $urlBase; ?>/tarifas" rel="section">
									<? echo lang('SUBMENU_TARIFAS'); ?>
								</a>
							</li>
						<?php } ?>
						<?php if($pais != 'Ec-bp'): ?>
						<li class="menu-item privacy">
							<a id='exit' href="<?php echo $urlBase; ?>/logout" rel="section">
								<? echo lang('SUBMENU_LOGOUT'); ?>
							</a>
						</li>

						<?php endif; } ?>
				</ul>
			</nav>
			<?php if($pais != 'Ec-bp'): ?>
			<a id="ownership" href="http://www.novopayment.com/" rel="me">Powered by NovoPayment, Inc.</a>
			<div class="separator"></div>
			<div id="credits">
				<!--<p>© <?php echo date('Y')?> <?php echo lang('FOOTER') ?> </p>-->
				<p>© <?php echo date('Y'); ?> NovoPayment Inc. All rights reserved.</p>
			</div>
				<?php endif; ?>
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
