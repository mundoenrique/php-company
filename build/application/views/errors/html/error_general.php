<?php
defined('BASEPATH') or exit('No direct script access allowed');

define(ENVIRONMENT, $_SERVER['CI_ENV']);
define(BASE_URL, $_SERVER['BASE_URL']);
define(ASSET_URL, $_SERVER['ASSET_URL']);
define(ASSET_PATH, $_SERVER['ASSET_PATH']);

require_once('error_helpers.php');

$country = get_country();
$stylesheet = 'novo-errors.css';
if ($country === 'Ec-bp') {
  $stylesheet = 'pichincha-errors.css';
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Error General</title>
  <meta http-equiv="cleartype" content="on">
  <meta name="googlebot" content="none">
  <meta name="robots" content="noindex, nofollow">
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <?php
  echo insert_favicon($country);
  echo insert_css($stylesheet);
  echo insert_js('third_party/html5.min.js');
  ?>
</head>

<body>
  <header id="head">
    <div id="head-wrapper">
      <a id="branding" rel="start"></a>
    </div>
  </header>
  <div id="wrapper">
    <div id="content">
      <h1>Error General</h1>
      <p>Ha ocurrido un problema técnico inesperado. Regrese a la página anterior e intenta nuevamente.</p>
      <a class="button" href="#" id="history-back">Regresar</a>
    </div>
  </div>
  <footer id="foot">
    <div id="foot-wrapper">
      <a id="ownership" href="http://www.novopayment.com/" rel="me">Powered by NovoPayment, Inc.</a>
      <div class="separator"></div>
      <div id="credits">
        <p>© <?php echo date('Y'); ?> NovoPayment Inc. All rights reserved.</p>
      </div>
    </div>
  </footer>
  <?php
  echo insert_js('third_party/jquery-3.6.0.min.js');
  ?>
  <script>
    $('#history-back').on('click', function(event) {
      event.preventDefault();

      window.history.back();
    });
  </script>
</body>

</html>