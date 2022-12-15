'use strict'
var table;
var tableContact;

$(function () {

	if ( $('#idEnterpriseList').attr("countEnterpriseList")==1 ) {
		form = $('#enterpriseSettListForm');
		validateForms(form);
			if (form.valid()) {
			getContacts(getDataForm(form));
			}
	};

	if ( lang.CONF_SETTINGS_PHONES_UPDATE == 'OFF' && $('#idEnterpriseList>option:selected').attr("countEnterpriseList")==1 ) {
		enablePhone();
	};

	/*$('#enterpriseList').on('change', function(e) {
		//e.preventDefault();

		//form = $('#enterpriseSettListForm');
		//validateForms(form);
		//if (form.valid()) {
		//	getContacts(getDataForm(form));
		//}
		data = {
			idFiscalList : $("option:selected", '#enterpriseList').val()
		};
		//console.log(data);
		getContacts(data);
	});*/


	/*$('#updateEnterpriceBtn').on('click', function (e) {
		e.preventDefault();
		form = $('#enterpriseDataForm');
		btnText = $(this).text().trim();

		switch (lang.CONF_LINK_UPDATE_ADDRESS_ENTERPRICE) {
			case 'changeTelephones':
				$("#address").addClass("ignore");
				$("#billingAddress").addClass("ignore");
				$("#phone2").addClass("ignore");
				$("#phone3").addClass("ignore");
				break;
			case 'changeDataEnterprice':
				$("#phone1").addClass("ignore");
				$("#phone2").addClass("ignore");
				$("#phone3").addClass("ignore");
				break;
		}

		validateForms(form);

		if (form.valid()) {
			who = 'Settings';
			where = lang.CONF_LINK_UPDATE_ADDRESS_ENTERPRICE;
			data = getDataForm(form);
			$(this).html(loader);
			insertFormInput(true);

			callNovoCore(who, where, data, function (response) {
				dataResponse = response.data;
				insertFormInput(false);
				$('#updateEnterpriceBtn').html(btnText);
			})
		}
	})*/

	/*$('#showContacts').on('click', function (e) {
		e.preventDefault;

		if (table != undefined) {
			table.destroy();
		}

		data = {"acrif": $('#idFiscal').val()}
		insertFormInput(true);

		getContacts(data);
	});*/

	/*$('#btnAddContact').on('click', function (e) {
		e.preventDefault;

		form = $('#addContactForm');

		validateForms(form);

		if (form.valid()) {
			data = getDataForm(form);
			data.idExper = $('#dniNewContact').val();
			data.acrif = $('#enterpriseList').find('option:selected').attr('idFiscal');

			if (lang.CONF_REMOTE_AUTH == 'OFF') {
				data.pass = cryptoPass($('#newContPass').val().trim());
			}

			addContact(data);
		}
	});*/

	/*$('#tableContacts1').DataTable({
		"autoWidth": false,
		"ordering": false,
		"searching": true,
		"lengthChange": false,
		"pagelength": 10,
		"pagingType": "full_numbers",
		"table-layout": "fixed",
		"columnDefs": [
			{
				"targets": 0,
				"className": "branchName",
				"width": "200px"
			},
			{
				"targets": 1,
				"className": "branchCode",
				"width": "200px"
			},
			{
				"targets": 2,
				"className": "contact",
				"width": "200px",
			},
			{
				"targets": 3,
				"className": "phone",
				"width": "auto"
			},
			{
				"targets": 4,
				"width": "auto"
			}
		],
	});*/

	/*$('#newContactBtn').on('click', function(e) {
		showManageContactView("create")
	});*/

	/*$('#backContactBtn').on('click', function(e) {
		$('#sectionConctact').fadeIn(700, 'linear');
		$('#btnSaveContact').removeAttr('data-action')
		$('#editAddContactSection').hide();
	});*/

	$('#idEnterpriseList').on('change', function (e) {
		e.preventDefault();
		var optionSelect = $(this).find('option:selected');
		$('#enterpriseData').addClass('hide');
		$('.hide-out').removeClass('hide');
		$('#sectionConctact').show();
		$('#existingContactButton').show();

		$.each( lang.SETTINGS_RENDER_CONTROLLER_VARIABLES, function( key ) {
			$('#'+ key).val(optionSelect.attr(key));
		});

		if ( lang.CONF_SETTINGS_PHONES_UPDATE == 'OFF' ) {
			enablePhone();
		};

		/*if ($('#existingContacts')[0] != undefined) {
			$('#tableContacts_wrapper').hide();
		}*/

		$('.hide-out').addClass('hide');
		$('#enterpriseData').removeClass('hide');

		form = $('#enterpriseSettListForm');
		validateForms(form);
			if (form.valid()) {
			getContacts(getDataForm(form));
			}
	});
});

