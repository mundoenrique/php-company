'use strict'
var signBulk;
var authorizeBulk;
$(function () {
	var sign = getPropertyOfElement('sign', '#sign-bulk');
	var auth = getPropertyOfElement('auth', '#authorize-bulk');
	var modalReq = {};
	var signBulkDtn = $('#sign-bulk-btn');
	var delSignBulkDtn = $('#del-sign-bulk-btn');

	signBulk = $('#sign-bulk').DataTable({
    "ordering": false,
    "pagingType": "full_numbers",
    "columnDefs": [
      {
        "targets": 0,
        "className": "select-checkbox",
				"checkboxes": {"selectRow": true}
      },
      {
        "targets": 2,
        "visible": false
      },
      {
        "targets": 5,
        "visible": false
      }
		],
		"autoWidth": false,
    "select": {
      "style": lang.GEN_TABLE_SELECT_SIGN,
			selector: ':not(td:nth-child(-n+6))'
    },
    "language": dataTableLang
	});

  $('#sign-bulk').on('click', '.toggle-all', function () {
    $(this).closest('tr').toggleClass('selected');
    if ($(this).closest('tr').hasClass('selected')) {
      signBulk.rows().select();
    } else {
      signBulk.rows().deselect();
    }
  });

  authorizeBulk = $('#authorize-bulk').DataTable({
		drawCallback: function(d) {
			$('#pre-loader').remove();
			$('.hide-out').removeClass('hide');
		},
		"autoWidth": false,
    "ordering": false,
    "pagingType": "full_numbers",
    "columnDefs": [
			{
				"targets": 0,
				"className": "select-checkbox",
				"checkboxes": {"selectRow": true},
				'visible': auth != false,
      	render: function (data, type, row) {
					var content = '';
					if(data != '') {
						content = '<button class="btn px-0" title="'+lang.GEN_BTN_DISASS_AUTH+'" data-toggle="tooltip">';
						content+= 	'<i class="icon icon-user" aria-hidden="true"></i>';
						content+= '</button>';
					}
					return content;
        }
      },
			{
        "targets": 2,
        "visible": false
      },
      {
        "targets": 5,
        "visible": false
      }
		],
		"select": {
      "style": lang.GEN_TABLE_SELECT_AUTH,
      "info": false,
      selector: ':not(td:nth-child(-n+6))'
    },
    "language": dataTableLang
	});

	$('#authorize-bulk').on('click', '.toggle-all', function () {
    $(this).closest('tr').toggleClass('selected');
    if ($(this).closest('tr').hasClass('selected')) {
      authorizeBulk.rows(':not(.no-select-checkbox)').select();
    } else {
      authorizeBulk.rows().deselect();
    }
	});

	$('#sign-bulk-btn, #del-sign-bulk-btn, #auth-bulk-btn, #del-auth-bulk-btn').on('click', function(e) {
		e.preventDefault()
		var action = $(this).text().trim();
		var thisId = $(this).attr('id');
		var passwordSignAuht = $(this).closest('form').find('input[type=password]');
		form = $(this).closest('form');
		modalReq['active'] = false;
		SignDeleteBulk(form, action, thisId, passwordSignAuht, modalReq)
	});

	$('#sign-bulk, #authorize-bulk').on('click', 'button', function(e) {
		e.preventDefault();
		var event = $(e.currentTarget);
		var action = event.attr('title');
		var submitForm = false;
		$(this).closest('tr').addClass('select');

		switch(action) {
			case lang.GEN_BTN_SEE:
				/* form.attr('action', baseURL+'detalle-lote');
				form.append('<input type="hidden" name="bulkView" value="detail">');
				submitForm = true; */
				break;
			case lang.GEN_BTN_CONFIRM:
				/* form.attr('action', baseURL+'confirmar-lote');
				form.append('<input type="hidden" name="bulkView" value="confirm">');
				submitForm = true; */
				break;
			case lang.GEN_BTN_DELETE:
			case lang.GEN_BTN_DISASS_SIGN:
				var oldId = $('#accept').attr('id');
				var currentIdBtn = 'delete-bulk-btn';
				var cancelDelete = $('#cancel');
				$('#accept').attr('id', currentIdBtn);
				var bulkNum = $(this).closest('tr').find('td:nth-child(2)').text();
				var inputModal;
				modalReq['table'] = $(this).closest('table');
				data = {
					btn1: {
						text: lang.GEN_BTN_DELETE,
						action: 'close'
					},
					btn2: {
						action: 'close'
					}
				}
				inputModal = '<form id="delete-bulk-form" class="form-group">';
				inputModal+= '<span class="regular"> '+lang.BULK_DELETE+': '+bulkNum+'</span>';
				inputModal+= 		'<input id="password" class="form-control mt-2 h6 col-9" name="password" type="password" ';
				inputModal+=		'autocomplete="off" placeholder="'+lang.GEN_PLACE_PASSWORD+'">';
				inputModal+= 		'<div class="help-block"></div>';
				inputModal+= '</form>';
				notiSystem(lang.BULK_DELETE_TITLE, inputModal, lang.GEN_ICON_INFO, data);
				$('#'+currentIdBtn).on('click', function(e) {
					e.preventDefault();
					form = $('#delete-bulk-form');
					var passwordDelete = $('#password');
					modalReq['active'] = true;
					modalReq['oldId'] = oldId;
					SignDeleteBulk(form, action, currentIdBtn, passwordDelete, modalReq);
				});
				cancelDelete.on('click', function(e){
					$('#'+currentIdBtn)
					.off('click')
					.attr('id', oldId);
				});
				break;
		}

		if(submitForm) {
			form.submit();
		}

	});
});
/**
 * @info Firma o elimina un lote
 * @author J. Enrique Peñaloza Piñero
 * @date December 27th, 2019
 */
