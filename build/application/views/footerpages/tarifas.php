<?php
$urlCdn = get_cdn();
?>
<div id="content-beneficioss">
  <h1>Tarifas</h1>
  <div class="content-beneficios">
    <div class="content-tarifas">
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
        <style>
          .content-tarifas * {
            box-sizing: border-box;
          }

          .background-brand-novopayment {
            background-color: #0b5ea9;
          }

          .background-brand-bonus {
            background-color: #7bc143;
          }

          .background-brand-plata {
            background-color: #8d9197;
          }

          .table-rates {
            border: 0;
            border-collapse: separate;
            border-spacing: 20px 0;
            width: 100%;
          }

          .table-caption {
            caption-side: bottom;
            color: #444;
            font-family: "Open Sans", sans-serif;
            font-size: 13px;
            font-weight: 300;
            padding: 10px 35px 0;
            text-align: left;
          }

          .table-caption p {
            line-height: 1.6;
            margin: 0;
            padding: 0;
          }

          .table-header {
            color: #fff;
            font-family: "Open Sans", sans-serif;
            font-size: 20px;
            font-weight: 400;
            height: 150px;
            line-height: 1.3;
            padding: 15px 15px 10px;
            position: relative;
            text-align: left;
            vertical-align: baseline;
          }

          .table-header-padded {
            padding-left: 100px;
          }

          .table-header img {
            border: 0;
            bottom: 15px;
            height: 70px;
            position: absolute;
            right: 20px;
            width: auto;
          }

          .table-rates tbody td {
            background-color: #fff;
            border-left: 1px solid #e6e6e6;
            border-right: 1px solid #e6e6e6;
            border-top: 1px solid #e6e6e6;
            color: #444;
            empty-cells: show;
            font-family: "Open Sans", sans-serif;
            font-size: 13px;
            font-weight: 300;
            line-height: 1.4;
            padding: 10px 13px;
            vertical-align: center;
          }

          .text-section {
            background-color: #b6bac0 !important;
            border: 0 !important;
            font-size: 14px !important;
            font-weight: 400 !important;
          }

          .text-section.center {
            text-align: center;
          }

          .text-subsection {
            background-color: #e6e6e6 !important;
            border: 0 !important;
            font-weight: 400 !important;
          }

          .text-description {
            color: #888;
            line-height: inherit;
            margin: 0;
            padding: 0;
          }

          .text-currency {
            font-size: 16px !important;
            font-weight: 400 !important;
            text-align: center;
          }

          .text-tag {
            display: inline-block;
            font-weight: 400 !important;
            margin-right: 5px;
            text-align: right;
            width: 15px;
          }

          .text-section .row,
          .text-currency .row {
            display: flex;
            justify-content: center;
            align-items: center;
          }

          .text-section .col-6,
          .text-currency .col-6 {
            float: left;
            width: 50%;
            text-align: center;
          }

          .icon-info-rates {
            font-size: 13px;
            color: #0b5ea9;
          }
        </style>
        <table cellpadding="0" cellspacing="0" class="table-rates">
          <?php if ($rates_refs) : ?>
            <caption class="table-caption">
              <?php foreach ($rates_refs as $ref) :
                $refTag = is_null($ref->tag) ? '' : '<span class="text-tag">' . $ref->tag . '</span>'; ?>
                <p><?php echo trim($refTag . ' ' . $ref->name); ?></p>
              <?php endforeach; ?>
            </caption>
          <?php endif; ?>
          <thead>
            <tr>
              <th class="table-header table-header-padded background-brand-novopayment" width="40%">
                Descripción
                <?php echo insert_image_cdn('rates/header-description.png'); ?>
              </th>
              <th class="table-header background-brand-bonus" width="30%">
                Bonus Alimentación<br>
                <?php echo insert_image_cdn('rates/header-bonus.svg'); ?>
              </th>
              <th class="table-header background-brand-plata" width="30%">
                Plata
                <?php echo insert_image_cdn('rates/header-plata.svg'); ?>
              </th>
            </tr>
          </thead>
          <?php if ($rates_data) : ?>
            <tbody>
              <?php foreach ($rates_data as $parent) : ?>
                <tr>
                  <td class="text-section">
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
                        <td>
                          <?php echo $child->name;
                          if (!is_null($child->description)) : ?>
                            <p class="text-description"><?php echo $child->description; ?></p>
                          <?php endif; ?>
                        </td>
                        <td class="text-currency">
                          <?php echo convert_rate($child->rates[0], $format_params); ?>
                        </td>
                        <td class="text-currency">
                          <?php echo convert_rate($child->rates[1], $format_params); ?>
                        </td>
                      </tr>
                    <?php else : ?>
                      <tr>
                        <td class="text-subsection">
                          <?php echo $child->name; ?>
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
          <tfoot>
            <tr>
              <td class="table-footer background-brand-novopayment">&nbsp;</td>
              <td class="table-footer background-brand-bonus">&nbsp;</td>
              <td class="table-footer background-brand-plata">&nbsp;</td>
            </tr>
          </tfoot>
        </table>
      <?php else : ?>
        <p><strong>¡Ha ocurrido un problema inesperado!</strong></p>
        <p>Por favor, consultar nuevamente nuestras tarifas en unos minutos.</p>
      <?php endif; ?>
      <div class="back-home">
        <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">
          <button>Regresar</button>
        </a>
      </div>
    </div>
  </div>

</div>