$(function() {

// Datos a enviar

var widget_var = {
 	acrif:"",
 	acnomcia:"",
 	acrazonsocial:"",
 	acdesc:"",
 	accodcia:"",
	accodgrupoe:"",

 	idproducto:"", nombprod:"", marcprod:""
}

// -- fin datos a enviar
// Cambiar empresa


	$("#sEmpresa").on("click",function(){

		$('#sEmpresa').hide();
    	$("#widget-info-2").append("<img class='load-widget' id='cargando' src='"+$('#cdn').val()+"media/img/loading.gif'>");

		$.getJSON(baseURL+api+isoPais+'/empresas/lista').always(function( data ) {

			$("#widget-info-2").find($('#cargando')).remove();

			$('#sEmpresaS').show();

			if(!data.ERROR){
  				$.each(data.lista, function(k,v){
					$("#empresasS").append('<option value="'+v.acrif+'" acnomcia="'+v.acnomcia+'" acrazonsocial="'+v.acrazonsocial+'" acdesc="'+v.acdesc+'" accodcia="'+v.accodcia+'" accodgrupoe='+v.accodgrupoe+'>'+v.acnomcia+'</option>');
				});
  			}else{
  				if(data.ERROR=='-29'){
  				alert('Usuario actualmente desconectado'); location.reload();
  				}
  			}
   		});

	});


//--Fin Cambiar empresa

// Seleccionar empresa

	$("#empresasS").on("change",function(){
		widget_var.acrif = $(this).val();
		widget_var.acnomcia = $('option:selected', this).attr('acnomcia');
		widget_var.acrazonsocial = $('option:selected', this).attr('acrazonsocial');
 		widget_var.acdesc = $('option:selected', this).attr('acdesc');
		widget_var.accodcia = $('option:selected', this).attr('accodcia');
		widget_var.accodgrupoe = $('option:selected', this).attr('accodgrupoe');

		$('#productosS').empty();
		$("#productosS").append('<option>Cargando...</option>');
		$(this).attr('disabled',true);
		$.post(baseURL+api+isoPais+"/producto/lista", { 'acrif': widget_var.acrif }, function(data){
			$("#empresasS").removeAttr('disabled');
			$('#productosS').empty();
			$("#productosS").append('<option>Seleccione un producto</option>');


			if(!data.ERROR){
			$.each(data, function(k,v){
				$("#productosS").append('<option value="'+v.idProducto+'" nombre='+v.nombre+' marca='+v.marca+' >'+v.descripcion+" / "+v.marca.toUpperCase()+'</option>');
			});
			}else{
  				if(data.ERROR=='-29'){
  				alert('Usuario actualmente desconectado'); location.reload();
  				}
  			}
		});

	});

//--Fin Seleccionar empresa


// Seleccionar producto

	$("#productosS").on("change", function(){

		widget_var.idproducto = $(this).val();
		widget_var.nombprod = $('option:selected', this).attr('nombre');
		widget_var.marcprod = $('option:selected', this).attr('marca');

	});

//--Fin Seleccionar producto

//	Enviar todo

	$('#aplicar').on('click',function(){


		if( widget_var.idproducto !== undefined ){

			$.post( baseURL+"api/v1/"+isoPais+"/empresas/cambiar",
				{ 'data-accodgrupoe':widget_var.accodgrupoe, 'data-acrif':widget_var.acrif, 'data-acnomcia':widget_var.acnomcia, 'data-acrazonsocial':widget_var.acrazonsocial, 'data-acdesc':widget_var.acdesc, 'data-accodcia':widget_var.accodcia, 'data-idproducto':widget_var.idproducto, 'data-nomProd':widget_var.nombprod, 'data-marcProd':widget_var.marcprod, 'llamada':'productos' },
				 function(data){

          			if(data === 1){
            			$(location).attr('href',baseURL+isoPais+"/dashboard/productos/detalle");
          			}else{
            			MarcarError('Intente de nuevo');
          			}
				 }
			);
		}else{
      		MarcarError('Seleccione una empresa');
    	}
  	});

function MarcarError(msj){
  $.balloon.defaults.classname = "error-login-2";
  $.balloon.defaults.css = null;
  $("#aplicar").showBalloon({position: "left", contents: msj});  //mostrar tooltip
  setTimeout( function(){ $("#aplicar").hideBalloon({position: "left", contents: msj}); }, 2500 );  // ocultar tooltip
}

//-- Fin enviar todo


 // BTN OTROS PROGRAMAS

 $('#sPrograms').on('click',function(){
 	$(location).attr('href',baseURL+isoPais+"/dashboard/programas");
 });

 //--FIN BTN OTROS PROGRAMS


	// widget FIXED

var top = ($('#sidebar-products').offset().top-100) - parseFloat($('#sidebar-products').css('marginTop').replace(/auto/, 0));
       $(window).scroll(function (event) {

         var y = $(this).scrollTop();

          if (y >= top) {

            $('#sidebar-products').addClass('sub-widget');

        } else {

            $('#sidebar-products').removeClass('sub-widget');
         }
     });

//--FIN widget FIXED

});
