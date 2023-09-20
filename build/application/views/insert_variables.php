<?php
defined('BASEPATH') or exit('No direct script access allowed');

$dataclient = new stdClass();
$dataclient->response = $response ?? NULL;
$dataclient->dataTableLang = NULL;
$dataclient->datePickerLang = NULL;
$dataclient->baseURL = base_url();
$dataclient->assetUrl = assetUrl();
$dataclient->logged = $logged;
$dataclient->userId = $userId;
$dataclient->customerUri = $customerUri;
$dataclient->customer = $customer;
$dataclient->sessionTime = $sessionTime;
$dataclient->callServer = $callServer;
$dataclient->lang = $this->lang->language;

$customerData = encryptData($dataclient);

?>
<section id="calledCoreApp"></section>
<script>
	const activeSafety = <?= json_encode(ACTIVE_SAFETY); ?>;
	const assetsClient = <?= $customerData; ?>;
</script>