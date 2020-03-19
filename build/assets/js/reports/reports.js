'use strict'
//4193280000300118
//C_1234567890
$(function () {
	var optionValues = [];
	var optiondiv = [];
	var prevOption;
	var reportSelected;
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
		$(`#${optiondiv[i]}`).hide();
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
			$("#div-download").fadeIn(700, 'linear');
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

		if ($(this).val() == "repMovimientoPorTarjeta") {
			$('#MovimientoPorTarjeta button').removeClass('none')
			$('#idType option').attr('disabled', false)
			$('#MovimientoPorTarjeta input').prop('readonly', false)
			$('#result-repMovimientoPorTarjeta').addClass('none')
			$('#result-repMovimientoPorTarjeta input, #result-repMovimientoPorTarjeta select').prop('disabled', true)
		}
		$('#cardNumberId').empty()
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
				case 'repListadoTarjetas':
				case 'repMovimientoPorEmpresa':
				case 'repMovimientoPorTarjeta':
				case 'repComprobantesVisaVale':
					downloadFile.attr('href', response.data.file)
					document.getElementById('download-file').click()
					who = 'DownloadFiles'; where = 'DeleteFile';
					data = {
						fileName: response.data.name
					}
					callNovoCore(verb, who, where, data, function (response) {})
					break;
				case 'repTarjetasPorPersona':
					var option;
					data.operation = 'repMovimientoPorTarjeta';
					$('#MovimientoPorTarjeta button').addClass('none')
					$('#MovimientoPorTarjeta input').prop('readonly', true);
					$('#idType option:not(:selected)').attr('disabled',true)

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
					var reportsResults = $("#reports-results").dataTable()
					reportsResults.fnDestroy();

					var file = '<tr class="select">';
					file+= '<td>'+response.data.idType+'</td>';
					file+= '<td>'+response.data.idNumber+'</td>';
					file+= '<td>'+response.data.userName+'</td>';
					file+= '<td>'+response.data.cardNumber+'</td>';
					file+= '<td>'+response.data.product+'</td>';
					file+= '<td>'+response.data.createDate+'</td>';
					file+= '<td>'+response.data.Expirydate+'</td>';
					file+= '<td>'+response.data.currentState+'</td>';
					file+= '<td>'+response.data.activeDate+'</td>';
					file+= '<td>'+response.data.reasonBlock+'</td>';
					file+= '<td>'+response.data.dateBlock+'</td>';
					file+= '<td>'+response.data.currentBalance+'</td>';
					file+= '<td>'+response.data.lastCredit+'</td>';
					file+= '<td>'+response.data.lastAmoutn+'</td>';
					file+= '<td>'+response.data.chargeGMF+'</td>';
					file+= '</tr>'
					$('#reports-results').append(file)
					$('#reports-results').DataTable({
						drawCallback: function(d) {
							$('input[type=search]').attr('name', 'search')
							$('#repTarjeta-result').removeClass('none');
						},
						"ordering": false,
						"pagingType": "full_numbers",
						responsive: true,
						"columnDefs": [
							{
								"targets": 3,
								render: function (data, type, row) {
									return data.length > 20 ?
										data.substr(0, 20) + '…' :
										data;
								}
							},
							{ "targets": 6,className: "none" },
							{ "targets": 7,className: "none" },
							{ "targets": 8,className: "none" },
							{ "targets": 9,className: "none" },
							{ "targets": 10,className: "none" },
							{ "targets": 11,className: "none" },
							{ "targets": 12,className: "none" },
							{ "targets": 13,className: "none" },
							{ "targets": 14,className: "none" }
						],
						"language": dataTableLang
					})

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
