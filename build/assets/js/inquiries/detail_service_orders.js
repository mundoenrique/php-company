'use strict'
var resultServiceOrders;
$(function() {

	resultServiceOrders = $('#authLotDetail').DataTable({
		drawCallback: function(d) {
			$('#pre-loader').remove();
			$('.hide-out').removeClass('hide');
		},
    "ordering": false,
    "pagingType": "full_numbers",
    "language": dataTableLang
	});

})
