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
			$('#spinnerBlock').removeClass("hide");
			insertFormInput(false);
			getSwitchTwirls(passData);
		}
	});

	$('#sign-btn').on('click', function(){
		$('#spinnerBlock').addClass("hide");
		var form = $('#sign-form');
		var passData = getDataForm(form);
		var changeBtn = $(this);
		var btnText = changeBtn.text().trim();

		validateForms(form)

		var vars = Object.getOwnPropertyNames(passData);

		$.each (vars, function(i) {
			var password = $("#password-auth").val();
			if( $( "#" + vars[i]).is(':checked') == true ){
				$( "#" + vars[i]).val('on');
			} else {
				$( "#" + vars[i]).val('off');
				$( "#password-auth").val(password);
			}
		});

		if (form.valid()) {
			insertFormInput(true, form);
			changeBtn.html(loader);
			updateTwirlsCard(passData, btnText);
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
				setTimeout(function(){ // Esto es solo para simular el tiempo de ejecucion del servicio y se vea el spinner.
					$('#spinnerBlock').addClass("hide");
					$('#blockResults').removeClass('hidden');
			}, 3000);
		}
	})
}

function updateTwirlsCard(passData, btnText) {
	verb = "POST"; who = 'Services'; where = 'updateCommercialTwirls'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
			dataResponse = response.data;
			code = response.code
			var info = dataResponse;

			if(code == 0){
				setTimeout(function(){ // Esto es solo para simular el tiempo de ejecucion del serivico y se vea el spinner.
					$('#password-auth').val('');
					insertFormInput(false);
        	$('#sign-btn').html(btnText);
			}, 3000);
		}
	})
}