/*function enablePhone(){
	for ( let i = 1; i < 4; i++ ) {
		if($('#phone'+ i).val()==''){
			$('#divPhone'+ i).addClass('hide');
		}else{
			$('#divPhone'+ i).removeClass('hide');
		}
	}
}*/

/*function getContacts (data) {
	who = 'Settings';
	where = 'getContacts';

	callNovoCore(who, where, data, function(response) {
		insertFormInput(false);

		if ( response.code == 0 ) {
			$('#tableContacts').show();
			var i = 1;

			table = $('#tableContacts').DataTable({
				"drawCallback": function( settings ) {
					$("#tableContacts thead").remove();
					$('#existingContactButton').removeClass('hidden');
				 },
				"autoWidth": false,
				"ordering": false,
				"searching": false,
				"bLengthChange": false,
				"pageLength": 1,
				"pagingType": "full_numbers",
				"table-layout": "fixed",
				"data": response.data,
				"columnDefs": [
					{
						"targets": 0,
					},
				],
				"columns": [
					{
						data: function (data) {
							var inputs;

							inputs = '<form id="existingContacts'+ data.id +'" type="post"><div class="row">';
							inputs +=	'<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">';
              inputs += '<label for="nameModifyContact'+ data.id +'">Nombre</label>';
              inputs += '<input value='+ data.nombres +' id="nameModifyContact'+ data.id +'" name="person" type="text" class="form-control" />';
              inputs += '<div class="help-block"></div>';
              inputs += '</div>';
							inputs +=	'<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">';
              inputs += '<label for="surnameModifyContact'+ data.id +'">Apellido</label>';
              inputs += '<input value='+ data.apellido +' id="surnameModifyContact'+ data.id +'" name="surnameModifyContact" type="text" class="form-control" />';
              inputs += '<div class="help-block"></div>';
              inputs += '</div>';
							inputs +=	'<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">';
              inputs += '<label for="positionModifyContact'+ data.id +'">Cargo</label>';
              inputs += '<input value='+ data.cargo +' id="positionModifyContact'+ data.id +'" name="positionModifyContact" type="text" class="form-control" />';
              inputs += '<div class="help-block"></div>';
              inputs += '</div>';
							inputs +=	'<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">';
              inputs += '<label for="dniModifyContact'+ data.id +'">DNI</label>';
              inputs += '<input value='+ data.idExtPer +' id="dniModifyContact'+ data.id +'" name="zoneName" type="text" class="form-control" />';
              inputs += '<div class="help-block"></div>';
              inputs += '</div>';
							inputs +=	'<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">';
              inputs += '<label for="emailModifyContact'+ data.id +'">Email</label>';
              inputs += '<input type="email" value='+ data.email +' id="emailModifyContact'+ data.id +'" name="email" class="form-control" />';
              inputs += '<div class="help-block"></div>';
              inputs += '</div>';
							inputs +=	'<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">';
              inputs += '<label for="typeModifyContact'+ data.id +'">Tipo</label>';
              inputs += '<input value='+ data.tipoContacto +' id="typeModifyContact'+ data.id +'" name="typeModifyContact" type="text" class="form-control" />';
              inputs += '<div class="help-block"></div>';
							inputs += '<div class="col-6 col-lg-4 col-xl-3 input-group">';
							inputs += '<label for="modifyContactPass'+ data.id +'"></label>';
							inputs += '<input id="modifyContactPass'+ data.id +'" class="form-control pwd-input" autocomplete="new-password" name="password" placeholder="Ingresa tu contraseña">';
							inputs += '<div class="input-group-append">';
							inputs += '<span id="pwd-addon" class="input-group-text pwd-action" title="'+ lang.GEN_SHOW_PASS+ '"><i	class="icon-view mr-0"></i></span>';
							inputs += '</div><div class="help-block"></div></div>';
							inputs += '</div></form>';

							return inputs
						}
					},
				],
				"language": dataTableLang,
			});

			$('#deleteContact').on('click', function (e) {
				e.preventDefault;
				deleteContact();
			});

			$('#btnLimpiar').on('click', function (e) {
				$('#addContactForm')[0].reset();
			});
		};

		$("#tableContacts tbody ").find("tr").css('background-color', 'none !important');
			var paginateCurrent = $('#tableContacts_paginate .current').text();

			$('#tableContacts_paginate').on('click', {param: paginateCurrent},  function (e) {
				paginateCurrent = $('#tableContacts_paginate .current').text();
				return paginateCurrent;
			});

			$('#modifyContact').on('click', function (e) {
				e.preventDefault;
				var form = $('#existingContacts' + $('#tableContacts_paginate .current').text());
				insertFormInput(true);
				validateForms(form)

				data = getDataForm(form)

				data.acrif = $('#enterpriseList').find('option:selected').attr('idFiscal');


				if (lang.CONF_REMOTE_AUTH == 'OFF') {
					data.modifyContactPass = cryptoPass($('#modifyContactPass' + $('#tableContacts_paginate .current').text()).val().trim());
				}

				if (form.valid()) {
					updateContact(data);
				}
			});
	});
};*/

