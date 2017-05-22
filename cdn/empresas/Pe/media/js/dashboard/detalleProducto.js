$(function(){

var path =window.location.href.split( '/' );
var baseURL = path[0]+ "//" +path[2]+'/'+path[3];
var isoPais = path[4];
var api = "/api/v1/";

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