function SignDeleteBulk(currentForm, action, btnId, passwordInput, modalReq) {
	formInputTrim(currentForm);
	validateForms(currentForm);
	var bulkData;
	var tableSelected
	var classSelect = '.selected:not(.no-select-checkbox)'
	switch(currentForm.attr('id')) {
		case 'sign-bulk-form':
			tableSelected = signBulk;
			break;
		case 'auth-bulk-form':
			tableSelected = authorizeBulk;
			break;
		default:
			classSelect = '.select';

			if(modalReq.table.attr('id') == 'sign-bulk') {
				tableSelected = signBulk;
			}

			if(modalReq.table.attr('id') == 'authorize-bulk') {
				tableSelected = authorizeBulk;
			}
	}

	bulkData = tableSelected.rows(classSelect).data();

	if(bulkData.length == 0 ) {
		currentForm.validate().resetForm();
		currentForm.find('.bulk-select').text(lang.BULK_SELECT);
	}

	if(bulkData.length > 0 && form.valid()) {
		var bulkInfo = [];
		var btnAction = $('#'+btnId);
		btnText = btnAction.text().trim();
		btnAction.html(loader);

		for(var i = 0; i < bulkData.length; i++) {
			var info = {};
			info['bulkNumber'] = bulkData[i][1];
			info['bulkId'] = bulkData[i][2];
			info['bulkIdType'] = bulkData[i][5];
			bulkInfo.push(JSON.stringify(info));
		}

		inputPass = cryptoPass(passwordInput.val());
		data = {
			modalReq: modalReq.active,
			bulk: bulkInfo,
			pass: inputPass
		}

		if(currentForm.attr('id') == 'auth-bulk-form') {
			data['typeOrder'] = $('#type-order').val()
		}

		if(modalReq.active) {
			btnAction
			.off('click')
			.prop('disabled', true)
			.attr('id', modalReq.oldId);
		}

		insertFormInput(true);

		switch(action) {
			case lang.GEN_BTN_SIGN:
				where = 'SignBulkList';
				break;
			case lang.GEN_BTN_AUTHORIZE:
				where = 'AuthorizeBulk';
				break;
			case lang.GEN_BTN_DELETE:
				where = 'DeleteConfirmBulk';
				break;
			case lang.GEN_BTN_DISASS_SIGN:
				where = 'DisassConfirmBulk';
				break;
		}

		verb = 'POST'; who = 'Bulk';
		callNovoCore(verb, who, where, data, function(response) {

			if(response.code == 0 && where == 'AuthorizeBulk') {
				$(location).attr('href', response.data);
			} else {
				notiSystem(response.title, response.msg, response.icon, response.data);
				btnAction.html(btnText);
				insertFormInput(false);
				passwordInput.val('');
				tableSelected.rows().deselect();
			}
		});
	}
}
