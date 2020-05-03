<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="bg-img flex flex-column flex-auto justify-center items-center secondary">
	<span class="spinner-border spinner-border-lg secondary" role="status" aria-hidden="true"></span>
	<h3 class="mt-2">Ingresando ...
	</h3>
</div>
<form id="single-signin-form" action="<?= base_url('ingresar') ?>" method="post" send="<?= $send; ?>">
	<input type="hidden" id="tokenId" name="tokenId" value="<?= $tokenId ?>">
</form>
