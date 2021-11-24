<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="logout-content max-width-5 mx-auto p-responsive py-4">
	<?php if($newUser): ?>
	<h5 class="mt-1"><?= $message ?></h5>
	<?php endif; ?>
	<h1 class="h0"><?= lang("TERMS_TITLE") ?></h1>
	<hr class="separador-one">
	<div class="pt-3">
	<?= lang("TERMS_CONTENT") ?>
	<section>
		<div class="pt-3">
			<?php if($newUser): ?>
			</div>
			<hr class="separador-one">
				<div class="flex flex-column mt-4 px-5 justify-center items-center">
					<div class="flex flex-row">
						<div class="mb-3 mr-3">
							<a href="<?= base_url($goOut); ?>" class="btn btn-link btn-small big-modal"><?= lang('GEN_BTN_CANCEL'); ?></a>
						</div>
						<div class="mb-3 mr-1 custom-switch">
							<input id="terms" name="terms" class="custom-control-input" type="checkbox">
							<label class="custom-control-label" for="terms"><?= lang('TERMS_ACCEPT');?></label>
						</div>
					</div>
				</div>
				<?php endif; ?>
				<hr class="separador-one">
				<div class="flex items-center justify-center pt-3">
					<a class="btn btn-link btn-small big-modal" href="javascript:history.back()"><?= lang('GEN_BTN_BACK'); ?></a>
				</div>
			</div>
		</div>
	</section>
</div>


