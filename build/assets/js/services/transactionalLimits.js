'use strict'
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	insertFormInput(false);
	$('#blockResults').addClass('hidden');

	$('.slide-slow').click(function() {
		$(this).next(".section").slideToggle("slow");
		$(".help-block").text("");
	});


	$('#card-holder-btn').on('click', function (e) {
		e.preventDefault();
		$('input[type=password]').val('');
		$('.section').css("display", "none");
		$('.money').removeClass("has-error");
		$('#blockResults').addClass('hidden');
		var form = $('#limitsForm');
		var passData = getDataForm(form);
		validateForms(form);
		if (form.valid()) {
			$('#spinnerBlock').removeClass("hide");
			insertFormInput(true);
			getForm(passData);
		}
	});

	$('#sign-btn').on('click', function(e){
		var changeBtn = $(this);
		var btnText = changeBtn.text().trim();
		var form = $('#limitsUpdateForm');
		var passData = getDataForm(form);
		passData.cardNumber = $('#cardNumber').val();
		validateForms(form);

		if (form.valid()) {
			changeBtn.html(loader);
			insertFormInput(true, form);
			updateLimits(passData, btnText);
		}
	});
});

function getForm(passData){
	verb = "POST"; who = 'Services'; where = 'transactionalLimits'; data = passData;	callNovoCore(verb, who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code
		if (code == 0) {
			insertFormInput(false);
			$('#spinnerBlock').addClass('hide');
			$('#blockResults').removeClass('hidden');

			$.each (dataResponse.dataLimits, function(key, val) {
				$('#' + key).text(val);
			});

			$.each (dataResponse.limits, function(key, val) {
				$('#' + key).val(val);
			});

		} else {
			insertFormInput(false);
			$('#spinnerBlock').addClass('hide');
		}
	});
};

function updateLimits(passData, btnText){
	verb = "POST"; who = 'Services'; where = 'updateTransactionalLimits'; data = passData;	callNovoCore(verb, who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code;
		insertFormInput(false);
		$('input[type=password]').val('');
		$('#sign-btn').html(btnText);
		if( code == 4){
			$('#accept').on('click', function(){
				$('#card-holder-btn').trigger('click');
			});
		}
	})
};
