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
			validInputFile();
    });
	});

	$('#branchListBr').on('change', function (e) {
		e.preventDefault();

		form = $('#branchSettListForm');
		validateForms(form);
		if (form.valid()) {
			getBranches(getDataForm(form));
		}
	});

	$('#loadBranchBtn').on('click', function () {
		$('#partedSection').hide();
		$('#branchLoadSection').fadeIn(700, 'linear');
	});

	$("#newBranchBtn").on("click", (e) => showManageBranchView("create"));

	$('#backBranchBtn').on('click', function (e) {
		$('#partedSection').fadeIn(700, 'linear');
		$('#btnSaveBranch').removeAttr('data-action')
		$('#editAddBranchSection').hide();
	});

	$('#backLoadBranchBtn').on('click', function (e) {
		$('#partedSection').fadeIn(700, 'linear');
		$('#branchLoadSection').hide();
		$('#fileBranch').val('');
    $('.input-file').next('.js-label-file').find('.js-file-name').html(lang.SETTINGS_SELECT_BRANCHES_FILE);
		$('.has-error').removeClass("has-error");
		$('.help-block').text('');
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

			$('#tableBranches tbody tr').on('click', "button[data-action='update']", function (e) {
				$.each(dataResponse.data[$(this).val()], function (key, val) {
					$('#'+ key ).val(val);
				});
				getRegion(dataResponse,$(this).val());
				showManageBranchView("update")
			});

		}else if (dataResponse.code == 1){
			branchesTable(dataResponse);
			$('#partedSection').show();
		}
	});
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

		if ($(this).attr('data-action') == 'saveCreate') {
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

$('#btnBranchUpload').on('click', function(e) {
	e.preventDefault();
	form = $('#txtBranchesForm');
	validInputFile();
	validateForms(form);

	if(form.valid()) {
		var btnAction =  $(this);
		btnText = btnAction.text().trim();
		btnAction.html(loader);

		var btn={};
		btn.btnAction = btnAction;
		btn.btnText =btnText;
		insertFormInput(true);
		data = {
			rif : $("option:selected", '#branchListBr').val(),
			file: $('#fileBranch')[0].files[0],
			typeBulkText: lang.SETTINGS_BRANCH_FILE_MASSIVE,
			branch : 'UploadFileBranch',
		}
		getCallNovoCore(data, btn);
	}
});

function getCallNovoCore(data, btn){
	who = 'Settings';
	where = data.branch;
	callNovoCore(who, where, data, function (response) {
		dataResponse = response;
		btn.btnAction.html(btn.btnText);
		insertFormInput(false);

		if(dataResponse.code==0){
			$('#fileBranch').val('');
			$('.input-file').next('.js-label-file').find('.js-file-name').html(lang.SETTINGS_SELECT_BRANCHES_FILE)
			appMessages(dataResponse.title, dataResponse.msg, dataResponse.icon, dataResponse.modalBtn);
			$('#accept').on('click', function(e) {
				e.preventDefault();
				$('#system-info').dialog('destroy');
				var newData = {};
				newData.branchListBr=data.rif;
				getBranches (newData);
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
					options += '<button value="'+ data.branchRow +'" class="edit btn mx-1 px-0" data-action="update">';
					options += '<i class="icon icon-edit"></i>';
					options += '</button>';
					return options;
				}
			}
		],
	});

	return table;
};

function showManageBranchView(action) {
	$('#partedSection').hide();
	$('#editAddBranchSection').fadeIn(700, 'linear');
	$('.has-error').removeClass("has-error");
	$('.help-block').text('');
	switch (action) {
		case "create":
			getRegion(dataResponse,'');
			$('#btnSaveBranch').attr('data-action', 'saveCreate');
			$('#branchCode').attr("disabled", false);
			$('#editAddBranchText').html(lang.GEN_ADD +' '+ lang.GEN_BRANC_OFFICE);
			$('#branchInfoForm')[0].reset();
			break;
		case "update":
			$('#btnSaveBranch').attr('data-action', 'saveUpdate');
			$('#branchCode').attr("disabled", true);
			$('#editAddBranchText').html(lang.GEN_EDIT +' '+ lang.GEN_BRANC_OFFICE);
			$('#password1').val('');
			break;
	}
}

function validInputFile() {
	form = $('#txtBranchesForm');
	validateForms(form);

	if ($('#fileBranch').valid()) {
		$('.js-label-file').removeClass('has-error');
	} else {
		$('.js-label-file').addClass('has-error');
	}
};
