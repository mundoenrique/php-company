'use strict'
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	insertFormInput(false);
	$('#enableSectionBtn').addClass('hidden');

	for (var i = 0; i <= 41; i++) {
		var checkboxValue = $("input[name=checkbox"+ i +"]");
		if (checkboxValue.val() == "off") {
			checkboxValue.prop("checked", false);
		}else if (checkboxValue.val() == "on"){
			checkboxValue.prop("checked", true);
		}
	}

	$( "#allPermits" ).change(function() {
		if ($("#allPermits").is(':checked')) {
			$('.permissions').prop("checked", true);
			$('.permissions').val("on");
			$("#removeAllPermissions"). prop("checked", false);
		}
	});

	$( "#removeAllPermissions" ).change(function() {
		if ($("#removeAllPermissions").is(':checked')) {
			$('.permissions'). prop("checked", false);
			$('.permissions').val("off");
			$("#allPermits"). prop("checked", false);
		}
	});

	$( ".permissions" ).change(function() {
		if ($(this).is(':checked')) {
			$(this).val('on');
			$("#removeAllPermissions"). prop("checked", false);
		} else {
			$(this).val('off');
		}
		$("#allPermits"). prop("checked", false);
	});

	$('#enableUserBtn').on('click', function() {
		$('#sectionPermits').fadeIn(700, 'linear');
		$('#enableSectionBtn').remove();
	});


	$('#updateUserBtn').on('click', function(e){
		var changeBtn = $(this);
		var btnText = changeBtn.text().trim();
		var form = $('#checkFormPermits');
		var passData = getDataForm(form);

		$('#spinnerBlock').addClass('hide');
		delete passData.allPermits;
		delete passData.removeAllPermissions;
		validateForms(form);

		if (form.valid()) {
			insertFormInput(true, form);
			passData.userId = $('#idUser').val();
			changeBtn.html(loader);
			updatePermissions(passData, btnText);
		}
	});
});

function updatePermissions(passData, btnText) {
	verb = 'POST'; who = 'User'; where = 'updatePermissions'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
		dataResponse = response.data;
		$('#updateUserBtn').html(btnText);
		insertFormInput(false);
	});
};
