$(function(){

// Seleccionar empresa

	$("#sEmpresa").on("click",function(){

		$.getJSON(baseURL+api+isoPais+'/empresas/lista').always(function( response ) {
			$("#sEmpresaS").append("<select style='width: 200px;' id='empresasS'></select>");
			$("#sEmpresaS").append("<select style='width: 200px;' id='productosS'></select>");
			$("#sEmpresaS").append("<button id='sEmpresa' type='submit'>Aplicar</button>");

  			$.each(response.lista, function(k,v){
				$("#empresasS").append('<option value="'+v.acrif+'" >'+v.acnomcia+'</option>');
			});
   		});
	});

//--Fin Seleccionar empresa

});
