<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($logged): ?>
<footer class="main-footer">
	<div class="flex pr-2 pr-lg-0">
		<img src="<?= $this->asset->insertFile('logo/logo-banco-bog-superint.svg'); ?>" alt="Logo Superintendencia">
	</div>
	<div class="flex flex-auto flex-wrap justify-around items-center">
		<img class="order-first" src="<?= $this->asset->insertFile('logo/logo-banco-bog-white.svg'); ?>"
			alt="Logo Banco de Bogotá">
		<img class="order-1" src="<?= $this->asset->insertFile('logo/logo-banco-bog-pci_compliance.svg'); ?>"
			alt="Logo PCI">
		<span class="copyright-footer mt-1 nowrap flex-auto lg-flex-none order-1 order-lg-0 center h6">© Todos los derechos
			reservados. Banco de Bogotá - 2019.</span>
	</div>
</footer>
<?php endif; ?>
