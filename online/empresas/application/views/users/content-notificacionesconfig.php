<?php
	if( ! $this->session->userdata('logged_in') ){redirect($urlBase);}
	$pais = $this->uri->segment(1);
	$urlCdn = get_cdn();
?>
<div id="config-empresas" style="width: 670px;">
	<div id = "cargando" style = "display:none">
		<h2 style="text-align:center">
		<?php echo lang('CARGANDO'); ?></h2>
		<img style="display:block; margin-left:auto; margin-right:auto"
		 		src="<?php echo $urlCdn."media/img/loading.gif"?>"/>
	</div>
	<h1><?php echo lang('NOTI_TITLE_CONFIG'); ?></h1>

		<select class="selectorNotificacion" id="listaEmpresas" name="batch"  onchange="selectorEmpresa()">
			<?php
				if(array_key_exists('ERROR', $listaEmpr[0])){
						if($listaEmpr[0]['ERROR']=='-29'){
							echo "<script>alert('".$listaEmpr[0]['msg']."'); location.reload();</script>";
						}
				}else{
						echo "<option value=''>Selecciona una Empresa</option>";
						foreach ($listaEmpr[0]->lista as $listado) {
							echo "<option data-rif='$listado->acrif' data-nombre='$listado->acnomcia'
											 data-accodcia='$listado->accodcia'>$listado->acnomcia</option>";
						}
				}
			?>
		</select>
		<!-- <label  class="checkNotiAdj">Desea Aceptar Notificaciones?</label>
		<input type="checkbox" id="checkNoti"name="checkNoti" value="" > -->

	<!--<div id="campos-1" class="input-email">
		<span class="input-email">
			<p id="first"><?php echo lang('INFO_USER_EMAIL'); ?></p>
			<input id="email_user_noti" type="text" value="" style="float:left;" maxlength='45'/>
		</span>
	</div>
	<div id="opciones-btn">
			<button id='btn-modificar-noti' type="submit">Guardar</button>
	</div> -->
	<br>
	<div id="notificacionesRequest" style="margin-left: 10px;"></div>
</div>
<script type="text/javascript">

		var elementos = document.getElementsByClassName( "input-email" );
		var check = document.getElementById( 'checkNoti' );
		var email_user_noti = document.getElementById( 'email_user_noti' );
		var btn_modificar_noti = document.getElementById( 'btn-modificar-noti' );
		var listaEmpresas =  document.getElementById( 'listaEmpresas' );
		var Notificaciones = {};

		function selectorEmpresa(){

			var datosPost = {};
			var myselect = document.getElementById("listaEmpresas");
			var selector = myselect.options[ myselect.selectedIndex ];
			var titulo = "Selector Empresa";

			datosPost.acrif = selector.getAttribute( 'data-rif' );

			response = WS( 'buscar', datosPost, titulo );

			if( response.status ){
 					HtmlRows( reponse.data );
			}

		}


		showHideClass('.input-email', 'none');

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

		 function captureEventCorreo( event ) {
				var email = document.getElementById( event.target.id );
				validacionCorreo (email.value);
	   }
		function validacionCorreo (value){

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

        $(canvas).dialog({
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

		function WS(funcion, datosPost, titulo){

				var path = window.location.href.split( '/' );
				var baseURL = path[0]+ "//" +path[2]+'/'+path[3];
				var isoPais = path[4];
				var api ="/api/v1/";
				var tamPg = 10;
				var selPgActual=1;
				var tipoLote;
				var arrayResponse = {
					status : false,
					data : ''
				};
				$.post( baseURL+api+isoPais+'/usuario/notificaciones/'+funcion, datosPost ).done(function(data){

					var data = JSON.parse(data);

						if( data.rc == '0' ){
							$(".ui-dialog-content").dialog("destroy");
							notificacion(titulo,'Proceso exitoso');

							arrayResponse.status = true;
							arrayResponse.data = data;

						}
						else{
								notificacion( titulo, data.mensaje );
								arrayResponse.status = false;
								arrayResponse.data = '';
						}
				});

				return arrayResponse;

		}

		function HtmlRows( data ){

			var notificaciones = data.notificaciones;
			var html = '';
			var notificacionesRequest = document.getElementById("notificacionesRequest");

			for( x = 0; x < notificaciones.length; x++ ) {

				var checkedTmp = (notificaciones[x].notificacionAct==1)?'checked':'';

				html += '<br><input type="checkbox" id="checkNoti'+notificaciones[x].codOperacion+
								'" name="checkNoti'+notificaciones[x].codOperacion+'" '+
								' value="'+notificaciones[x].codOperacion+'" '+checkedTmp+'> '+notificaciones[x].descripcion +
								'<br> <br>Correo : <input type="text" name="correo'+notificaciones[x].codOperacion+
										'" id="'+notificaciones[x].codOperacion+'"><br><hr class="classHrNoti"><br><br>';
			}

			html += '<br><div id="opciones-btn"><button id="btn-modificar-noti" type="submit">Guardar</button></div>';
			notificacionesRequest.innerHTML = html;

		}
</script>
