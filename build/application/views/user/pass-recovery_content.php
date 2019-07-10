<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="container">
	<header class="padding-left-right">
		<h1>Recuperar contraseña</h1>
	</header>
	<style>


/**Estilos de los botones NOVO NEW****/

.novo-btn-primary,.novo-btn-secondary{
	width:120px;
	padding-left:15px;
	padding-right:15px;
}
.novo-btn-primary-modal,.novo-btn-secondary-modal{
	width:68px;
	padding-left:15px;
	padding-right:15px;
}

.novo-btn-primary:hover{
	background: url('../img/BackgroundButtonHover.png') left center no-repeat #ffdd00;
}
.novo-btn-primary:active{
	background: #ffd000;
}
.novo-btn-primary-modal:hover{
	background: url('../img/BackgroundButtonHover.png') left center no-repeat #ffdd00;
}

.novo-btn-secondary,.novo-btn-secondary-modal{
	border: 1px solid #ffdd00;
	background:white !important;
}
/* .novo-btn-secondary-modal{
	background:white;
} */
.novo-btn-secondary:hover{
	border: 1px solid #ffdd00 !important;
	background-color: #fffede !important;
}
.novo-btn-secondary:active{
	border: 1px solid #ffdd00 !important;
	color:#10133f;
	background-color: #fdfbb5 !important;
}

.novo-btn-secondary-modal:hover{

	background:#fffede !important;
	border: 1px solid #ffdd00 !important;
}
.novo-btn-secondary-modal:active{

	background:#fdfbb5 !important;
	border: 1px solid #ffdd00 !important;
}
.btn-authorization{
	margin-bottom: 10px;
	margin-top: 10px;
	border: 1px solid #ffdd00;
}

	</style>
	<article class="padding-left-right">
		<p class="paragraph"><?= lang('FORGOT_PASS'); ?></p>
		<form id="form-pass-recovery" name="form-pass-recovery" accept-charset="utf-8">
			<fieldset class="recuperar-clave-fieldset">
				<div class="field-wrapper">
					<label for="user-name" class="line-field"><?= lang('USER_USER'); ?></label>
					<input type="text" id="user-name" name="user-name" class="input-field field-large" maxlength="15" required>
				</div>
				<div class="field-wrapper">
					<label for="id-company" class="line-field"><?= lang('RIF_NIT'); ?></label>
					<input type="text" id="id-company" name="id-company" class="input-field field-large" maxlength="17"
						placeholder="<?= lang('PLACE_HOLDER_NIT'); ?>"  required>
				</div>
				<div class="field-wrapper">
					<label for="email" class="line-field"><?= lang('MAIL'); ?></label>
					<input type="text" id="email" name="email" class="input-field  field-large" maxlength="64"
						placeholder="<?= lang('PLACE_HOLDER_MAIL') ?>" required>
				</div>
			</fieldset>
			<div class="form-actions">

			<?php
			//	echo "sdjkha".base_url('inicio');
			$pais=$this->urlCountry = $this->uri->segment(1, 0);
				if($pais=='bp'){
					?>
						<center>
					<?php
				}
			?>
			<button class="novo-btn-secondary">
				<a class="cancel-anchor  r-button " href="<?= base_url('inicio') ?>" style="display:inline;height:;">Cancelar</a></button>
				<button id="btn-pass-recover" class="r-button novo-btn-primary">Continuar</button>
				<?php if($pais=='bp'){
					?>
						</center>
					<?php
				}?>
			</div>
		</form>
	</article>
</section>
