<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="bg-img flex flex-column flex-auto justify-center items-center secondary">
  <span class="spinner-border spinner-border-lg secondary" role="status" aria-hidden="true"></span>
  <h3 class="mt-2">Ingresando ...
  </h3>
</div>
<form id="single-signin-form" action="<?= base_url('ingresar') ?>" method="POST" submit="<?= $submit; ?>">
  <?php foreach ($form as $key => $value) : ?>
    <input type="hidden" id="<?= $key; ?>" name="<?= $key; ?>" value="<?= $value ?>">
  <?php endforeach; ?>
</form>