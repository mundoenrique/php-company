<?php
defined('BASEPATH') or exit('No direct script access allowed');

$dataclient = new stdClass();
// deprecated for compatibility only
$dataclient->oldCustomerUri = $this->config->item('customer');
$dataclient->code = $code ?? NULL;
$dataclient->title = $title ?? NULL;
$dataclient->msg = $msg ?? NULL;
$dataclient->icon = $icon ?? NULL;
$dataclient->data = $data ?? NULL;
$dataclient->params = $params ?? NULL;
$dataclient->callModal = $callModal ?? NULL;
$dataclient->inputModal = NULL;
$dataclient->cypherPass = NULL;
$dataclient->defaultCode = NULL;
$dataclient->btnText = NULL;
$dataclient->dataResponse = NULL;
$dataclient->cpo_cook = NULL;
$dataclient->modalBtn = $modalBtn ?? NULL;
$dataclient->form = NULL;
$dataclient->validatePass = NULL;
$dataclient->currentDate = NULL;
$dataclient->otpActive = $this->session->otpActive;
$dataclient->otpChannel = $this->session->otpChannel;
$dataclient->otpMfaAuth = $this->session->otpMfaAuth;
// --------------------------------
$dataclient->who = NULL;
$dataclient->where = NULL;
$dataclient->btnContent = NULL;
$dataclient->loader = NULL;
$dataclient->dataTableLang = NULL;
$dataclient->datePickerLang = NULL;
$dataclient->response = $response ?? NULL;
$dataclient->baseURL = base_url();
$dataclient->assetUrl = assetUrl();
$dataclient->redirectLink = uriRedirect($singleSession);
$dataclient->logged = $logged;
$dataclient->userId = $userId;
$dataclient->customerUri = $customerUri;
$dataclient->sessionTime = $sessionTime;
$dataclient->callServer = $callServer;
$dataclient->lang = $this->lang->language;

$customerData = encryptData($dataclient);

?>
<section id="calledCoreApp"></section>
<script>
	const assetsClient = <?= $customerData; ?>;
	const activeSafety = <?= json_encode(ACTIVE_SAFETY); ?>;
	const cryptography = {};
</script>