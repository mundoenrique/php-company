'use strict'
var resultServiceOrders;
$(function() {
	var serviceOrdersBtn = $('#service-orders-btn');
	var firstDate;
	var lastdate;
	form = $('#service-orders-form');
	resultServiceOrders = $('#resultServiceOrders').DataTable({
		drawCallback: function(d) {
			$('#pre-loader').remove();
			$('.hide-out').removeClass('hide');
		},
    "ordering": false,
    "pagingType": "full_numbers",
    "language": {
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix": "",
      "slengthMenu": "Mostrar _MENU_ registros por pagina",
      "sSearch": "",
      "sSearchPlaceholder": "Buscar...",
      "sUrl": "",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
      "sprocessing": "Procesando ...",
      "oPaginate": {
        "sFirst": "««",
        "sLast": "»»",
        "sNext": "»",
        "sPrevious": "«"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }
	});
	$('#resultServiceOrders tbody').on('click', 'button.details-control', function(){
    var tr = $(this).closest('tr');
		var row = resultServiceOrders.row( tr );
		var bulk = $(this).closest('tr').attr('bulk')

    if(row.child.isShown()){
			row.child.hide();
			tr.removeClass('shown');
    } else {
			row.child(format(bulk)).show();
			tr.addClass('shown');
    }
	});

	$('#datepicker_start, #datepicker_end').datepicker({
		onSelect: function(selectedDate) {
			var dateSelected = selectedDate.split('/');
			dateSelected = dateSelected[1]+'/'+dateSelected[0]+'/'+dateSelected[2]
			var inputDate = $(this).attr('id');
			var maxTime = new Date(dateSelected);

			if(inputDate == 'datepicker_start'){
				$('#datepicker_end').datepicker('option', 'minDate', selectedDate);
				maxTime.setDate(maxTime.getDate() - 1);
				maxTime.setMonth(maxTime.getMonth() + 3);

				if(currentDate > maxTime) {
					$('#datepicker_end').datepicker('option', 'maxDate', maxTime);
				}
			}

			if(inputDate == 'datepicker_end'){
				$('#datepicker_start').datepicker('option', 'maxDate', selectedDate);
			}

			if($('#datepicker_start').val() != '' && $('#datepicker_end').val() != '') {
				$('input:radio').prop('checked', false);
				firstDate = $('#datepicker_start').val();
				lastdate = $('#datepicker_end').val();
			}
		}
  });

	$(":radio").on("change", function() {
		$('#datepicker_start, #datepicker_end').datepicker('setDate', null);
		$('.help-block').text('');
		form.validate().resetForm();
		var timeBefore = parseInt($(this).val());
		var initDate = new Date();
		var finalDate = new Date();
		initDate.setDate(initDate.getDate() - timeBefore);
		initDate = initDate.getDate()+'/'+(initDate.getMonth()+1)+'/'+initDate.getFullYear();
		finalDate = finalDate.getDate()+'/'+(finalDate.getMonth()+1)+'/'+finalDate.getFullYear();
		firstDate = initDate;
		lastdate = finalDate;
	});

	serviceOrdersBtn.on('click', function(e) {
		e.preventDefault();
		var btnAction = $(this);
		var statusOrder = $('#status-order');
		btnText = btnAction.text().trim();
		formInputTrim(form);
		validateForms(form);

		if(form.valid()) {
			btnAction.html(loader);
			data = {
				initialDate: firstDate,
				finalDate: lastdate,
				status: statusOrder.val(),
				statusText: statusOrder.find('option:selected').text()
			}
			insertFormInput(true);
			verb = 'POST'; who = 'Inquiries'; where = 'GetServiceOrders';
			callNovoCore(verb, who, where, data, function(response) {
				if(response.code == 0) {
					$(location).attr('href', response.data);
				} else {
					notiSystem(response.title, response.msg, response.icon, response.data);
					btnAction.html(btnText);
					insertFormInput(false);
				}
			});
		}

	});
})

function format (bulk) {
	var table, body = '';
	bulk = JSON.parse(bulk)
	$.each(bulk, function(key, value){
		body+= '<tr>';
		body+= 	'<td>'+value.bulkNumber+'</td>';
		body+= 	'<td>'+value.bulkLoadDate+'</td>';
		body+= 	'<td>'+value.bulkLoadType+'</td>';
		body+= 	'<td>'+value.bulkRecords+'</td>';
		body+= 	'<td>'+value.bulkStatus+'</td>';
		body+= 	'<td>'+value.bulkAmount+'</td>';
		body+= 	'<td>'+value.bulkCommisAmount+'</td>';
		body+= 	'<td>'+value.bulkTotalAmount+'</td>';
		body+= '</tr>';
	})
	table = '<table class="detail-lot h6 cell-border primary semibold" style="width:100%">';
	table+= 	'<tbody>';
	table+= 		'<tr class="bold" style="margin-left: 0px;">';
	table+= 			'<td>'+lang.GEN_TABLE_BULK_NUMBER+'</td>';
	table+= 			'<td>'+lang.GEN_TABLE_BULK_DATE+'</td>';
	table+= 			'<td>'+lang.GEN_TABLE_BULK_TYPE+'</td>';
	table+= 			'<td>'+lang.GEN_TABLE_BULK_RECORDS+'</td>';
	table+= 			'<td>'+lang.GEN_TABLE_BULK_STATUS+'</td>';
	table+= 			'<td>'+lang.GEN_TABLE_BULK_AMOUNT+'</td>';
	table+= 			'<td>'+lang.GEN_TABLE_COMMISSION+'</td>';
	table+= 			'<td>'+lang.GEN_TABLE_DEPOSIT_AMOUNT+'</td>';
	table+= 		'</tr>';
	table+= 		body;
	table+= 	'</tbody>';
	table+= '</table>';

	return table;
}
