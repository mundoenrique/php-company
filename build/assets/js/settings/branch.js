'use strict'
var table;

$(function () {

$('#partedSection').hide();

	$('ul.nav-config-box, .slide-slow').on('click', function (e) {
		if ($('#branchListBr > option').length > 1) {
			$('#branchListBr').prop('selectedIndex', 0);
			$('#partedSection').hide();
			$('#branchLoadSection').hide();
			$('#editAddBranchSection').hide();
		}
	})

	$('#branchListBr').on('change', function (e) {
		e.preventDefault();

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
			getRegion(dataResponse,'');
			$('#editAddBranchSection').fadeIn(700, 'linear');
			$('#btnSaveBranch').removeClass('btn-edit-brach')
			$('#btnSaveBranch').addClass('btn-new-brach');
			$('#branchCode').attr("disabled", false);
			$('#editAddBranchText').html(lang.GEN_ADD +' '+ lang.GEN_BRANC_OFFICE);
			$('#branchInfoForm')[0].reset();
			$('.has-error').removeClass("has-error");
			$('.help-block').text('');
		} else {
			$('#branchLoadSection').fadeIn(700, 'linear');
		}
	});

	$('.btn-back-branch').on('click', function (e) {
		var name = (this.id);
		$('#partedSection').fadeIn(700, 'linear');
		if (name == "backBranchBtn") {
			$('#btnSaveBranch').removeClass('btn-new-brach btn-edit-brach')
			$('#editAddBranchSection').hide();
		} else {
			$('#branchLoadSection').hide();
		}
	});

});


function getBranches (value) {
	$('#partedSection').hide();
	$('#editAddBranchSection').hide();
	$('#branchLoadSection').hide();
	$('.hide-out').removeClass('hide');

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
			$('#partedSection').show();

			$('.edit').on('click', function (e) {
				$('#partedSection').hide();
				$('#btnSaveBranch').removeClass('btn-new-brach');
				$('#btnSaveBranch').addClass('btn-edit-brach');
				$('#branchCode').attr("disabled", true);
				$('#editAddBranchSection').fadeIn(700, 'linear');
				$('#editAddBranchText').html(lang.GEN_EDIT +' '+ lang.GEN_BRANC_OFFICE);
				$.each(dataResponse.data[$(this).val()], function (key, val) {
					$('#'+ key ).val(val);
				});
				$('.has-error').removeClass("has-error");
				$('.help-block').text('');
				$('#password1').val('');
				getRegion(dataResponse,$(this).val());
			});

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

$("#btnSaveBranch").on("click", function(e) {
		form = $('#branchInfoForm');
		validateForms(form);

		if (form.valid()) {
			var btnAction = $('#btnSaveBranch');
			btnText = btnAction.text().trim();
			btnAction.html(loader);

			var btn={};
			btn.btnAction = btnAction;
			btn.btnText =btnText;
			insertFormInput(true);
			data = getDataForm(form);
			data.pass = cryptoPass(data.password1);

			if ($(this).hasClass('btn-new-brach')) {
				data.rif = $("option:selected", '#branchListBr').val();
				data.branch = 'addBranch';
				getCallNovoCore(data, btn);
			}else{
				data.rif = data.rifB;
				data.branch = 'updateBranch';
				getCallNovoCore(data, btn);
			}
		}
});

function getCallNovoCore(data, btn){
	who = 'Settings';
	where = data.branch;
	callNovoCore(who, where, data, function (response) {
		dataResponse = response;
		if(dataResponse.code==0){
			btn.btnAction.html(btn.btnText);
			insertFormInput(false);

			appMessages(dataResponse.title, dataResponse.msg, dataResponse.icon, dataResponse.modalBtn);

			$('#accept').on('click', function(e) {
				e.preventDefault();
				$(this).html(loader);

				var newData = {};
				newData.branchListBr=data.rif;
				getBranches (newData);
			})
		}
	});
};
