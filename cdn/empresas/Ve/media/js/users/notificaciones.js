
		var elementos = document.getElementsByClassName( "input-email" );
		var check = document.getElementById( 'checkNoti' );
		var email_user_noti = document.getElementById( 'email_user_noti' );
		var btn_modificar_noti = document.getElementById( 'btn-modificar-noti' );
		var listaEmpresas =  document.getElementById( 'listaEmpresas' );
		var Notificaciones;
		var datosPost = {
			acrif : ''
		};

		function selectorEmpresa(){

			var datosPost = {};
			var titulo = "Notificaciones";

			datosPost.acrif = getRif();
			ConsultaNotificaciones( 'buscar', datosPost, titulo );

		}
		function getRif(){
			var myselect = document.getElementById("listaEmpresas");
			var selector = myselect.options[ myselect.selectedIndex ];
			return selector.getAttribute( 'data-rif' );
		}
		showHideClass('.input-email', 'none');

		function capturaCheck( e ){

			var str = e.getAttribute( 'id' );
			var idSelect = str.split("checkNoti");

			var element = document.getElementById('div'+idSelect[1]);

			element.style.display = ( e.checked )?'block':'none';

		}

		function envioDatos(){

  		var ErrorCount = 0;

			$.each($(":checkbox"),function(k,v){

					var id = $( this ).attr( 'id' );
				  id = id.split( "checkNoti" );
					id = id[1];

					for( a = 0; a <= Notificaciones.notificaciones.length -1; a++ ){

						Notificaciones.notificaciones[ a ].contacto.estatus = "A";
						Notificaciones.notificaciones[ a ].contacto.acrif = getRif();
							if( Notificaciones.notificaciones[ a ].codOperacion == id ){
									if(Notificaciones.notificaciones[ a ].contacto.tipoContacto == ""){
										Notificaciones.notificaciones[ a ].contacto.tipoContacto = "G";
									}
									if ( this.checked ) {
											var correo = document.getElementById(id).value;
											if(correo != ""){
													Notificaciones.notificaciones[ a ].notificacionAct = 1;
													Notificaciones.notificaciones[ a ].contacto.email = correo;
											}else{
													var msj ='Por favor introdusca un correo en la entrada : <strong>'+
																			Notificaciones.notificaciones[ a ].descripcion+'</strong>';
													notificacion('Notificacion', msj);
												  ErrorCount += 1;
											}
									}else{
										Notificaciones.notificaciones[ a ].notificacionAct = 0;
									}
							}
					}

			});
					console.log( Notificaciones);
			if( ErrorCount == 0 ){

				var path = window.location.href.split( '/' );
				var baseURL = path[ 0 ]+ "//" +path[ 2 ]+'/'+path[ 3];
				var isoPais = path[4];
				var api ="/api/v1/";

				$.post( baseURL+api+isoPais+'/usuario/notificaciones/envio', Notificaciones ).done(function(data){
					var data = JSON.parse( data );
						if( data.rc == 0 ){
								$( ".ui-dialog-content" ).dialog( "destroy" );
								notificacion( 'Notificacion', 'El correo electrónico fue registrado exitosamente. Ahora recibirá notificaciones del producto y/o servicio seleccionado.' );
						}
						else{
								notificacion( 'Notificacion',"No se pudo actualizar las notificaciones del Usuario.", );
						}
				});
			}
		}
		 function captureEventCorreo( e ) {
				var value = e.value;
				validacionCorreo (value);
	   }
		function validacionCorreo (value){
				value = value.trim();
				var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

				if(re.test(value)){
						return true;
				}else{
						var msj ='Por favor introdusca un formato valido de correo Electrónico';
						notificacion('Notificacion', msj);
						return false;
				}

		}
		function showHideClass(Clase, Accion){
				[].forEach.call( document.querySelectorAll( Clase ), function ( el ) {
						el.style.display = Accion;
				});
		}

		function notificacion( titulo, mensaje ){

        var canvas = "<div>"+mensaje+"</div>";

        $( canvas ).dialog({
            title: titulo,
            modal: true,
            maxWidth: 700,
            maxHeight: 300,
            buttons: {
                OK: function(){
                    $(this).dialog("destroy");
                }
            }
        });
    }

		function ConsultaNotificaciones( funcion, datosPost, titulo ){

				var path = window.location.href.split( '/' );
				var baseURL = path[ 0 ]+ "//" +path[ 2 ]+'/'+path[ 3];
				var isoPais = path[4];
				var api ="/api/v1/";
				var tamPg = 10;
				var selPgActual=1;
				var tipoLote;
				var arrayResponse = [];

				$.post( baseURL+api+isoPais+'/usuario/notificaciones/'+funcion, datosPost ).done(function(data){
						var data = JSON.parse( data );
						Notificaciones = data;
						if( data.rc == 0 ){
								if(data.notificaciones.length != 0){
									$( ".ui-dialog-content" ).dialog( "destroy" );
									HtmlRows( data );
								}else{
									notificacion( titulo, 'En estos momentos no podemos procesar su solicitud' );
								}
						}
						else{
							if(data.rc == '-378'){
								  $('#notificacionesRequest').html("");
									$('#mensaje').html('<br><strong>'+data.mensaje+'</strong>')
														   .css({"text-align": "center", "font-size": "18px"});
							}else{
								notificacion( titulo, data.mensaje );
								}
						}
				});
		}

		function HtmlRows( data ){

			var notificaciones = data.notificaciones;
			var html = '';
			var notificacionesRequest = document.getElementById("notificacionesRequest");

			for( x = 0; x < notificaciones.length; x++ ) {

					var checkedTmp = (notificaciones[x].notificacionAct==1)?'checked':'';

					var ContactoEmail = (notificaciones[x].contacto.email != " ")?notificaciones[x].contacto.email:"";

					var style	= (notificaciones[x].notificacionAct==1)?
								'display: block;':'display: none;';
					html += '<br><input type="checkbox" id="checkNoti'+notificaciones[x].codOperacion+
									'" name="checkNoti'+notificaciones[x].codOperacion+'" onchange="capturaCheck(this)" '+
									' value="'+notificaciones[x].codOperacion+'" '+checkedTmp+'> '+notificaciones[x].descripcion +
									'<br> <br><div id="div'+notificaciones[x].codOperacion+'" style="'+style+'">'+
									'Correo : <input type="text" name="correo'+notificaciones[x].codOperacion+
											'" id="'+notificaciones[x].codOperacion+'" value="'+ContactoEmail+
											'"  onchange="captureEventCorreo(this)">'+
											'<br><hr class="classHrNoti"></div><br><br>';
				}
				html += '<br><div id="opciones-btn"><button id="btn-modificar-noti"  '+
										'type="submit" onclick="envioDatos()">Guardar</button></div>';
				notificacionesRequest.innerHTML = html;

		}
