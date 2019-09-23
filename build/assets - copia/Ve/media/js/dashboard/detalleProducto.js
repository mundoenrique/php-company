$(function(){

// Seleccionar empresa

	$("#sEmpresa").on("click",function(){

		$.getJSON(baseURL+api+isoPais+'/eol/empresas/lista').always(function( data ) {
			$("#sEmpresaS").append("<select style='width: 200px;' id='empresasS'></select>");
			$("#sEmpresaS").append("<select style='width: 200px;' id='productosS'></select>");
			$("#sEmpresaS").append("<button id='sEmpresa' type='submit'>Aplicar</button>");

  			$.each(data.lista, function(k,v){
				$("#empresasS").append('<option value="'+v.acrif+'" >'+v.acnomcia+'</option>');
			});
   		});
	});


//--Fin Seleccionar empresa


// scroll para el menu-
$.each( $('.batchs ul'), function(k,v){

  if( $(this).find('li').length > 4 ){

     $(this).css('overflow','hidden');
     $(this).css('height','168px');
     $(this).css('padding','20px 0');

     $(this).find('li').css('left','0');
     $(this).menu().removeClass('ui-menu-icons');
     $(this).menu().removeClass('ui-widget-content');
     $(this).find('.scrollup').show();
     $(this).find('.scrolldown').show();
  }
});


});
