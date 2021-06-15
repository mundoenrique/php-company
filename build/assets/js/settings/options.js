'use strict'
var table;
var geo;
$(function () {
	var ulOptions = $('.nav-item-config');
	$('#partedSection').hide();
	$('#newBranch').hide();

	$.each(ulOptions, function (pos, liOption) {
		$('#' + liOption.id).on('click', function (e) {
			var liOptionId = e.currentTarget.id;
			$(ulOptions).removeClass('active');
			$('.option-service').hide();
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

		$.each( lang.SETTINGS_RENDER_CONTROLLER_VARIABLES, function( key ) {
			$('#'+ key).val(optionSelect.attr(key));
		});

		if ( lang.CONF_SETTINGS_TELEPHONES == 'ON' ) {
			for ( let i = 1; i < 4; i++ ) {
				$('#phone'+ i).val(optionSelect.attr('phone'+ i));
			}
		};

		$('.hide-out').addClass('hide');
		$('#enterpriseData').removeClass('hide');
	})

	$('#userDataBtn').on('click', function (e) {
		e.preventDefault();
		form = $('#userDataForm');
		btnText = $(this).text().trim();
		validateForms(form);

		if (form.valid()) {
			insertFormInput(true);
			data = getDataForm(form);
			data.email = $('#currentEmail').val().toLowerCase();
			$(this).html(loader);

			verb = "POST"; who = 'Settings'; where = 'changeEmail';
			callNovoCore(verb, who, where, data, function (response) {
				dataResponse = response.data
				insertFormInput(false);
				$('#userDataBtn').html(btnText)
			})
		}
	})

	if (lang.CONF_APPS_DOWNLOAD.length > 0) {
		$('a.' + lang.CONF_APPS_DOWNLOAD[0][0]).on('click', function () {
			if ($(this).attr('title') == '') {
				verb = 'POST'; who = 'Settings'; where = 'GetFileIni';
				data = {};
				callNovoCore(verb, who, where, data, function (response) {
					if (response.code == 0) {
						downLoadfiles(response.data);
					}
					$('.cover-spin').hide();
				})
			}
		})
	}

	$('#branchListBr').on('change', function (e) {
		e.preventDefault;

		if (table != undefined) {
			table.destroy();
		}

		$('#branchInfoForm')[0].reset();
		$(".completeSection").addClass('hidden');
		$("#partedSection").hide();
		$('.hide-out').removeClass('hide');

		form = $('#branchSettListForm');

		insertFormInput(true);
		formInputTrim(form);
		validateForms(form);

		if (form.valid()) {
			getBranches(getDataForm(form));
		}
	});

	var inputFile =  $('#file-branch').next('.js-label-file').html().trim() ;

	$('.input-file').each(function () {
		var label = $(this).next('.js-label-file');
		var labelVal = label.html();

		$(this).on('change', function (element) {
			$(this)
				.focus()
				.blur();
				var fileName = '';
			if (element.target.value) fileName = element.target.value.split('\\').pop();
				fileName ? label.addClass('has-file').find('.js-file-name').html(fileName) : label.removeClass('has-file').html(labelVal);
			});
			validInputFile();
	});

	$('#file-branch').on('change', function(e){
		e.preventDefault;
		$('#btnTxtSend').removeAttr("disabled");
	});

	$('#btnTxtSend').on('click', function(e) {
		e.preventDefault
		var btnAction = $(this);
		btnText = btnAction.text().trim();
		form = $('#txtBranchesForm');

		validateForms(form);

		if (form.valid()) {
			$(this).html(loader);

			data = {
			file:	$('#file-branch')[0].files[0],
			typeBulkText: $('#file-branch')[0].files[0].type
			}
			verb = 'POST'; who = 'Settings'; where = 'uploadFileBranches';

			callNovoCore(verb, who, where, data, function (response) {
				insertFormInput(false);
				$('#file-branch').val('');
				$('#file-branch').next('.js-label-file').html(inputFile);
			})
		}
	});

	$('#newBranchBtn').on('click', function () {
		$('#branchInfoForm')[0].reset();
		$(".completeSection").removeClass('hidden');
		$(".secondSection").show();
	});
});

function getBranches (value) {
	data = value;
	verb = 'POST'; who = 'Settings'; where = 'getBranches';

	callNovoCore(verb, who, where, data, function(response) {
		dataResponse = response;
		insertFormInput(false);

		if ( dataResponse.code == 0 ) {
			geo = response.geoInfo;

			branchesTable( dataResponse );

			$('.edit').on('click', function (e) {
				$.each(dataResponse.data[$(this).val()], function (key, val) {
					$('#'+ key ).val(val);
				});

				$('#cityCodeBranch').empty().prop('disabled', false);
				$('#stateCodeBranch').empty();

				getGeoData(['city', dataResponse.geoUserData[e.currentTarget.value].state]);
				getGeoData(['state', dataResponse.country.countryCodeBranch])

				$.each(dataResponse.geoUserData[e.currentTarget.value], function(key, val) {
					$('#'+ key + 'CodeBranch option[value="'+ val +'"]').attr("selected", "selected");
				});

				$("html, body").animate({ scrollTop: $(".edit").offset().top  }, 500);
			})
		} else if ( dataResponse.code == 1 ) {
			branchesTable( dataResponse );
		} else if ( dataResponse.code == 2 ) {
			$('#newBranchBtn').hide();
			$('.hide-out').addClass('hide');

			table = $('#tableBranches').DataTable({
				"autoWidth": false,
				"ordering": false,
				"searching": false,
				"lengthChange": false,
				"pagelength": 10,
				"pagingType": "full_numbers",
				"table-layout": "fixed",
				"data": [],
				"language": dataTableLang,
			});

			$('.secondarySectionBranch').addClass('hidden');
			$('#partedSection').show();
			$(".completeSection").removeClass('hidden');
		};
	});
};

function validInputFile() {
	form = $('#txtBranchesForm');
	validateForms(form);

	if ($('#file-branch').valid()) {
		$('.js-label-file').removeClass('has-error');
	} else {
		$('.js-label-file').addClass('has-error');
	};
};

function updateBranch(data) {
	verb = 'POST'; who = 'Settings'; where = 'updateBranch';

	callNovoCore(verb, who, where, data, function (response) {
		dataResponse = response;

		if ( dataResponse.code ==  5) {
			$('#branchInfoForm')[0].reset();
		};
	});
};

function getGeoData(data){
	switch (data[0]) {
		case 'city':
			$('#cityCodeBranch').children().remove();
			geo.listaEstados.forEach(element => {
				if (element.codEstado == data[1]) {
					$.each(element.listaCiudad, function(key, val) {
						$('#cityCodeBranch').append("<option value='"+ val['codCiudad'] +"' >"+ val['ciudad'] +"</option>");
					});
				}
			});
			break;
		case 'state':
				if (geo.codPais == data[1]) {
					$.each(geo.listaEstados, function(key, val) {
						$('#stateCodeBranch').append("<option value='"+ val['codEstado'] +"' >"+ val['estados'] +"</option>");
					});
				}
			break;
		case 'district':
			geo.listaDistrict.forEach(element => {
				if (element.codDistrict == data[1]) {
					$.each(element.listaDistrict, function(key, val) {
						$('#districtCodeBranch').children().remove();
						$('#districtCodeBranch').append("<option value='"+ val['codDistrict'] +"' >"+ val['disctrict'] +"</option>");
					});
				}
			});
			break;
	}
}

function branchesTable( dataResponse ) {
	$('#btnTxtSend').attr("disabled", "true");
	$('#newBranchBtn').show();
	$('.hide-out').addClass('hide');
	$('.secondarySectionBranch').removeClass('hidden');
	$('#partedSection').show();
	$(".completeSection").removeClass('hidden');

	$.each(dataResponse.geoInfo.listaEstados, function(key, val){
		$('#stateCodeBranch').append("<option value='"+ val[ 'codEstado'] +"' >"+ val['estados'] +"</option>");
	});

	$('#stateCodeBranch').on('change', function () {
		$('#cityCodeBranch').prop('disabled', false);

		if ($(this).find('option:first').val() == '') {
			$(this).find('option').get(0).remove();
		};
		getGeoData(['city', $(this).val()]);
	});

	if (lang.CONF_SETTINGS_DISCTRICT) {
		$('#cityCodeBranch').on('change', function () {
			$('#districtCodeBranch').prop('disabled', false);

			if ($(this).find('option:first').val() == '') {
				$(this).find('option').get(0).remove();
			};

			if (response.longProfile == 'L') {
				$('#districtBlock').removeClass('none');
				$('#districtCodeBranch').children().remove();
				$('#districtCodeBranch').prepend('<option value="" selected>' + lang.GEN_BTN_SELECT + '</option>');

				getGeoData(['district', $(this).val()]);
			};
		});
	};

	$('#district').on('change', function () {
		if ($(this).find('option:first').val() == '') {
			$(this).find('option').get(0).remove();
		};
	});

	table = $('#tableBranches').DataTable({
		drawCallback: function () {
			$('#partedSection').show();
			$('.hide-out').addClass('hide');
		},
		"autoWidth": false,
		"ordering": false,
		"searching": false,
		"lengthChange": false,
		"pagelength": 10,
		"pagingType": "full_numbers",
		"table-layout": "fixed",

		"data": dataResponse.data,
		"language": dataTableLang,
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
		"columns": [
			{ data: 'branchName' },
			{ data: 'branchCode' },
			{ data: 'contact' },
			{ data: 'phone' },
			{
				data: function (data) {
					var options = '';

					options += '<button value="'+ data.id +'" class="edit btn mx-1 px-0"'
					options += '>';
					options += '<i value="'+ data.id +'" class="icon icon-find"></i>';
					options += '</button>';

					return options;
				}
			}
		],
	});

	$('#countryCodeBranch').append("<option selected value='"+ dataResponse.country[ 'countryCodeBranch'] +"' >"+ dataResponse.country[ 'countryNameBranch'] +"</option>");

	if ($('#stateCodeBRanch').length > 0) {
		getGeoData(['city', $('#stateCodeBranch option:selected').val()]);
	}

	$('#btn-update-branch').on('click', function (e) {
		e.preventDefault;
		form = $('#branchInfoForm');
		formInputTrim(form);
		validateForms(form);

		if (form.valid()) {
			data =getDataForm(form);
			data.cod = $('#codB').val();
			data.rif = $('#rifb').val();
			data.user = $('#userNameB').val();
			updateBranch(getDataForm(form));
		}
	});

	$('#partedSection').show();
	$(".completeSection").removeClass('hidden');
	$('#newBranch').show();
	$('#cityCodeBranch').empty().prop('disabled', true).prepend('<option value="" selected>' + lang.GEN_BTN_SELECT + '</option>');

	return table;
};

