<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$ceo_name = $this->security->get_csrf_token_name();
$ceo_cook = $this->security->get_csrf_hash();
?>

<script>
	var data = <?= json_encode($listaEmpresas) ?>;
</script>

<h1><?= lang('BREADCRUMB_WELCOME'); ?> <span class='first-title'><?= $fullName; ?></span></h1>
<ol class="breadcrumb">
	<li>
		<a href="#" rel="start">
			<?php echo lang('BREADCRUMB_INICIO'); ?>
		</a>
	</li>
	/
	<li>
		<a href="#" rel="section">
		<?php echo lang('BREADCRUMB_EMPRESAS'); ?>
		</a>
	</li>
</ol>
<div class="lastsession"><?php echo lang('LAST_SESSION'); ?>: <?= $lastSession; ?></div>
<div class="filter" id="options">
	<p id='totalEmpresas' class='total-empr-dash'></p>
	<ul class="filter-ul option-set" >
		<li><a value="" data-option-value="*" class="selected">Todas</a></li>
		<li><a value="A-C" data-option-value=".A, .B, .C">a-c</a></li>
		<li><a value="D-G" data-option-value=".D, .E, .F, .G">d-g</a></li>
		<li><a value="H-K" data-option-value=".H, .I, .J, .K">h-k</a></li>
		<li><a value="L-O" data-option-value=".L, .M, .N, .O">l-o</a></li>
		<li><a value="P-S" data-option-value=".P, .Q, .R, .S">p-s</a></li>
		<li><a value="T-W" data-option-value=".T, .U, .V, .W">t-w</a></li>
		<li><a value="X-Z" data-option-value=".X, .Y, .Z">x-z</a></li>
		<li ><input id="search-filter" placeholder="<?php echo lang('BREADCRUMB_PH_BUSCAR') ?>"></li>
		<li class="filter-3"><a id="buscar" title="<?php echo lang('BREADCRUMB_TITL_BUSCAR') ?>"><span aria-hidden="true" class="icon" data-icon="&#xe07a;" ></span></a></li>
	</ul>
</div>

<form id="empresas" method="post" action="<?= site_url($pais.'/dashboard/productos/'); ?>">
	<input type='hidden' name='<?php echo $ceo_name ?>' value='<?php echo $ceo_cook ?>'>
</form>

<!-- despliegue del contenido del listado de las empresas -->
<ul id="listCompanies" class="dashboard-companies">
</ul>

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
<div class='more-empr'><a id='more' class='elem-hidden'><?php echo lang('LOAD'); ?></a></div>

<div id='products-general' class="elem-hidden resultSet" style='width: 930px; margin-top: -50px'>
	<h2 style='text-align:center;' ><?php echo lang('ERROR_(-150)') ?></h2>
</div>
<div id='products-general' class="elem-hidden resultSet2" style='width: 930px; margin-top: -30px'>
	<h2 style='text-align:center;' ></h2>
</div>

<input id="estandar" type="hidden" data-fiscal="<?php echo lang('ID_FISCAL') ?>" />
