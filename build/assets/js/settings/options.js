'use strict'
var table;
var tableContact;
var geo;

$(function () {

	if ( lang.CONF_SETTINGS_PHONES_UPDATE == 'OFF' && $('#enterpriseList>option:selected').attr("countEnterpriseList")==1 ) {
		enablePhone();
	};

	var ulOptions = $('.nav-item-config');
	$('#existingContactButton').addClass('hidden');
	$('#tableContacts').hide();
	$('#sectionConctact').hide();

	$.each(ulOptions, function (pos, liOption) {
		$('#' + liOption.id).on('click', function (e) {
			var liOptionId = e.currentTarget.id;
			$(ulOptions).removeClass('active');
			$('.option-service').hide();
			$('#sectionConctact').hide();
			$('#tableContacts_wrapper').hide();
			$(this).addClass('active');
			$('#' + liOptionId + 'View').fadeIn(700, 'linear');
		})
	})

	$('.slide-slow').on('click', function () {
		$('.section').slideToggle('slow');
	})

	$('ul.nav-config-box, .slide-slow').on('click', function (e) {
		var event = $(e.currentTarget)

		if (!event.hasClass('slide-slow')) {
			$('.section').hide();
		}

		$('input, select').removeClass('has-error');
		$('.help-block').text('');

		if ($('#enterpriseList > option').length > 1) {
			$('#enterpriseList').prop('selectedIndex', 0);
			$('#enterpriseDataForm')[0].reset();
			$('#passwordChangeForm')[0].reset();
			$('#enterpriseData').addClass('hide');
		}
	})


	$('.nav-item-config:first-child').addClass('active');
	var firstActive = $('.nav-config-box > li:first-child').attr('id');
	$('#' + firstActive + 'View').show();

	$('#enterpriseList').on('change', function () {
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

		if ($('#existingContacts')[0] != undefined) {
			$('#tableContacts_wrapper').hide();
		}

		$('.hide-out').addClass('hide');
		$('#enterpriseData').removeClass('hide');
	})

	$('#userDataBtn').on('click', function (e) {
		e.preventDefault();
		form = $('#userDataForm');
		btnText = $(this).text().trim();
		validateForms(form);

		if (form.valid()) {
			who = 'Settings';
			where = 'changeEmail';
			data = getDataForm(form);
			data.email = $('#currentEmail').val().toLowerCase();
			$(this).html(loader);
			insertFormInput(true);

			callNovoCore(who, where, data, function (response) {
				dataResponse = response.data
				insertFormInput(false);
				$('#userDataBtn').html(btnText)
			})
		}
	})

	$('#updateEnterpriceBtn').on('click', function (e) {
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
	})

	$.each(lang.SETTINGS_FILES_DOWNLOAD, function( index, header ) {
		$.each(header, function( index2, detail ) {
			if (detail[3] == 'request') {
				$('a.' + detail[0]).on('click', function () {
					if ($(this).attr('title') == '') {
						who = 'Settings';
						where = 'GetFileIni';
						data = {};
						callNovoCore(who, where, data, function (response) {
							if (response.code == 0) {
								downLoadfiles(response.data);
							}

							$('.cover-spin').hide();
						})
					}
				})
			}
		});
	});

	$('#showContacts').on('click', function (e) {
		e.preventDefault;

		if (table != undefined) {
			table.destroy();
		}

		data = {"acrif": $('#idFiscal').val()}
		insertFormInput(true);

		getContacts(data);
	});

	$('#btnAddContact').on('click', function (e) {
		e.preventDefault;

		form = $('#formAddContact');

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
	});

});

function enablePhone(){
	for ( let i = 1; i < 4; i++ ) {
		if($('#phone'+ i).val()==''){
			$('#divPhone'+ i).addClass('hide');
		}else{
			$('#divPhone'+ i).removeClass('hide');
		}
	}
}

function getContacts (data) {
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
				$('#formAddContact')[0].reset();
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
};


/*function loadCuntry(data) {
	$('#countryCodeBranch').empty();
	$('#countryCodeBranch').append('<option value="' + data.paisTo.codPais + '">' + data.paisTo.pais + '</option>');

	$('#stateCodeBranch').empty();
	$.each(data.paisTo.listaEstados, function (listaPos, listaItem) {
		$('#stateCodeBranch').append('<option value="' + listaItem.codEstado + '">' + listaItem.estados + '</option>');
	});

	var ciudades = data.paisTo.listaEstados.filter(function (dat) { return dat.codEstado == $("option:selected", "#stateCodeBranch").val() });

	$('#cityCodeBranch').empty();
	$.each(ciudades[0].listaCiudad, function (pos, val) {
		$('#cityCodeBranch').append('<option value="' + val.codCiudad + '">' + val.ciudad + '</option>');
	});
}*/

function validInputFile() {
	form = $('#txtBranchesForm');
	validateForms(form);

	if ($('#file-branch').valid()) {
		$('.js-label-file').removeClass('has-error');
	} else {
		$('.js-label-file').addClass('has-error');
	};
};

function deleteContactM(data) {
	who = 'Settings';
	where = 'deleteContact';

	callNovoCore(who, where, data, function (response) { });
};

function addContact(data) {
	who = 'Settings';
	where = 'addContact';

	callNovoCore(who, where, data, function (response) {
		insertFormInput(false);

		if ( response.code ==  0) {
			$('#formAddContact')[0].reset();
		};
	});
};

function updateContact(data) {
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
};

function deleteContact(){
	form = $('#existingContacts')
	data = {
		"acrif" : $('#enterpriseList').find('option:selected').attr('idFiscal'),
		"idExper": $('#dniModifyContact').val(),
	}

	if (lang.CONF_REMOTE_AUTH == 'OFF') {
		data.pass = cryptoPass($('#modifyContactPass').val().trim());
	}

	deleteContactM(data);
}


