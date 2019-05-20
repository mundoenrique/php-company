$(function () {

	var code = $('#errorDriver').attr('code'),
		title = $('#errorDriver').attr('title'),
		msg = $('#errorDriver').attr('msg');
	switch (code) {
		case '1':
		case '2':
			$('#msg-info').append('<p class="agrups">' + msg + '</p>');
			$('#close-info').attr('finish', 'b');
			notiSystem(title);
			break;
		case '3':
			$('#msg-info').append('<p class="agrups">' + msg + '</p>');
			$('#close-info').attr('finish', 'c');
			notiSystem(title);
			break;
	}

	//Inhabilitar conductor
	$("#disabled").on("click", function () {
		var title = $(this).text();
		$('#send-info, #close-info').text('');
		$('#msg-info').empty();
		$('#msg-info').append('<div id="loading" class="agrups"><img src=" ' + baseCDN + '/media/img/loading.gif' + '"></div>');
		notiSystem(title);

		var user = $('#user').val(),
			status = $('#disabled').data('status');

		var disabledUser = [{
			'user': user,
			'status': status
		}];
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);

		$.post(baseURL + "/" + isoPais + '/trayectos/modelo', {
				way: 'disabledDriver',
				modelo: 'driver',
				data: disabledUser,
				ceo_name: ceo_cook
			})
			.done(function (response) {
				$('#msg-info').empty();
				var lang = response.lang;
				switch (response.code) {
					case 0:
						$('#disabled').text(response.button);
						$('#disabled').data('status', response.status);
						$('#msg-info').append('<p class="agrups">' + response.msg + '</p>');
						$('#close-info')
							.removeClass('button-cancel')
							.text(lang.TAG_ACCEPT);
						break;
					case 1:
						$('#msg-info').append('<p class="agrups">' + response.msg + '</p>');
						$('#close-info')
							.removeClass('button-cancel')
							.text(lang.TAG_ACCEPT);
						break;
					case 2:
						$('#msg-info').append('<p class="agrups">' + response.msg + '</p>');
						$('#close-info')
							.removeClass('button-cancel')
							.attr('finish', 'y')
							.text(lang.TAG_ACCEPT);
						notiSystem(response.title);
						break;
					case 3:
						$('#msg-info').append('<p class="agrups">' + response.msg + '</p>');
						$('#close-info')
							.removeClass('button-cancel')
							.attr('finish', 'c')
							.text(lang.TAG_ACCEPT);
						notiSystem(response.title);
						break;
				}
			});

	});

	//habilitar click del formulario
	$('#formAddEdit').on('change keyup', function () {
		var changes = $('#add-edit').attr('changes'),
			action = $('#add-edit').attr('function');

		if (action === 'update') {
			$('#add-edit').prop('disabled', false);
			$('#add-edit')
				.removeClass('withoutChanges')
				.text(changes);
		}

	});

	$('#add-edit').on("click", function () {
		var title = $('#reg-upt').text(),
			action = $('#add-edit').attr('function'),
			ignore = action === 'update' ? $('#user').addClass('ignore') : $('#user').removeClass('ignore')
		formAddEdit = $('#formAddEdit');


		validar_campos();
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);

		if (formAddEdit.valid() == true) {
			formAddEdit = formAddEdit.serialize();
			$('#send-info, #close-info').text('');
			$('#msg-info').empty();
			$('#msg-info').append('<div id="loading" class="agrups"><img src=" ' + baseCDN + '/media/img/loading.gif' + '"></div>');
			notiSystem(title);
			$.post(baseURL + '/' + isoPais + '/trayectos/modelo', {
					way: 'addEditDriver',
					modelo: 'driver',
					data: formAddEdit,
					ceo_name: ceo_cook
				})
				.done(function (response) {
					$('#msg-info').empty();
					var lang = response.lang;
					switch (response.code) {
						case 0:
							$('#msg-info').append('<p class="agrups">' + response.msg + '</p>');
							$('#close-info')
								.removeClass('button-cancel')
								.attr('finish', response.back)
								.text(lang.TAG_ACCEPT);
							break;
						case 1:
							$('#msg-info').append('<p class="agrups">' + response.msg + '</p>');
							$('#close-info')
								.removeClass('button-cancel')
								.attr('finish', 'u')
								.text(lang.TAG_ACCEPT);
							break;
						case 2:
							$('#msg-info').append('<p class="agrups">' + response.msg + '</p>');
							$('#close-info')
								.removeClass('button-cancel')
								.attr('finish', 'b')
								.text(lang.TAG_ACCEPT);
							notiSystem(response.title);
							break;
						case 3:
							$('#msg-info').append('<p class="agrups">' + response.msg + '</p>');
							$('#close-info')
								.removeClass('button-cancel')
								.attr('finish', 'c')
								.text(lang.TAG_ACCEPT);
							notiSystem(response.title);
							break;
					}
				});
		}
	});

	//DataPicker
	$('#birthDay').datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		changeYear: true,
		numberOfMonths: 1,
		dateFormat: "dd/mm/yy",
		maxDate: "-18y:",
		minDate: "-100y:+1",
		yearRange: "-100:+0"
	});

});

