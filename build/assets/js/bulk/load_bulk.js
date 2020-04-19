'use strict'
var pendingBulk;
$(function () {
	if(code > 2 ) {
		$('#content-datatable').addClass('none');
		$('#no-bulk').removeClass('none');
	}
	var file = $('#file-bulk');
	var inputFile = file.next('.js-label-file').html().trim();
	var selectBranchOffice = $('#branch-office');
	var selectTypeBulk = $('#type-bulk');
	var uploadFileBtn = $('#upload-file-btn');
	form = $('#upload-file-form');

	$('.input-file').each(function () {
    var input = $(this);
		var label = input.next('.js-label-file');
    var labelVal = label.html();

    input.on('change', function (element) {
      var fileName = '';
      if (element.target.value) fileName = element.target.value.split('\\').pop();
      fileName ? label.addClass('has-file').find('.js-file-name').html(fileName) : label.removeClass('has-file').html(labelVal);
    });
	});

	selectTypeBulk.on('change', function() {
		var getBranchOffices = [lang.BULK_GET_BRANCHOFFICE];
		if(getBranchOffices != []) {
			getBranchOffices.indexOf($(this).val()) != -1
			? selectBranchOffice.prop('disabled', false).parent().removeClass('hide')
			: selectBranchOffice.prop('disabled', true).parent().addClass('hide');
		}

	});

	uploadFileBtn.on('click', function(e) {
		e.preventDefault();
		var btnAction = $(this);
		btnText = btnAction.text().trim();
		validateForms(form);

		if(form.valid()) {
			btnAction.html(loader);
			data = {
				file: file[0].files[0],
				branchOffice: selectBranchOffice.val(),
				typeBulk: selectTypeBulk.val(),
				formatBulk: $('#type-bulk option:selected').attr('format'),
				typeBulkText: $('#type-bulk option:selected').text()
			}
			insertFormInput(true);
			verb = 'POST'; who = 'Bulk'; where = 'LoadBulk';
			callNovoCore(verb, who, where, data, function(response) {
				btnAction.html(btnText);
				insertFormInput(false);
				file.val('');
				file.next('.js-label-file').html(inputFile);
				selectBranchOffice.prop('selectedIndex', 0);
				selectTypeBulk.prop('selectedIndex', 0);
				respLoadBulk[response.code](response);
			});
		}
	});

	const respLoadBulk = {
		2: function(response) {
			notiSystem(response.title, response.msg, response.icon, response.data);
		},
		3: function(response) {
			var msgModal = '';

			$.each(response.msg, function(item, content) {
				if(item == 'header') {
					$.each(content, function(index, value) {
						msgModal+= '<h5 class="regular mr-1">'+value+'</h5>';
					});
				}

				if(item == 'fields') {
					$.each(content, function(index, value) {
						msgModal+= '<h5>'+index+'</h5>';
						$.each(value, function(pos, val) {
							msgModal+= '<h6 class="light mr-1">'+val+'</h6>';
						})
					});
				}
			});

			notiSystem(response.title, msgModal, response.icon, response.data);
		}
	}

  pendingBulk = $('#pending-bulk').DataTable({
		drawCallback: function(d) {
			$('#pre-loader').remove();
			$('#content-datatable').removeClass('hide');
		},
    "ordering": false,
		"pagingType": "full_numbers",
		"columns": [
			null,
			null,
			null,
			{ "width": "160px" },
			{ "width": "130px" },
		],
		"columnDefs": [{
			"targets": 1,
			render: function ( data, type, row ) {
				return data.length > 20 ?
        	data.substr( 0, 20 ) +'…' :
        	data;
      }
		}],
		"autoWidth": false,
    "language": dataTableLang
	});

	$('#pending-bulk').on('click', 'button', function(e) {
		e.preventDefault();
		var event = $(e.currentTarget);
		var action = event.attr('title');
		form = $(this).parent().find('form')
		insertFormInput(true, form);

		switch(action) {
			case lang.GEN_BTN_SEE:
				form.attr('action', baseURL+'detalle-lote');
				form.append('<input type="hidden" name="bulkView" value="detail">');
				break;
			case lang.GEN_BTN_CONFIRM:
				form.attr('action', baseURL+'confirmar-lote');
				form.append('<input type="hidden" name="bulkView" value="confirm">');
				break;
			case lang.GEN_BTN_DELETE:
				var oldID = $('#accept').attr('id');
				$(this).closest('tr').addClass('select');
				$('#accept').attr('id', 'delete-bulk-btn');
				var inputModal;
				data = {
					btn1: {
						text: lang.GEN_BTN_DELETE,
						action: 'none'
					},
					btn2: {
						action: 'close'
					}
				}
				var bulkFile = $(this).closest('tr').find('td:nth-child(3)').text();
				inputModal = '<form id="delete-bulk-form" class="form-group">';
				inputModal+= '<span class="regular"> '+lang.BULK_DELETE_DATE+': '+bulkFile+'</span>';
				inputModal+= 		'<input id="password" class="form-control mt-2 h6 col-9 pwd-input" name="password" type="password" autocomplete="off" placeholder="'+lang.GEN_PLACE_PASSWORD+'">';
				inputModal+= 		'<div class="help-block"></div>';
				inputModal+= '</form>';
				notiSystem(lang.BULK_DELETE_TITLE, inputModal, lang.GEN_ICON_INFO, data);
				deleteBulk(oldID);
				$('#cancel').on('click', function(e){
					e.preventDefault();
					$('#pending-bulk').find('tr').removeClass('select');
					$('#delete-bulk-btn').attr('id', oldID);
				});
				break;
		}

		if(action != lang.GEN_BTN_DELETE) {
			form.submit();
		}

		insertFormInput(false);
	});

});
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
			inputPass = cryptoPass($('#password').val());
			data = {
				modalReq: true,
				bulkId: form.find('input[name="bulkId"]').val(),
				bulkTicked: form.find('input[name="bulkTicked"]').val(),
				bulkStatus: form.find('input[name="bulkStatus"]').val(),
				pass: inputPass
			}
			verb = 'POST'; who = 'Bulk'; where = 'DeleteNoConfirmBulk';
			callNovoCore(verb, who, where, data, function(response) {

				if(response.cod == 0) {
					pendingBulk.row('.select').remove().draw(false);
				}
			});
		}
	});
}
