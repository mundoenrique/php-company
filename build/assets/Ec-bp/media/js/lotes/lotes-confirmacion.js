$(function(){

  //LOTES CONFIRMACION (CHECK)

  $('#batchs-last').on('click','#confirma', function(){

      var pass = $("#clave").val();
      var embozo1 = $("#embozo1").val();
      var embozo2 = $("#embozo2").val();
      var conceptoDim = $("#conceptoDinamico").val();
      var info = $("#info").attr('value');
      var idTipoLote = $("#idTipoLote").attr('value');

      var tipo = $("#tipo").attr('data-tipo');
      var b;

      if(tipo.toUpperCase()!="EMISION"){
        embozo1 = "";
        embozo2 = "";
      }else{
        embozo1 = $('#embozo1').val();
        embozo2 = $('#embozo2').val();
        b=true;
      }

      if( b && (embozo1=="" || embozo2=="")  ){
        notificacion("Confirmación", "Debe seleccionar los embozos",null);
      }else{
        if(pass!=""){
					var form = $('#form-confirmacion');
					validateForms(form);
					console.log(form.valid());
					if (form.valid()) {
						pass = hex_md5( pass );
						$('#clave').val( '' );

						$("#confirma").replaceWith('<h3 id="confirm">confirmando...</h3>');
						var ceo_cook = decodeURIComponent(
							document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
						);
						var dataRequest = JSON.stringify ({
							pass: pass,
							embozo1: embozo1,
							embozo2: embozo2,
							conceptoDim: conceptoDim,
							info: info,
							idTipoLote: idTipoLote
							})

							dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {format: CryptoJSAesJson}).toString();
							$.post(baseURL+isoPais+'/lotes/confirmacion/confirmar',
							{request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook) })
							.done( function(response){
								data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
								if(!data.ERROR){
									if (data.linkAut) {
										notificacion('Confirmación','Proceso exitoso.<h5>Ha confirmado el Lote Nro: '+$('#numLote').text()+'</h5>', baseURL+isoPais+'/lotes/autorizacion')
									}else if(data.ordenes){
											$("#data-confirm").attr('value',data.ordenes);
											notificacion('Confirmación', '<h3>Proceso exitoso</h3>','form#toOS');
									}else{
										notificacion('Confirmación', 'Proceso exitoso.<h5>Ha confirmado el Lote Nro: '+$('#numLote').text()+'</h5>',baseURL+isoPais+'/lotes');
										//$(".ui-button").hide();
										//$(location).attr(sitio);
									}

								}else{
									if(data.ERROR=='-29'){
														alert('Usuario actualmente desconectado');  location.reload();
														}  else{notificacion("Confirmación", data.ERROR,null);}
									$("#confirm").replaceWith('<button id="confirma" class="novo-btn-primary" >Confirmar</button>');

								}
						});
					} else {
						notificacion("Confirmación","Verifique los datos ingresados e intente nuevamente",null);
					}
        }else{
          notificacion("Confirmación","Debe ingresar contraseña",null);
        }
      }

  });

  //FIN LOTES CONFIRMACION (CHECK)


function notificacion(titulo, mensaje, sitio){

  var canvas = "<div>"+mensaje+"</div>";

  $(canvas).dialog({

		dialogClass: "hide-close",
    title: titulo,
    modal: true,
    maxWidth: 700,
    maxHeight: 300,
    close: function(){
      $(this).dialog('destroy');
      if (sitio) {
              if(sitio=='form#toOS')
              $(sitio).submit();
              else
              $(location).attr('href',sitio);
            }
    },
    buttons: {
			"Cancelar": { text: 'Cancelar', class: 'novo-btn-secondary-modal', style: 'border-color: #ffdd00 !important;background:white !important;', click: function () {
				$(this).dialog("close"); }
			},
      Aceptar: function(){
            $(this).dialog("close");
            if (sitio) {
              if(sitio=='form#toOS')
              $(sitio).submit();
              else
              $(location).attr('href',sitio);
            }
          }
    }
  });
}


}) //fin document ready
