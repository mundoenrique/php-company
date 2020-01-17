<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pt-3 pb-5 px-5">
	<div class="logout-content flex flex-column items-center justify-center">
		<h1 class="my-4 tertiary regular">Gracias por usar nuestros servicios</h1>
		<span class="mb-5 light h4"><?= $sessionEnd; ?></span>
		<?php if($showBtn): ?>
		<a class="mb-5 pb-1 btn btn-primary big-modal" href="<?= $action ?>">Aceptar</a>
		<?php endif; ?>
	</div>
</div>
