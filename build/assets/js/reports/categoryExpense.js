'use strict';
var reportsResults;
$(function () {
  $('#pre-loader').remove();
  $('.hide-out').removeClass('hide');

	if ($("input[name='results']:checked").val() != 0) {
		$("#initialDate ").attr('required', 'required');
		$("#finalDate ").attr('required', 'required');
	}
  $('#range').attr('checked', true);
  $("#annual").val('12');
	$("#range").val('0');

	var datePicker = $('.date-picker');
		datePicker.datepicker({
		onSelect: function (selectedDate) {
			$(this)
				.focus()
				.blur();
			var dateSelected = selectedDate.split('/');
			dateSelected = dateSelected[1] + '/' + dateSelected[0] + '/' + dateSelected[2];
			dateSelected = new Date(dateSelected);
			var inputDate = $(this).attr('id');

			if (inputDate == 'initialDate') {
				$('#finalDate').datepicker('option', 'minDate', selectedDate);
				var maxTime = new Date(dateSelected.getFullYear(), dateSelected.getMonth() + lang.SETT_DATEPICKER_MONTHRANGE, dateSelected.getDate() - 1);

				if (currentDate > maxTime) {
					$('#finalDate').datepicker('option', 'maxDate', maxTime);
				} else {
					$('#finalDate').datepicker('option', 'maxDate', currentDate);
				}
			}
		}
	});

  $("#radio-form").on('change', function(){
		$('#finalDate').removeClass('has-error');
		$('#initialDate').removeClass('has-error');
		$(".help-block").text("");
		if ($("input[name='results']:checked").val() != 0) {
      $(".year").removeClass('hide')
      $(".range").addClass("hide")
      $(".search-bnt").addClass("col-6")
		} else if ($("input[name='results']:checked").val() == 0 ){
      $(".range").removeClass('hide')
      $(".year").addClass("hide")
      $(".search-bnt").removeClass("col-6")
      $(".search-bnt").addClass("col-3")
		}
  });

  $('#resultsAccount').DataTable({
    ordering: false,
    responsive: true,
    pagingType: 'full_numbers',
    language: dataTableLang,
  });
});
