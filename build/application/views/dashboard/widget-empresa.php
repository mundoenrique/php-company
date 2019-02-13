<?php
$acnomciaS = $this->session->userdata('acnomciaS');
$acdescS = $this->session->userdata('acdescS');
$acrifS = $this->session->userdata('acrifS');
$acrazonsocialS = $this->session->userdata('acrazonsocialS');
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
			<option><?php echo lang('WIDGET_EMPRESAS_OPC_SEL_EMPRESAS') ?></option>
		</select>
		<select style='width: 200px;' id='productosS'>
		</select>
		<button id='aplicar'><?php echo lang('WIDGET_EMPRESAS_BTNAPLICAR') ?></button>
	</div>
</div>
<div id="widget-info-2">
		<button id="sPrograms" ><?php echo lang('WIDGET_EMPRESAS_BTNOTROSPROGRAMAS') ?></button>
</div>
<input type='hidden' id='cdn' value=<?php echo get_cdn(); ?> />

