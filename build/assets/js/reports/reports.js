'use strict'
$(function () {
	var optionValues = [];
	var prevOption;
	var reportSelected;
	form = $('#form-report')

	$('#reports option').each(function () {
		optionValues.push($(this).val());
	});

	$(".reports-form").delay(2000).removeClass('none');

	optionValues.splice(0, 2);

	for (var i = 0; i < optionValues.length; i++) {
		$(`#${optionValues[i]}`).hide();
	}

	$("#reports").change(function () {
		$('#form-report').trigger("reset")
		$('#form-report input, #form-report select')
		.removeClass('has-error')
		.prop('disabled', true);
		$('.help-block').text('');
		reportSelected = $(this).val()

		if ($(this).val() == "repListadoTarjetas") {
			$("#search-criteria").addClass('none');
			$("#line-reports").addClass('none');
			$("#div-download").removeClass('none');
			$("#div-download").fadeIn(700, 'linear');;
		} else {
			$("#search-criteria").removeClass('none');
			$("#line-reports").removeClass('none');
			$("#div-download").addClass('none');
		}

		$('#' + $(this).val())
		.fadeIn(700, 'linear')
		.find('input, select')
		.prop('disabled', false)
		$(prevOption).hide();
		$('#' + $(this).val()).show();
		prevOption = '#' + $(this).val();
		data = {
			operation: reportSelected
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
		}
	});

	$('#reports-results').DataTable({
		"ordering": false,
		"pagingType": "full_numbers",
		"columnDefs": [
			{
				"targets": 3,
				render: function (data, type, row) {
					return data.length > 20 ?
						data.substr(0, 20) + '…' :
						data;
				}
			},
		],
		"language": dataTableLang
	})

	$('#btn-download').on('click', function (e) {
		e.preventDefault();
		getReport(data)
	})

	$('.btn-report').on('click', function(e) {
		e.preventDefault()

		var btnAction = $(this);
		btnText = btnAction.text().trim();
		validateForms(form);

		if(form.valid()) {
			var tempId;
			var tempVal;
			btnAction.html(loader);
			insertFormInput(true);
			$('#'+reportSelected+' input').each(function(index, element) {
				tempId = $(element).attr('id')
				tempVal = $(element).val()
				data[tempId] = tempVal
			})
			getReport(data, btnAction)
		}
	})
})

function getReport(data, btn) {
	btn = btn == undefined ? false : btn
	insertFormInput(true);
	verb = 'POST'; who = 'Reports'; where = 'getReport';
	var downloadFile = $('#download-file');
	callNovoCore(verb, who, where, data, function (response) {
		if(response.code == 0) {
			switch (data.operation) {
				case 'repListadoTarjetas':
				case 'repMovimientoPorEmpresa':
				case 'repComprobantesVisaVale':
					downloadFile.attr('href', response.data.file)
					document.getElementById('download-file').click()
					who = 'DownloadFiles'; where = 'DeleteFile';
					data = {
						fileName: response.data.name
					}
					callNovoCore(verb, who, where, data, function (response) {})
					break;

				default:
					break;
			}
		}
		if(btn) {
			btn.html(btnText)
		}
		insertFormInput(false);
		$('.help-block').text('');
		$('#form-report').validate().resetForm();
		$('#form-report input, #form-report select')
		.not('#'+data.operation+' input', '#'+data.operation+' select')
		.prop('disabled', true);
		$('.cover-spin').hide();
	})
}
