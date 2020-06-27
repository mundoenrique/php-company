'use strict'
var reportsResults;
currentDate = new Date();
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	$('#resultsAccount').DataTable({
		"ordering": false,
		"responsive": true,
		"pagingType": "full_numbers",
		"language": dataTableLang
	});

	$("#initialDate").datepicker({
		dateFormat: 'mm/yy',
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		maxDate: "+0D",
		closeText: 'Aceptar',
		yearRange: '-10:' + currentDate.getFullYear(),

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

	$("#initialDate").focus(function () {
		$(".ui-datepicker-calendar").hide();
		$("#ui-datepicker-div").position({
			my: "center top",
			at: "center bottom",
			of: $(this)
		});
	});
});
