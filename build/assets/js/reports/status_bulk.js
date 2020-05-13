'use strict'
var reportsResults;
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	$('#resultStatusBulk').DataTable({
		"ordering": false,
		"responsive": true,
		"pagingType": "full_numbers",
		"language": dataTableLang
	});

	$('#initialDate, #finalDate').datepicker({
		onSelect: function (selectedDate) {
			var dateSelected = selectedDate.split('/');
			dateSelected = dateSelected[1] + '/' + dateSelected[0] + '/' + dateSelected[2]
			var inputDate = $(this).attr('id');
			var maxTime = new Date(dateSelected);

			if (inputDate == 'initialDate') {
				$('#finalDate').datepicker('option', 'minDate', selectedDate);
				maxTime.setDate(maxTime.getDate() - 1);
				maxTime.setMonth(maxTime.getMonth() + 3);

				if (currentDate > maxTime) {
					$('#finalDate').datepicker('option', 'maxDate', maxTime);
				}
			}

			if (inputDate == 'finalDate') {
				$('#initialDate').datepicker('option', 'maxDate', selectedDate);
			}

			if ($('#initialDate').val() != '' || $('#finalDate').val() != '') {
				$('input:radio').prop('checked', false);
				firstDate = $('#initialDate').val();
				lastdate = $('#finalDate').val();
			}
		}
	});
});
