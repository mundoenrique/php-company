<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="content-products">
    <h1><?php echo lang('TITLE_VISA'); ?></h1>
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
            <a href="<?php echo base_url($pais . '/controles/visa'); ?>" rel="section">
                <?php echo lang('BREADCRUMB_CONTROLES'); ?>
            </a>
        </li>
    </ol>
</div>
<div class="container">
    <div class="container-header">
        <span aria-hidden="true" class="icon icon-list" data-icon="&#xe02c;"></span>
        <?php echo lang('SECTION_TITLE_VISA'); ?>
    </div>
	<div class="container-body body-center">
		<form id="data-card">
			<div class="filters">
				<label class="label-input" for="dni">DNI</label>
				<input id="dni" name="dni" placeholder="Ingerese DNI" maxlength="10">
			</div>
			<div class="filters">
				<label class="label-input" for="card-number">Número de tarjeta</label>
				<input id="card" name="card" placeholder="Ingerese Número de Tarjeta"  maxlength="16">
			</div>
			<div class="filters">
				<button class="search" id="buscar">Buscar</button>
			</div>
		</form>
		<div id="validate-list"></div>
	</div>
    <div class="container-body">
        <div id='loading' style='text-align:center'><?php echo insert_image_cdn("loading.gif"); ?></div>
        <table id="novo-table" class="hover cell-border" width="620px" style="display: none"></table>
    </div>
    <div class="contanier-footer"></div>
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
