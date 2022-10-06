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
		$('.hide-out').removeClass('hide');

		if (table != undefined) {
			table.destroy();
		}

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
	data = value;
	who = 'Settings';
	where = 'getBranches';

	callNovoCore(who, where, data, function(response) {
		dataResponse = response;
		insertFormInput(false);
		if ( dataResponse.code == 0 ) {
			branchesTable( dataResponse );
			$('#partedSection').show();

			$('.edit').on('click', function (e) {
				$('#partedSection').hide();
				$('#editAddBranchSection').fadeIn(700, 'linear');
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
			})
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
					options += '<button value="'+ data.branchCode +'" class="edit btn mx-1 px-0">';
					options += '<i class="icon icon-edit"></i>';
					options += '</button>';
					return options;
				}
			}
		],
	});

	return table;
};

