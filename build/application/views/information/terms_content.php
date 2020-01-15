<div id="content-condiciones">
	<?php if($newUser): ?>
	<h2><?= $message ?></h2>
	<?php endif; ?>
	<h1><?= lang("TITULO_TERMINOS") ?></h1>
	<?= lang("TERMINOS") ?>
	<?php if($goBack) : ?>
	<div style="margin-top:25px; text-align: center;">
		<a href="<?= $referer;?>"><button style="float: none;"><?= lang('GEN_BTN_BACK');?></button></a>
	</div>
	<?php endif; ?>
</div>
<?php if($newUser) : ?>
<div class="align-text-center">
	<span class="selected-option">
		<input type="checkbox" id="terms" name="terms" class="control-checkbox">
		<label for="terms"><?= lang('ACCEPT_TERMS');?></label>
	</span>
</div>
<?php endif; ?>
