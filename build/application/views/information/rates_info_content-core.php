<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $urlCdn = get_cdn(); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_FOTTER_RATES') ?></h1>
<div class="flex mt-1 bg-color flex-nowrap justify-between">
	<div id="pre-loader" class="mt-2 mx-auto">
		<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
	</div>
	<div class="w-100 hide-out hide mt-3">
		<?php
		$json_file = file_get_contents(ASSET_PATH . 'data/ve/rates.json');
		$json_data = json_decode($json_file);

		$rates_currency = $json_data->currency;
		$rates_currency_symbol = $json_data->currency_symbol;
		$rates_last_update = $json_data->last_update;
		$rates_refs = $json_data->refs;
		$rates_data = $json_data->data;

		$format_decimals = 2;
		$format_dec_point = '.';
		$format_thousands_sep = ',';
		if ($rates_currency === 'cop' || $rates_currency === 'ves') {
			$format_dec_point = ',';
			$format_thousands_sep = '.';
		}
		$format_params = (object)[
			'currency_symbol' => $rates_currency_symbol,
			'decimals' => $format_decimals,
			'dec_point' => $format_dec_point,
			'thousands_sep' => $format_thousands_sep
		];

		// Function to convert rate values to its adequate local format
		function convert_rate($rate, $params)
		{
			$rate_currency_symbol = $params->currency_symbol;
			$rate_type = strtolower(gettype($rate));

			switch ($rate_type) {
				case 'double':
					$rate_converted = $rate_currency_symbol . ' ' . number_format(
						$rate,
						$params->decimals,
						$params->dec_point,
						$params->thousands_sep
					);
					break;
				case 'integer':
					$rate_converted = $rate_currency_symbol . ' ' . number_format(
						$rate,
						0,
						$params->dec_point,
						$params->thousands_sep
					);
					break;
				case 'null':
					$rate_converted = 'N/A';
					break;
				default:
					$rate_converted = $rate;
			}
			return $rate_converted;
		}

		if ($json_data) :
		?>
			<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400&display=swap">
			<div class="flex flex-auto flex-column">
				<div class="center mx-1">
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
										<td class="text-left text-section"></td>
										<td class="text-left text-section"></td>
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
											<?php echo convert_rate($child->rates[0], $format_params); ?>
										</td>
										<td class="h4">
											<?php echo convert_rate($child->rates[1], $format_params); ?>
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
		<?php else : ?>
			<div class="center">
				<p><strong>¡Ha ocurrido un problema inesperado!</strong></p>
				<p>Por favor, consultar nuevamente nuestras tarifas en unos minutos.</p>
			</div>
		<?php endif; ?>
	</div>
</div>
