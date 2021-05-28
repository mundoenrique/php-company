'use strict'
var rechargeParam;
var checkType;

$(function () {
	if (params && code == 0) {
		rechargeParam = params;

		$("#pay").prop("checked", true);
		checkType = $("input:radio[name=transferType]:checked").val();

		$("input[name=transferType]").click(function() {
			 checkType = $("input:radio[name=transferType]:checked").val();
		})
		
		$('#transferAmount').mask('#' + lang.GEN_THOUSANDS + '##0' + lang.GEN_DECIMAL + '00', { reverse: true });
		$('#transferAmount').on('keyup', function() {
			$(this).val(function(_index, value) {

				if (value.indexOf('0') != -1 && value.indexOf('0') == 0) {
					value = value.replace(0, '');
				}

				if (value.length == 1 && /^[0-9,.]+$/.test(value)) {
					value = '00' + lang.GEN_DECIMAL + value
				}

				return value
			})
		});
		
		$('#masterAccountRechargeBtn').on('click', function(e) {
			e.preventDefault();
			form = $('#masterAccountRechargeForm');
			btnText = $(this).text();
			validateForms(form);

			if (form.valid()) {
				data = getDataForm(form);
				data.transferAmount = normalizeAmount(data.transferAmount);
				$(this).html(loader);
				insertFormInput(true);

				if (lang.CONF_INPUT_PASS == 'OFF') {
					getTokenRecharge();
				} else {
					rechargeAccount();
				}
			}
		});

		$('#system-info').on('click', '.send-otp', function() {
			form = $('#formVerificationOTP');
			formInputTrim(form);
			validateForms(form);
	
			if (form.valid()) {
				$(this)
					.html(loader)
					.prop('disabled', true)
					.removeClass('send-otp');
				insertFormInput(true);

				rechargeAccount();
			}
		});
	}
});

function getTokenRecharge() {
	verb = 'POST'; who = 'Services'; where = 'RechargeAuthorization';

	callNovoCore(verb, who, where, data, function (response) {
		$('#masterAccountRechargeBtn').html(btnText);
		switch (response.code) {
			case 0:
				$('#accept').addClass('send-otp');
				inputModal = '<form id="formVerificationOTP" name="formVerificationOTP" class="mr-2" method="post" ';
				inputModal +=  'onsubmit="return false">';
				inputModal += 		'<p class="pt-0 p-0">' + response.msg +'</p>';
				inputModal += 		'<div class="row">';
				inputModal +=			'<div class="form-group col-11">';
				inputModal +=				'<input id="otpCode" class="form-control" type="text" name="otpCode" autocomplete="off" ';
				inputModal +=       ' maxlength="10">';
				inputModal +=				'<div class="help-block"></div>';
				inputModal +=			'</div">';
				inputModal += 		'</div>';
				inputModal += '</form>';
				appMessages(response.title, inputModal, response.icon, response.modalBtn);
			break;
		}
		insertFormInput(false);
	});
}

function rechargeAccount() {
	if (lang.CONF_INPUT_PASS == 'OFF') {
		data.passwordTranfer = $('#otpCode').val();
	} else {
		data.passwordTranfer = cryptoPass(data.passwordTranfer);
	}

	verb = 'POST'; who = 'Services'; where = 'masterAccountTransfer';

	callNovoCore(verb, who, where, data, function (response) {
		$('#masterAccountRechargeBtn').html(btnText);
		insertFormInput(false);
	});
}
