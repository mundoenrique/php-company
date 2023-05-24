<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="logout-content max-width-5 mx-auto p-responsive py-4">
	<h1 class="primary h0"><?= lang('GEN_FOTTER_RATES'); ?></h1>
	<section>
		<hr class="separador-one">
		<div id="pre-loader" class="mx-auto flex justify-center">
			<span class="spinner-border spinner-border-lg my-2" role="status" aria-hidden="true"></span>
		</div>
		<div class="pt-3 hide-out hide">
			<?php if ($json_data) : ?>
				<div class="flex flex-auto flex-column">
					<div class="center mx-0">
						<table id="rates" class="cell-border h6 display rates text">
							<thead class="regular h4">
								<tr>
									<th class="col-4 bg-card-icon">
										<?= lang('GEN_DESCRIPTION') ?>
									</th>
									<th class="col-4 bg-card-bonus">
										<?= lang('GEN_BONUS_CARD') ?>
									</th>
									<th class="col-4 bg-card-plata">
										<?= lang('GEN_SILVER_CARD') ?>
									</th>
								</tr>
							</thead>
							<?php if ($rates_data) : ?>
								<tbody>
									<?php foreach ($rates_data as $parent) : ?>
										<tr>
											<td class="text-left text-section h5 semibold">
												<?php echo $parent->name; ?>
											</td>
											<td class="text-section center">
												<span>Bs.</span>
											</td>
											<td class="text-section center">
												<span>Bs.</span>
											</td>
										</tr>
										<?php if ($parent->items) :
										foreach ($parent->items as $child) :
											if ($child->rates) : ?>
												<tr>
													<td class="text-left">
														<span class="h5">
															<?php echo $child->name;
															if (!is_null($child->description)) : ?>
																<p class="regular"><?php echo $child->description; ?></p>
															<?php endif; ?>
														</span>
													</td>
													<td class="h4">
														<?php echo $child->rates[0]; ?>
													</td>
													<td class="h4">
														<?php echo $child->rates[1]; ?>
													</td>
												</tr>
											<?php else : ?>
												<tr class="subsection">
													<td class="text-left">
														<span class="semibold"><?php echo $child->name; ?></span>
													</td>
													<td class="text-subsection"></td>
													<td class="text-subsection"></td>
												</tr>
											<?php endif;
										endforeach;
										endif;
									endforeach; ?>
								</tbody>
							<?php endif; ?>
						</table>
						<?php if ($rates_refs) : ?>
							<div class="mx-3 h3">
								<?php foreach ($rates_refs as $ref) :
									$refTag = is_null($ref->tag) ? '' : '<span class="text-tag">' . $ref->tag . '</span>'; ?>
									<div class="flex mt-2 items-center">
										<div class="icon-square bg-vista-blue" alt=""></div>
										<span class="pl-1 h6"><?php echo trim($refTag . ' ' . $ref->name); ?></span>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="flex items-center justify-center pt-3">
					<a class="btn btn-link btn-small big-modal" href="javascript:history.back()"><?= lang('GEN_BTN_BACK'); ?></a>
				</div>
			<?php else : ?>
				<div class="center">
					<?= lang('ERROR_MSG_RATES'); ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
</div>

