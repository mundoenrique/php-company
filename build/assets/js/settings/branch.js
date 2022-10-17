'use strict'
var table;

$(function () {

$('#partedSection').hide();

	$('ul.nav-config-box, .slide-slow').on('click', function (e) {
		if ($('#branchListBr > option').length > 1) {
			$('#branchListBr').prop('selectedIndex', 0);
			$('#partedSection').hide();
		}
	})

	$('#branchListBr').on('change', function (e) {
		e.preventDefault();
		$('#partedSection').hide();
		$('#editAddBranchSection').hide();
		$('#branchLoadSection').hide();
		$('.hide-out').removeClass('hide');

		form = $('#branchSettListForm');
		validateForms(form);
		if (form.valid()) {
			getBranches(getDataForm(form));
		}
	});

	$('.btn-branch').on('click', function () {
		var name = (this.id);
		$('#partedSection').hide();
		if (name == "newBranchBtn") {
			$('#editAddBranchSection').fadeIn(700, 'linear');
			$('#branchInfoForm')[0].reset();
			$('#stateCodBranch').prop('selectedIndex',0);
		} else {
			$('#branchLoadSection').fadeIn(700, 'linear');
		}
	});

	$('.btn-back-branch').on('click', function (e) {
		var name = (this.id);
		$('#partedSection').fadeIn(700, 'linear');
		if (name == "backBranchBtn") {
			$('#editAddBranchSection').hide();
		} else {
			$('#branchLoadSection').hide();
		}
	})
});


function getBranches (value) {
	if (table != undefined) {
		table.destroy();
	}

	data = value;
	who = 'Settings';
	where = 'getBranches';

	callNovoCore(who, where, data, function(response) {
		dataResponse = response;
		insertFormInput(false);

		if ( dataResponse.code == 0 ) {
			branchesTable(dataResponse);
			getRegion(dataResponse,'');

			$('#partedSection').show();

			$('.edit').on('click', function (e) {
				$('#partedSection').hide();
				$('#editAddBranchSection').fadeIn(700, 'linear');
				$.each(dataResponse.data[$(this).val()], function (key, val) {
					$('#'+ key ).val(val);
				});
				getRegion(dataResponse,$(this).val());
			})
		}else if (dataResponse.code == 1){
			branchesTable(dataResponse);
			$('#partedSection').show();
		}
	});
};

function branchesTable( dataResponse ) {
	$('.hide-out').addClass('hide');

	table = $('#tableBranches').DataTable({
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
					options += '<button value="'+ data.branchRow +'" class="edit btn mx-1 px-0">';
					options += '<i class="icon icon-edit"></i>';
					options += '</button>';
					return options;
				}
			}
		],
	});

	return table;
};

function getRegion(dataResponse,row){
	var region = dataResponse.paisTo;
	var selectedState = '';
	var selectedCity = '';

	$('#countryCodBranch').empty();
	$('#countryCodBranch').append('<option value="' + region.codPais + '">' + region.pais + '</option>');

	$('#stateCodBranch').empty();
	$('#stateCodBranch').prepend('<option value="" selected disabled>' + lang.GEN_BTN_SELECT + '</option>');

	$('#cityCodBranch').empty();
  $('#cityCodBranch').prepend('<option value="" selected disabled>' + lang.GEN_BTN_SELECT + '</option>');

	$.each(region.listaEstados, function(key, val){
		if(row!=''){
			selectedState = val['codEstado'] == dataResponse.data[row].stateCod ? 'selected' : '';
			getCities(dataResponse.data[row].stateCod);
		}
		$('#stateCodBranch').append("<option value='"+ val[ 'codEstado'] +"' "+selectedState+">"+ val['estados'] +"</option>");
	});

	$('#stateCodBranch').on('change', function () {
		$('#cityCodBranch').prop('disabled', false);
		getCities($(this).val());
	});

	function getCities(stateCode){
		$.each(region.listaEstados, function(key, val){
			if (val['codEstado']== stateCode) {
				$.each(val['listaCiudad'], function(key2, val2) {
					if(row!=''){
					selectedCity = val2['codCiudad'] == dataResponse.data[row].cityCod ? 'selected' : '';
					}
					$('#cityCodBranch').append("<option value='"+ val2['codCiudad'] +"' "+selectedCity+">"+ val2['ciudad'] +"</option>");
				});
			}
		});
	}
}

$('.btn-add-branch').on('click', function (e) {
	e.preventDefault();
	form = $('#branchInfoForm');
	validateForms(form);

	if (form.valid()) {
		var btnAction = $('#btn-add-branch');
		btnText = btnAction.text().trim();
		btnAction.html(loader);
		insertFormInput(true);

		data = getDataForm(form);
		data.rif = $("option:selected", '#branchListBr').val();
		data.pass = cryptoPass(data.password1);

		who = 'Settings';
		where = 'addBranch';
		callNovoCore(who, where, data, function (response) {
			dataResponse = response;
			if(dataResponse.code==0){
				btnAction.html(btnText);
				insertFormInput(false);

				appMessages(dataResponse.title, dataResponse.msg, dataResponse.icon, dataResponse.modalBtn);

				$('#accept').on('click', function(e) {
					e.preventDefault();
					$('#partedSection').hide();
					$('#editAddBranchSection').hide();
					$('#branchLoadSection').hide();
					$(this).html(loader);

					var newData = {};
					newData.branchListBr=data.rif;
					getBranches (newData);
				})
			}
		});
	}
});

$('.btn-update-branch').on('click', function (e) {
	e.preventDefault();
	form = $('#branchInfoForm');
	validateForms(form);

	if (form.valid()) {
		var btnAction = $('#btn-update-branch');
		btnText = btnAction.text().trim();
		btnAction.html(loader);
		insertFormInput(true);

		data =getDataForm(form);
		who = 'Settings';
		where = 'updateBranch';
		callNovoCore(who, where, data, function (response) {
			dataResponse = response;
			if(dataResponse.code==0){
				btnAction.html(btnText);
				insertFormInput(false);

				appMessages(dataResponse.title, dataResponse.msg, dataResponse.icon, dataResponse.modalBtn);

				$('#accept').on('click', function(e) {
					e.preventDefault();
					$('#partedSection').hide();
					$('#editAddBranchSection').hide();
					$('#branchLoadSection').hide();
					$(this).html(loader);

					var newData = {};
					newData.branchListBr=data.rif;
					getBranches (newData);
				})
			}
		});
	}
});
