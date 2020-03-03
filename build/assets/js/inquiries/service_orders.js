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
    "language": dataTableLang
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

			$('#detailServiceOrders').on('click','#numberid',function(){

				insertFormInput(true, $("#detail-orders-form"));

				$("#detail-orders-form").submit();

			})
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

	$('#resultServiceOrders').on('click', 'button', function(e) {
		e.preventDefault();
		var event = $(e.currentTarget);
		var action = event.attr('title');
		form = $(this).parent().find('form')
		insertFormInput(true, form);

		switch(action) {
			case lang.GEN_BTN_DOWN_PDF:
				form.attr('action', baseURL+'descargar-archivo-os');
				break;
			case lang.GEN_BTN_CONFIRM:
				form.attr('action', baseURL+'confirmar-lote');
				form.append('<input type="hidden" name="bulkView" value="confirm">');
				break;
			case lang.GEN_BTN_CANCEL_ORDER:
				var oldID = $('#accept').attr('id');
				$(this).closest('tr').addClass('select');
				$('#accept').attr('id', 'delete-bulk-btn');
				var inputModal;
				data = {
					btn1: {
						text: lang.GEN_BTN_CANCEL_ORDER,
						action: 'none'
					},
					btn2: {
						action: 'close'
					}
				}
				inputModal = '<form id="delete-bulk-form" class="form-group">';
				inputModal+= '<span class="regular"> Por favor ingresa la contraseña para anular la orden de servicio </span>';
				inputModal+= 		'<input id="password" class="form-control mt-2 h6 col-9" name="password" type="password" autocomplete="off" placeholder="'+lang.GEN_PLACE_PASSWORD+'">';
				inputModal+= 		'<div class="help-block"></div>';
				inputModal+= '</form>';
				notiSystem('Anular orden de servicio', inputModal, lang.GEN_ICON_INFO, data);
				deleteBulk(oldID);
				$('#cancel').on('click', function(e){
					e.preventDefault();
					$('#pending-bulk').find('tr').removeClass('select');
					$('#delete-bulk-btn').attr('id', oldID);
				});
				break;
		}

		insertFormInput(false);

		if(action == lang.GEN_BTN_DOWN_PDF) {
			form.submit();
		}

	});

})

function format (bulk) {

	var table, body = '';
	bulk = JSON.parse(bulk)
	$.each(bulk, function(key, value){
		body+= '<tr>';
		body+= 	'<td ><a id=numberid class="btn-link">'+value.bulkNumber+'</a></td>';
		body+= 	'<td>'+value.bulkLoadDate+'</td>';
		body+= 	'<td>'+value.bulkLoadType+'</td>';
		body+= 	'<td>'+value.bulkRecords+'</td>';
		body+= 	'<td>'+value.bulkStatus+'</td>';
		body+= 	'<td>'+value.bulkAmount+'</td>';
		body+= 	'<td>'+value.bulkCommisAmount+'</td>';
		body+= 	'<td>'+value.bulkTotalAmount+'<form id="detail-orders-form" class="form-group" action="'+baseURL+'detalle-orden-de-servicio" method="post"><input type="hidden" id="numberOrden" name="numberOrden" value='+value.bulkacidlote+'></form></td>';
		body+= '</tr>';

	})
	table = '<table id="detailServiceOrders" class="detail-lot h6 cell-border primary semibold" style="width:100%">';
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

/**
 * @info Elimina un lote
 * @author J. Enrique Peñaloza Piñero
 * @date December 18th, 2019
 */
function deleteBulk(oldID) {
	var deleteBulkBtn = $('#delete-bulk-btn')
	var formDeleteBulk = $('#delete-bulk-form');
	deleteBulkBtn.on('click', function() {
		formInputTrim(formDeleteBulk);
		validateForms(formDeleteBulk);

		if(formDeleteBulk.valid()) {
			$(this)
			.off('click')
			.html(loader)
			.prop('disabled', true)
			.attr('id', oldID);
			ceo_cook = getCookieValue();
			cypherPass = CryptoJS.AES.encrypt($('#password').val(), ceo_cook, { format: CryptoJSAesJson }).toString();
			data = {
				modalReq: true,
				idOS: form.find('input[name="idOS"]').val(),
				pass: btoa(JSON.stringify({
					passWord: cypherPass,
					plot: btoa(ceo_cook)
				}))
			}
			verb = 'POST'; who = 'Inquiries'; where = 'ClearServiceOrders';
			callNovoCore(verb, who, where, data, function(response) {

				if(response.cod == 0) {
					resultServiceOrders.row('.select').remove().draw(false);
				}
			});
		}
	});
}
