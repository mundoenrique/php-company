'use strict'
var reportsResults;
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
		$('#'+optiondiv[i]+'').hide();
	}

	$("#reports").change(function () {
		$('#form-report').trigger("reset")
		$('#form-report input, #form-report select')
		.removeClass('has-error')
		.prop('disabled', true);
		$('.help-block').text('');
		$("#idType").prop('selectedIndex',0);
		reportSelected = $(this).val()

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
		}
		$('#cardNumberId').empty()
		$('#repTarjeta-result').addClass('none');
		reportsResults.row('tr').remove().draw( false );

		if(reportSelected == 'repCertificadoGmf' && !firsrYear) {
			data = {
				btn1: {
					text: lang.GEN_BTN_ACCEPT,
					link: 'reportes',
					action: 'redirect'
				}
			}
			appMessages(lang.REPORTS_TITLE, lang.REPORTS_NO_GMF, lang.GEN_ICON_INFO, data);
		}
	});

	$(".date-picker").datepicker({
		changeMonth: true,
		changeYear: true,
		maxDate: currentDate,
		yearRange: '-10:' + currentDate.getFullYear(),
		showAnim: "slideDown",
		beforeShow: function (input, inst) {
			inst.dpDiv.removeClass("ui-datepicker-month-year");
		},
		onSelect: function (selectDate) {
			$(this)
				.focus()
				.blur();
		}
	});

	$('.month-year').datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		yearRange: "-20:+0",
		maxDate: '-M',
		dateFormat: 'mm/yy',
		closeText: 'Aceptar',
		onClose: function (dateText, inst) {
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			$(this).datepicker('setDate', new Date(year, month, 1));
		},
		beforeShow: function (input, inst) {
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			inst.dpDiv.addClass("ui-datepicker-month-year");
			$(this).datepicker('option', 'defaultDate', new Date(year, month, 1));
		},
		onSelect: function (selectDate) {
			$(this)
				.focus()
				.blur();
		}
	});

	$('.year').datepicker({
		changeMonth: false,
		changeYear: true,
		showButtonPanel: true,
		yearRange: firsrYear+':'+currentDate.getFullYear(),
		dateFormat: 'yy',
		closeText: 'Aceptar',
		onClose: function (dateText, inst) {
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			$(this).datepicker('setDate', new Date(year, 1));
		},
		beforeShow: function (input, inst) {
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			inst.dpDiv.addClass("ui-datepicker-month-year");
			$(this).datepicker('option', 'defaultDate', new Date(year, 1));
		}
	});


	$('#btn-download').on('click', function (e) {
		e.preventDefault();
		getReport(data)
	})

	$('.btn-report').on('click', function(e) {
		e.preventDefault()
		var btnAction = $(this);
		var cardsPeople = btnAction.attr('cards')
		var tempId;
		var tempVal;
		btnText = btnAction.text().trim();
		$('#repTarjeta-result').addClass('none');
		reportsResults.row('tr').remove().draw( false );
		validateForms(form);

		if(form.valid()) {

			data.operation = cardsPeople || data.operation
			btnAction.html(loader);
			insertFormInput(true);
			$('#'+reportSelected+' input, #'+reportSelected+' select')
			.not('[type=search]')
			.each(function(index, element) {
				tempId = $(element).attr('id')
				tempVal = $(element).val()
				data[tempId] = tempVal;
			})
			getReport(data, btnAction)
		}
	})

	$("#idType").change(function () {

		$('#result-repMovimientoPorTarjeta').find('input, select').prop('disabled', true).val("");
		$('#result-repMovimientoPorTarjeta').removeClass('has-error').addClass('none');
		$('#cardNumberId').empty();
		$('#MovimientoPorTarjeta input').prop('readonly', false).val("");
		$('#MovimientoPorTarjeta button').removeClass('none');
	});

	reportsResults = $('#reports-results').DataTable({
		drawCallback: function(d) {
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

})

function getReport(data, btn) {
	var disabledNot = false;
	btn = btn == undefined ? false : btn
	insertFormInput(true);
	verb = 'POST'; who = 'Reports'; where = 'getReport';
	var downloadFile = $('#download-file');
	callNovoCore(verb, who, where, data, function (response) {
		if(response.code == 0) {
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
					who = 'DownloadFiles'; where = 'DeleteFile';
					data.fileName = response.data.name
					callNovoCore(verb, who, where, data, function (response) {})
					break;
				case 'repTarjetasPorPersona':
					var option;
					data.operation = 'repMovimientoPorTarjeta';
					$('#MovimientoPorTarjeta button').addClass('none')
					$('#MovimientoPorTarjeta input').prop('readonly', true);

					$.each(response.data, function(index, element) {
						option = '<option value="'+element.key+'">'+element.cardMask+'</option>'
						$('#cardNumberId').append(option)
					});

					disabledNot = '#result-repMovimientoPorTarjeta input, #result-repMovimientoPorTarjeta select'
					$('#result-repMovimientoPorTarjeta')
					.find('input, select')
					.prop('disabled', false)
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
		if(btn) {
			btn.html(btnText)
		}
		insertFormInput(false);
		$('.help-block').text('');
		$('#form-report').validate().resetForm();
		data.operation = data.operation == 'repTarjetasPorPersona' ? 'MovimientoPorTarjeta' : data.operation;
		$('#form-report input, #form-report select')
		.not('#'+data.operation+' input, #'+data.operation+' select')
		.not(disabledNot)
		.prop('disabled', true);
		$('.cover-spin').hide();
	})
}
