'use strict';
function validateForms(form) {
  formInputTrim(form);

  var longPhraseUpper = /^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\# (),.-\/])+$/i;
  var longPhrase = /^[a-z0-9ñáéíóú ().-]{6,70}$/i;
  var alphaName = /^[a-zñáéíóú ]{1,50}$/i;
  var alphanumunder = /^([\w.\-+&ñÑ ]+)+$/i;
  var alphanumspecial = /^([a-zA-Z0-9\ñ\Ñ]{1}[a-zA-Z0-9-z\.\-\_\ \#\%\/\Ñ\ñ]{0,39})+$/i;
  var address = new RegExp(lang.REGEX_ADDRESS, 'i');
  var fiscalId = new RegExp(lang.REGEX_FISCAL_ID, 'i');
  var addressCod = new RegExp(lang.REGEX_ADDRESS_COD, 'i');
  var contact = new RegExp(lang.REGEX_CONTACT, 'i');
  var phoneNumber = new RegExp(lang.REGEX_PHONE);
  var userPassword = new RegExp(lang.REGEX_PASSWORD, 'i');
  var alphaString = new RegExp(lang.REGEX_ALPHA_STRING, 'i');
  var idNumber = new RegExp(lang.REGEX_ID_NUMBER, 'i');
  var numeric = new RegExp(lang.REGEX_NUMERIC, 'i');
  var emailValid = new RegExp(lang.REGEX_EMAIL, 'i');
  var alphanum = new RegExp(lang.REGEX_ALPHA_NUM, 'i');
  var alphanumspace = new RegExp(lang.SETT_VALIDATE_ALPHA_NUM_SPACE, 'i');
  var alphabetical = new RegExp(lang.SETT_VALIDATE_ALPHABETICAL, 'i');
  var alphabeticalspace = new RegExp(lang.SETT_VALIDATE_ALPHABETICAL_SPACE, 'i');
  var floatAmount = new RegExp(lang.SETT_VALIDATE_FLOAT_AMOUNT, 'i');
  var fiscalReg = lang.REGEX_FISCAL_ID;
  var idNumberReg = new RegExp(lang.SETT_VALIDATE_REG_ID_NUMBER, 'i');
  var rechargeDesc = new RegExp(lang.SETT_VALIDATE_RECHAR_REGEX_DESC, 'i');
  var documentId = new RegExp(lang.REGEX_DOCUMENT_ID, 'i');
  var date = {
    dmy: /^(0?[1-9]|[12][0-9]|3[01])\/(0?[1-9]|1[012])\/[0-9]{4}$/,
    my: /^(0?[1-9]|1[012])\/[0-9]{4}$/,
    y: /^[0-9]{4}$/,
  };
  var binary = /^[0-1]+$/;
  var contactType = /^[F|H|C]*$/i;
  var defaults = {
    debug: true,
    errorClass: lang.SETT_VALID_ERROR,
    validClass: lang.SETT_VALID_VALID,
    success: lang.SETT_VALID_SUCCESS,
    ignore: lang.SETT_VALID_IGNORE,
    errorElement: lang.SETT_VALID_ELEMENT,
  };

  jQuery.validator.setDefaults(defaults);

  form.validate({
    focusInvalid: false,
    rules: {
      userName: { required: true, pattern: alphanumunder },
      userPass: { verifyRequired: '#userName', verifyPattern: '#userName' },
      'user-name': { required: true, pattern: alphanumunder },
      'id-company': { required: true, fiscalRegistry: true },
      email: { required: true, pattern: emailValid },
      idDocument: {
        required: {
          depends: function (element) {
            return $(element).attr('req') === 'yes';
          },
        },
        pattern: documentId,
      },
      'current-pass': { required: true },
      'new-pass': { required: true, differs: '#currentPass', validatePass: true },
      'confirm-pass': { required: true, equalTo: '#newPass' },
      'branch-office': { requiredBranchOffice: true },
      'type-bulk': { requiredTypeBulk: true },
      'file-bulk': { required: true, extension: lang.SETT_FILES_EXTENSION, sizeFile: true },
      fileBranch: { required: true, extension: lang.SETT_FILES_EXTENSION, sizeFile: true },
      password: { required: true, pattern: userPassword },
      'type-order': { required: true },
      datepicker_start: {
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
          },
        },
        pattern: date.dmy,
      },
      datepicker_end: {
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
          },
        },
        pattern: date.dmy,
      },
      datepicker_year: { required: true, pattern: date.y },
      'status-order': { required: true, requiredTypeOrder: true },
      'selected-date': { required: true, pattern: date.my },
      'selected-month-year': { required: true, pattern: date.my },
      'selected-year': { required: true, pattern: date.y },
      'id-type': { requiredSelect: true },
      'id-number': { required: true, pattern: numeric },
      'id-number1': { pattern: numeric, maxlength: 15 },
      tlf1: { required: true, pattern: numeric, maxlength: 15 },
      'card-number': { required: true, pattern: numeric, maxlength: 16, minlength: 16 },
      'card-number-sel': { requiredSelect: true },
      'inquiry-type': { requiredSelect: true },
      saveIP: { pattern: numeric },
      'expired-date': { required: true, pattern: date.my },
      'max-cards': { required: true, pattern: numeric, maxcards: true },
      'starting-line1': {
        required: {
          depends: function () {
            return lang.SETT_STARTING_LINE1_REQUIRED == 'ON';
          },
        },
        pattern: alphanumspace,
      },
      'starting-line2': {
        required: {
          depends: function () {
            return lang.SETT_STARTING_LINE2_REQUIRED == 'ON';
          },
        },
        pattern: alphanumspace,
      },
      'bulk-number': { pattern: numeric },
      enterpriseName: { required: true },
      productName: { required: true },
      enterpriseCode: { requiredSelect: true },
      productCode: { requiredSelect: true, required: true },
      checkbox: { pattern: binary },
      initDate: { required: true, pattern: date.dmy },
      initialDate: { required: true, pattern: date.dmy },
      finalDate: { required: true, pattern: date.dmy },
      initialDatemy: { required: true, pattern: date.my },
      finalDatemy: { required: true, pattern: date.my },
      monthYear: { required: true, pattern: date.my },
      selection: { required: true },
      idNumber: { pattern: idNumberReg },
      'anio-consolid': { requiredSelect: true, min: 1, pattern: date.y },
      yearReport: { required: true, pattern: date.y },
      reference: { pattern: alphanumspecial, maxlength: 40 },
      cardNumber: {
        required: {
          depends: function (element) {
            var validate = false;
            if ($(element).attr('req') == 'yes') {
              var validate = true;
            }

            return validate;
          },
        },
        pattern: numeric,
        maxlength: 16,
        minlength: 16,
      },
      lockType: { requiredSelect: true },
      otpCode: { required: true, pattern: alphanum },
      orderNumber: { pattern: numeric, require_from_group: [1, '.select-group'] },
      bulkNumber: { pattern: numeric, require_from_group: [1, '.select-group'] },
      idNumberP: {
        required: {
          depends: function (element) {
            var valid = false;

            if (lang.SETT_INQUIRY_DOCTYPE == 'ON') {
              valid = alphabetical.test($('#docType').val()) && $('#docType').val() != '';
            }

            return valid;
          },
        },
        pattern: idNumberReg,
        require_from_group: [1, '.select-group'],
      },
      docType: {
        required: {
          depends: function (element) {
            return idNumberReg.test($('#idNumberP').val());
          },
        },
        pattern: alphabetical,
      },
      cardNumberP: {
        pattern: numeric,
        minlength: lang.SETT_VALIDATE_MINLENGTH,
        require_from_group: [1, '.select-group'],
      },
      masiveOptions: { requiredSelect: true },
      documentId: { required: true, pattern: alphanum },
      radioDni: {
        required: {
          depends: function () {
            var check = false;
            if ($('#resultByNIT:checked').val() == 'on') {
              check = true;
            }
            return check;
          },
        },
        pattern: alphanum,
        maxlength: lang.VALIDATE_MAXLENGTH_IDEXTPER,
      },
      radioName: {
        required: {
          depends: function () {
            var check = false;
            if ($('#resultByName:checked').val() == 'on') {
              check = true;
            }
            return check;
          },
        },
        pattern: alphaName,
        minlength: 3,
        maxlength: lang.VALIDATE_MAXLENGTH,
      },
      radioCard: {
        required: {
          depends: function () {
            var check = false;
            if ($('#resultByCard:checked').val() == 'on') {
              check = true;
            }
            return check;
          },
        },
        pattern: numeric,
        maxlength: lang.VALIDATE_MAXLENGTH_CARD,
        minlength: lang.VALIDATE_MINLENGTH_CARD,
      },
      documentType: { requiredSelect: true },
      optCode: { required: true, pattern: alphanum },
      firstName: { required: true, pattern: alphabeticalspace },
      lastName: { required: true, pattern: alphabeticalspace },
      movil: { required: true, pattern: numeric, maxlength: 10, minlength: 7 },
      numberDayPurchasesCtp: { required: true, pattern: numeric, maxLimitZero: '#numberWeeklyPurchasesCtp' },
      numberWeeklyPurchasesCtp: { required: true, pattern: numeric, maxLimitZero: '#numberMonthlyPurchasesCtp' },
      dailyPurchaseamountCtp: { required: true, pattern: numeric, maxLimitZero: '#weeklyAmountPurchasesCtp' },
      weeklyAmountPurchasesCtp: { required: true, pattern: numeric, maxLimitZero: '#monthlyPurchasesAmountCtp' },
      numberDayPurchasesStp: { required: true, pattern: numeric, maxLimitZero: '#numberWeeklyPurchasesStp' },
      numberWeeklyPurchasesStp: { required: true, pattern: numeric, maxLimitZero: '#numberMonthlyPurchasesStp' },
      dailyPurchaseamountStp: { required: true, pattern: numeric, maxLimitZero: '#weeklyAmountPurchasesStp' },
      weeklyAmountPurchasesStp: { required: true, pattern: numeric, maxLimitZero: '#monthlyPurchasesAmountStp' },
      dailyNumberWithdraw: { required: true, pattern: numeric, maxLimitZero: '#weeklyNumberWithdraw' },
      weeklyNumberWithdraw: { required: true, pattern: numeric, maxLimitZero: '#monthlyNumberWithdraw' },
      dailyAmountWithdraw: { required: true, pattern: numeric, maxLimitZero: '#weeklyAmountWithdraw' },
      weeklyAmountWithdraw: { required: true, pattern: numeric, maxLimitZero: '#monthlyAmountwithdraw' },
      dailyNumberCredit: { required: true, pattern: numeric, maxLimitZero: '#weeklyNumberCredit' },
      weeklyNumberCredit: { required: true, pattern: numeric, maxLimitZero: '#monthlyNumberCredit' },
      dailyAmountCredit: { required: true, pattern: numeric, maxLimitZero: '#weeklyAmountCredit' },
      weeklyAmountCredit: { required: true, pattern: numeric, maxLimitZero: '#monthlyAmountCredit' },
      numberMonthlyPurchasesCtp: { pattern: numeric, required: true },
      monthlyPurchasesAmountCtp: { pattern: numeric, required: true },
      purchaseTransactionCtp: { pattern: numeric, required: true, maxLimitZero: '#dailyPurchaseamountCtp' },
      numberMonthlyPurchasesStp: { pattern: numeric, required: true },
      monthlyPurchasesAmountStp: { pattern: numeric, required: true },
      purchaseTransactionStp: { pattern: numeric, required: true, maxLimitZero: '#dailyPurchaseamountStp' },
      monthlyNumberWithdraw: { pattern: numeric, required: true },
      monthlyAmountwithdraw: { pattern: numeric, required: true },
      WithdrawTransaction: { pattern: numeric, required: true, maxLimitZero: '#dailyAmountWithdraw' },
      monthlyNumberCredit: { pattern: numeric, required: true },
      monthlyAmountCredit: { pattern: numeric, required: true },
      CreditTransaction: { pattern: numeric, required: true, maxLimitZero: '#dailyAmountCredit' },
      transferType: { required: true },
      transferAmount: {
        required: true,
        pattern: floatAmount,
        lessBalance: true,
        dailyQuantity: true,
        minAmount: true,
        maxAmount: true,
        maxAmountTransDaily: true,
        maxAmountTransweekly: true,
      },
      description: { required: true, pattern: rechargeDesc },
      branchName: { required: true, pattern: address, rangelength: [5, 100] },
      zoneName: { required: true, pattern: address, rangelength: [3, 100] },
      address: { required: true, pattern: longPhraseUpper, rangelength: [10, 150] },
      address1: { required: true, pattern: address, rangelength: [10, 150] },
      address2: { pattern: address, rangelength: [10, 150] },
      address3: { pattern: address, rangelength: [10, 150] },
      billingAddress: { required: true, pattern: longPhraseUpper },
      countryCode: { required: true, pattern: numeric },
      countryCodBranch: { required: true, pattern: numeric },
      stateCodBranch: { required: true, pattern: addressCod },
      cityCodBranch: { required: true, pattern: addressCod },
      districtCodBranch: { required: true, pattern: addressCod },
      idFiscalList: { required: true, selectRequired: [fiscalId, address] },
      idEnterpriseList: { required: true },
      areaCode: { required: true, pattern: addressCod, rangelength: [3, 4] },
      phone: { required: true, pattern: phoneNumber },
      phone1: { required: true, pattern: phoneNumber },
      phone2: { pattern: phoneNumber },
      phone3: { pattern: phoneNumber },
      person: { pattern: contact, rangelength: [5, 100] },
      branchCode: { required: true, pattern: addressCod, rangelength: [1, 3] },
      surnameModifyContact: { required: true, pattern: alphanumspace },
      positionModifyContact: { required: true, pattern: alphanumspace },
      typeModifyContact: { required: true },
      idExtEmpXls: { required: true, pattern: numeric },
      initialDateXls: { pattern: date.dmy },
      finalDateXls: { pattern: date.dmy },
      filterDateXls: { required: true, pattern: numeric },
      nameEnterpriseXls: { required: true, pattern: alphanumunder },
      branchListBr: { required: true, pattern: alphanumspecial },
      contactNames: { required: true, pattern: alphaString },
      contactLastNames: { required: true, pattern: alphaString },
      contactPosition: { required: true, pattern: alphaString },
      idExtPer: { required: true, pattern: idNumber },
      contactEmail: { required: true, pattern: emailValid },
      contactType: { required: true, pattern: contactType },
      replaceType: { required: true, requiredSelect: true },
      paymentConcept: { required: true, selectRequired: [alphaString, alphaString] },
      embLine1: { required: true, selectRequired: [alphaString, alphaString] },
      embLine2: { required: true, selectRequired: [alphaString, alphaString] },
    },
    messages: {
      userName: lang.VALIDATE_USERLOGIN,
      userPass: {
        verifyRequired: lang.VALID_USERPASS_REQ,
        verifyPattern: lang.VALID_USERPASS_PATTERN,
      },
      'user-name': lang.VALID_USERNAME,
      idDocument: lang.VALID_DOC_ID,
      'id-company': lang.VALIDATE_ID_COMPANY + lang.VALIDATE_EXAMPLE_ID_FISCAL,
      'anio-consolid': lang.VALIDATE_SELECTED_YEAR,
      yearReport: lang.VALIDATE_SELECTED_YEAR,
      email: lang.VALIDATE_EMAIL,
      'current-pass': lang.VALIDATE_CURRENT_PASS,
      'new-pass': {
        required: lang.VALIDATE_NEW_PASS,
        differs: lang.VALIDATE_DIFFERS_PASS,
        validatePass: lang.VALID_PASSWORD,
      },
      'confirm-pass': {
        required: lang.VALIDATE_CONFIRM_PASS,
        equalTo: lang.VALIDATE_EQUAL_PASS,
      },
      'branch-office': lang.VALIDATE_BRANCH_OFFICE,
      'type-bulk': lang.VALIDATE_BULK_TYPE,
      'file-bulk': {
        required: lang.VALIDATE_FILE_TYPE,
        extension: lang.VALIDATE_FILE_TYPE,
        sizeFile: lang.VALIDATE_FILE_SIZE,
      },
      fileBranch: {
        required: lang.VALIDATE_FILE_TYPE,
        extension: lang.VALIDATE_FILE_TYPE,
        sizeFile: lang.VALIDATE_FILE_SIZE,
      },
      password: lang.VALIDATE_PASS,
      'type-order': lang.VALIDATE_ORDER_TYPE,
      datepicker_start: lang.VALIDATE_INITIAL_DATE,
      datepicker_end: lang.VALIDATE_FINAL_DATE,
      datepicker_year: lang.VALIDATE_SELECTED_YEAR,
      'status-order': lang.VALIDATE_ORDER_STATUS,
      'selected-date': lang.VALIDATE_SELECTED_DATE,
      'selected-month-year': lang.VALIDATE_SELECTED_MONTH_YEAR,
      'selected-year': lang.VALIDATE_SELECTED_YEAR,
      'id-type': lang.VALIDATE_ID_TYPE,
      'id-number': lang.VALIDATE_NUMBER_ID,
      'id-number1': {
        pattern: lang.VALIDATE_NUMBER_ID,
        maxlength: lang.VALIDATE_LENGHT_NUMBER,
      },
      tlf1: {
        pattern: lang.VALIDATE_NUMBER_ID,
        required: lang.VALIDATE_PHONE_REQ,
        maxlength: lang.VALIDATE_LENGHT_NUMBER,
      },
      'card-number': lang.VALIDATE_CARD_NUMBER,
      'card-number-sel': lang.VALIDATE_CARD_NUMBER_SEL,
      'inquiry-type': lang.VALIDATE_INQUIRY_TYPE_SEL,
      'expired-date': lang.VALIDATE_SELECTED_DATE,
      'max-cards': lang.VALIDATE_TOTAL_CARDS,
      'starting-line1': lang.VALIDATE_STARTING_LINE,
      'starting-line2': lang.VALIDATE_STARTING_LINE,
      'bulk-number': lang.VALIDATE_BULK_NUMBER,
      enterpriseCode: lang.VALIDATE_SELECT_ENTERPRISE,
      productCode: lang.VALIDATE_SELECT_PRODUCT,
      initDate: lang.VALIDATE_DATE_DMY,
      initialDate: lang.VALIDATE_DATE_DMY,
      finalDate: lang.VALIDATE_DATE_DMY,
      initialDatemy: lang.VALIDATE_DATE_MY,
      monthYear: lang.VALIDATE_DATE_MY,
      selection: lang.VALIDATE_OPTION,
      idNumber: lang.VALIDATE_NUMBER_ID,
      reference: lang.VALIDATE_REFERENCE,
      cardNumber: lang.VALIDATE_CARD_NUMBER,
      lockType: lang.VALIDATE_OPTION,
      otpCode: lang.VALID_OTP_CODE,
      orderNumber: {
        pattern: lang.VALIDATE_BULK_NUMBER,
        require_from_group: lang.VALIDATE_SELECT_GROUP,
      },
      bulkNumber: {
        pattern: lang.VALIDATE_BULK_NUMBER,
        require_from_group: lang.VALIDATE_SELECT_GROUP,
      },
      idNumberP: {
        required: lang.VALIDATE_NUMBER_ID,
        pattern: lang.VALIDATE_NUMBER_ID,
        require_from_group: lang.VALIDATE_SELECT_GROUP,
      },
      docType: {
        required: lang.VALIDATE_SELECT_DOCTYPE,
        pattern: lang.VALIDATE_SELECT_DOCTYPE,
      },
      cardNumberP: {
        pattern: lang.VALIDATE_CARD_NUMBER_MIN,
        minlength: lang.VALIDATE_CARD_NUMBER_MIN,
        require_from_group: lang.VALIDATE_SELECT_GROUP,
      },
      masiveOptions: lang.VALIDATE_OPTION,
      documentId: lang.VALIDATE_DOCUMENT_ID,
      radioDni: lang.VALIDATE_DOCUMENT_ID,
      radioName: lang.VALIDATE_NAME_LASTNAME,
      radioCard: lang.VALIDATE_CARD_NUMBER_MIN,
      documentType: lang.VALIDATE_SELECT_DOCTYPE,
      optCode: lang.VALID_OTP_CODE,
      firstName: lang.VALIDATE_NAME_LASTNAME,
      lastName: lang.VALIDATE_NAME_LASTNAME,
      movil: lang.VALIDATE_PHONE,
      contactEmail: lang.VALIDATE_EMAIL,
      numberMonthlyPurchasesCtp: {
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      monthlyPurchasesAmountCtp: {
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      purchaseTransactionCtp: {
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
        maxLimitZero: lang.VALIDATE_MAX_DAY,
      },
      numberMonthlyPurchasesStp: {
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      monthlyPurchasesAmountStp: {
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      purchaseTransactionStp: {
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
        maxLimitZero: lang.VALIDATE_MAX_DAY,
      },
      monthlyNumberWithdraw: {
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      monthlyAmountwithdraw: {
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      WithdrawTransaction: {
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
        maxLimitZero: lang.VALIDATE_MAX_DAY,
      },
      monthlyNumberCredit: {
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      monthlyAmountCredit: {
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      CreditTransaction: {
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
        maxLimitZero: lang.VALIDATE_MAX_DAY,
      },
      numberDayPurchasesCtp: {
        maxLimitZero: lang.VALIDATE_MAX_WEEK,
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      numberWeeklyPurchasesCtp: {
        maxLimitZero: lang.VALIDATE_MAX_MONTH,
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      dailyPurchaseamountCtp: {
        maxLimitZero: lang.VALIDATE_MAX_WEEK,
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      weeklyAmountPurchasesCtp: {
        maxLimitZero: lang.VALIDATE_MAX_MONTH,
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      numberDayPurchasesStp: {
        maxLimitZero: lang.VALIDATE_MAX_WEEK,
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      numberWeeklyPurchasesStp: {
        maxLimitZero: lang.VALIDATE_MAX_MONTH,
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      dailyPurchaseamountStp: {
        maxLimitZero: lang.VALIDATE_MAX_WEEK,
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      weeklyAmountPurchasesStp: {
        maxLimitZero: lang.VALIDATE_MAX_MONTH,
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      dailyNumberWithdraw: {
        maxLimitZero: lang.VALIDATE_MAX_WEEK,
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      weeklyNumberWithdraw: {
        maxLimitZero: lang.VALIDATE_MAX_MONTH,
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      dailyAmountWithdraw: {
        maxLimitZero: lang.VALIDATE_MAX_WEEK,
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      weeklyAmountWithdraw: {
        maxLimitZero: lang.VALIDATE_MAX_MONTH,
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      dailyNumberCredit: {
        maxLimitZero: lang.VALIDATE_MAX_WEEK,
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      weeklyNumberCredit: {
        maxLimitZero: lang.VALIDATE_MAX_MONTH,
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      dailyAmountCredit: {
        maxLimitZero: lang.VALIDATE_MAX_WEEK,
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      weeklyAmountCredit: {
        maxLimitZero: lang.VALIDATE_MAX_MONTH,
        pattern: lang.VALIDATE_INVALID_NUMBER,
        required: lang.VALIDATE_NUMBER_REQ,
      },
      transferType: lang.VALIDATE_TRANSFER_TYPE,
      transferAmount: {
        required: lang.VALIDATE_VALID_AMOUNT,
        pattern: lang.VALIDATE_VALID_AMOUNT,
        lessBalance: lang.VALIDATE_LESS_BALANCE,
        dailyQuantity: lang.VALIDATE_DAILY_QUANTITY,
        minAmount: lang.VALIDATE_MIN_AMOUNT,
        maxAmount: lang.VALIDATE_MAX_AMOUNT,
        maxAmountTransDaily: lang.VALIDATE_MAX_AMOUNT_TRANSDAILY,
        maxAmountTransweekly: lang.VALIDATE_MAX_AMOUNT_TRANSWEEKLY,
      },
      description: {
        required: lang.VALIDATE_RECHAR_DESC1,
        pattern: lang.VALIDATE_NAME_BRANCHES,
      },
      branchName: {
        pattern: lang.VALIDATE_NAME_BRANCHES,
      },
      zoneName: {
        pattern: lang.VALIDATE_NAME_BRANCHES,
      },
      address: {
        required: lang.VALIDATE_ADDRESS_ENTERPRICE,
        pattern: lang.VALIDATE_ADDRESS_BRANCHES,
      },
      address1: {
        pattern: lang.VALIDATE_ADDRESS_BRANCHES,
      },
      address2: {
        pattern: lang.VALIDATE_ADDRESS_BRANCHES,
      },
      address3: {
        pattern: lang.VALIDATE_ADDRESS_BRANCHES,
      },
      billingAddress: {
        required: lang.VALIDATE_BILLING_ADDRESS_ENTERPRICE,
        pattern: lang.VALIDATE_ADDRESS_BRANCHES,
      },
      countryCode: {
        required: lang.VALID_REQUIRED,
        pattern: lang.VALIDATE_NIT,
      },
      countryCodBranch: {
        required: lang.VALID_REQUIRED,
        pattern: lang.VALIDATE_NIT,
      },
      stateCodBranch: {
        required: lang.VALIDATE_PROVINCE_BRANCHES,
        pattern: lang.VALIDATE_NIT,
      },
      cityCodBranch: {
        required: lang.VALIDATE_DEPARTMENT_BRANCHES,
        pattern: lang.VALIDATE_NIT,
      },
      districtCodeBranch: {
        required: lang.VALID_REQUIRED,
        pattern: lang.VALIDATE_NIT,
      },
      idFiscalList: {
        required: lang.VALIDATE_SELECT,
        selectRequired: lang.VALIDATE_SELECT_CONTENT,
      },
      idEnterpriseList: {
        required: lang.VALIDATE_SELECT,
      },
      areaCode: {
        pattern: lang.VALIDATE_NIT,
      },
      phone: {
        required: lang.VALID_REQUIRED,
        pattern: lang.VALIDATE_PHONE_NUMBER,
      },
      phone1: {
        required: lang.VALID_REQUIRED,
        pattern: lang.VALIDATE_PHONE_NUMBER,
      },
      phone2: {
        required: lang.VALID_REQUIRED,
        pattern: lang.VALIDATE_PHONE_NUMBER,
      },
      phone3: {
        required: lang.VALID_REQUIRED,
        pattern: lang.VALIDATE_PHONE_NUMBER,
      },
      person: {
        pattern: lang.VALIDATE_NAME_BRANCHES,
      },
      branchCode: {
        pattern: lang.VALIDATE_NIT,
      },
      surnameModifyContact: {
        required: lang.VALID_REQUIRED,
        pattern: lang.VALIDATE_INPUT_SURNAME,
      },
      positionModifyContact: {
        required: lang.VALID_REQUIRED,
        pattern: lang.VALIDATE_INPUT_POSITION,
      },
      typeModifyContact: {
        required: lang.VALIDATE_SELECT,
      },
      contactNames: {
        required: lang.VALIDATE_NAME_BRANCHES,
        pattern: lang.VALIDATE_NAME_BRANCHES,
      },
      contactLastNames: {
        required: lang.VALIDATE_INPUT_SURNAME,
        pattern: lang.VALIDATE_INPUT_SURNAME,
      },
      contactPosition: {
        required: lang.VALIDATE_INPUT_POSITION,
        pattern: lang.VALIDATE_INPUT_POSITION,
      },
      idExtPer: {
        required: lang.VALIDATE_ID_NUMBER,
        pattern: lang.VALIDATE_ID_NUMBER,
      },
      contactType: {
        required: lang.VALIDATE_CONTACT_TYPE_SELECT,
        pattern: lang.VALIDATE_CONTACT_TYPE_SELECT,
      },
      replaceType: lang.VALIDATE_OPTION,
      paymentConcept: lang.VALIDATE_OPTION,
      embLine1: lang.VALIDATE_OPTION,
      embLine2: lang.VALIDATE_OPTION,
    },
    errorPlacement: function (error, element) {
      $(element).closest('.form-group').find('.help-block').html(error.html());
    },
  });

  $.validator.methods.verifyRequired = function (value, element, param) {
    return value != '' && $(param).val() != '';
  };

  $.validator.methods.verifyPattern = function (value, element, param) {
    return userPassword.test(value) && alphanumunder.test($(param).val());
  };

  $.validator.methods.requiredTypeBulk = function (value, element, param) {
    var eval1 = alphanum.test($(element).find('option:selected').attr('format').trim());
    var eval2 = longPhrase.test($(element).find('option:selected').text().trim());
    var eval3 = alphanum.test($(element).find('option:selected').val().trim());
    return eval1 && eval2 && eval3;
  };

  $.validator.methods.requiredBranchOffice = function (value, element, param) {
    return alphanum.test($(element).find('option:selected').val());
  };

  $.validator.methods.fiscalRegistry = function (value, element, param) {
    var RegExpfiscalReg = new RegExp(fiscalReg, 'i');
    return RegExpfiscalReg.test(value);
  };

  $.validator.methods.validatePass = function (value, element, param) {
    return passStrength(value);
  };

  $.validator.methods.differs = function (value, element, param) {
    var target = $(param);
    return value !== target.val();
  };

  $.validator.methods.requiredTypeOrder = function (value, element, param) {
    var eval1 = alphanumunder.test($(element).find('option:selected').text().trim());
    var eval2 = alphanum.test($(element).find('option:selected').val().trim());
    return eval1 && eval2;
  };

  $.validator.methods.sizeFile = function (value, element, param) {
    return element.files[0].size > 0;
  };

  $.validator.methods.requiredSelect = function (value, element, param) {
    var valid = true;
    if ($(element).find('option').length > 0) {
      valid = alphanumunder.test($(element).find('option:selected').val().trim());
    }

    return valid;
  };

  $.validator.methods.staticlength = function (value, element, param) {
    let valid = value.length === param[0];

    return valid;
  };

  $.validator.methods.selectRequired = function (value, element, param) {
    value = value.trim();
    const text = $(element).find('option:selected').text().trim();
    const validValue = param[0].test(value);
    const validtext = param[1].test(text);
    console.log(validValue);
    console.log(validtext);

    return validValue && validtext;
  };

  $.validator.methods.maxcards = function (value, element, param) {
    var valid = true;
    var cardsMax = parseInt($(element).attr('max-cards'));
    var cards = parseInt(value);

    valid = cards > 0;

    if (cardsMax > 0 && valid) {
      valid = cardsMax > cards;
    }

    return valid;
  };

  $.validator.methods.maxLimitZero = function (value, element, param) {
    var valid = false;

    if (Number($(param).val()) == 0) {
      if (value > 0) {
        valid = true;
      } else if (value == Number($(param).val())) {
        valid = true;
      }
    } else if (Number($(param).val()) > 0) {
      if (value <= Number($(param).val())) {
        valid = true;
      }
    }

    return valid;
  };

  $.validator.methods.lessBalance = function (value, element, param) {
    var valid = true;
    value = normalizeAmount(value);

    if (rechargeParam.validateParams && checkType !== lang.VALIDATE_TRANSFERTYPE) {
      if (value > rechargeParam.balance) {
        valid = false;
      } else if (value + rechargeParam.commission > rechargeParam.balance) {
        valid = false;
      }
    }
    return valid;
  };

  $.validator.methods.dailyQuantity = function (value, element, param) {
    var valid = true;

    if (rechargeParam.validateParams) {
      valid =
        rechargeParam.maxQuanTransDaily > 0 && rechargeParam.maxQuanTransDaily < rechargeParam.accumTransDaily + 1
          ? false
          : true;
    }
    return valid;
  };

  $.validator.methods.minAmount = function (value, element, param) {
    var valid = true;

    if (rechargeParam.validateParams) {
      valid = rechargeParam.minAmount > 0 && value < rechargeParam.minAmount ? false : true;
    }
    return valid;
  };

  $.validator.methods.maxAmount = function (value, element, param) {
    var valid = true;

    if (rechargeParam.validateParams) {
      valid = rechargeParam.maxAmount > 0 && value > rechargeParam.maxAmount ? false : true;
    }
    return valid;
  };

  $.validator.methods.maxAmountTransDaily = function (value, element, param) {
    var valid = true;

    if (rechargeParam.validateParams) {
      valid =
        rechargeParam.maxAmountTransDaily > 0 && value + rechargeParam.dailyAmount > rechargeParam.maxAmountTransDaily
          ? false
          : true;
    }
    return valid;
  };

  $.validator.methods.maxAmountTransweekly = function (value, element, param) {
    var valid = true;

    if (rechargeParam.validateParams) {
      valid =
        rechargeParam.maxAmountWeek > 0 && value + rechargeParam.weeklyAmount > rechargeParam.maxAmountWeek
          ? false
          : true;
    }
    return valid;
  };

  form.validate().resetForm();
}
