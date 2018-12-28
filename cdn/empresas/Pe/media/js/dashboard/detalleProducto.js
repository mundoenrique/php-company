$(function(){

var baseURL = $('body').attr('data-app-base');
var isoPais = $('body').attr('data-country');
var api = "api/v1/";

// Seleccionar empresa

	$("#sEmpresa").on("click",function(){

		$.getJSON(baseURL+api+isoPais+'/empresas/lista').always(function( data ) {
			$("#sEmpresaS").append("<select style='width: 200px;' id='empresasS'></select>");
			$("#sEmpresaS").append("<select style='width: 200px;' id='productosS'></select>");
			$("#sEmpresaS").append("<button id='sEmpresa' type='submit'>Aplicar</button>");

  			$.each(data.lista, function(k,v){
				$("#empresasS").append('<option value="'+v.acrif+'" >'+v.acnomcia+'</option>');
			});
   		});
	});


//--Fin Seleccionar empresa





});
