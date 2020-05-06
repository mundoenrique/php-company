'use strict'
var inventoryBulkResults;

$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	var firstDate;
	var lastdate;

	inventoryBulkResults = $('#inventoryBulkResults').DataTable({
		drawCallback: function (d) {
			$('#pre-loader').remove();
			$('.hide-out').removeClass('hide');
		},
		"ordering": false,
		"pagingType": "full_numbers",
		"language": dataTableLang
	});

	$('#datepicker_start, #datepicker_end').datepicker({
		onSelect: function (selectedDate) {
			var dateSelected = selectedDate.split('/');
			dateSelected = dateSelected[1] + '/' + dateSelected[0] + '/' + dateSelected[2]
			var inputDate = $(this).attr('id');
			var maxTime = new Date(dateSelected);

			if (inputDate == 'datepicker_start') {
				$('#datepicker_end').datepicker('option', 'minDate', selectedDate);
				maxTime.setDate(maxTime.getDate() - 1);
				maxTime.setMonth(maxTime.getMonth() + 3);

				if (currentDate > maxTime) {
					$('#datepicker_end').datepicker('option', 'maxDate', maxTime);
				}
			}

			if (inputDate == 'datepicker_end') {
				$('#datepicker_start').datepicker('option', 'maxDate', selectedDate);
			}

			if ($('#datepicker_start').val() != '' || $('#datepicker_end').val() != '') {
				$('input:radio').prop('checked', false);
				firstDate = $('#datepicker_start').val();
				lastdate = $('#datepicker_end').val();
			}
		}
	});
});
