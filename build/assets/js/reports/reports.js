'use strict'
$(function () {
	/* var optionValues = [];
	var prevOption;
	$('#reports option').each(function () {
		optionValues.push($(this).val());
	});

	$(".reports-form").delay(2000).removeClass('none');

	optionValues.splice(0, 2);

	for (i = 0; i < optionValues.length; i++) {
		$(`#${optionValues[i]}`).hide();
	}; */

	$("#reports").change(function () {
		$('.no-select').addClass('none')
		var type = $(this).find('option:selected').attr('type')

		if (type !== 'DOWNLOAD') {
			$('#search-criteria, #line-reports, #form-report').removeClass('none')
		}

		switch (type) {
			case 'DOWNLOAD':
				$("#div-download").removeClass('none');
				$("#div-download").fadeIn(700, 'linear');
				break;
			case 'FILTER':

				break;
			case 'TABLE':

				break;
			case 'QUERY':

				break;
		}




		/* if ($(this).val() == "customer-movements") {
			$("#search-criteria").addClass('none');
			$("#line-reports").addClass('none');
			$("#div-download").removeClass('none');
			$("#div-download").fadeIn(700, 'linear');;
		} else {
			$("#search-criteria").removeClass('none');
			$("#line-reports").removeClass('none');
			$("#div-download").addClass('none');
		}
		$('#' + $(this).val()).fadeIn(700, 'linear');
		$(prevOption).hide();
		$('#' + $(this).val()).show();
		prevOption = '#' + $(this).val(); */
	});

	$("#datepicker_start, #datepicker_end").datepicker({
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
		dateFormat: 'MM yy',
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
		var reportSelected = $('#reports').val()
		data = {
			operation: reportSelected
		}
		getReport(data)
	})
})

function getReport(data) {
	insertFormInput(true);
	verb = 'POST'; who = 'Reports'; where = 'getReport';
	var downloadFile = $('#download-file');
	callNovoCore(verb, who, where, data, function (response) {
		insertFormInput(false);
		if(response.code == 0) {
			switch (data.operation) {
				case 'repListadoTarjetas':
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
		$('.cover-spin').hide();
	})
}
