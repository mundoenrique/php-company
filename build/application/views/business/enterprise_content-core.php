<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<span class="primary"><?= $greeting.' '.$fullName ?></span>
<div class="flex mb-2 light items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 light h6 tertiary list-style-none list-inline ">
				<li class="inline"><a class="tertiary" href="ceo_dashboard.html">Inicio /</a></li>
				<li class="inline"><a class="tertiary" href="ceo_dashboard.html">Empresas</a></li>
			</ul>
		</nav>
	</div>
	<div class="flex h6 flex-auto justify-end">
		<span>Último acceso: <?= $lastSession ?></span>
	</div>
</div>

<div class="flex mt-3 light items-center">
	<div class="flex h5">
		<span>Total Empresas: <?= $enterprisesTotal ?></span>
	</div>
	<div id="alphabetical" class="flex h6 flex-auto justify-end">
		<button class="btn btn-outline btn-small btn-rounded-left bg-white" filter-page="page_1">TODOS</button>
		<?php foreach($filters AS $filtersAttr): ?>
		<button class="btn-options btn-outline bold bg-white" filter-page="<?= $filtersAttr['filter'] ?>"
			<?= $filtersAttr['active'] ? '' : 'disabled' ?>><?= $filtersAttr['text']; ?></button>
		<?php endforeach; ?>
		<button class="btn-search btn-outline bg-white"></button>
	</div>
</div>
<div class="line mt-1"></div>

<div id="enterprise-list" class="products my-5 mx-auto pt-2 visible">
	<?php foreach($enterpriseList AS $enterpriseaAttr): ?>
	<div class="card bg-white mb-2 <?= $enterpriseaAttr->page.' '.$enterpriseaAttr->albeticalPage ?>">
		<div class="product prod-first flex mx-1 px-1 py-3 flex-column">
			<span class="h5 semibold primary truncate enterprise-name"><?= $enterpriseaAttr->acnomcia; ?></span>
			<span class="my-1 h6 light text truncate"><?= $enterpriseaAttr->acdesc; ?></span>
			<span class="pt-1 h5 regular tertiary truncate id-fiscal">
				<?= lang('GEN_FISCAL_REGISTRY').': '.$enterpriseaAttr->acrif; ?>
			</span>
			<div class="mask flex mt-5 mx-1 pt-2 flex-column tertiary bg-white">
				<span class="product-pb h5 truncate total-product"><?= $enterpriseaAttr->resumenProductos ?></span>
				<span class="product-pb h5 truncate"><?= lang('GEN_CONTAC_PERSON').':'; ?></span>
				<span class="product-pb h5 truncate"><?= $enterpriseaAttr->acpercontac; ?></span>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>

<div class="pagination page-number flex mb-5 py-5 flex-auto justify-center">
	<nav class="h4">
		<a href="#">Primera</a>
		<a href="#">««</a>
		<a href="#">«</a>
	</nav>
	<div id="show-page" class="h4 flex justify-center ">
		<?php for($i=1; $i <= $recordsPage; $i++): ?>
		<span class="mx-1">
			<a href="javascript:" filter-page="page_<?= $i ?>"><?= $i; ?></a>
		</span>
		<?php endfor; ?>
	</div>
	<nav class="h4">
		<a href="#">»</a>
		<a href="#">»»</a>
		<a href="#">Última</a>
	</nav>
</div>

<div id="no-enterprise" class="bg-color mx-4 my-5">
	<div class="flex justify-center">
		<span class="my-5 py-5 h4 regular text">No tienes empresas asignadas</span>
	</div>
</div>
<form id="get_products" action="<?= base_url('productos') ?>" method="POST">
	<input type="hidden" name="<?= $novoName ?>" valule="<?= $novoCook ?>">
</form>
