<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<span class="primary">Buenos dias <?= $fullName ?></span>
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
		<span>Último acceso: 11/08/2019 a las 19:12:35</span>
	</div>
</div>

<div class="flex mt-3 light items-center">
	<div class="flex h5">
		<span>Total Empresas: 4</span>
	</div>
	<div id="alphabetical"  class="flex h6 flex-auto justify-end">
		<button class="btn btn-outline btn-small btn-rounded-left bg-white" filter-page="page_1">TODOS</button>
		<button class="btn-options btn-outline bold bg-white" filter-page="A-C_1">A-C</button>
		<button class="btn-options btn-outline bold bg-white" filter-page="D-G_1">D-G</button>
		<button class="btn-options btn-outline bold bg-white" filter-page="H-K_1">H-K</button>
		<button class="btn-options btn-outline bold bg-white" filter-page="L-O_1">L-O</button>
		<button class="btn-options btn-outline bold bg-white" filter-page="P-S_1">P-S</button>
		<button class="btn-options btn-outline bold bg-white" filter-page="T-W_1">T-W</button>
		<button class="btn-options btn-outline bold bg-white" filter-page="X-Z_1">X-Z</button>
		<button class="btn-search btn-outline bg-white"></button>
	</div>
</div>
<div class="line mt-1"></div>

<div id="enterprise-list" class="flex my-5 pt-2 justify-center flex-wrap" record-page="<?= $recordsPage; ?>">

	<?php foreach($enterpriseList AS $enterpriseaAttr): ?>
	<div class="card bg-white m-2 <?= $enterpriseaAttr->category.' '.$enterpriseaAttr->page.' '.$enterpriseaAttr->albeticalPage ?>">
		<div class="product prod-first flex mx-1 px-1 py-3 flex-column">
			<span class="h5 semibold primary truncate"><?= $enterpriseaAttr->acnomcia; ?></span>
			<span class="my-1 h6 light text truncate"><?= $enterpriseaAttr->acdesc; ?></span>
			<span class="pt-1 h5 regular tertiary truncate">
				<?= lang('GEN_FISCAL_REGISTRY').' '.$enterpriseaAttr->acrif; ?>
			</span>
			<div class="mask flex mt-5 mx-1 pt-2 flex-column tertiary bg-white">
				<span class="product-pb h5 truncate"><?= $enterpriseaAttr->resumenProductos ?></span>
				<span class="product-pb h5 truncate"><?= lang('GEN_CONTAC_PERSON').':'; ?></span>
				<span class="product-pb h5 truncate"><?= $enterpriseaAttr->acpercontac; ?></span>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>

<div>
	<div class="pagination flex flex-auto justify-center my-5 py-5">

		<nav class="h4">
			<a href="#">Primera</a>
			<a href="#">««</a>
			<a href="#">«</a>
		</nav>

		<div id="show-page" class="h4">
			<?php for($i=1; $i <= $recordsPage; $i++): ?>
			<span>
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
</div>
