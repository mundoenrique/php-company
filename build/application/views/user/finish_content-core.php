<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pt-3 px-5 pb-5">
	<div class="logout-content flex flex-column items-center justify-center">
		<h1 class="tertiary regular h2 my-4 ">Gracias por usar nuestros servicios</h1>
		<span class="tertiary regular h4 mb-5 "><?= $sessionEnd; ?></span>
		<?php if($showBtn): ?>
		<a class="btn btn-primary my-5 big-modal" href="<?= $action ?>">Aceptar</a>
		<?php endif; ?>
	</div>
</div>
