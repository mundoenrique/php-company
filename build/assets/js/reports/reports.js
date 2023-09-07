'use strict'
var reportsResults;
var radioType = 'input:radio[name=results]';
$(function () {
	var optionValues = [];
	var optiondiv = [];
	var prevOption;
	var reportSelected;
	var firsrYear = getPropertyOfElement('firts-year', '#repCertificadoGmf');
	form = $('#form-report')

	$('#reports option').each(function () {
		optionValues.push($(this).val());
	});

	$('#form-report > div').each(function () {
		optiondiv.push($(this).attr('id'));
	});

	$(".reports-form").delay(2000).removeClass('none');
	optionValues.splice(0, 2);

	for (var i = 0; i < optiondiv.length; i++) {
		$('#' + optiondiv[i] + '').hide();
	}

	$("#reports").change(function () {
		$('#form-report').trigger("reset")
		$('#form-report input, #form-report select')
			.removeClass('has-error')
			.prop('disabled', true);
		$('.help-block').text('');
		$("#idType").prop('selectedIndex', 0);
		reportSelected = $(this).val();

		if (reportSelected == "repListadoTarjetas") {
			$("#search-criteria").addClass('none');
			$("#line-reports").addClass('none');
			$("#div-download").removeClass('none');
			$("#div-download").fadeIn(700, 'linear');
		} else {
			$("#search-criteria").removeClass('none');
			$("#line-reports").removeClass('none');
			$("#div-download").addClass('none');
		}

		$('#' + reportSelected)
			.fadeIn(700, 'linear')
			.find('input, select')
			.prop('disabled', false)
		$(prevOption).hide();
		$('#' + reportSelected).show();
		prevOption = '#' + reportSelected;
		data = {
			operation: reportSelected
		}

		if (reportSelected == "repMovimientoPorTarjeta") {
			$('#MovimientoPorTarjeta button').removeClass('none')
			$('#MovimientoPorTarjeta input').prop('readonly', false)
			$('#result-repMovimientoPorTarjeta').addClass('none')
			$('#result-repMovimientoPorTarjeta input, #result-repMovimientoPorTarjeta select').prop('disabled', true)
			$('#sectionByIdNumber').addClass('none')
			$('#sectionByCard').addClass('none')
		}

		$('#cardNumberId').empty()
		$('#repTarjeta-result').addClass('none');
		reportsResults.row('tr').remove().draw(false);

		if (reportSelected == 'repCertificadoGmf' && !firsrYear) {
			modalBtn = {
				btn1: {
					text: lang.GEN_BTN_ACCEPT,
					link: lang.SETT_LINK_REPORTS,
					action: 'redirect'
				}
			}
			appMessages(lang.REPORTS_TITLE, lang.REPORTS_NO_GMF, lang.SETT_ICON_INFO, modalBtn);
		}

		$('.month-year').datepicker({
			showButtonPanel: true,
			dateFormat: 'mm/yy',
			onClose: function (dateText, inst) {
				var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
				var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
				$(this).datepicker('setDate', new Date(year, month, 1));
				$(this)
					.focus()
					.blur();
			},
			beforeShow: function (input, inst) {
				var minDate, maxDate, month, year;

				switch (reportSelected) {
					case 'repComprobantesVisaVale':
						minDate = '-12M';
						break;
					case 'repExtractoCliente':
						minDate = new Date(params['minYear'], params['minMonth'] - 1, params['minDay']);
						maxDate = new Date(params['maxYear'], params['maxMonth'] - 1, params['maxDay']);
						break;
				}

				$(this).datepicker('option', 'minDate', minDate);
				$(this).datepicker('option', 'maxDate', maxDate);

				month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
				year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
				inst.dpDiv.addClass("ui-datepicker-month-year");
				$(this).datepicker('option', 'defaultDate', new Date(year, month, 1));
			}
		});
	});

	$(".date-picker").datepicker({
		beforeShow: function (input, inst) {
			inst.dpDiv.removeClass("ui-datepicker-month-year");
		},
		onSelect: function (selectedDate) {
			$(this)
				.focus()
				.blur();
			var dateSelected = selectedDate.split('/');
			dateSelected = dateSelected[1] + '/' + dateSelected[0] + '/' + dateSelected[2];
			dateSelected = new Date(dateSelected);
			var inputDate = $(this).attr('id');

			if (inputDate == 'enterpriseDateBegin') {
				$('#enterpriseDateEnd').datepicker('option', 'minDate', dateSelected);
				var maxTime = new Date(dateSelected.getFullYear(), dateSelected.getMonth() + lang.SETT_DATEPICKER_MONTHRANGE, dateSelected.getDate() - 1);

				if (currentDate > maxTime) {
					$('#enterpriseDateEnd').datepicker('option', 'maxDate', maxTime);
				} else {
					$('#enterpriseDateEnd').datepicker('option', 'maxDate', currentDate);
				}
			}
		}
	});

	$(".date-picker-card").datepicker({
		minDate: '-12m',
		beforeShow: function (input, inst) {
			inst.dpDiv.removeClass("ui-datepicker-month-year");
		},
		onSelect: function (selectedDate) {
			$(this)
				.focus()
				.blur();
			var dateSelected = selectedDate.split('/');
			dateSelected = dateSelected[1] + '/' + dateSelected[0] + '/' + dateSelected[2];
			dateSelected = new Date(dateSelected);
			var inputDate = $(this).attr('id');

			if (inputDate == 'cardDateBegin') {
				$('#cardDateEnd').datepicker('option', 'minDate', dateSelected);
				var maxTime = new Date(dateSelected.getFullYear(), dateSelected.getMonth() + lang.SETT_DATEPICKER_MONTHRANGE, dateSelected.getDate() - 1);

				if (currentDate > maxTime) {
					$('#cardDateEnd').datepicker('option', 'maxDate', maxTime);
				} else {
					$('#cardDateEnd').datepicker('option', 'maxDate', currentDate);
				}
			}
		}
	});

	$('.year').datepicker({
		changeMonth: false,
		showButtonPanel: true,
		dateFormat: 'yy',
		onClose: function (dateText, inst) {
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			$(this).datepicker('setDate', new Date(year, 1));
		},
		beforeShow: function (input, inst) {
			inst.dpDiv.addClass("ui-datepicker-month-year");
		}
	});

	$('#btn-download').on('click', function (e) {
		e.preventDefault();
		getReport(data)
	});

	$('.btn-report').on('click', function (e) {
		e.preventDefault()
		var btnAction = $(this);
		var cardsPeople = btnAction.attr('cards')
		var tempId;
		var tempVal;
		btnText = btnAction.text().trim();
		$('#repTarjeta-result').addClass('none');
		reportsResults.row('tr').remove().draw(false);
		validateForms(form);

		if (form.valid()) {
			data.operation = cardsPeople || data.operation;
			btnAction.html(loader);
			insertFormInput(true);

			$('#' + reportSelected + ' input, #' + reportSelected + ' select')
				.not('[type=search], [type=radio], .ignore')
				.each(function (index, element) {
					tempId = $(element).attr('id')
					tempVal = $(element).val()
					data[tempId] = tempVal;
				});

			if (data.operation == "repMovimientoPorTarjeta" && $(radioType + ':checked').val() == "ByCard") {
				data.cardNumber = data.cardNumber2;
				delete data.cardNumber2;
			}

			getReport(data, btnAction)
		}
	});

	$("#idType").change(function () {

		$('#result-repMovimientoPorTarjeta').find('input, select').prop('disabled', true).val("");
		$('#result-repMovimientoPorTarjeta').removeClass('has-error').addClass('none');
		$('#cardNumberId').empty();
		$('#MovimientoPorTarjeta input').not(radioType).prop('readonly', false).val("");
		$('#MovimientoPorTarjeta button').removeClass('none');
	});

	reportsResults = $('#reports-results').DataTable({
		drawCallback: function (d) {
			$('input[type=search]').attr('name', 'search')
		},
		"ordering": false,
		"pagingType": "full_numbers",
		responsive: true,
		"columnDefs": [
			{ "targets": 6, className: "none" },
			{ "targets": 7, className: "none" },
			{ "targets": 8, className: "none" },
			{ "targets": 9, className: "none" },
			{ "targets": 10, className: "none" },
			{ "targets": 11, className: "none" },
			{ "targets": 12, className: "none" },
			{ "targets": 13, className: "none" },
			{ "targets": 14, className: "none" }
		],
		"language": dataTableLang
	})

	$(radioType).change(function () {
		data = {
			operation: reportSelected
		};

		if ($(this).attr('value') == 'byIdNumber') {
			$('#sectionByCard, #result-repMovimientoPorTarjeta').find('input, select')
				.prop('disabled', true).val("")
				.addClass('ignore');
			$('#sectionByCard, #result-repMovimientoPorTarjeta').removeClass('has-error').addClass('none');

			$('#sectionByIdNumber').find('input, select')
				.prop('disabled', false)
				.removeClass('ignore');
			$('#sectionByIdNumber').removeClass('none');
		} else {
			$('#sectionByIdNumber, #result-repMovimientoPorTarjeta').find('input, select')
				.prop('disabled', true).val("")
				.addClass('ignore');
			$('#sectionByIdNumber, #result-repMovimientoPorTarjeta').removeClass('has-error').addClass('none');

			$('#sectionByCard').find('input, select')
				.prop('disabled', false)
				.prop('readonly', false)
				.removeClass('ignore');
			$('#sectionByCard, #sectionByCard button').removeClass('none');
		}
	});

	$('#sectionByIdNumber').on('keyup keypress', function (e) {
		var keyCode = e.keyCode || e.which;
		if (keyCode === 13) {
			e.preventDefault();
			$('#repTarjetasPorPersona').trigger("click");
		}
	});

})