/*function deleteContactM(data) {
	who = 'Settings';
	where = 'deleteContact';

	callNovoCore(who, where, data, function (response) { });
};*/

/*function addContact(data) {
	who = 'Settings';
	where = 'addContact';

	callNovoCore(who, where, data, function (response) {
		insertFormInput(false);

		if ( response.code ==  0) {
			$('#addContactForm')[0].reset();
		};
	});
};*/

/*function updateContact(data) {
	who = 'Settings';
	where = 'updateContact';

	callNovoCore(who, where, data, function (response) {
		dataResponse = response;
		insertFormInput(false);

		if ( dataResponse.code ==  0) {
			$( "#showContacts" ).trigger( "click" );
			$('#branchInfoForm')[0].reset();
		};
	});
};*/

/*function deleteContact(){
	form = $('#existingContacts')
	data = {
		"acrif" : $('#enterpriseList').find('option:selected').attr('idFiscal'),
		"idExper": $('#dniModifyContact').val(),
	}

	if (lang.CONF_REMOTE_AUTH == 'OFF') {
		data.pass = cryptoPass($('#modifyContactPass').val().trim());
	}

	deleteContactM(data);
}*/

/*function showManageContactView(action) {
	$('#sectionConctact').hide();
	$('#editAddContactSection').fadeIn(700, 'linear');
	$('.has-error').removeClass("has-error");
	$('.help-block').text('');
	switch (action) {
		case "create":
			$('#btnSaveContact').attr('data-action', 'saveCreate');
			$('#editAddContactText').html(lang.SETTINGS_BTN_NEW +' '+ lang.GEN_CONTAC_PERSON.toLowerCase());
			$('#ContactInfoForm')[0].reset();
			break;
		case "update":
			$('#btnSaveContact').attr('data-action', 'saveUpdate');
			$('#editAddContactText').html(lang.GEN_EDIT +' '+ lang.GEN_CONTAC_PERSON.toLowerCase());
			$('#password1').val('');
			break;
	}
}*/

function getContacts(value) {
	if (table != undefined) {
		table.destroy();
	}
	data = value;
	who = 'Settings';
	where = 'getContacts';

	callNovoCore(who,where,data, function(response) {
		insertFormInput(false);
		if ( response.code == 0 ) {
			contactsTable(response);
		}else if (response.code == 1){
			contactsTable(response);
		}
	});
};

function contactsTable(dataResponse) {
	table = $('#tableContacts1').DataTable({
		"autoWidth": false,
		"ordering": false,
		"searching": true,
		"lengthChange": false,
		"pagelength": 10,
		"pagingType": "full_numbers",
		"table-layout": "fixed",
		"data": dataResponse.data,
		"language": dataTableLang,
		"columnDefs": [
			{
				"targets": 0,
				"className": "contactNames",
				"width": "200px"
			},
			{
				"targets": 1,
				"className": "contactLastNames",
				"width": "200px"
			},
			{
				"targets": 2,
				"className": "contactPosition",
				"width": "200px",
			},
			{
				"targets": 3,
				"className": "idExtPer",
				"width": "200px",
			},
			{
				"targets": 4,
				"className": "contactEmail",
				"width": "200px",
			},
			{
				"targets": 5,
				"className": "typeContact",
				"width": "200px",
			},
			{
				"targets": 6,
				"width": "auto"
			}
		],
		"columns": [
			{ data: 'contactNames' },
			{ data: 'contactLastNames' },
			{ data: 'contactPosition' },
			{ data: 'idExtPer' },
			{ data: 'contactEmail' },
			{ data: 'typeContact' },
			{
				data: function (data) {
					var options = '';
					options += '<button value="'+ data.id +'" class="edit btn mx-1 px-0" title="'+lang.GEN_EDIT+'" data-action="update" data-toggle="tooltip">';
					options += '<i class="icon icon-edit"></i>';
					options += '</button>';
					return options;
				}
			}
		],
	});
	return table;
};