function validar_campos() {

	jQuery.validator.setDefaults({
		debug: true,
		success: "valid"
	});

	jQuery.validator.addMethod(
		"mail",
		function (value, element, regex) {
			return regex.test(value);
		}
	);

	jQuery.validator.addMethod(
		"username",
		function (value, element, regex) {
			return regex.test(value);
		}
	);

	jQuery.validator.addMethod(
		"birth",
		function (value, element, regex) {
			return regex.test(value);
		}
	);

	jQuery.validator.addMethod(
		"getAge",
		function (birthDate, element, regex) {

			birthDate = birthDate.split('/');
			birthDate = birthDate[1] + '/' + birthDate[0] + '/' + birthDate[2];
			birthDate = new Date(birthDate);
			var today = new Date(),
				age = today.getFullYear() - birthDate.getFullYear(),
				m = today.getMonth() - birthDate.getMonth();

			if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
				age--;
			}

			return (age > 17 && age < 100);
		}
	);

	//Expresiones regulares
	var letter = /^[a-zA-Z_áéíóúñ\s]*$/; //Expresión regular para solo letras y acentos

	$("#formAddEdit").validate({
		errorElement: "label",
		ignore: ".ignore",
		errorContainer: "#msg",
		errorClass: "field-error",
		validClass: "field-success",
		errorLabelContainer: "#msg",
		rules: {
			"dniDriver": {
				required: true,
				number: true
			},
			"user": {
				required: true,
				username: /^[a-z0-9_-]{6,16}$/i
			},
			"mail": {
				required: true,
				mail: /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/
			},
			"telf_mov": {
				required: true,
				number: true,
				minlength: 10
			},
			"name1": {
				required: true,
				pattern: letter
			},
			"name2": {
				pattern: letter
			},
			"ape1": {
				required: true,
				pattern: letter
			},
			"ape2": {
				pattern: letter
			},
			"birthDay": {
				required: true,
				birth: /^\d{2}\/\d{2}\/\d{4}$/,
				getAge: true
			},
			"sex": {
				required: true
			}
		},

		messages: {
			"dniDriver": "El campo Dni NO puede estar vacío y debe contener solo números",
			"birthDay": {
				"required": "El campo Fecha de Nacimiento No puede estar vacío y debe tener un formato correcto. (dd/mm/aaaa)",
				"birth": "El formato correcto para la fecha de nacimiento es: (dd/mm/aaaa)",
				"getAge": "El conductor no puede ser menor a 18 ni mayor de 99 años"
			},
			"name1": "El campo Primer nombre NO puede estar vacío y debe contener solo letras.",
			"name2": "El campo Segundo nombre debe contener solo letras.",
			"ape1": "El campo Primer apellido NO puede estar vacío y debe contener solo letras.",
			"ape2": "El campo Segundo apellido debe contener solo letras.",
			"sex": "Debe seleccionar el sexo.",
			"user": {
				"required": "El campo Usuario NO puede estar vacío. mínimo 6 máximo 16 caracteres",
				"username": "El campo Usuario no tiene un formato valido. Permitido alfanumérico y underscore (barra_piso)"
			},
			"mail": "El correo electrónico NO puede estar vacío y debe tener un formato correcto. (usuario@ejemplo.com)",
			"telf_mov": "El campo Teléfono Móvil NO puede estar vacío, debe contener solo números y al menos 10 dígitos"
		}
	});
}

function notiSystem(title) {

	var msgSystem = $('#msg-system');
	$(msgSystem).dialog({
		title: title,
		modal: 'true',
		width: '210px',
		draggable: false,
		rezise: false,
		open: function (event, ui) {
			$('.ui-dialog-titlebar-close', ui.dialog).hide();
		}
	});
	$('#close-info').on('click', function (e) {
		e.preventDefault();
		$(msgSystem).dialog('close');
		$('#msg-info').empty();
		var finish = $(this).attr('finish');
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		switch (finish) {
			case 'u':
				$('form#formulario').empty();
				$('form#formulario').append('<input type="hidden" name="ceo_name" value="' + ceo_cook + '"/>');
				$('form#formulario').append('<input type="hidden" name="modelo" value="driver"/>');
				$('form#formulario').append('<input type="hidden" name="function" value="update"/>');
				$('form#formulario').append('<input type="hidden" name="data-id" value="' + $('#user').val() + '" />');
				$('form#formulario').attr('action', baseURL + '/' + isoPais + '/trayectos/conductores/perfil');
				$('form#formulario').submit();
				break;
			case 'b':
				window.location.replace(baseURL + '/' + isoPais + '/trayectos/conductores');
				break;
			case 'c':
				window.location.replace(baseURL + '/' + isoPais + '/logout');
				break;
		}
	});
}
