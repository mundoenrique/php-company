'use strict'
var authBulkDetail;
$(function() {
	authBulkDetail = $('#auth-bulk-detail').DataTable({
		drawCallback: function(d) {
			$('#pre-loader').remove();
			$('.hide').removeClass('hide');
		},
    "ordering": false,
    "pagingType": "full_numbers",
    "columnDefs": [
      {"targets": 3,
      render: function ( data, type, row ) {
        return data.length > 20 ?
				  data.substr( 0, 20 ) +'…' :
				  data;
        }
      },
    ],
    "language": dataTableLang
	});



});
