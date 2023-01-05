'use strict'
function validateForms(form) {
	formInputTrim(form);
	var onlyNumber = /^[0-9]{6,8}$/;
	var namesValid = /^([a-zñáéíóú.]+[\s]*)+$/i;
	var validNickName = /^([a-z]{2,}[0-9_]*)$/i;
	var regNumberValid = /^['a-z0-9']{6,45}$/i;
	var shortPhrase = /^['a-z0-9ñáéíóú ().']{4,25}$/i;
	var middlePhrase = /^['a-z0-9ñáéíóú ().']{5,45}$/i;
	var longPhraseUpper = /^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ# (),.-])+$/i;
	var longPhrase = /^[a-z0-9ñáéíóú ().-]{6,70}$/i;
	var emailValid = new RegExp(lang.CONF_VALIDATE_EMAIL, 'i');
	var alphanumunder = /^([\w.\-+&ñÑ ]+)+$/i;
	var alphanumspecial = /^([a-zA-Z0-9\ñ\Ñ]{1}[a-zA-Z0-9-z\.\-\_\ \#\%\/\Ñ\ñ]{0,39})+$/i;
	var alphanum =  new RegExp(lang.CONF_VALIDATE_ALPHA_NUM, 'i');
	var alphanumspace = new RegExp(lang.CONF_VALIDATE_ALPHA_NUM_SPACE, 'i');
	var userPassword = validatePass;
	var numeric =  new RegExp(lang.CONF_VALIDATE_NUMERIC, 'i');
	var alphabetical = new RegExp(lang.CONF_VALIDATE_ALPHABETICAL, 'i');
	var alphabeticalspace =  new RegExp(lang.CONF_VALIDATE_ALPHABETICAL_SPACE, 'i');
	var floatAmount =  new RegExp(lang.CONF_VALIDATE_FLOAT_AMOUNT, 'i');
	var fiscalReg = lang.CONF_VALIDATE_FISCAL_REGISTRY;
	var idNumberReg = new RegExp(lang.CONF_VALIDATE_REG_ID_NUMBER, 'i');
	var rechargeDesc = new RegExp(lang.CONF_VALIDATE_RECHAR_REGEX_DESC, 'i');
	var date = {
		dmy: /^(0?[1-9]|[12][0-9]|3[01])\/(0?[1-9]|1[012])\/[0-9]{4}$/,
		my: /^(0?[1-9]|1[012])\/[0-9]{4}$/,
		y: /^[0-9]{4}$/,
	};
	var binary = /^[0-1]+$/;
	var contactType = /^[F|H|C]*$/i;
	var defaults = {
		debug: true,
		errorClass: lang.CONF_VALID_ERROR,
		validClass: lang.CONF_VALID_VALID,
		success: lang.CONF_VALID_SUCCESS,
		ignore: lang.CONF_VALID_IGNORE,
		errorElement: lang.CONF_VALID_ELEMENT
	};

	jQuery.validator.setDefaults(defaults);

	form.validate({
		focusInvalid: false,
		rules: {
			"userName": { required: true, pattern: alphanumunder },
			"userPass": { verifyRequired: '#userName', verifyPattern: '#userName' },
			"user-name": { required: true, pattern: alphanumunder },
			"id-company": { required: true, fiscalRegistry: true },
			"email": { required: true, pattern: emailValid },
			"nit": { pattern: numeric },
			"current-pass": { required: true },
			"new-pass": { required: true, differs: "#currentPass", validatePass: true },
			"confirm-pass": { required: true, equalTo: "#newPass" },
			"branch-office": { requiredBranchOffice: true },
			"type-bulk": { requiredTypeBulk: true },
			"file-bulk": { required: true, extension: lang.CONF_FILES_EXTENSION, sizeFile: true },
			"fileBranch": { required: true, extension: lang.CONF_FILES_EXTENSION, sizeFile: true },
			"password": { required: true, pattern: userPassword },
			"type-order": { required: true },
			"datepicker_start": {
				required: {
					depends: function (element) {
						var requireEl = true;

						if (form.attr('id') === 'service-orders-form') {
							requireEl = !($('#five-days').is(':checked') || $('#ten-days').is(':checked'));
						}

						if (form.attr('id') === 'unna-list-form') {
							requireEl = $('#bulkNumber').val() == '' && !$('#all-bulks').is(':checked');
						}

						return requireEl;
					}
				},
				pattern: date.dmy
			},
			"datepicker_end": {
				required: {
					depends: function (element) {
						var requireEl = true;

						if (form.attr('id') === 'service-orders-form') {
							requireEl = !($('#five-days').is(':checked') || $('#ten-days').is(':checked'));
						}

						if (form.attr('id') === 'unna-list-form') {
							requireEl = $('#bulkNumber').val() == '' && !$('#all-bulks').is(':checked');
						}

						return requireEl;
					}
				},
				pattern: date.dmy
			},
			"status-order": { required: true, requiredTypeOrder: true },
			"selected-date": { required: true, pattern: date.my },
			"selected-month-year": { required: true, pattern: date.my },
			"selected-year": { required: true, pattern: date.y },
			"id-type": { requiredSelect: true },
			"id-number": { required: true, pattern: numeric },
			"id-number1": { pattern: numeric, maxlength: 15 },
			"tlf1": { required: true, pattern: numeric, maxlength: 15 },
			"card-number": { required: true, pattern: numeric, maxlength: 16, minlength: 16 },
			"card-number-sel": { requiredSelect: true },
			"inquiry-type": { requiredSelect: true },
			"saveIP": { pattern: numeric },
			"expired-date": { required: true, pattern: date.my },
			"max-cards": { required: true, pattern: numeric, maxcards: true },
			"starting-line1": {
				required: {
					depends: function () {

						return lang.CONF_STARTING_LINE1_REQUIRED == 'ON';
					}
				},
				pattern: alphanumspace
			},
			"starting-line2": {
				required: {
					depends: function () {

						return lang.CONF_STARTING_LINE2_REQUIRED == 'ON';
					}
				},
				pattern: alphanumspace
			},
			"bulk-number": { pattern: numeric },
			"enterpriseName": { required: true },
			"productName": { required: true },
			"enterpriseCode": { requiredSelect: true },
			"productCode": { requiredSelect: true, required: true },
			"checkbox": { pattern: binary },
			"initDate": { required: true, pattern: date.dmy },
			"initialDate": { required: true, pattern: date.dmy },
			"finalDate": { required: true, pattern: date.dmy },
			"initialDatemy": { required: true, pattern: date.my },
			"finalDatemy": { required: true, pattern: date.my },
			"monthYear": { required: true, pattern: date.my },
			"selection": { required: true },
			"idNumber": { pattern: idNumberReg },
			"anio-consolid": { requiredSelect: true, min: 1, pattern: date.y },
			"yearReport": { required: true, pattern: date.y },
			"reference": { pattern: alphanumspecial, maxlength: 40 },
			"cardNumber": {
				required: {
					depends: function (element) {
						var validate = false;
						if ($(element).attr('req') == 'yes') {
							var validate = true;
						}

						return validate
					}
				},
				pattern: numeric, maxlength: 16, minlength: 16
			},
			"lockType": { requiredSelect: true },
			"otpCode": { required: true, pattern: alphanum },
			"orderNumber": { pattern: numeric, require_from_group: [1, '.select-group'] },
			"bulkNumber": { pattern: numeric, require_from_group: [1, '.select-group'] },
			"idNumberP": {
				required: {
					depends: function (element) {
						var valid = false;

						if (lang.CONF_INQUIRY_DOCTYPE == 'ON') {
							valid = alphabetical.test($('#docType').val()) && $('#docType').val() != '';
						}

						return valid;
					}
				},
				pattern: idNumberReg, require_from_group: [1, '.select-group']
			},
			"docType": {
				required: {
					depends: function (element) {
						return idNumberReg.test($('#idNumberP').val())
					}
				},
				pattern: alphabetical
			},
			"cardNumberP": { pattern: numeric, minlength: lang.CONF_VALIDATE_MINLENGTH, require_from_group: [1, '.select-group'] },
			"masiveOptions": { requiredSelect: true },
			"documentId": { required: true, pattern: alphanum },
			"radioDni": {
				required: {
					depends: function () {
						var check = false;
						if ($('#resultByNIT:checked').val() == 'on') {
							check = true;
						}
						return check
					}
				}, pattern: alphanum, maxlength: lang.VALIDATE_MAXLENGTH_IDEXTPER,
			},
			"radioCard": {
				required: {
					depends: function () {
						var check = false;
						if ($('#resultByCard:checked').val() == 'on') {
							check = true;
						}
						return check
					}
				}, pattern: numeric, maxlength: lang.VALIDATE_MAXLENGTH_CARD, minlength: lang.VALIDATE_MINLENGTH_CARD
			},
			"documentType": { requiredSelect: true },
			"optCode": { required: true, pattern: alphanum },
			"firstName": { required: true, pattern: alphabeticalspace },
			"lastName": { required: true, pattern: alphabeticalspace },
			"movil": { required: true, pattern: numeric, maxlength: 10, minlength: 7 },
			"numberDayPurchasesCtp": { required: true, pattern: numeric, maxLimitZero: '#numberWeeklyPurchasesCtp' },
			"numberWeeklyPurchasesCtp": { required: true, pattern: numeric, maxLimitZero: '#numberMonthlyPurchasesCtp' },
			"dailyPurchaseamountCtp": { required: true, pattern: numeric, maxLimitZero: '#weeklyAmountPurchasesCtp' },
			"weeklyAmountPurchasesCtp": { required: true, pattern: numeric, maxLimitZero: '#monthlyPurchasesAmountCtp' },
			"numberDayPurchasesStp": { required: true, pattern: numeric, maxLimitZero: '#numberWeeklyPurchasesStp' },
			"numberWeeklyPurchasesStp": { required: true, pattern: numeric, maxLimitZero: '#numberMonthlyPurchasesStp' },
			"dailyPurchaseamountStp": { required: true, pattern: numeric, maxLimitZero: '#weeklyAmountPurchasesStp' },
			"weeklyAmountPurchasesStp": { required: true, pattern: numeric, maxLimitZero: '#monthlyPurchasesAmountStp' },
			"dailyNumberWithdraw": { required: true, pattern: numeric, maxLimitZero: '#weeklyNumberWithdraw' },
			"weeklyNumberWithdraw": { required: true, pattern: numeric, maxLimitZero: '#monthlyNumberWithdraw' },
			"dailyAmountWithdraw": { required: true, pattern: numeric, maxLimitZero: '#weeklyAmountWithdraw' },
			"weeklyAmountWithdraw": { required: true, pattern: numeric, maxLimitZero: '#monthlyAmountwithdraw' },
			"dailyNumberCredit": { required: true, pattern: numeric, maxLimitZero: '#weeklyNumberCredit' },
			"weeklyNumberCredit": { required: true, pattern: numeric, maxLimitZero: '#monthlyNumberCredit' },
			"dailyAmountCredit": { required: true, pattern: numeric, maxLimitZero: '#weeklyAmountCredit' },
			"weeklyAmountCredit": { required: true, pattern: numeric, maxLimitZero: '#monthlyAmountCredit' },
			"numberMonthlyPurchasesCtp": { pattern: numeric, required: true },
			"monthlyPurchasesAmountCtp": { pattern: numeric, required: true },
			"purchaseTransactionCtp": { pattern: numeric, required: true, maxLimitZero: '#dailyPurchaseamountCtp' },
			"numberMonthlyPurchasesStp": { pattern: numeric, required: true },
			"monthlyPurchasesAmountStp": { pattern: numeric, required: true },
			"purchaseTransactionStp": { pattern: numeric, required: true, maxLimitZero: '#dailyPurchaseamountStp' },
			"monthlyNumberWithdraw": { pattern: numeric, required: true },
			"monthlyAmountwithdraw": { pattern: numeric, required: true },
			"WithdrawTransaction": { pattern: numeric, required: true, maxLimitZero: '#dailyAmountWithdraw' },
			"monthlyNumberCredit": { pattern: numeric, required: true },
			"monthlyAmountCredit": { pattern: numeric, required: true },
			"CreditTransaction": { pattern: numeric, required: true, maxLimitZero: '#dailyAmountCredit' },
			"transferType": { required: true },
			"transferAmount": { required: true, pattern: floatAmount, lessBalance: true, dailyQuantity: true, minAmount: true, maxAmount: true, maxAmountTransDaily: true, maxAmountTransweekly: true},
			"description": { required: true, pattern: rechargeDesc },
			"branchName": { required: true, pattern: longPhraseUpper },
			"zoneName": { required: true, pattern: numeric },
			"address": { required: true, pattern: longPhraseUpper },
			"address1": { required: true, pattern: longPhraseUpper },
			"address2": { pattern: longPhraseUpper },
			"address3": { pattern: longPhraseUpper },
			"billingAddress": { required: true, pattern: longPhraseUpper },
			"countryCode": { required: true, pattern: numeric },
			"countryCodBranch": { required: true, pattern: numeric },
			"stateCodBranch": { required: true, pattern: numeric },
			"cityCodBranch": { required: true, pattern: numeric },
			"districtCodBranch": { required: true, pattern: numeric },
			"idFiscalList" : { required: true},
			"idEnterpriseList" : { required: true},
			"areaCode": { required: true, pattern: numeric },
			"phone": { required: true, pattern: numeric },
			"phone1": { required: true, pattern: numeric },
			"phone2": { required: true, pattern: numeric },
			"phone3": { required: true, pattern: numeric },
			"person": { required: true, pattern: alphanumspace },
			"branchCode": { required: true, pattern: numeric },
			"surnameModifyContact":{ required: true, pattern: alphanumspace },
			"positionModifyContact":{ required: true, pattern: alphanumspace },
			"typeModifyContact":{ required: true },
			"idExtEmpXls":{ required: true,pattern: numeric},
			"initialDateXls":{ pattern: date.dmy},
			"finalDateXls":{ pattern: date.dmy},
			"filterDateXls":{ required: true,pattern: numeric},
			"nameEnterpriseXls":{ required: true,pattern: alphanumunder},
			"branchListBr":{ required: true, pattern: alphanumspecial},
			"contactNames":{ required: true, pattern: longPhraseUpper},
			"contactLastNames":{ required: true, pattern: longPhraseUpper},
			"contactPosition":{ required: true, pattern: longPhraseUpper},
			"idExtPer":{ required: true, pattern: numeric },
			"contactEmail":{ required: true, pattern: emailValid },
			"contactType":{ required: true, pattern: contactType },

		},
		messages: {
			"userName": lang.VALIDATE_USERLOGIN,
			"userPass": {
				verifyRequired: lang.VALIDATE_USERPASS_REQ,
				verifyPattern: lang.VALIDATE_USERPASS_PATT
			},
			"user-name": lang.VALIDATE_USERNAME,
			"nit": lang.VALIDATE_USERNAME,
			"id-company": lang.VALIDATE_ID_COMPANY + lang.VALIDATE_EXAMPLE_ID_FISCAL,
			"anio-consolid": lang.VALIDATE_SELECTED_YEAR,
			"yearReport": lang.VALIDATE_SELECTED_YEAR,
			"email": lang.VALIDATE_EMAIL,
			"current-pass": lang.VALIDATE_CURRENT_PASS,
			"new-pass": {
				required: lang.VALIDATE_NEW_PASS,
				differs: lang.VALIDATE_DIFFERS_PASS,
				validatePass: lang.VALIDATE_REQUIREMENTS_PASS
			},
			"confirm-pass": {
				required: lang.VALIDATE_CONFIRM_PASS,
				equalTo: lang.VALIDATE_EQUAL_PASS
			},
			"branch-office": lang.VALIDATE_BRANCH_OFFICE,
			"type-bulk": lang.VALIDATE_BULK_TYPE,
			"file-bulk": {
				required: lang.VALIDATE_FILE_TYPE,
				extension: lang.VALIDATE_FILE_TYPE,
				sizeFile: lang.VALIDATE_FILE_SIZE
			},
			"fileBranch": {
				required: lang.VALIDATE_FILE_TYPE,
				extension: lang.VALIDATE_FILE_TYPE,
				sizeFile: lang.VALIDATE_FILE_SIZE
			},
			"password": lang.VALIDATE_PASS,
			"type-order": lang.VALIDATE_ORDER_TYPE,
			"datepicker_start": lang.VALIDATE_INITIAL_DATE,
			"datepicker_end": lang.VALIDATE_FINAL_DATE,
			"status-order": lang.VALIDATE_ORDER_STATUS,
			"selected-date": lang.VALIDATE_SELECTED_DATE,
			"selected-month-year": lang.VALIDATE_SELECTED_MONTH_YEAR,
			"selected-year": lang.VALIDATE_SELECTED_YEAR,
			"id-type": lang.VALIDATE_ID_TYPE,
			"id-number": lang.VALIDATE_ID_NUMBER,
			"id-number1": {
				pattern: lang.VALIDATE_ID_NUMBER,
				maxlength: lang.VALIDATE_LENGHT_NUMBER,
			},
			"tlf1": {
				pattern: lang.VALIDATE_ID_NUMBER,
				required: lang.VALIDATE_PHONE_REQ,
				maxlength: lang.VALIDATE_LENGHT_NUMBER
			},
			"card-number": lang.VALIDATE_CARD_NUMBER,
			"card-number-sel": lang.VALIDATE_CARD_NUMBER_SEL,
			"inquiry-type": lang.VALIDATE_INQUIRY_TYPE_SEL,
			"expired-date": lang.VALIDATE_SELECTED_DATE,
			"max-cards": lang.VALIDATE_TOTAL_CARDS,
			"starting-line1": lang.VALIDATE_STARTING_LINE,
			"starting-line2": lang.VALIDATE_STARTING_LINE,
			"bulk-number": lang.VALIDATE_BULK_NUMBER,
			"enterpriseCode": lang.VALIDATE_SELECT_ENTERPRISE,
			"productCode": lang.VALIDATE_SELECT_PRODUCT,
			"initDate": lang.VALIDATE_DATE_DMY,
			"initialDate": lang.VALIDATE_DATE_DMY,
			"finalDate": lang.VALIDATE_DATE_DMY,
			"initialDatemy": lang.VALIDATE_DATE_MY,
			"monthYear": lang.VALIDATE_DATE_MY,
			"selection": lang.VALIDATE_OPTION,
			"idNumber": lang.VALIDATE_ID_NUMBER,
			"reference": lang.VALIDATE_REFERENCE,
			"cardNumber": lang.VALIDATE_CARD_NUMBER,
			"lockType": lang.VALIDATE_OPTION,
			"otpCode": lang.VALIDATE_OTP_CODE,
			"orderNumber": {
				pattern: lang.VALIDATE_BULK_NUMBER,
				require_from_group: lang.VALIDATE_SELECT_GROUP

			},
			"bulkNumber": {
				pattern: lang.VALIDATE_BULK_NUMBER,
				require_from_group: lang.VALIDATE_SELECT_GROUP

			},
			"idNumberP": {
				required: lang.VALIDATE_ID_NUMBER,
				pattern: lang.VALIDATE_ID_NUMBER,
				require_from_group: lang.VALIDATE_SELECT_GROUP
			},
			"docType": {
				required: lang.VALIDATE_SELECT_DOCTYPE,
				pattern: lang.VALIDATE_SELECT_DOCTYPE
			},
			"cardNumberP": {
				pattern: lang.VALIDATE_CARD_NUMBER_MIN,
				minlength: lang.VALIDATE_CARD_NUMBER_MIN,
				require_from_group: lang.VALIDATE_SELECT_GROUP
			},
			"masiveOptions": lang.VALIDATE_OPTION,
			"documentId": lang.VALIDATE_DOCUMENT_ID,
			"radioDni": lang.VALIDATE_DOCUMENT_ID,
			"radioCard": lang.VALIDATE_CARD_NUMBER_MIN,
			"documentType": lang.VALIDATE_SELECT_DOCTYPE,
			"optCode": lang.VALIDATE_OTP_CODE,
			"firstName": lang.VALIDATE_NAME_LASTNAME,
			"lastName": lang.VALIDATE_NAME_LASTNAME,
			"movil": lang.VALIDATE_PHONE,
			"numberMonthlyPurchasesCtp": {
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"monthlyPurchasesAmountCtp": {
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"purchaseTransactionCtp": {
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ,
				maxLimitZero: lang.VALIDATE_MAX_DAY
			},
			"numberMonthlyPurchasesStp": {
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"monthlyPurchasesAmountStp": {
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"purchaseTransactionStp": {
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ,
				maxLimitZero: lang.VALIDATE_MAX_DAY
			},
			"monthlyNumberWithdraw": {
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"monthlyAmountwithdraw": {
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"WithdrawTransaction": {
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ,
				maxLimitZero: lang.VALIDATE_MAX_DAY
			},
			"monthlyNumberCredit": {
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"monthlyAmountCredit": {
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"CreditTransaction": {
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ,
				maxLimitZero: lang.VALIDATE_MAX_DAY
			},
			"numberDayPurchasesCtp": {
				maxLimitZero: lang.VALIDATE_MAX_WEEK,
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"numberWeeklyPurchasesCtp": {
				maxLimitZero: lang.VALIDATE_MAX_MONTH,
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"dailyPurchaseamountCtp": {
				maxLimitZero: lang.VALIDATE_MAX_WEEK,
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"weeklyAmountPurchasesCtp": {
				maxLimitZero: lang.VALIDATE_MAX_MONTH,
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"numberDayPurchasesStp": {
				maxLimitZero: lang.VALIDATE_MAX_WEEK,
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"numberWeeklyPurchasesStp": {
				maxLimitZero: lang.VALIDATE_MAX_MONTH,
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"dailyPurchaseamountStp": {
				maxLimitZero: lang.VALIDATE_MAX_WEEK,
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"weeklyAmountPurchasesStp": {
				maxLimitZero: lang.VALIDATE_MAX_MONTH,
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"dailyNumberWithdraw": {
				maxLimitZero: lang.VALIDATE_MAX_WEEK,
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"weeklyNumberWithdraw": {
				maxLimitZero: lang.VALIDATE_MAX_MONTH,
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"dailyAmountWithdraw": {
				maxLimitZero: lang.VALIDATE_MAX_WEEK,
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"weeklyAmountWithdraw": {
				maxLimitZero: lang.VALIDATE_MAX_MONTH,
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"dailyNumberCredit": {
				maxLimitZero: lang.VALIDATE_MAX_WEEK,
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"weeklyNumberCredit": {
				maxLimitZero: lang.VALIDATE_MAX_MONTH,
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"dailyAmountCredit": {
				maxLimitZero: lang.VALIDATE_MAX_WEEK,
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"weeklyAmountCredit": {
				maxLimitZero: lang.VALIDATE_MAX_MONTH,
				pattern: lang.VALIDATE_INVALID_NUMBER,
				required: lang.VALIDATE_NUMBER_REQ
			},
			"transferType": lang.VALIDATE_TRANSFER_TYPE,
			"transferAmount": {
				required: lang.VALIDATE_VALID_AMOUNT,
				pattern: lang.VALIDATE_VALID_AMOUNT,
				lessBalance: lang.VALIDATE_LESS_BALANCE,
				dailyQuantity: lang.VALIDATE_DAILY_QUANTITY,
				minAmount: lang.VALIDATE_MIN_AMOUNT,
				maxAmount: lang.VALIDATE_MAX_AMOUNT,
				maxAmountTransDaily: lang.VALIDATE_MAX_AMOUNT_TRANSDAILY,
				maxAmountTransweekly: lang.VALIDATE_MAX_AMOUNT_TRANSWEEKLY
			},
			"description": {
				required: lang.VALIDATE_RECHAR_DESC1,
				pattern: lang.VALIDATE_NAME_BRANCHES
			},
			"branchName": {
				required: lang.VALIDATE_NAME_BRANCHES,
				pattern: lang.VALIDATE_NAME_BRANCHES
			},
			"zoneName": {
				required: lang.VALIDATE_ZONE_BRANCHES,
				pattern: lang.VALIDATE_NIT
			},
			"address": {
				required: lang.VALIDATE_ADDRESS_ENTERPRICE,
				pattern: lang.VALIDATE_ADDRESS_BRANCHES
			},
			"address1": {
				required: lang.VALIDATE_ADDRESS_BRANCHES,
				pattern: lang.VALIDATE_ADDRESS_BRANCHES
			},
			"address2": {
				required: lang.VALIDATE_INPUT_REQUIRED,
				pattern: lang.VALIDATE_ADDRESS_BRANCHES
			},
			"address3": {
				required: lang.VALIDATE_INPUT_REQUIRED,
				pattern: lang.VALIDATE_ADDRESS_BRANCHES
			},
			"billingAddress": {
				required: lang.VALIDATE_BILLING_ADDRESS_ENTERPRICE,
				pattern: lang.VALIDATE_ADDRESS_BRANCHES
			},
			"countryCode": {
				required: lang.VALIDATE_INPUT_REQUIRED,
				pattern: lang.VALIDATE_NIT
			},
			"countryCodBranch": {
				required: lang.VALIDATE_INPUT_REQUIRED,
				pattern: lang.VALIDATE_NIT
			},
			"stateCodBranch": {
				required: lang.VALIDATE_PROVINCE_BRANCHES,
				pattern: lang.VALIDATE_NIT
			},
			"cityCodBranch": {
				required: lang.VALIDATE_DEPARTMENT_BRANCHES,
				pattern: lang.VALIDATE_NIT
			},
			"districtCodeBranch": {
				required: lang.VALIDATE_INPUT_REQUIRED,
				pattern: lang.VALIDATE_NIT
			},
			"idFiscalList": {
				required: lang.VALIDATE_SELECT
			},
			"idEnterpriseList": {
				required: lang.VALIDATE_SELECT
			},
			"areaCode": {
				required: lang.VALIDATE_INPUT_REQUIRED,
				pattern: lang.VALIDATE_NIT
			},
			"phone": {
				required: lang.VALIDATE_INPUT_REQUIRED,
				pattern: lang.VALIDATE_NIT
			},
			"phone1": {
				required: lang.VALIDATE_INPUT_REQUIRED,
				pattern: lang.VALIDATE_NIT
			},
			"phone2": {
				required: lang.VALIDATE_INPUT_REQUIRED,
				pattern: lang.VALIDATE_NIT
			},
			"phone3": {
				required: lang.VALIDATE_INPUT_REQUIRED,
				pattern: lang.VALIDATE_NIT
			},
			"person": {
				required: lang.VALIDATE_INPUT_REQUIRED,
				pattern: lang.VALIDATE_NAME_BRANCHES
			},
			"branchCode": {
				required: lang.VALIDATE_CODE_BRANCHES,
				pattern: lang.VALIDATE_NIT
			},
			"surnameModifyContact":{
				required: lang.VALIDATE_INPUT_REQUIRED,
				pattern: lang.VALIDATE_INPUT_SURNAME
			},
			"positionModifyContact":{
				required: lang.VALIDATE_INPUT_REQUIRED,
				pattern: lang.VALIDATE_INPUT_POSITION
			},
			"typeModifyContact":{
				required: lang.VALIDATE_SELECT
			},
			"contactNames": {
				required: 'Indica un nombre válido',
				pattern: 'Indica un nombre válido'
			},
			"contactLastNames": {
				required: 'Indica un apellido válido',
				pattern: 'Indica un apellido válido'
			},
			"contactPosition": {
				required: 'Indica un cargo válido',
				pattern: 'Indica un cargo válido'
			},
			"idExtPer": {
				required: 'Indica un NIT válido',
				pattern: 'Indica un NIT válido'
			},
			"contactEmail": {
				required: 'Indica un email válido',
				pattern: 'Indica un email válido'
			},
			"contactType": {
				required: 'Selecciona un tipo contacto válido',
				pattern: 'Selecciona un tipo contacto válido'
			},
		},
		errorPlacement: function (error, element) {
			$(element).closest('.form-group').find('.help-block').html(error.html());
		}
	});

	$.validator.methods.verifyRequired = function (value, element, param) {
		return value != '' && $(param).val() != '';
	}

	$.validator.methods.verifyPattern = function (value, element, param) {
		return userPassword.test(value) && alphanumunder.test($(param).val());
	}

	$.validator.methods.requiredTypeBulk = function (value, element, param) {
		var eval1 = alphanum.test($(element).find('option:selected').attr('format').trim());
		var eval2 = longPhrase.test($(element).find('option:selected').text().trim());
		var eval3 = alphanum.test($(element).find('option:selected').val().trim());
		return eval1 && eval2 && eval3;
	}

	$.validator.methods.requiredBranchOffice = function (value, element, param) {
		return alphanum.test($(element).find('option:selected').val());
	}

	$.validator.methods.fiscalRegistry = function (value, element, param) {
		var RegExpfiscalReg = new RegExp(fiscalReg, 'i')
		return RegExpfiscalReg.test(value);
	}

	$.validator.methods.validatePass = function (value, element, param) {
		return passStrength(value);
	}

	$.validator.methods.differs = function (value, element, param) {
		var target = $(param);
		return value !== target.val();
	}

	$.validator.methods.requiredTypeOrder = function (value, element, param) {
		var eval1 = alphanumunder.test($(element).find('option:selected').text().trim());
		var eval2 = alphanum.test($(element).find('option:selected').val().trim());
		return eval1 && eval2;
	}

	$.validator.methods.sizeFile = function (value, element, param) {
		return element.files[0].size > 0;
	}

	$.validator.methods.requiredSelect = function (value, element, param) {
		var valid = true;

		if ($(element).find('option').length > 0) {
			valid = alphanumunder.test($(element).find('option:selected').val().trim());
		}

		return valid
	}

	$.validator.methods.maxcards = function (value, element, param) {
		var valid = true;
		var cardsMax = parseInt($(element).attr('max-cards'));
		var cards = parseInt(value);

		valid = cards > 0;

		if (cardsMax > 0 && valid) {
			valid = cardsMax > cards
		}

		return valid
	}

	$.validator.methods.maxLimitZero = function (value, element, param) {
		var valid = false;

		if (Number($(param).val()) == 0) {
			if (value > 0) {
				valid = true
			} else if (value == Number($(param).val())) {
				valid = true;
			}
		} else if (Number($(param).val()) > 0) {
			if (value <= Number($(param).val())) {
				valid = true;
			}
		}

		return valid;
	}

	$.validator.methods.lessBalance = function (value, element, param) {
		var valid = true
		value = normalizeAmount(value);

		if (rechargeParam.validateParams && checkType !== lang.VALIDATE_TRANSFERTYPE) {
			if(value > rechargeParam.balance ){
				valid = false;
			}else if((value + rechargeParam.commission) > rechargeParam.balance){
				valid = false;
			}
		}
		return valid
	}

	$.validator.methods.dailyQuantity = function (value, element, param) {
		var valid = true;

		if (rechargeParam.validateParams) {
			valid = (rechargeParam.maxQuanTransDaily > 0) && (rechargeParam.maxQuanTransDaily < (rechargeParam.accumTransDaily + 1)) ? false : true ;
		}
		return valid;
	}

	$.validator.methods.minAmount = function (value, element, param) {
		var valid = true;

		if (rechargeParam.validateParams) {
			valid = (rechargeParam.minAmount > 0) && (value < rechargeParam.minAmount) ? false : true ;
		}
		return valid;
	}

	$.validator.methods.maxAmount = function (value, element, param) {
		var valid = true;

		if (rechargeParam.validateParams) {
			valid = (rechargeParam.maxAmount > 0) && (value > rechargeParam.maxAmount) ? false : true ;
		}
		return valid;
	}

	$.validator.methods.maxAmountTransDaily = function (value, element, param) {
		var valid = true;

		if (rechargeParam.validateParams) {
			valid = (rechargeParam.maxAmountTransDaily > 0) && ((value + rechargeParam.dailyAmount) > rechargeParam.maxAmountTransDaily) ? false : true ;
		}
		return valid;
	}

	$.validator.methods.maxAmountTransweekly = function (value, element, param) {
		var valid = true;

		if (rechargeParam.validateParams) {
			valid = (rechargeParam.maxAmountWeek > 0) && ((value + rechargeParam.weeklyAmount) > rechargeParam.maxAmountWeek) ? false : true ;
		}
		return valid;
	}

	form.validate().resetForm();
}
