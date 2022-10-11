<?php
$CI =& get_instance();
$pais = $CI->config->item('country');
$urlBase= $CI->config->item('base_url').$pais.'/';
$urlBaseCDN = $CI->config->item('base_url_cdn');
$nombreCompleto = $this->session->userdata('nombreCompleto');
$ceo_name = $this->security->get_csrf_token_name();
$ceo_cook = $this->security->get_csrf_hash();
?>
<h1><?= lang('BREADCRUMB_WELCOME'); ?> <span class='first-title'>{titulo}</span></h1>

<ol class="breadcrumb">
	<li>
		<a href="<?php echo $urlBase; ?>dashboard" rel="start">
			<?php echo lang('BREADCRUMB_INICIO'); ?>
		</a>
	</li>
	/
	<li>
		<a href="<?php echo $urlBase; ?>dashboard" rel="section">
		<?php echo lang('BREADCRUMB_EMPRESAS'); ?>
		</a>
	</li>
</ol>
<div class="lastsession"><?php echo lang('LAST_SESSION'); ?>: {lastSession}</div>
<div class="filter" id="options">
	<p id='totalEmpresas' class='total-empr-dash'></p>
	<ul class="filter-ul option-set" >
	<?php if($pais == 'Ec-bp'): ?>
	<select id="filtrar" class="categories-products">
		<option value="" data-option-value="*" class="selected">Todas</option>
		<option value="A-C" data-option-value=".A, .B, .C">a-c</option>
		<option value="D-G" data-option-value=".D, .E, .F, .G">d-g</option>
		<option value="H-K" data-option-value=".H, .I, .J, .K">h-k</option>
		<option value="L-O" data-option-value=".L, .M, .N, .O">l-o</option>
		<option value="P-S" data-option-value=".P, .Q, .R, .S">p-s</option>
		<option value="T-W" data-option-value=".T, .U, .V, .W">t-w</option>
		<option value="X-Z" data-option-value=".X, .Y, .Z">x-z</option>
	</select>
	<?php endif; ?>
		<?php if($pais != 'Ec-bp'): ?>
		<li><a value="" data-option-value="*" class="selected">Todas</a></li>
		<li><a value="A-C" data-option-value=".A, .B, .C">a-c</a></li>
		<li><a value="D-G" data-option-value=".D, .E, .F, .G">d-g</a></li>
		<li><a value="H-K" data-option-value=".H, .I, .J, .K">h-k</a></li>
		<li><a value="L-O" data-option-value=".L, .M, .N, .O">l-o</a></li>
		<li><a value="P-S" data-option-value=".P, .Q, .R, .S">p-s</a></li>
		<li><a value="T-W" data-option-value=".T, .U, .V, .W">t-w</a></li>
		<li><a value="X-Z" data-option-value=".X, .Y, .Z">x-z</a></li>
		<?php endif; ?>
		<li ><input id="search-filter" placeholder="<?php echo lang('BREADCRUMB_PH_BUSCAR') ?>"></li>
		<li class="filter-3"><a id="buscar" title="<?php echo lang('BREADCRUMB_TITL_BUSCAR') ?>"><span aria-hidden="true" class="icon" data-icon="&#xe07a;" ></span></a></li>
	</ul>
</div>

<form id="empresas" method="post" action="<?php echo site_url($pais.'/dashboard/productos/'); ?> ">

</form>


<ul id="listCompanies" class="dashboard-companies">

</ul>
<!--<div id='paginado-dash' class='paginacion-dash'></div>-->

<div id="contend-pagination-p" style="width:950px; float:left; display:none;">
	<table align="center">
		<tr>
			<td>
				<div id="contend-pagination">

					<nav id="nav_left">
						<a href="#" id="anterior-22">Primera</a>
						&nbsp;
						<a href="#" id="anterior-2">&laquo;&laquo;</a>
						&nbsp;
						<a href="#" id="anterior-1">&laquo;</a>
					</nav>

					<div id="list_pagination"></div>

					<nav id="nav_right">
						<a href="#" id="siguiente-1">&raquo;</a>
						&nbsp;
						<a href="#" id="siguiente-2">&raquo;&raquo;</a>
						&nbsp;
						<a href="#" id="siguiente-22">&Uacute;ltima</a>
					</nav>

				</div>
			</td>
		</tr>
	</table>
</div>

<div id='loading' style='text-align:center'><?php echo insert_image_cdn("loading.gif"); ?></div>
<div class='more-empr'><a id='more' class='elem-hidden'><?php echo lang('LOAD'); ?></a></div>

<div id='products-general' class="elem-hidden resultSet" style='width: 930px; margin-top: -22px'>
	<h2 style='text-align:center;' ><?php echo lang('ERROR_(-150)') ?></h2>
</div>
<div id='products-general' class="elem-hidden resultSet2" style='width: 930px; margin-top: -30px'>
	<h2 style='text-align:center;' ></h2>
</div>

<input id="estandar" type="hidden" data-fiscal="<?php echo lang('ID_FISCAL') ?>" />

<div id="dialog-monetary-reconversion" style='display:none'>
	<div class="dialog-small" id="dialog">
		<div class="alert-simple" id="message">
			<div>
				<img src="<?= base_url('assets/images/migracion-sgc.png') ?>" alt="Notificación" style="height: 410px; width: 430px;">
			</div>
		</div>
		<div class="form-actions">
			<button id="dialog-monetary" class="novo-btn-primary">Aceptar</button>
		</div>
	</div>
</div>
