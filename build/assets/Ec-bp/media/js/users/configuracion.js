$(function () {

	var totalpaginas;

	var max = 15;
	// funcionalidad tab

	$("#lotes-general").tabs({
		load: function (event, ui) {
			$("#lotes-general").find("#agregarContacto").hide();
			$("#lotes-general").find("#contactos").hide();
			$("#lotes-general").find("#form-new-suc").hide();
		},
		beforeLoad: function (event, ui) {

			ui.panel.html("Cargando...");

		}

	});

	$("#lotes-general").removeClass("ui-widget-content");
	$("#lotes-general").tabs({ active: $('#tab').val() });
	$('#tab').val() == '0' ? CargarPerfilUser() : $('#tab').val();


	// -- Fin funcionalidad tab


	//CONFIGURACION USUARIO

	$('#usuario').on('click', function () {  //listar empresas

		if ($('#nom_user').is(':empty')) {
			CargarPerfilUser();
		}

	});


	//cambio de clave
	$('#btn-cambioC').on('click', function () {
		var canvas = "<form id='formu'><input type=password id='old' name='user-password' placeholder='Contraseña actual' size=26 class='required'/>";
		canvas += "<input type=password id='new' name='user-password-1' placeholder='Contraseña nueva' maxlength=" + max + " size=26 class='required'/>";
		canvas += "<input type=password id='confNew' name='user-password-2' placeholder='Confirma la nueva contraseña ' maxlength=" + max + " size=26/ class='required'><h5 id='vacio'></h5></form>";

		$(canvas).dialog({

			dialogClass: "hide-close",
			title: "Cambiar contraseña",
			modal: true,
			maxWidth: 470,
			maxHeight: 280,
			resizable: false,
			close: function () { $(this).dialog("destroy") },
			buttons: {
				"Cancelar": { text: 'Cancelar', class: 'novo-btn-secondary-modal',
				click: function () {
					$(this).dialog("close"); }
				},
				"Aceptar": {
								text: 'Aceptar',
								class: 'novo-btn-primary-modal',
								click: function(){
									$("input[type='password']").on('focus keypress', function(){
										$(this).removeAttr("style");
									});
									var old = $(this).find($('#old')).val();
								var newC = $(this).find($('#new')).val();
								var cNewC = $(this).find($('#confNew')).val();
								var $dialogo = $(this);
									$.each($("input[type='password'].required"), function (posItem, item) {
										var elemento = $(item);
										if (elemento.val() == "") {
											$(this).find($('#vacio')).text('Todos los campos son obligatorios (*).');
											elemento.attr("style", "border-color:red");
										}
									});

								if (newC != cNewC) {
									$(this).find($('#vacio')).text('Contraseñas no coinciden.');
									$('#confNew').attr("style", "border-color:red");
								} else if (newC.length > max) {
									 $('#new').attr("style","border-color:red");

									$(this).find($('#vacio')).text('Máximo ' + max + ' caracteres')

								} else if (!($('#length').hasClass("valid") && $('#letter').hasClass("valid") && $('#capital').hasClass("valid") && $('#number').hasClass("valid") && $('#consecutivo').hasClass("valid") && $('#especial').hasClass("valid"))) {
									$(this).find($('#vacio')).text('Verifica el formato de la contraseña.');
								} else {
									$(this).find($('#vacio')).text('Cambiando contraseña...');
									$('.ui-button').hide();
									old = hex_md5(old);
									newC = hex_md5(newC);
									cNewC = hex_md5(cNewC);
									var ceo_cook = decodeURIComponent(
										document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
									);
									var dataRequest = JSON.stringify({
										userpwdOld: old,
										userpwd: newC,
										userpwdConfirm: cNewC,
									})
									var form = $(this).closest('form');
									validateForms(form);
									if (form.valid()) {

										dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
										$.post(baseURL + '/' + isoPais + "/changePassNewUserAuth", { request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook) })
											.done(function (response) {
												data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))
												//data = $.parseJSON(data)

												if (data.rc == 0) {
													$dialogo.dialog("destroy");
													notificacion('Cambiar contraseña', 'Proceso exitoso.');
												} else {
													if (data.rc == -29) {
														alert("Usuario actualmente desconectado");
														$(location).attr('href', baseURL + '/' + isoPais + '/login');
													} else {
														$dialogo.dialog("destroy");
														notificacion('Cambiar contraseña', data.msg);
													}
												}
											});

									} else {
										$(this).find($('#vacio')).text('Verifica los datos ingresados e intenta nuevamente.')
									}
								}
							}
					}
				}
		});


		$('#new').on('keyup focus', function () {
			// set password variable
			var pswd = $(this).val();
			//validate the length
			if (pswd.length < 8 || pswd.length > max) {
				$('#length').removeClass('valid').addClass('invalid');
			} else {
				$('#length').removeClass('invalid').addClass('valid');
			}

			//validate letter
			if (pswd.match(/[a-z]/)) {
				$('#letter').removeClass('invalid').addClass('valid');
			} else {
				$('#letter').removeClass('valid').addClass('invalid');
			}

			//validate capital letter
			if (pswd.match(/[A-Z]/)) {
				$('#capital').removeClass('invalid').addClass('valid');
			} else {
				$('#capital').removeClass('valid').addClass('invalid');
			}

			//validate number

			if (pswd.split(/[0-9]/).length - 1 >= 1 && pswd.split(/[0-9]/).length - 1 <= 3) {
				$('#number').removeClass('invalid').addClass('valid');
				valid = !valid ? valid : true;
			} else {
				$('#number').removeClass('valid').addClass('invalid');
				valid = false;
			}

			if (!pswd.match(/(.)\1{2,}/)) {
				$('#consecutivo').removeClass('invalid').addClass('valid');
			} else {
				$('#consecutivo').removeClass('valid').addClass('invalid');
			}

			if (pswd.match(/([!@\*\-\?¡¿+\/.,_#])/)) {
				$('#especial').removeClass('invalid').addClass('valid');
			} else {
				$('#especial').removeClass('valid').addClass('invalid');
			}


		}).focus(function () {

			$("#new").showBalloon({ position: "right", contents: $('#psw_info') });
			$('#psw_info').show();

		}).blur(function () {

			$("#new").hideBalloon({ position: "right", contents: $('#psw_info') });
			$('#psw_info').hide();
		});


	});
	//fin cambio de clave

	function CargarPerfilUser() {
		$('#loading').dialog({
			dialogClass: "hide-close",title: "Perfil de usuario", modal: true, maxWidth: 700, maxHeight: 300, close: function () { $(this).dialog('destroy'); } });
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$.post(baseURL + api + isoPais + '/usuario/config/perfilUsuario', { ceo_name: ceo_cook }).done(function (response) {
			data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))
			if (!data.ERROR) {
				$(".ui-dialog-content").dialog("destroy");

				$("#nom_user").text(data.primerNombre);
				$("#ape_user").text(data.primerApellido);
				$("#cargo_user").text(data.cargo);
				$("#area_user").text(data.area);
				$("#email_user").val(data.email);
			} else {
				if (data.rc == '-61' || data.rc == '-29') {
					alert('Usuario actualmente desconectado'); location.reload();
				} else {
					$(".ui-dialog-content").dialog("destroy");
					notificacion('Perfil de usuario', data.ERROR);
				}
			}
		});
	}

	$("#btn-modificar").click(function () {
		$('#loading').dialog({ title: "Modificar usuario", modal: true, maxWidth: 700, maxHeight: 300, dialogClass: 'hide-close' });
		emailRegex = /^([^]+[\w-\.]+@([\w-]+\.)+[\w-]{2,4})+$/;
		var email = $("#email_user").val();

		if (emailRegex.test($("#email_user").val())) {
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			var dataRequest = JSON.stringify({
				email: email
			})
			dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
			$.post(baseURL + api + isoPais + '/usuario/config/ActualizarPerfilUsuario', { request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook) })
				.done(function (response) {
					data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))
					$(".ui-dialog-content").dialog().dialog("destroy");
					if (data.rc == '0') {
						notificacion("Modificar usuario", "Proceso exitoso.");
					} else {
						if (data.rc == '-61' || data.rc == '-29') {
							alert('Usuario actualmente desconectado'); location.reload();
						} else {
							notificacion("Modificar usuario", data.ERROR);
						}
					}
				});
		} else {
			$(".ui-dialog-content").dialog().dialog("destroy");
			notificacion("Modificar usuario", "Verifica que el formato del e-mail sea correcto.")
		}
	});


	//--FIN CONFIGURACION USUARIO


	//CONFIGURACION EMPRESAS

	var rif, nombre, accodcia, tipo;

	// CARGA DE LA INFORMACION DE LA EMPRESA SELECCIONADA
	$('#ui-id-1').on('change', '#listaEmpresas', function () { //seleccionar una empresa
		if ($('option:selected', this).attr('data-accodcia') == undefined) {
			$("#config-empresas #campos-config").addClass('elem-hidden');
			return;
		}
		$('#loading').dialog({ title: "Información empresa", modal: true, maxWidth: 700, maxHeight: 300, dialogClass: 'hide-close' });
		$(this).attr('disabled', true);
		$("#lotes-general").find("#contactos").hide();
		$('#lotes-general').find('#contact-paginacion').hide();
		$('#lotes-general').find('#agregarContact').show();
		$('#lotes-general').find('#mostrarContact').show();
		$('#lotes-general').find("#agregarContacto").hide();

		$("#tlf1").attr('maxlength', '9');
		$("#tlf2").attr('maxlength', '9');
		$("#tlf3").attr('maxlength', '9');

		rif = $('option:selected', this).attr('data-rif');
		nombre = $('option:selected', this).attr('data-nombre');
		accodcia = $('option:selected', this).attr('data-accodcia');

		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		var dataRequest = JSON.stringify({
			data_accodcia: accodcia
		})
		dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
		$.post(baseURL + api + isoPais + '/usuario/config/infoEmpresa', { request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook) })
			.done(function (response) {
				data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))

				$(".ui-dialog-content").dialog().dialog("destroy");
				$('#ui-id-1 #listaEmpresas').removeAttr('disabled');
				if (!data.ERROR) {
					$("#config-empresas #campos-config").removeClass('elem-hidden');

					var info = data.lista[0];

					$('#rif').text(info.acrif);

					$('#nombre').text(info.acnomcia);

					$('#razon').text(info.acrazonsocial);

					$('#contacto').text(info.acpercontac);

					$('#ubicacion').text(info.acdirubica);

					$('#facturacion').text(info.acdirenvio);

					$('#tlf1').val(info.actel);

					$('#tlf2').val(info.actel2);

					$('#tlf3').val(info.actel3);

					//CARAGA DE LOS TIPOS DE COTACTO
					$('#tipo_contact').empty();

					tipo = data.listaContacto;

					$.each(data.listaContacto, function (k, v) {
						$("#tipo_contact").append('<option value="' + v.idTipoContacto + '" >' + v.nombre + '</option>');
					});

					// OCULTA BOTONES DE AGREGAR CONTACTO, MOSTRAR CONTACTOS EMPRESA
					$('#agregarContact').css('visibility', 'hidden');
					$('#mostrarContact').css('visibility', 'hidden');
					$('#modif').css('float', 'left');
				} else {
					if (data.ERROR == '-29') {
						alert('Usuario actualmente desconectado'); location.reload();
					} else {
						notificacion("Información empresa", data.ERROR);
					}
				}
			});

	});


	$("#lotes-general").on("click", "#agregar", function () { // boton agregar el contecto

		var json = {};
		json.rif = rif;
		json.nombre = $("#agregarContacto #contact-nomb").val();
		json.apellido = $("#agregarContacto #contact-apell").val();
		json.cedula = $("#agregarContacto #contact-id").val();
		json.cargo = $("#agregarContacto #contact-carg").val();
		json.email = $("#agregarContacto #contact-email").val();
		json.tipoContacto = $('option:selected', "#agregarContacto #tipo_contact").attr("value");
		json.pass = hex_md5($("#passAgregar").val());

		if (validar(json, $("#agregarContacto"), "agregar") && $("#passAgregar").val() !== "") {
			resetPass();
			if (!rif == "") {
				var ceo_cook = decodeURIComponent(
					document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
				);
				$carg = $('#loading').dialog({ title: "Agregar contacto", modal: true, maxWidth: 700, maxHeight: 300, dialogClass: 'hide-close' });

				var dataRequest = JSON.stringify(json)
				dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
				$.post(baseURL + api + isoPais + '/usuario/config/agregarContacto', { request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook) })
					.done(function (response) {
						data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))
						$carg.dialog("destroy");
						$("#pass").css("border-color", "");
						if (data.rc == "0") {
							$("#lotes-general #agregarContacto input").val('');
							$("<div>Proceso exitoso.<h5>Listando contactos...</h5></div>").dialog({
								dialogClass: "hide-close",title: "Agregar contacto", modal: true, close: function () { $(this).dialog('destroy') } })
							$('#contenedor_contacts').empty();
							listarContactos(0);
							$('#agregarContact').show();
						} else {
							if (data.rc == '-61' || data.rc == '-29') {
								alert('Usuario actualmente desconectado'); location.reload();
							} else {
								notificacion("Agregar contacto", data.ERROR);
							}
						}
					});


			} else {
				$(".ui-dialog-content").dialog().dialog("destroy");
				notificacion("Agregar contacto", "Debes seleccionar una empresa.")
			}
		} else if ($("#passAgregar").val() == "") {
			$(".ui-dialog-content").dialog().dialog("destroy");
			$("#passAgregar").addClass("error");
			notificacion("Agregar contacto", "Debes ingresar tu contraseña.");
		} else {
			$(".ui-dialog-content").dialog().dialog("destroy");
			$("#passAgregar").removeClass("error");
			notificacion("Agregar contacto", "Formulario inválido, verifica los datos suministrados.");
		}

	});
	function validar(json, $contenedor, funcion) {

		$.each($contenedor.find(".error"), function () {
			$(this).removeClass("error");
		});

		emailRegex = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})+$/;
		charRegex = /^([a-zA-ZñÑáéíóúÁÉÍÓÚ]+\s*){1,100}$/;
		ciRegex = /^[0-9]{4,10}$/;
		alfanumericRegex = /^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ]+\s*){1,100}$/;
		validez = true;

		if (!emailRegex.test(json.email)) {
			$contenedor.find("#contact-email").addClass("error");
			validez = false;
		}
		if (!charRegex.test(json.nombre) || json.nombre == "") {
			$contenedor.find("#contact-nomb").addClass("error");
			validez = false;
		}
		if (!charRegex.test(json.apellido) || json.apellido == "") {
			$contenedor.find("#contact-apell").addClass("error");
			validez = false;
		}
		if (!alfanumericRegex.test(json.cargo) || json.cargo == "") {
			$contenedor.find("#contact-carg").addClass("error");
			validez = false;
		}
		if (!ciRegex.test(json.cedula) && funcion == "agregar") {
			$contenedor.find("#contact-id").addClass("error");
			validez = false;
		}
		if (json.tipoContacto == "") {
			$contenedor.find("#tipo_contact").addClass("error");
			validez = false;
		}
		return validez;
	}

	$("#lotes-general").on("click", "#modif", function () {
		tlfRegex = /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])+$/;
		var json = {};
		json.rif = rif;
		json.tlf = $("#tlf1").val();
		json.tlf2 = $("#tlf2").val();
		json.tlf3 = $("#tlf3").val();


		if (tlfRegex.test($("#tlf1").val()) && (tlfRegex.test($("#tlf2").val()) || $("#tlf2").val() == "") && (tlfRegex.test($("#tlf3").val()) || $("#tlf3").val() == "")) {
			$('#loading').dialog({
				dialogClass: "hide-close",title: "Modificar empresa", modal: true, maxWidth: 700, maxHeight: 300, dialogClass: 'hide-close' });
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			var dataRequest = JSON.stringify(json);
			dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
			$.post(baseURL + api + isoPais + '/usuario/config/ActualizarTlfEmpresa', { request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook) })
				.done(function (response) {
					data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))
					$(".ui-dialog-content").dialog().dialog("destroy");

					if (data.rc == "0") {
						notificacion("Modificar empresa", "Proceso exitoso.")
					} else {
						if (data.rc == '-61' || data.rc == '-29') {
							alert('Usuario actualmente desconectado'); location.reload();
						} else {
							notificacion("Modificar empresa", data.ERROR);
						}
					}

				});
		} else {
			notificacion("Modificar empresa", "Verifica número de teléfono.")
		}
	});

	$("#lotes-general").on("click", "#eliminar_contact", function () {

		if (!$("#pass").val() == "") {
			var json = {};
			json.rif = rif;
			json.cedula = idcontacto;
			json.pass = hex_md5($("#pass").val());
			resetPass();
			$('#loading').dialog({
				dialogClass: "hide-close",title: "Eliminar contacto", modal: true, maxWidth: 700, maxHeight: 300, dialogClass: 'hide-close' });
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);

			var dataRequest = JSON.stringify(json)
			dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
			$.post(baseURL + api + isoPais + '/usuario/config/eliminarContacto', { request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook) })
				.done(function (response) {
					data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))
					$(".ui-dialog-content").dialog().dialog("destroy");

					if (data.rc == "0") {
						$("#pass.pass").css("border-color", "");
						$('#contenedor_contacts').empty();
						listarContactos(0);
						$("<div>Proceso exitoso.<h5>Listando contactos...</div>").dialog({
							dialogClass: "hide-close",title: "Eliminar contacto", modal: true, close: function () { $(this).dialog('destroy') } });//notificacion("Eliminar contacto","Proceso exitoso.");
					} else {
						if (data.rc == '-61' || data.rc == '-29') {
							alert('Usuario actualmente desconectado'); location.reload();
						} else {
							$("#pass.pass").css("border-color", "");
							notificacion("Eliminar contacto", data.ERROR);
						}
					}
				});
		} else {
			$("#pass.pass").css("border-color", "red");
			notificacion("Eliminar contacto", "Debes ingresar tu contraseña.");
		}
	});


	$("#lotes-general").on("click", "#modificar_contact", function () {

		var json = {};
		json.rif = rif;
		json.cedula = idcontacto;
		json.nombre = $pgContact.find("#contact-nomb").val();
		json.apellido = $pgContact.find("#contact-apell").val();
		json.cargo = $pgContact.find("#contact-carg").val();
		json.email = $pgContact.find("#contact-email").val();
		json.tipoContacto = $pgContact.find("#tipo_contact").val();
		json.pass = hex_md5($("#pass").val());

		if (validar(json, $pgContact, "modificar")) {
			if (!$("#pass").val() == "") {
				$("#pass").val("");
				resetPass();
				$('#loading').dialog({
					dialogClass: "hide-close",title: "Actualizar contacto", modal: true, maxWidth: 700, maxHeight: 300, dialogClass: 'hide-close' });
				var ceo_cook = decodeURIComponent(
					document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
				);
				var dataRequest = JSON.stringify(json)
				dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
				$.post(baseURL + api + isoPais + '/usuario/config/ActualizarContacto', { request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook) })
					.done(function (response) {
						data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))
						$(".ui-dialog-content").dialog().dialog("destroy");
						if (data.rc == "0") {
							$("#pass.pass").css("border-color", "");
							$('#contenedor_contacts').empty();
							listarContactos(0);
							$("<div>Proceso exitoso.<h5>Listando contactos...</div>").dialog({

			dialogClass: "hide-close",title: "Actualizar contacto", modal: true, close: function () { $(this).dialog('destroy') } });//notificacion("Actualizar contacto","Proceso exitoso.");
						} else {
							if (data.rc == '-61' || data.rc == '-29') {
								alert('Usuario actualmente desconectado'); location.reload();
							} else {
								$("#pass.pass").css("border-color", "");
								notificacion("Actualizar contacto", data.ERROR);
							}
						}
					});
			} else {
				$("#pass.pass").css("border-color", "red");
				notificacion("Actualizar contacto", "Debes ingresar tu contraseña.");
			}
		} else {
			notificacion("Actualizar contacto", "Formulario inválido, verifica los datos suministrados.");
		}

	});



	//--FIN CONFIGURACION EMPRESAS




	//CONFIGURACION SUCURSALES
	var datos;
	$("#ui-id-2").on('click', "#archivo", function () {
		$("#userfile").trigger('click');
	});

	$('#ui-id-2').on("click", "#btn-new-suc", function () {
		$.each($(".error"), function () {
			$(this).removeClass("error");
		});
		$("#suc_cod").val('');
		$("#suc_nom").val('');
		$("#suc_dir1").val('');
		$("#suc_dir2").val('');
		$("#suc_dir3").val('');
		$("#suc_zona").val('');
		$("#suc_estado").val('');
		$("#suc_ciudad").val('');
		$("#suc_contacto").val('');
		$("#suc_area").val('');
		$("#suc_tlf").val('');
		$("#form-new-suc").fadeIn("slow");
		$("#agregarSuc").show();
		$("#btn-modif-suc").hide();
		$("#suc_cod").removeAttr("disabled");
	});

	$('#ui-id-2').on('change', '#listaEmpresasSuc', function () {
		$.each($(".error"), function () {
			$(this).removeClass("error");
		});
		$("#tbody-datos-general").empty();
		$("#tabla-datos-general").hide();
		$("#form-new-suc").hide();
		$("#sucursales-paginacion").hide();
		$(".suc").hide();
		consultarSucursales("1");
		$('#suc_area').attr('maxlength', '4');
		$('#suc_tlf').attr('maxlength', '7');
	});

	$("#lotes-general").on("click", ".OS-icon", function () {
		$.each($(".error"), function () {
			$(this).removeClass("error");
		});
		var obj = $(this).attr("cod");

		$("#suc_cod").attr("disabled", "disabled");

		$("#agregarSuc").hide();
		$("#btn-modif-suc").show();
		$("#form-new-suc").fadeIn("slow");

		$.each(datos.lista, function (pos, item) {
			if (obj == item.cod) {
				$("#suc_cod").val(item.codigo);
				$("#suc_nom").val(item.nomb_cia);
				$("#suc_dir1").val(item.direccion_1);
				$("#suc_dir2").val(item.direccion_2);
				$("#suc_dir3").val(item.direccion_3);
				$("#suc_zona").val(item.zona);
				$("#suc_contacto").val(item.persona);
				$("#suc_area").val(item.cod_area);
				$("#suc_tlf").val(item.telefono);
				$("#suc_cod").attr("cod", item.cod);

				ciudades = datos.paisTo.listaEstados.filter(function (dat) { return dat.codEstado == item.estado });
				$('#suc_ciudad').empty();
				$.each(ciudades[0].listaCiudad, function (pos, val) {
					$('#suc_ciudad').append('<option value="' + val.codCiudad + '">' + val.ciudad + '</option>');
				});
				$("#suc_pais")[0].value = item.codPais;
				$("#suc_estado")[0].value = item.estado;
				$('#suc_ciudad')[0].value = item.ciudad;
				return;
			}
		});

	});

	$('#ui-id-2').on('change', '#suc_pais', function () {
		$.each(datos.paisTo.listaEstados, function (listaPos, listaItem) {
			$('#suc_estado').append('<option value="' + listaItem.codEstado + '">' + listaItem.estados + '</option>');
		});
	});

	$('#ui-id-2').on('change', '#pais', function () {
		$.each(datos.paisTo.listaEstados, function (listaPos, listaItem) {
			$('#suc_estado').append('<option value="' + listaItem.codEstado + '">' + listaItem.estados + '</option>');
		});
	});

	$('#ui-id-2').on('change', '#suc_estado', function () {

		ciudades = datos.paisTo.listaEstados.filter(function (dat) { return dat.codEstado == $("option:selected", '#suc_estado').val() });
		$('#suc_ciudad').empty();
		$.each(ciudades[0].listaCiudad, function (pos, val) {
			$('#suc_ciudad').append('<option value="' + val.codCiudad + '">' + val.ciudad + '</option>');
		});

	});



	$("#lotes-general").on("click", "#agregarSuc", function () {

		validez = validarSucursal();

		if (validez && $("#pass_suc").val() != "") {
			var json = {};

			json.rif = $("option:selected", '#listaEmpresasSuc').attr('data-rif');
			json.codigo = $("#suc_cod").val();
			json.nombre = $("#suc_nom").val();
			json.dir1 = $("#suc_dir1").val();
			json.dir2 = $("#suc_dir2").val();
			json.dir3 = $("#suc_dir3").val();
			json.zona = $("#suc_zona").val();
			json.pais = $("option:selected", "#suc_pais").val();
			json.estado = $("option:selected", "#suc_estado").val();
			json.ciudad = $("option:selected", "#suc_ciudad").val();
			json.contacto = $("#suc_contacto").val();
			json.area = $("#suc_area").val();
			json.tlf = $("#suc_tlf").val();
			json.pass = hex_md5($("#pass_suc").val());
			resetPass();

			$('#loading').dialog({
				dialogClass: "hide-close",title: "Agregar sucursal", modal: true, maxWidth: 700, maxHeight: 300, dialogClass: 'hide-close' });
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			var dataRequest = JSON.stringify(json)
			dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
			$.post(baseURL + api + isoPais + '/usuario/config/agregarSucursales', { request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook) })
				.done(function (response) {
					data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))
					$(".ui-dialog-content").dialog().dialog("destroy");
					if (data.rc == "0") {
						$("#lotes-general #form-new-suc").hide();
						$("#lotes-general #btn-new-suc").show();
						$("#suc_cod").val("");
						$("#suc_nom").val("");
						$("#suc_dir1").val("");
						$("#suc_dir2").val("");
						$("#suc_dir3").val("");
						$("#suc_zona").val("");
						$("#suc_estado").val("");
						$("#suc_ciudad").val("");
						$("#suc_tlf").val("");
						$("#suc_contacto").val("");
						$("#suc_area").val("");
						$("#tbody-datos-general").empty();
						$("#ui-id-2 #campos-config").hide();
						consultarSucursales(totalpaginas);
						$("<div>Proceso exitoso.<h5>Listando sucursales...</h5></div>").dialog({
							dialogClass: "hide-close",title: "Agregar sucursal", modal: true, close: function () { $(this).dialog('destroy') } })

					} else {
						if (data.rc == '-61' || data.rc == '-29') {
							alert('Usuario actualmente desconectado'); location.reload();
						} else {
							notificacion("Agregar sucursal", data.ERROR);
						}
					}
				});
		} else if (validez && $('#pass_suc').val() == '') {
			notificacion("Agregar sucursal", "Debes ingresar tu contraseña.")
		} else {
			notificacion("Agregar sucursal", "Formulario inválido, verifica los datos suministrados.")
		}

	});

	function validarSucursal() {
		$.each($(".error"), function () {
			$(this).removeClass("error");
		});

		alfaRegex = /(^([a-zA-Z]+\s*){0,100}$)?/;
		alfaNumRegex = /^([a-zA-Z0-9]+\s*){1,100}$/;
		areaRegex = /^[0-9]{1,4}$/;
		tlfRegex = /^[0-9]{7}$/;
		numbRegex = /^[0-9]{1,15}$/;

		validez = true;

		if ($("#suc_nom").val() == "") {
			$("#suc_nom").addClass("error");
			validez = false;
		} if ($("#suc_zona").val() == "") {
			$("#suc_zona").addClass("error");
			validez = false;
		} if ($("#suc_dir1").val() == "") {
			$("#suc_dir1").addClass("error");
			validez = false;
		} if (!$("option:selected", "#suc_pais").val()) {
			$("#suc_pais").addClass("error");
			validez = false;
		} if (!$("option:selected", "#suc_estado").val()) {
			$("#suc_estado").addClass("error");
			validez = false;
		} if (!$("option:selected", "#suc_ciudad").val()) {
			$("#suc_ciudad").addClass("error");
			validez = false;
		} if (!numbRegex.test($("#suc_cod").val()) || $("#suc_cod").val() == "") {
			$("#suc_cod").addClass("error");
			validez = false;
		} if (!areaRegex.test($("#suc_area").val()) && $("#suc_area").val() != "") {
			$("#suc_area").addClass("error");
			validez = false;
		} if (!tlfRegex.test($("#suc_tlf").val()) && $("#suc_tlf").val() != "") {
			$("#suc_tlf").addClass("error");
			validez = false;
		} if (!alfaRegex.test($("#suc_contacto").val())) {
			$("#suc_contacto").addClass("error");
			validez = false;
		}
		return validez;
	}


	$("#lotes-general").on("click", "#btn-modif-suc", function () {
		validez = validarSucursal();
		if (validez && $("#pass_suc").val() != "") {
			var json = {};
			$('#loading').dialog({
				dialogClass: "hide-close",title: "Modificar sucursal", modal: true, maxWidth: 700, maxHeight: 300, dialogClass: 'hide-close' });
			json.rif = $("option:selected", '#listaEmpresasSuc').attr('data-rif');
			json.codigo = $("#suc_cod").attr("cod");
			json.cod = $("#suc_cod").val();
			json.nombre = $("#suc_nom").val();
			json.dir1 = $("#suc_dir1").val();
			json.dir2 = $("#suc_dir2").val();
			json.dir3 = $("#suc_dir3").val();
			json.zona = $("#suc_zona").val();
			json.pais = $("option:selected", "#suc_pais").val();
			json.estado = $("option:selected", "#suc_estado").val();
			json.ciudad = $("option:selected", "#suc_ciudad").val();
			json.contacto = $("#suc_contacto").val();
			json.area = $("#suc_area").val();
			json.tlf = $("#suc_tlf").val();
			json.pass = hex_md5($("#pass_suc").val());
			json.ceo_name = ceo_cook;
			resetPass();
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);

			var dataRequest = JSON.stringify(json)
			dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
			$.post(baseURL + api + isoPais + '/usuario/config/actualizarSucursales', { request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook) })
				.done(function (response) {
					data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))
					$(".ui-dialog-content").dialog().dialog("destroy");

					if (data.rc == "0") {
						$.each(datos.lista, function (k, v) {
							if (v.cod == json.codigo) {
								v.ciudad = json.ciudad;
								v.cod_area = json.area;
								v.direccion_1 = json.dir1;
								v.direccion_2 = json.dir2;
								v.direccion_3 = json.dir3;
								v.estado = json.estado;
								v.nomb_cia = json.nombre;
								v.persona = json.contacto;
								v.telefono = json.tlf;
								v.zona = json.zona;
								return;
							}
						});
						notificacion("Modificar sucursal", "Proceso exitoso.");
					} else {
						if (data.rc == '-61' || data.rc == '-29') {
							alert('Usuario actualmente desconectado'); location.reload();
						} else {
							notificacion("Modificar sucursal", data.ERROR);
						}
					}

				});

		} else {
			if (validez && $("#pass_suc").val() == "") {
				notificacion("Modificar sucursal", "Debes ingresar tu contraseña.");
			} else {
				notificacion("Modificar sucursal", "Formulario inválido, verifica los datos suministrados.");

			}
		}
	});


	function consultarSucursales(paginaActual) {
		var rif = $("option:selected", '#listaEmpresasSuc').attr('data-rif');
		if (rif == undefined) {
			return;
		}

		$('#loading').dialog({
			dialogClass: "hide-close",title: "Sucursales", modal: true, maxWidth: 700, maxHeight: 300, dialogClass: 'hide-close' });
		$('#listaEmpresasSuc').attr('disabled', true);

		pagina = false;
		cantItems = 10;
		pgitem = 1, pgs = 1;
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		var dataRequest = JSON.stringify({
			rif: rif,
			paginaActual: paginaActual,
			data_paginar: pagina,
			data_cantItems: cantItems,
		})
		dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
		$.post(baseURL + api + isoPais + '/usuario/config/consultarSucursales', { request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook) })
			.done(function (response) {
				data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))
				$(".ui-dialog-content").dialog().dialog("destroy");
				$('#listaEmpresasSuc').removeAttr('disabled');
				datos = data;
				if (data.rc == "0") {
					totalpgs = data.totalPaginas;

					$("#ui-id-2 #campos-config").show();
					$("#tabla-datos-general").show();

					if ($("#tabla-datos-general").hasClass('dataTable')) {
						$('#tabla-datos-general').dataTable().fnClearTable();
						$('#tabla-datos-general').dataTable().fnDestroy();
					}

					$("#tabla-datos-general").show();
					$.each(data.lista, function (pos, item) {

						pagina ? pgs = data.paginaActual : totalpgs = pgs;

						var html = "<tr class='" + pgs + "'><td>" + item.nomb_cia + "</td>";
						html += "<td>" + item.codigo + "</td>";
						html += "<td>" + item.persona + "</td>";
						html += "<td>" + item.telefono + "</td>";
						html += "<td class='OS-icon' cod=" + item.cod + " style='width:10px'> <a id='editar_suc' ><span title='Modificar Sucursal' aria-hidden='true' class='icon' data-icon='&#xe08f;'> </span></a></td></tr>";
						$(".tabla-sucursales tbody").append(html);
						if (pgitem <= cantItems) {
							pgitem += 1;
						} else {
							pgitem = 1; pgs += 1;
						}

					});

					$('.tbody-sucursales tr').hide();
					$('.tabla-sucursales .' + data.paginaActual).show();
					$("#opciones-btn.suc").show();

					$('#sucursales-paginacion').show();
					paginarSucursales(data.paginaActual, totalpgs);
					totalpaginas = data.totalPaginas;


					cargarPais(data);


				} else {
					if (data.rc == '-150') {
						$('#sucursales-paginacion').hide();
						$("#opciones-btn.suc").show();
						cargarPais(data);
						notificacion("Consultar sucursales", data.ERROR);
					} else if (data.rc == '-61' || data.rc == '-29') {
						alert('Usuario actualmente desconectado'); location.reload();
					} else {
						$("#opciones-btn.suc").hide();
						notificacion("Consultar sucursales", data.ERROR);
					}
				}

			});

	}


	function cargarPais(data) {
		$('#suc_pais').empty();
		$('#suc_pais').append('<option value="' + data.paisTo.codPais + '">' + data.paisTo.pais + '</option>');

		$('#suc_estado').empty();
		$.each(data.paisTo.listaEstados, function (listaPos, listaItem) {
			$('#suc_estado').append('<option value="' + listaItem.codEstado + '">' + listaItem.estados + '</option>');
		});
		ciudades = data.paisTo.listaEstados.filter(function (dat) { return dat.codEstado == $("option:selected", "#suc_estado").val() });
		$('#suc_ciudad').empty();
		$.each(ciudades[0].listaCiudad, function (pos, val) {
			$('#suc_ciudad').append('<option value="' + val.codCiudad + '">' + val.ciudad + '</option>');
		});

	}

	var ceo_cook;
	$("#ui-id-2").on("click", "#userfile", function () {
		var dat;
		$(this).fileupload({
			type: 'post',
			replaceFileInput: false,
			// formData: {'data-tipoLote':tipol},
			url: baseURL + api + isoPais + "/usuario/config/cargarSucursales",

			add: function (e, data) {
				f = $('#userfile').val();
				$('#archivo').val($('#userfile').val());
				dat = data;

				var ext = $('#userfile').val().substr($('#userfile').val().lastIndexOf(".") + 1);
				if (ext === "txt" || ext === "TXT") {
					data.context = $('#btn-new-mas')
						.click(function () {
							if ($("option:selected", "#listaEmpresasSuc").attr("data-rif") != "") {
								$("#form-new-suc").fadeOut("fast");
								$("#btn-new-mas").replaceWith('<h3 id="cargando_masivo">Cargando...</h3>');
								var ceo_cook = decodeURIComponent(
									document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
								);

								var dataRequest = JSON.stringify({ 'data_rif': $("option:selected", "#listaEmpresasSuc").attr("data-rif") });
								dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
								dat.formData = {
									request: dataRequest,
									ceo_name: ceo_cook,
									plot: btoa(ceo_cook)
								};

								dat.submit().done(function (response, textStatus, jqXHR) {
									result = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8));

									if (result) {
										if (!result.ERROR) {
											mostrarError(result);
										} else {
											if (result.rc == '-61' || data.rc == '-29') {
												alert('Usuario actualmente desconectado'); location.reload();
											} else {
												notificacion("Cargar archivo sucursales", result.ERROR);
											}
										}
									}


									$('#userfile').val("");
									$('#archivo').val("");
								});
							} else {
								notificacion("Cargar archivo sucursales", "Selecciona una empresa.");
							}
						});
				} else {
					notificacion("Cargar archivo sucursales", "Tipo de archivo no permitido. <h5>Formato requerido: txt</h5>");
					$('#userfile').val("");
					$('#archivo').val("");
				}
			},
			done: function (e, data) {

				$('#userfile').val(""); $('#archivo').val("");
				$('#cargando_masivo').replaceWith('<button id="btn-new-mas" >Subir Archivo</button>');
			},
			error: function (e) {
				notificacion("Cargar archivo", "No fue posible cargar el archivo");
				$('#userfile').val(""); $('#archivo').val("");
				$('#cargando_masivo').replaceWith('<button id="btn-new-mas" >Subir Archivo</button>');
			}
		});
	});


	//--FIN CONFIGURACION SUCURSALES



	// FUNCIONES GENERALES

	//POUP Notificacion
	function notificacion(titulo, mensaje) {

		var canvas = "<div>" + mensaje + "</div>";

		$(canvas).dialog({

			dialogClass: "hide-close",
			title: titulo,
			modal: true,
			maxWidth: 700,
			maxHeight: 300,
			buttons: {
				"Aceptar": {
					text: 'Aceptar',
					class: 'novo-btn-primary-modal',
					click: function () {
					$(this).dialog("destroy");
					}
				}
			}
		});
	}
	//--Fin POUP Notificacion


	$("#lotes-general").on("click", "#mostrarContact", function () {  // boton agregar contacto a empresa
		if (!$("#lotes-general #agregarContacto").is(':visible')) {
			$('#contenedor_contacts').empty();
			listarContactos(0);
		}

	});

	var config_mostrar_form = function (boton, content) {  // BOTON MOSTRAR FORMULARIOS
		$("#lotes-general").on("click", boton, function () {
			$("#lotes-general " + content).fadeIn();
			$("#lotes-general #agregarContacto input").val('');
			$("#agregarContacto #contact-id").attr('maxlength', '8');
			$(this).hide();
			$.each($(".error"), function () {
				$(this).removeClass("error");
			});
		});
	}
	config_mostrar_form(".agregar-contact", "#agregarContacto");

	var config_limpiar = function (boton, content) {
		$("#lotes-general").on("click", boton, function () { //Boton limpiar inputs
			$("#lotes-general " + content + " input").val('');
		});
	}
	config_limpiar('#limpiar', '#agregarContacto');
	config_limpiar('#limpiarSuc', '#form-new-suc');


	function marcarError($obj, alerta) {  //tool-tip
		$.balloon.defaults.classname = "error-login-2";
		$.balloon.defaults.css = null;
		$obj.showBalloon({ position: "right", contents: alerta });
		setTimeout(function () { $obj.hideBalloon({ position: "right", contents: alerta }); }, 3000);
	}


	var habilitarInput = function (name) { // lapicito
		$("#lotes-general").on("click", "#" + name + "Input", function () {
			$(this).parents('span').find('#' + name).attr('disabled', false);
			$(this).parents('span').find('#' + name).focus();
		});
	}

	//habilitar inputs
	habilitarInput("email");
	habilitarInput("tlf1");
	habilitarInput("tlf2");
	habilitarInput("tlf3");
	habilitarInput("contact-nomb");
	habilitarInput("contact-apell");
	habilitarInput("contact-carg");
	habilitarInput("contact-id");
	habilitarInput("contact-email");
	habilitarInput("tipo_contact");
	habilitarInput("email_user");

	function mostrarError(result) {

		if (result.rc != "0") {



			var canvas = "<h4>ENCABEZADO</h4>";
			$.each(result.erroresFormato.erroresEncabezado.errores, function (k, v) {
				canvas += "<h6>" + v + "</h6>";
			});

			canvas += "<h4>REGISTRO</h4>";
			$.each(result.erroresFormato.erroresRegistros, function (k, vv) {
				canvas += "<h5>" + vv.nombre + "</h5>";
				$.each(result.erroresFormato.erroresRegistros[k].errores, function (i, v) {
					canvas += "<h6>" + v + "<h6/>";
				});

			});
			notificacion(result.msg, canvas);

		} else {


			$("#tbody-datos-general").empty();
			$("#tabla-datos-general").hide();
			$("#sucursales-paginacion").hide();
			consultarSucursales(1);
			notificacion("Cargar archivo sucursales", "Archivo cargado con éxito.\n" + result.msg);
		}

	}



	function listarContactos(paginaActual) {

		$("#pass").css("border-color", "");

		if (!rif == "") {
			pagina = false;
			cantItems = 1;
			if (!$("#lotes-general #agregarContacto").is(':visible')) {
				$('#loading').dialog({
					dialogClass: "hide-close",title: "Contactos", modal: true, maxWidth: 700, maxHeight: 300, dialogClass: 'hide-close' });
			}
			$("#lotes-general #contactos").fadeOut("slow");
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			var dataRequest = JSON.stringify({
				data_rif: rif,
				paginaActual: paginaActual,
				data_paginar: pagina,
				data_cantItems: cantItems
			})
			dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
			$.post(baseURL + api + isoPais + '/usuario/config/InfoContactoEmpresa', { request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook) })
			$consulta.done(function (response) {
				data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))

				$("#agregarContact").show();
				$("#lotes-general #agregarContacto").fadeOut();
				$(".ui-dialog-content").dialog().dialog("destroy");
				if (data.rc == '0') {


					$("#lotes-general #contactos").fadeIn("slow");
					$('#lotes-general').find('#contact-paginacion').show();


					$select = '<select id="tipo_contact" type="text" disabled="disabled" style="float:left;" >'

					$.each(tipo, function (k, v) {
						$select += '<option value="' + v.idTipoContacto + '" >' + v.nombre + '</option>';
					});
					$select += '<select/>';

					$('#contactos').show();
					$.each(data.lista, function (k, v) {

						$canvas = '<div id="campos-config-2" class="contact ' + (k + 1) + '"><div id="campos-1"><span><p id="first">' + $('#info_user_name').val() + '</p>';
						$canvas += '<input id="contact-nomb" type="text" disabled="disabled" value="' + v.nombres + '" style="float:left;" size=24 maxlength=100/>';
						$canvas += '<a title="' + $('#title_modificar').val() + '"><span id="contact-nombInput" class="icon lapiz-mod" data-icon="&#xe08f;" ></span></a>';
						$canvas += '</span><span><p id="first">' + $('#info_user_apellido').val() + '</p><input id="contact-apell" type="text" disabled="disabled" value="' + v.apellido + '" style="float:left;" maxlength=100/>';
						$canvas += '<a title="' + $('#title_modificar').val() + '"><span id="contact-apellInput" class="icon lapiz-mod" data-icon="&#xe08f;" ></span>';
						$canvas += '</a></span></div><div id="campos-1"><span><p id="first">' + $('#info_user_cargo').val() + '</p>';
						$canvas += '<input id="contact-carg" type="text" disabled="disabled" value="' + v.cargo + '" style="float:left;" size=26 maxlength=50/>';
						$canvas += '<a title="' + $('#title_modificar').val() + '"><span id="contact-cargInput" class="icon lapiz-mod" data-icon="&#xe08f;" ></span>';
						$canvas += '</a></span><span><p id="first">' + $('#id_persona').val() + ':</p><input id="contact-id" type="text" disabled="disabled" value="' + v.idExtPer + '" style="float:left;" />';
						$canvas += '<a title="' + $('#title_modificar').val() + '"><span id="cedulaInput" class=""></span>';
						$canvas += '</a></span></div><div id="campos-1"><span><p id="first">' + $('#info_user_mail').val() + '</p>';
						$canvas += '<input id="contact-email" type="text" disabled="disabled" value="' + v.email + '" style="float:left;" size=26 maxlength=45/>';
						$canvas += '<a title="' + $('#title_modificar').val() + '"><span id="contact-emailInput" class="icon lapiz-mod" data-icon="&#xe08f;" ></span>';
						$canvas += '</a></span><span><p id="first">' + $('#emp_contact_tipo').val() + '</p>' + $select;
						$canvas += '<a title="' + $('#title_modificar').val() + '">';
						$canvas += '<span id="tipo_contactInput" class="icon lapiz-mod" data-icon="&#xe08f;" ></span></a></span></div></div>';

						$('#contenedor_contacts').append($canvas);
						$('#contenedor_contacts .' + (k + 1) + ' #tipo_contact').val(v.tipoContacto);
					});

					paginar(data.paginaActual, data.totalRegistros);
					$('.contact').hide();
					$('.1').show();
					$pgContact = $('.1');
					idcontacto = $pgContact.find("#contact-id").val();
				} else {
					if (data.rc == '-61' || data.rc == '-29') {
						alert('Usuario actualmente desconectado'); location.reload();
					} else {

						notificacion("Contactos", data.ERROR);
					}
					if (data.rc == "-150") {
						$("#agregarContact").show();
						$('#contactos').hide();

					}
				}

			});

		} else {
			notificacion("Contactos", "Debes seleccionar una empresa.");
		}
	}

	var idcontacto;
	function paginar(pagina, totalpaginas) {
		$('#lotes-general').find('#contact-paginacion').paginate({
			count: totalpaginas,
			start: 1,
			display: 10,
			border: false,
			text_color: '#79B5E3',
			background_color: 'none',
			text_hover_color: '#2573AF',
			background_hover_color: 'none',
			images: false,
			onChange: function (page) {
				$('.contact').hide();
				$('.' + page).show();
				$pgContact = $('.' + page);
				idcontacto = $pgContact.find("#contact-id").val();
				$.each($pgContact.find(".error"), function () {
					$(this).removeClass("error");
				});
			}
		});
	}

	function paginarSucursales(pagina, totalpaginas) {
		$('#lotes-general').find('#sucursales-paginacion').paginate({
			count: totalpaginas,
			start: pagina,
			display: 5,
			border: false,
			text_color: '#79B5E3',
			background_color: 'none',
			text_hover_color: '#2573AF',
			background_hover_color: 'none',
			images: false,
			onChange: function (page) {

				if (!$('.tabla-sucursales').find($('.' + page)).hasClass(page)) {

					$('#loading').dialog({
						dialogClass: "hide-close",modal: true, maxWidth: 700, maxHeight: 300, dialogClass: 'hide-close' });
					$("#tabla-datos-general").hide();
					consultarSucursales(page);
				}
				$('.tbody-sucursales tr').hide();
				$('.tabla-sucursales .' + page).show();

			}
		});
	}


	function resetPass() {
		$(':password').each(function () { $(this).val(''); });
	}

	// FIN FUNCIONES GENERALES






}); //Fin document ready
