<?php defined('BASEPATH') OR exit('No direct script access allowed');

$pais= $this->uri->segment(1, 0);
?>
<section class="container">
	<header class="padding-left-right">
		<h1><?= lang('GEN_RECOVER_PASS_TITLE'); ?></h1>
	</header>
	<article class="padding-left-right">
		<p class="paragraph"><?= novoLang(lang('RECOVER_PASS_FORGOTTEN'), lang('GEN_SYSTEM_NAME')); ?></p>
		<form id="form-pass-recovery" name="form-pass-recovery" accept-charset="utf-8">
			<fieldset class="recuperar-clave-fieldset">
				<div class="field-wrapper">
					<label for="user-name" class="line-field"><?= lang('GEN_USER'); ?></label>
					<input type="text" id="user-name" name="user-name" class="input-field field-large" maxlength="15" required>
				</div>
				<div class="field-wrapper">
					<label for="id-company" class="line-field">
						<?= novoLang(lang('RECOVER_PASS_FISCAL_REGISTRY'), lang('GEN_FISCAL_REGISTRY')); ?>
					</label>
					<input type="text" id="id-company" name="id-company" class="input-field field-large" maxlength="17"
						placeholder="<?= lang('PLACE_HOLDER_FISCAL_REGISTER'); ?>" required>
				</div>
				<div class="field-wrapper">
					<label for="email" class="line-field"><?= lang('GEN_EMAIL'); ?></label>
					<input type="text" id="email" name="email" class="input-field  field-large" maxlength="64"
						placeholder="<?= lang('GEN_PLACE_HOLDER_EMAIL') ?>" required>
				</div>
			</fieldset>
			<div class="form-actions">

			<?php	if($pais!='bp'): ?>
				<table style="float:right;">
			<?php else: ?>
				<center>
				<table>
			<?php endif; ?>
					<tr>
						<td valign="top">
						<?php	if($pais=='bp'): ?>
								<center>
						<?	endif; ?>
								<a class="cancel-anchor novo-btn-secondary novo-cancel-pass-recovery" href="<?= base_url('inicio') ?>"><?= lang('GEN_BTN_CANCEL'); ?></a>
						<?php	if($pais=='bp'): ?>
						<?php else:
								?>
								<button id="btn-pass-recover" class="novo-btn-primary"><?= lang('GEN_BTN_CONTINUE'); ?></button><?php
						endif; ?>
						</td>
						<td valign="top">
						<?php	if($pais=='bp'): ?>
								<button id="btn-pass-recover" class="novo-btn-primary">
								<a class="cancel-anchor" href="<?= base_url('inicio') ?>"><?= lang('GEN_BTN_CONTINUE'); ?></a>
								</button>
								</center>
						<?	endif; ?>
					 </td>
					</tr>
				</table>
			<?php	if($pais=='bp'): ?>
			<center>
			<?php endif; ?>
			<div class="content-t">

			</div>
				</div>
		</form>
	</article>
</section>
