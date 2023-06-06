<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular"><?= $greeting.' '.$fullName ?></h1>
<div class="flex mb-2 light items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 light h6 tertiary list-style-none list-inline ">
				<li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li>
			</ul>
		</nav>
	</div>
	<div class="flex h6 flex-auto justify-end">
		<span><?= lang('BUSINESS_ENTERPRISE_LAST_ACCESS') ?>: <?= $lastSession ?></span>
	</div>
</div>

<div class="flex mt-3 light items-center">
	<div class="flex col-3">
		<span><?= lang('BUSINESS_ENTERPRISE_TOTAL') ?>: <?= $enterprisesTotal ?></span>
	</div>
	<div id="alphabetical" class="flex h6 flex-auto justify-end">
		<button class="btn btn-outline btn-small btn-rounded-left bg-white" filter-page="page_1" <?= $disabled ?>>
			<?= lang('BUSINESS_ENTERPRISE_FILTER_ALL'); ?>
		</button>
		<?php foreach($filters as $filtersAttr): ?>
		<button class="btn-options btn-outline bold bg-white" filter-page="<?= $filtersAttr['filter'] ?>"
			<?= $filtersAttr['active'] ? '' : 'disabled' ?>><?= $filtersAttr['text']; ?></button>
		<?php endforeach; ?>
		<div id="sb-search" class="sb-search">
			<input id="search" class="sb-search-input" type="search" name="search" value="" placeholder="Buscar...">
			<span class="sb-icon-search"><i class="icon icon-find mr-1"></i></span>
		</div>
	</div>
</div>
<div class="line mt-1"></div>

<div id="pre-loader" class="mt-5 mx-auto flex justify-center">
	<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
</div>

<div id="no-enterprise" class="mx-4 my-5">
	<div class="flex justify-center">
		<span class="my-5 py-5 h4 regular text"><?= $msgEnterprise ?></span>
	</div>
</div>
<div id="enterprise-list" class="products my-5 <?= $enterprisesTotal < 5 ? '' : 'mx-auto';?> pt-2 visible">
	<?php foreach($enterpriseList AS $enterpriseaAttr): ?>
	<div class="card bg-white mb-2 <?= $enterpriseaAttr->page.' '.$enterpriseaAttr->albeticalPage ?>">
		<div class="product prod-first flex mx-1 px-1 py-3 flex-column">
			<span class="semibold primary truncate"><?= $enterpriseaAttr->acnomcia; ?></span>
			<span class="my-1 h6 light text truncate"><?= $enterpriseaAttr->acdesc; ?></span>
			<span class="pt-1 regular tertiary truncate">
				<?= lang('GEN_FISCAL_REGISTRY').' '.$enterpriseaAttr->acrif; ?>
			</span>
			<div class="mask flex mt-5 mx-1 pt-2 flex-column tertiary bg-white">
				<?php $danger = strpos($enterpriseaAttr->resumenProductos, '0') !== FALSE ? ' danger' : FALSE; ?>
				<span class="product-pb truncate<?= $danger; ?> total-product">
					<?= $enterpriseaAttr->resumenProductos ?>
				</span>
				<span class="product-pb truncate"><?= lang('GEN_CONTAC_PERSON').':'; ?></span>
				<span class="product-pb truncate"><?= $enterpriseaAttr->acpercontac; ?></span>
			</div>
		</div>
		<form id="enterprise-<?= $enterpriseaAttr->accodcia; ?>" action="<?= base_url(lang('SETT_LINK_PRODUCTS')) ?>" method="POST">
			<input type="hidden" name="enterpriseCode" value="<?= $enterpriseaAttr->accodcia; ?>">
			<input type="hidden" name="enterpriseGroup" value="<?= $enterpriseaAttr->accodgrupoe; ?>">
			<input type="hidden" name="idFiscal" value="<?= $enterpriseaAttr->acrif; ?>">
			<input type="hidden" name="enterpriseName" value="<?= $enterpriseaAttr->acnomcia; ?>">
			<input type="hidden" name="thirdApp" value="<?= $enterpriseaAttr->acobservacion ?? 'ANY'; ?>">
			<input type="hidden" name="fiscalNumber" value="<?= $enterpriseaAttr->acnit; ?>">
		</form>
	</div>
	<?php endforeach; ?>
</div>

<div id="enterprise-pages" class="visible">
	<div class="pagination page-number flex mb-5 py-5 flex-auto justify-center">
		<nav class="h4">
			<a href="javascript:" position="first"><?= lang('BUSINESS_ENTERPRISE_FIRST_PAGE'); ?></a>
			<a href="javascript:" position="prev">«</a>
		</nav>
		<div id="show-page" class="h4 flex justify-center ">
			<?php for($i=1; $i <= $recordsPage; $i++): ?>
			<span class="mx-1">
				<a href="javascript:" position="page" filter-page="page_"><?= $i; ?></a>
			</span>
			<?php endfor; ?>
		</div>
		<nav class="h4">
			<a href="javascript:" position="next">»</a>
			<a href="javascript:" position="last"><?= lang('BUSINESS_ENTERPRISE_LAST_PAGE'); ?></a>
		</nav>
	</div>
</div>
