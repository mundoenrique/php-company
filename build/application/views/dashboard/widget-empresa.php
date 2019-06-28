<?php
$acnomciaS = $this->session->userdata('acnomciaS');
$acdescS = $this->session->userdata('acdescS');
$acrifS = $this->session->userdata('acrifS');
$acrazonsocialS = $this->session->userdata('acrazonsocialS');
$pais = $this->uri->segment(1);
?>

<div id="widget-info">
	<?php echo $acrazonsocialS;?> /
	<?php echo lang('ID_FISCAL')." ". $acrifS;?> /
	<?php echo $acdescS;?>
</div>
<div id="widget-info-2">
	<button id="sEmpresa" type="submit"><?php echo lang('WIDGET_EMPRESAS_BTNSELECCIONAR') ?></button>
	<div id="sEmpresaS" style='display:none'>
		<select style='width: 200px;' id='empresasS'>
			<option value="0" id='seleccionar_empresaS'><?php echo lang('WIDGET_EMPRESAS_OPC_SEL_EMPRESAS') ?></option>
		</select>
		<select style='width: 200px; display:none' id='productosS'>
			<option value="0"></option>
		</select>
		<button id='aplicar'><?php echo lang('WIDGET_EMPRESAS_BTNAPLICAR') ?></button>
	</div>
</div>
<?php if ($pais !== 'Ec-bp'): ?>
<div id="widget-info-2">
		<button id="sPrograms" ><?php echo lang('WIDGET_EMPRESAS_BTNOTROSPROGRAMAS') ?></button>
</div>
<?php endif; ?>
<input type='hidden' id='cdn' value=<?php echo get_cdn(); ?> />

