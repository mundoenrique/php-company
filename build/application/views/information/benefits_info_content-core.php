<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="pt-3 pb-5">
	<div class="logout-content max-width-5 mx-auto p-responsive">
		<h1 class="primary h0"><?= lang('TITLE_BENEFITS'); ?></h1>
		<section>
			<hr class="separador-one">
			<div id="pre-loader" class="mx-auto flex justify-center">
				<span class="spinner-border spinner-border-lg my-2" role="status" aria-hidden="true"></span>
			</div>
			<div class="pt-3 hide-out hide">
				<div class="fit-lg mx-auto pt-3">
					<div class="row mb-2">
						<div class="col-4 text center">
							<div class="flex flex-column justify-center items-center card bg-white w-100 h-100 p-1">
								<i class="icon-lock mr-0 h00 primary"></i>
								<h5><?= lang('BENEFITS_TITULO_1'); ?></h5>
								<p><?= lang('BENEFITS_MSG_1'); ?></p>
							</div>
						</div>
						<div class="col-4 text center">
							<div class="flex flex-column justify-center items-center card bg-white w-100 h-100 p-1">
								<i class="icon-clock mr-0 h00 primary"></i>
								<h5><?= lang('BENEFITS_TITULO_2'); ?></h5>
								<p><?= lang('BENEFITS_MSG_2'); ?></p>
							</div>
						</div>
						<div class="col-4 text center">
							<div class="flex flex-column justify-center items-center card bg-white w-100 h-100 p-1">
								<i class="icon-reload mr-0 h00 primary"></i>
								<h5><?= lang('BENEFITS_TITULO_3'); ?></h5>
								<p><?= lang('BENEFITS_MSG_3'); ?></p>
							</div>
						</div>
					</div>

					<div class="row mb-2">
						<div class="col-4 text center">
							<div class="flex flex-column justify-center items-center card bg-white w-100 h-100 p-1">
								<i class="icon-phone mr-0 h00 primary"></i>
								<h5><?= lang('BENEFITS_TITULO_4'); ?></h5>
								<p><?= lang('BENEFITS_MSG_4'); ?></p>
							</div>
						</div>
						<div class="col-4 text center">
							<div class="flex flex-column justify-center items-center card bg-white w-100 h-100 p-1">
								<i class="icon-plus mr-0 h00 primary"></i>
								<h5><?= lang('BENEFITS_TITULO_5'); ?></h5>
								<p><?= lang('BENEFITS_MSG_5'); ?></p>
							</div>
						</div>
						<div class="col-4 text center">
							<div class="flex flex-column justify-center items-center card bg-white w-100 h-100 p-1">
								<i class="icon-integration mr-0 h00 primary"></i>
								<h5><?= lang('BENEFITS_TITULO_6'); ?></h5>
								<p><?= lang('BENEFITS_MSG_6'); ?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="flex items-center justify-center pt-3">
					<a class="btn btn-link btn-small big-modal" href="javascript:history.back()"><?= lang('GEN_BTN_BACK'); ?></a>
				</div>
			</div>
		</section>
	</div>
</div>
