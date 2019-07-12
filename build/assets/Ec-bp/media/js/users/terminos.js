$( document ).ready(function() {

  $("#enviarTerminos").on("click",function(){

  		if($("#aceptoTerminos").is(':checked')==true){
  			$(location).attr('href',baseURL+isoPais+'/clave');
  		}else{

			$('<h6>'+$('#confirmSalir').val()+'</h6>').dialog({

				dialogClass: "hide-close",
				title: "Términos y condiciones de uso",
    			modal: true,
    			maxWidth: 700,
    			maxHeight: 300,
    			resizable: false,
    			close: function(){$(this).dialog("destroy");},
    			buttons: {
						"Continuar": {
							text: 'Continuar',
							class: 'novo-btn-primary-modal',
							click: function () {
							$(this).dialog("close");
							}
						},
						"Salir": {
							text: 'Salir',
							class: 'novo-btn-secondary-modal',
							click: function () {
								$(location).attr('href',baseURL+isoPais+'/logout');
							}
						}
    			}
			});
  		}
  });

});
