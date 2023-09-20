<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script>
	var lang = <?php print_r(json_encode($this->lang->language)); ?>;
	var baseURL = '<?= base_url(); ?>';
	var assetUrl = '<?= assetUrl(); ?>';
	var customerUri = '<?= $customerUri; ?>';
	var customerStyle = '<?= $customerStyle; ?>';
	var oldCustomerUri = '<?= $this->config->item('customer'); ?>';
	var code = <?= $code ?? 0; ?>;
	var title = '<?= $title ?? ' '; ?>';
	var msg = '<?= $msg ?? ' '; ?>';
	var icon = '<?= $icon ?? ' '; ?>';
	var modalBtn = <?= $modalBtn ?? 0; ?>;
	var params = <?= $params ?? 0; ?>;
	var logged = <?= json_encode($this->session->has_userdata('logged')); ?>;
	var sessionTime = <?= $sessionTime; ?>;
	var callModal = <?= $callModal; ?>;
	var callServer = <?= $callServer; ?>;
	var data;
	const activeSafety = <?= json_encode(ACTIVE_SAFETY); ?>;
</script>