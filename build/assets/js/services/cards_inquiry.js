'use strict'
var reportsResults;
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	$('#tableCardInquiry').DataTable({
		"ordering": false,
		"responsive": true,
		"pagingType": "full_numbers",
		"columnDefs": [
      {
        "targets": 0,
        "className": "select-checkbox",
				"checkboxes": {"selectRow": true}
      }
		],
    "select": {
      "style": lang.GEN_TABLE_SELECT_SIGN,
			selector: ':not(td:nth-child(-n+6))'
    },
		"language": dataTableLang
	});
});
