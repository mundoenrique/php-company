function validaForm()
{
	jQuery.validator.setDefaults({
		debug: true,
		success: "valid"
	});

	var dni = /^(\d{6,10})$/,
		card = /^(\d{16})$/,
		amount = /^-?[\d]+([.][\d]{1,2})?$/,
		code = /^([\w ]{2,20})$/;

	//Validar solicitud de DNI y tarjeta------------------------------------------------------------
	$('#data-card').validate({
		errorElement: 'label',
		ignore: '.nonValidate',
		errorContainer: '#validate-list',
		errorClass: 'field-error',
		validClass: 'field-success',
		errorLabelContainer: '#validate-list',

		rules:{
			dni: {validafield: true},
			card: {validafield: true}
		},

		messages: {
			dni: 'Indique un DNI válido',
			card: 'Indique un número de tarjeta válido'
		}
	});
	//----------------------------------------------------------------------------------------------

	//Validar fechas requeridas en los controles----------------------------------------------------
	$('#contols-dates').validate({
		errorElement: 'label',
		ignore: '.nonValidate',
		errorContainer: '#validate-list',
		errorClass: 'field-error',
		validClass: 'field-success',
		errorLabelContainer: '#validate-list',

		rules:{
			'first-date': {required: true},
			'last-date': {required: true}
		},

		messages: {
			'first-date': 'Indique la fecha de inicio del control',
			'last-date': 'Indique la fecha fin del control'
		}
	});
	//----------------------------------------------------------------------------------------------

	//Validar fechas requeridas en los controles----------------------------------------------------
	$('#data-payment').validate({
		errorElement: 'label',
		ignore: '.nonValidate',
		errorContainer: '#validate-list',
		errorClass: 'field-error',
		validClass: 'field-success',
		errorLabelContainer: '#validate-list',

		rules:{
			'code': {required: true, pattern: code},
			'amount': {required: true, pattern: amount, amountValid: true}
		},

		messages: {
			'code': 'Indique un código de proveedor válido (min 2, max 20)',
			'amount': {
				required: 'Indique un monto válido (x.xx "Solo números").',
				pattern: 'Indique un monto válido (x.xx "Solo números").',
				amountValid: 'El monto a pagar es superior al saldo disponible.'
			}
		}
	});
	//----------------------------------------------------------------------------------------------

	jQuery.validator.addMethod('validafield', function(value, element, regex){
		var response = true, testing;
		switch($(element).attr('id')){
			case 'dni':
				testing = dni;
				break;
			case 'card':
				testing = card;
		}

		if($('#card').val() !== '' || $('#dni').val() !== '') {
			response = testing.test(value);
		}

		return  response;
	});

	jQuery.validator.addMethod('amountValid', function(value, element, regex){
		var response,
			balance = parseFloat($('#balance').val().substr(3)),
			payAmount = parseFloat(value);

		response = (balance >= payAmount);

		return response
	});
	//----------------------------------------------------------------------------------------------
}
