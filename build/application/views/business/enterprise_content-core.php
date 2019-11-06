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



<div class="none">

	<h1>Isotope - filtering &amp; sorting</h1>
	<h2>Filter</h2>
	<div id="filters" class="button-group">
		<button class="button is-checked" data-filter="*">show all</button>
		<button class="button" data-filter=".metal">metal</button>
		<button class="button" data-filter=".transition">transition</button>
		<button class="button" data-filter=".alkali, .alkaline-earth">alkali and alkaline-earth</button>
		<button class="button" data-filter=":not(.transition)">not transition</button>
		<button class="button" data-filter=".metal:not(.transition)">metal but not transition</button>
		<button class="button" data-filter="numberGreaterThan50">number > 50</button>
		<button class="button" data-filter="ium">name ends with &ndash;ium</button>
	</div>

	<h2>Sort</h2>
	<div id="sorts" class="button-group">
		<button class="button is-checked" data-sort-by="original-order">original order</button>
		<button class="button" data-sort-by="name">name</button>
		<button class="button" data-sort-by="symbol">symbol</button>
		<button class="button" data-sort-by="number">number</button>
		<button class="button" data-sort-by="weight">weight</button>
		<button class="button" data-sort-by="category">category</button>
	</div>

	<div class="grid">
		<div class="element-item transition metal " data-category="transition">
			<h3 class="name">Mercury</h3>
			<p class="symbol">Hg</p>
			<p class="number">80</p>
			<p class="weight">200.59</p>
		</div>
		<div class="element-item metalloid " data-category="metalloid">
			<h3 class="name">Tellurium</h3>
			<p class="symbol">Te</p>
			<p class="number">52</p>
			<p class="weight">127.6</p>
		</div>
		<div class="element-item post-transition metal " data-category="post-transition">
			<h3 class="name">Bismuth</h3>
			<p class="symbol">Bi</p>
			<p class="number">83</p>
			<p class="weight">208.980</p>
		</div>
		<div class="element-item post-transition metal " data-category="post-transition">
			<h3 class="name">Lead</h3>
			<p class="symbol">Pb</p>
			<p class="number">82</p>
			<p class="weight">207.2</p>
		</div>
		<div class="element-item transition metal " data-category="transition">
			<h3 class="name">Gold</h3>
			<p class="symbol">Au</p>
			<p class="number">79</p>
			<p class="weight">196.967</p>
		</div>
		<div class="element-item alkali metal " data-category="alkali">
			<h3 class="name">Potassium</h3>
			<p class="symbol">K</p>
			<p class="number">19</p>
			<p class="weight">39.0983</p>
		</div>
		<div class="element-item alkali metal " data-category="alkali">
			<h3 class="name">Sodium</h3>
			<p class="symbol">Na</p>
			<p class="number">11</p>
			<p class="weight">22.99</p>
		</div>
		<div class="element-item transition metal " data-category="transition">
			<h3 class="name">Cadmium</h3>
			<p class="symbol">Cd</p>
			<p class="number">48</p>
			<p class="weight">112.411</p>
		</div>
		<div class="element-item alkaline-earth metal " data-category="alkaline-earth">
			<h3 class="name">Calcium</h3>
			<p class="symbol">Ca</p>
			<p class="number">20</p>
			<p class="weight">40.078</p>
		</div>
		<div class="element-item transition metal " data-category="transition">
			<h3 class="name">Rhenium</h3>
			<p class="symbol">Re</p>
			<p class="number">75</p>
			<p class="weight">186.207</p>
		</div>
		<div class="element-item post-transition metal " data-category="post-transition">
			<h3 class="name">Thallium</h3>
			<p class="symbol">Tl</p>
			<p class="number">81</p>
			<p class="weight">204.383</p>
		</div>
		<div class="element-item metalloid " data-category="metalloid">
			<h3 class="name">Antimony</h3>
			<p class="symbol">Sb</p>
			<p class="number">51</p>
			<p class="weight">121.76</p>
		</div>
		<div class="element-item transition metal " data-category="transition">
			<h3 class="name">Cobalt</h3>
			<p class="symbol">Co</p>
			<p class="number">27</p>
			<p class="weight">58.933</p>
		</div>
		<div class="element-item lanthanoid metal inner-transition " data-category="lanthanoid">
			<h3 class="name">Ytterbium</h3>
			<p class="symbol">Yb</p>
			<p class="number">70</p>
			<p class="weight">173.054</p>
		</div>
		<div class="element-item noble-gas nonmetal " data-category="noble-gas">
			<h3 class="name">Argon</h3>
			<p class="symbol">Ar</p>
			<p class="number">18</p>
			<p class="weight">39.948</p>
		</div>
		<div class="element-item diatomic nonmetal " data-category="diatomic">
			<h3 class="name">Nitrogen</h3>
			<p class="symbol">N</p>
			<p class="number">7</p>
			<p class="weight">14.007</p>
		</div>
		<div class="element-item actinoid metal inner-transition " data-category="actinoid">
			<h3 class="name">Uranium</h3>
			<p class="symbol">U</p>
			<p class="number">92</p>
			<p class="weight">238.029</p>
		</div>
		<div class="element-item actinoid metal inner-transition " data-category="actinoid">
			<h3 class="name">Plutonium</h3>
			<p class="symbol">Pu</p>
			<p class="number">94</p>
			<p class="weight">(244)</p>
		</div>
	</div>
</div>
