$( document ).ready(function() {

  var path =window.location.href.split( '/' );
var baseURL = path[0]+ "//" +path[2]+'/'+path[3];
var isoPais = path[4];

  $("#enviarTerminos").on("click",function(){
  		
  		if($("#aceptoTerminos").is(':checked')==true){
  			$(location).attr('href',baseURL+'/'+isoPais+'/clave');
  		}else{
		
			$('<h6>'+$('#confirmSalir').val()+'</h6>').dialog({
				title: "Términos y condiciones de uso",
    			modal: true,
    			maxWidth: 700,
    			maxHeight: 300,
    			resizable: false,
    			close: function(){$(this).dialog("destroy");},
    			buttons: {
    				Continuar: function(){
                  $(this).dialog("close");
                },
      				Salir: function () {
                  $(location).attr('href',baseURL+'/'+isoPais+'/logout');
                }          			
    			}
			});
  		}
  });

});