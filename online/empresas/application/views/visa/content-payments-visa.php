<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
$data = json_decode($dataResponse);
$balance = '';
if($data->code === 0) {
	$balance = $data->data;
	
}
?>

<div id="content-products" code="<?php echo $data->code; ?>" <?php echo $data->code !== 0 ?
	'title="' . $data->title . '" msg="' . $data->msg . '"' : ""; ?>>
    <h1><?php echo $action; ?></h1>
    <h2 class="title-marca">
        <?php echo ucwords(mb_strtolower($programa)); ?>
    </h2>
    <ol class="breadcrumb">
		<li>
            <a href="<?php echo base_url($pais . '/dashboard'); ?>" rel="start">
                <?php echo lang('BREADCRUMB_INICIO'); ?>
            </a>
        </li>
        /
        <li>
            <a href="<?php echo base_url($pais . '/dashboard'); ?>" rel="section">
                <?php echo lang('BREADCRUMB_EMPRESAS'); ?></a>
        </li>
        /
        <li>
            <a href="<?php echo base_url($pais . '/dashboard/productos'); ?>" rel="section">
                <?php echo lang('BREADCRUMB_PRODUCTOS'); ?></a>
        </li>
        /
        <li>
            <a rel="section">
                <?php echo lang('BREADCRUMB_SERVICIOS'); ?>
            </a>
        </li>
        /
        <li class="breadcrumb-item-current">
            <a href="<?php echo base_url($pais . '/pagos'); ?>" rel="section">
                <?php echo lang('BREADCRUMB_PAYMENTS'); ?>
            </a>
        </li>
    </ol>
</div>
<div class="container">
    <div class="container-header">
        <span aria-hidden="true" class="icon icon-list" data-icon="&#xe02c;"></span>
        <?php echo lang('TITLE_VISA_PAYMENTS_DETAIL'); ?>
    </div>
    <div class="container-body">
	    <div id='loading' style='text-align:center'><?php echo insert_image_cdn("loading.gif"); ?></div>
	    <form id="data-payment" style="display: none;">
		    <div class="filters">
			    <label class="label-input" for="balance">Saldo disponible</label>
			    <input id="balance" name="balance" class="nonValidate balance" value="<?php echo $balance; ?>" readonly>
		    </div>
		    <div class="filters">
			    <label class="label-input" for="code">Código del proveedor</label>
			    <input id="code" name="code" placeholder="Indique Código del proveedor">
		    </div>
		    <div class="filters">
			    <label class="label-input" for="amount">Monto</label>
			    <input id="amount" name="amount" placeholder="Indique el monto">
		    </div>
	    </form>
	    <div id="validate-list"></div>
    </div>
    <div class="contanier-footer">
	    <button id="payment">Pagar</button>
    </div>
</div>
<form id='formulario' method='post'></form>

<div id="msg-system" style="display:none">
	<div id="msg-info" class="comb-content">
		<p></p>
	</div>
	<div id="actions" class="comb-content actions-buttons">
		<button id="close-info" class="buttons-action">Aceptar</button>
	</div>
</div>
