
		var elementos = document.getElementsByClassName( "input-email" );
		var check = document.getElementById( 'checkNoti' );
		var email_user_noti = document.getElementById( 'email_user_noti' );
		var btn_modificar_noti = document.getElementById( 'btn-modificar-noti' );
		var listaEmpresas =  document.getElementById( 'listaEmpresas' );
		var Notificaciones;

		function selectorEmpresa(){

			var datosPost = {};
			var myselect = document.getElementById("listaEmpresas");
			var selector = myselect.options[ myselect.selectedIndex ];
			var titulo = "Selector Empresa";

			datosPost.acrif = selector.getAttribute( 'data-rif' );
			ConsultaNotificaciones( 'buscar', datosPost, titulo );

		}

		showHideClass('.input-email', 'none');

		function capturaCheck( e ){

			var str = e.getAttribute( 'id' );
			var idSelect = str.split("checkNoti");

			var element = document.getElementById('div'+idSelect[1]);

			element.style.display = ( e.checked )?'block':'none';

		}

		/*check.addEventListener( "click", function( event ) {
				showHideClass( '.input-email', (check.checked)?'block':'none' );
	  });

		btn_modificar_noti.addEventListener( "click", function( event ) {
				 if( check.checked ){
					 	if( validacionCorreo( email_user_noti.value ) ){
							alert("Notificaciones Activas");
						}
				 }else{
					 alert("Notificaciones Desactivadas");
				 }
		});
*/
	 	// email_user_noti.addEventListener( "onchange", captureEventCorreo);
		// email_user_noti.addEventListener( "blur", captureEventCorreo);
		function envioDatos(){

			var listaInputs = [];

			$.each($(":checkbox"),function(k,v){

				if (this.checked) {
					var datos = JSON.parse($(this).attr('id'));

					listaInputs.push(datos);
				}
			});

			console.log(listaInputs);

			var path = window.location.href.split( '/' );
			var baseURL = path[ 0 ]+ "//" +path[ 2 ]+'/'+path[ 3];
			var isoPais = path[4];
			var api ="/api/v1/";
/*
			$.post( baseURL+api+isoPais+'/usuario/notificaciones/envio', datosPost ).done(function(data){

				var data = JSON.parse( data );

					if( data.rc == 0 ){
							$( ".ui-dialog-content" ).dialog( "destroy" );
							notificacion( titulo, data.mensaje );
					}
					else{
							notificacion( titulo, data.mensaje );
					}

			});*/

		}
		 function captureEventCorreo( e ) {
			  console.log(e);
				var value = e.value;
				console.log(value);
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
								$( ".ui-dialog-content" ).dialog( "destroy" );
								HtmlRows( data );
						}
						else{
								notificacion( titulo, data.mensaje );
						}
				});

		}

		function HtmlRows( data ){

			var notificaciones = data.notificaciones;
			var html = '';
			var notificacionesRequest = document.getElementById("notificacionesRequest");

			for( x = 0; x < notificaciones.length; x++ ) {

					var checkedTmp = (notificaciones[x].notificacionAct==1)?'checked':'';

					var style	= (notificaciones[x].notificacionAct==1)?
								'display: block;':'display: none;';
								console.log(notificaciones[x].contacto);
					html += '<br><input type="checkbox" id="checkNoti'+notificaciones[x].codOperacion+
									'" name="checkNoti'+notificaciones[x].codOperacion+'" onchange="capturaCheck(this)" '+
									' value="'+notificaciones[x].codOperacion+'" '+checkedTmp+'> '+notificaciones[x].descripcion +
									'<br> <br><div id="div'+notificaciones[x].codOperacion+'" style="'+style+'">'+
									'Correo : <input type="text" name="correo'+notificaciones[x].codOperacion+
											'" id="'+notificaciones[x].codOperacion+'" value="" '+notificaciones[x].contacto.email+
											' onchange="captureEventCorreo(this)">'+
											'<br><hr class="classHrNoti"></div><br><br>';
				}

				html += '<br><div id="opciones-btn"><button id="btn-modificar-noti" '+
										'type="submit" onclick="envioDatos()">Guardar</button></div>';
				notificacionesRequest.innerHTML = html;

		}
