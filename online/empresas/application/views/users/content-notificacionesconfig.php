<?php
	if( ! $this->session->userdata('logged_in') ){redirect($urlBase);}
	$pais = $this->uri->segment(1);
	$urlCdn = get_cdn();
?>
<div id="config-empresas">
	<div id = "cargando" style = "display:none">
		<h2 style="text-align:center">
		<?php echo lang('CARGANDO'); ?></h2>
		<img style="display:block; margin-left:auto; margin-right:auto"
		 		src="<?php echo $urlCdn."media/img/loading.gif"?>"/>
	</div>
	<h1><?php echo lang('NOTI_TITLE_CONFIG'); ?></h1>
	<div id="campos-1" class="pad10">
		<select class="select-empresa" id="listaEmpresas" name="batch">
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
		<br>
		<label  class="checkNotiAdj">Desea Aceptar Notificaciones?</label>
<input type="checkbox" id="checkNoti"name="checkNoti" value="" >
	</div>
	<div id="campos-1" class="input-email">
		<span class="input-email">
			<p id="first"><?php echo lang('INFO_USER_EMAIL'); ?></p>
			<input id="email_user_noti" type="text" value="" style="float:left;" maxlength='45'/>
		</span>
	</div>
	<div id="opciones-btn">
			<button id='btn-modificar-noti' type="submit">Guardar</button>
	</div>
</div>
<script type="text/javascript">

		var elementos = document.getElementsByClassName( "input-email" );
		var check = document.getElementById( 'checkNoti' );
		var email_user_noti = document.getElementById( 'email_user_noti' );
		var btn_modificar_noti = document.getElementById( 'btn-modificar-noti' );

		showHideClass('.input-email', 'none');

		check.addEventListener( "click", function( event ) {
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

	 	 email_user_noti.addEventListener( "onchange", captureEventCorreo);
		 email_user_noti.addEventListener( "blur", captureEventCorreo);

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

		function notificacion(titulo, mensaje){

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

			$.post(baseURL+api+isoPais+'/lotes/reproceso/'+funcion, datosPost).done(function(data){
				if(!data.ERROR){
					$(".ui-dialog-content").dialog("destroy");
					notificacion(titulo,'Proceso exitoso');
				}else{
					if(data.ERROR=='-29'){
						alert('Usuario actualmente desconectado'); location.reload();
					}else{
						notificacion(titulo, data.ERROR);
					}
				}
			});

		}
</script>
