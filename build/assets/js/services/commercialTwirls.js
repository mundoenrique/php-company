'use strict'
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	$('#blockResults').addClass('hidden');
	insertFormInput(false);

	$('#card-holder-btn').on('click', function(){
		$('#blockResults').addClass('hidden');
		var form = $('#formTwirls');
		var passData = getDataForm(form);

		validateForms(form);

		if (form.valid()) {
			$('#spinnerBlockBudget').removeClass("hide");
			insertFormInput(false);
			getSwitchTwirls(passData);
		}
	});

	$('#sign-bulk-btn').on('click', function(){

		var form = $('#formChecks');
		var passData = getDataForm(form);

		validateForms(form)

		if (form.valid()) {
			$('#spinnerBlockBudget').removeClass("hide");
			insertFormInput(false);
			console.log(passData);
			// updateTwirlsCard(passData);
		}
	});
});

function getSwitchTwirls(passData) {
	verb = "POST"; who = 'Services'; where = 'commercialTwirls'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
			dataResponse = response.data;
			code = response.code
			var info = dataResponse;

			if(code == 0){
				setTimeout(function(){ // Esto es solo para simular el tiempo de ejecucion del serivico y se vea el spinner.
				$('#spinnerBlockBudget').addClass("hide");
				$('#blockResults').removeClass('hidden');
			}, 3000);
		}
	})
}

function updateTwirlsCard(passData) {
	verb = "POST"; who = 'Services'; where = 'updateCommercialTwirls'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
			dataResponse = response.data;
			code = response.code
			var info = dataResponse;

			if(code == 0){
				setTimeout(function(){ // Esto es solo para simular el tiempo de ejecucion del serivico y se vea el spinner.

			}, 3000);
		}
	})
}