function getReport(data, btn) {
	var disabledNot = false;
	btn = btn == undefined ? false : btn
	insertFormInput(true);
	who = 'Reports';
	where = 'getReport';
	var downloadFile = $('#download-file');

	callNovoCore(who, where, data, function (response) {
		if (response.code == 0) {
			switch (data.operation) {
				case 'repMovimientoPorTarjeta':
					$('#result-repMovimientoPorTarjeta').addClass('none')
					$('#result-repMovimientoPorTarjeta input, #result-repMovimientoPorTarjeta select').prop('disabled', true)
					$('#cardNumberId').empty()
					$('#MovimientoPorTarjeta button').removeClass('none')
					$('#MovimientoPorTarjeta input').prop('readonly', false)
				case 'repListadoTarjetas':
				case 'repMovimientoPorEmpresa':
				case 'repComprobantesVisaVale':
				case 'repExtractoCliente':
				case 'repCertificadoGmf':
					downloadFile.attr('href', response.data.file)
					document.getElementById('download-file').click()
					who = 'DownloadFiles';
					where = 'DeleteFile';
					data.fileName = response.data.name

					callNovoCore(who, where, data, function (response) { })
					break;
				case 'repTarjetasPorPersona':
					var option;
					data.operation = 'repMovimientoPorTarjeta';
					$('#MovimientoPorTarjeta button').addClass('none')
					$('#MovimientoPorTarjeta input').prop('readonly', true);

					$.each(response.data, function (index, element) {
						option = '<option value="' + element.key + '">' + element.cardMask + '</option>'
						$('#cardNumberId').append(option)
					});

					disabledNot = '#result-repMovimientoPorTarjeta input, #result-repMovimientoPorTarjeta select'
					$('#result-repMovimientoPorTarjeta')
						.find('input, select')
						.prop('disabled', false)
						.removeClass('ignore');
					$('#result-repMovimientoPorTarjeta')
						.removeClass('none')
					break;
				case 'repTarjeta':
					$('#repTarjeta-result').removeClass('none');

					reportsResults.row.add([
						response.data.idType,
						response.data.idNumber,
						response.data.userName,
						response.data.cardNumber,
						response.data.product,
						response.data.createDate,
						response.data.Expirydate,
						response.data.currentState,
						response.data.activeDate,
						response.data.reasonBlock,
						response.data.dateBlock,
						response.data.currentBalance,
						response.data.lastCredit,
						response.data.lastAmoutn,
						response.data.chargeGMF
					]).draw(false);
					break;
			}
		}
		if (btn) {
			btn.html(btnText)
		}
		insertFormInput(false);
		$('.help-block').text('');
		$('#form-report').validate().resetForm();
		data.operation = data.operation == 'repTarjetasPorPersona' ? 'MovimientoPorTarjeta' : data.operation;
		$('#form-report input, #form-report select')
			.not('#' + data.operation + ' input, #' + data.operation + ' select')
			.not(disabledNot)
			.prop('disabled', true);
		$('.cover-spin').hide();
	})
}

function resetInput(form) {
	form.find('input:text').val('').removeAttr('aria-describedby');
	form.find('.help-block').text('');
	form.find('.has-error').removeClass('has-error');
}
