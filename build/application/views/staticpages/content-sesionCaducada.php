<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;

?>

<div class='content-endSession'>
	<h1><?php echo lang('LOGOUT_TITULO') ?></h1>
	<h2><?php echo lang('LOGOUT_MSG') ?></h2>
<?php
	if($pais=='Ec-bp'){
		?>
			<a href="<?php echo $urlBase.'/login' ?>"><button class="novo-btn-primary"><?php echo lang('LOGOUT_BTN_BACK') ?></button></a>
		<?php
	}else{ ?>
			<a href="<?php echo $urlBase.'/login' ?>"><button><?php echo lang('LOGOUT_BTN_BACK') ?></button></a>
	<?php }
?>

</div>
